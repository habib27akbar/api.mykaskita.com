<?php

namespace App\Http\Controllers\Api;


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AiOrchestratorController extends Controller
{

    /**
     * Body (opsional):
     * {
     *   "email": "user@example.com",
     *   "months": 12,         // default 12
     *   "horizon": 3,         // default 3
     *   "kas_patterns": ["111%","112%"] // opsional, pola nomor akun kas/bank di master_coa
     * }
     */
    public function analyze(Request $req)
    {
        $email   = $req->input('email', $req->user()->email ?? null);
        $months  = max(1, (int) $req->input('months', 12));
        $horizon = max(1, (int) $req->input('horizon', 3));

        $start = now()->subMonths($months - 1)->startOfMonth();
        $end   = now()->endOfMonth();

        // =========================
        // CASH-IN
        // =========================
        $penjualanIn = DB::table('penjualan as p')
            ->selectRaw("DATE_FORMAT(p.tanggal, '%Y-%m') as ym,
            SUM(
              (COALESCE(p.penjualan,0) + COALESCE(p.biaya_angkut,0) + COALESCE(p.ppn_keluaran,0))
              * (1 - COALESCE(p.discount,0)/100)
            ) as total_in")
            ->when($email, fn($q) => $q->where('p.email', $email))
            ->whereBetween('p.tanggal', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total_in', 'ym');

        //dd($penjualanIn->toSql());

        $penerimaanIn = DB::table('penerimaan as pr')
            ->selectRaw("DATE_FORMAT(pr.tanggal_debet, '%Y-%m') as ym,
            SUM(COALESCE(pr.debet,0) - COALESCE(pr.kredit,0)) as total_in")
            ->when($email, fn($q) => $q->where('pr.email', $email))
            ->whereBetween('pr.tanggal_debet', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total_in', 'ym');

        $jurnalIn = DB::table('jurnal_umum as ju')
            ->join('master_coa as mc', 'mc.id', '=', 'ju.id_coa_debet')
            ->selectRaw("DATE_FORMAT(ju.tanggal_debet, '%Y-%m') as ym, SUM(COALESCE(ju.debet,0)) as total_in")
            ->when($email, fn($q) => $q->where('ju.email', $email))
            ->whereBetween('ju.tanggal_debet', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total_in', 'ym');

        // =========================
        // CASH-OUT
        // =========================
        $pembelianOut = DB::table('pembelian as b')
            ->selectRaw("DATE_FORMAT(b.tanggal, '%Y-%m') as ym,
            SUM(
              (COALESCE(b.persediaan_barang,0) + COALESCE(b.biaya_angkut,0) + COALESCE(b.ppn_masukan,0))
              * (1 - COALESCE(b.discount,0)/100)
            ) as total_out")
            ->when($email, fn($q) => $q->where('b.email', $email))
            ->whereBetween('b.tanggal', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total_out', 'ym');

        $pengeluaranOut = DB::table('pengeluaran as pg')
            ->selectRaw("DATE_FORMAT(pg.tanggal_kredit, '%Y-%m') as ym,
            SUM(COALESCE(pg.kredit,0) - COALESCE(pg.debet,0)) as total_out")
            ->when($email, fn($q) => $q->where('pg.email', $email))
            ->whereBetween('pg.tanggal_kredit', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total_out', 'ym');

        $jurnalOut = DB::table('jurnal_umum as ju')
            ->join('master_coa as mc', 'mc.id', '=', 'ju.id_coa_kredit')
            ->selectRaw("DATE_FORMAT(ju.tanggal_debet, '%Y-%m') as ym, SUM(COALESCE(ju.kredit,0)) as total_out")
            ->when($email, fn($q) => $q->where('ju.email', $email))
            ->whereBetween('ju.tanggal_debet', [$start->toDateString(), $end->toDateString()])
            ->groupBy('ym')
            ->pluck('total_out', 'ym');

        // =========================
        // SUSUN HISTORY BULANAN (IN / OUT / NET)
        // =========================
        $cursor      = $start->copy();
        $labels      = [];
        $insHistory  = []; // total cash-in per bulan
        $outsHistory = []; // total cash-out per bulan
        $netHistory  = []; // cash-in - cash-out

        while ($cursor->lte($end)) {
            $ym = $cursor->format('Y-m');

            $in  = (float) (($penjualanIn[$ym] ?? 0) + ($penerimaanIn[$ym] ?? 0) + ($jurnalIn[$ym] ?? 0));
            $out = (float) (($pembelianOut[$ym] ?? 0) + ($pengeluaranOut[$ym] ?? 0) + ($jurnalOut[$ym] ?? 0));
            $net = $in - $out;

            $labels[]      = $ym;
            $insHistory[]  = $in;
            $outsHistory[] = $out;
            $netHistory[]  = $net;

            $cursor->addMonth()->startOfMonth();
        }

        // =========================
        // CASH-ON-HAND APPROX & AVG OUTFLOW
        // =========================
        $cashOnHandApprox = array_sum($netHistory);
        $avgOutflow = count($outsHistory) ? array_sum($outsHistory) / count($outsHistory) : 0.0;

        // =========================
        // CALL FLASK AI (2x: IN & OUT)
        // =========================
        $ai_in  = ['error' => 'AI service unavailable'];
        $ai_out = ['error' => 'AI service unavailable'];

        try {
            $client = new \GuzzleHttp\Client([
                'base_uri' => rtrim(env('PY_AI_URL'), '/'),
                'timeout'  => 30,
            ]);

            // 1) Prediksi & anomali Cash-IN
            $respIn = $client->post('/ai/predict-and-detect', [
                'json' => [
                    'history'  => $insHistory,   // LSTM untuk IN
                    'outflows' => $insHistory,   // Isolation Forest untuk IN
                    'horizon'  => $horizon,
                ],
            ]);
            $ai_in = json_decode($respIn->getBody(), true);

            // 2) Prediksi & anomali Cash-OUT
            $respOut = $client->post('/ai/predict-and-detect', [
                'json' => [
                    'history'  => $outsHistory,  // LSTM untuk OUT
                    'outflows' => $outsHistory,  // Isolation Forest untuk OUT
                    'horizon'  => $horizon,
                ],
            ]);
            $ai_out = json_decode($respOut->getBody(), true);
        } catch (\Throwable $e) {
            //\Log::error('AI call failed: ' . $e->getMessage());
        }

        return response()->json([
            'input' => [
                'email'            => $email,
                'months'           => $months,
                'horizon'          => $horizon,
                'labels'           => $labels,
                'cash_on_hand_approx' => $cashOnHandApprox,
                'avg_outflow'      => $avgOutflow,
            ],

            // histori mentah yang dipisah
            'history_net'  => $netHistory,
            'history_in'   => $insHistory,
            'history_out'  => $outsHistory,

            // hasil AI terpisah
            // Struktur mengikuti respons Flask Anda sekarang:
            //  { prediction:{forecast,...}, anomaly:{anomalies,scores}, ... }
            'ai' => [
                'in'  => $ai_in,
                'out' => $ai_out,
            ],
        ]);
    }
}
