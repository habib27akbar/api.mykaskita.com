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
        $email       = $req->input('email', $req->user()->email ?? null);
        $months      = max(1, (int) $req->input('months', 12));
        $horizon     = max(1, (int) $req->input('horizon', 3));


        $start = now()->subMonths($months - 1)->startOfMonth();
        $end   = now()->endOfMonth();

        // =========================
        // CASH-IN
        // =========================

        // PENJUALAN (anggap semua tunai; kalau tidak, nanti tambahkan flag is_cash)
        // (penjualan + biaya_angkut + ppn_keluaran) * (1 - discount/100)
        $penjualanIn = DB::table('penjualan as p')
            ->selectRaw("DATE_FORMAT(p.tanggal, '%Y-%m') as ym,
                SUM(
                  (COALESCE(p.penjualan,0) + COALESCE(p.biaya_angkut,0) + COALESCE(p.ppn_keluaran,0))
                  * (1 - COALESCE(p.discount,0)/100)
                ) as total_in")
            ->when($email, fn($q) => $q->where('p.email', $email))
            ->whereBetween('p.tanggal', [$start, $end])
            ->groupBy('ym')
            ->pluck('total_in', 'ym');

        // PENERIMAAN (anggap debet-kredit = kas-in modul penerimaan)
        $penerimaanIn = DB::table('penerimaan as pr')
            ->selectRaw("DATE_FORMAT(pr.tanggal_debet, '%Y-%m') as ym,
                SUM(COALESCE(pr.debet,0) - COALESCE(pr.kredit,0)) as total_in")
            ->when($email, fn($q) => $q->where('pr.email', $email))
            ->whereBetween('pr.tanggal_debet', [$start, $end])
            ->groupBy('ym')
            ->pluck('total_in', 'ym');

        // JURNAL UMUM — DEBET kas/bank (JOIN master_coa, tanpa whereIn/implode)
        $jurnalIn = DB::table('jurnal_umum as ju')
            ->join('master_coa as mc', 'mc.id', '=', 'ju.id_coa_debet')
            ->selectRaw("DATE_FORMAT(ju.tanggal_debet, '%Y-%m') as ym, SUM(COALESCE(ju.debet,0)) as total_in")
            ->when($email, fn($q) => $q->where('ju.email', $email))
            ->whereBetween('ju.tanggal_debet', [$start, $end])
            ->groupBy('ym')
            ->pluck('total_in', 'ym');

        // =========================
        // CASH-OUT
        // =========================

        // PEMBELIAN (anggap semua tunai)
        // (persediaan_barang + biaya_angkut + ppn_masukan) * (1 - discount/100)
        $pembelianOut = DB::table('pembelian as b')
            ->selectRaw("DATE_FORMAT(b.tanggal, '%Y-%m') as ym,
                SUM(
                  (COALESCE(b.persediaan_barang,0) + COALESCE(b.biaya_angkut,0) + COALESCE(b.ppn_masukan,0))
                  * (1 - COALESCE(b.discount,0)/100)
                ) as total_out")
            ->when($email, fn($q) => $q->where('b.email', $email))
            ->whereBetween('b.tanggal', [$start, $end])
            ->groupBy('ym')
            ->pluck('total_out', 'ym');

        // PENGELUARAN (anggap kredit - debet = kas-out di modul ini)
        $pengeluaranOut = DB::table('pengeluaran as pg')
            ->selectRaw("DATE_FORMAT(pg.tanggal_kredit, '%Y-%m') as ym,
                SUM(COALESCE(pg.kredit,0) - COALESCE(pg.debet,0)) as total_out")
            ->when($email, fn($q) => $q->where('pg.email', $email))
            ->whereBetween('pg.tanggal_kredit', [$start, $end])
            ->groupBy('ym')
            ->pluck('total_out', 'ym');

        // JURNAL UMUM — KREDIT kas/bank
        $jurnalOut = DB::table('jurnal_umum as ju')
            ->join('master_coa as mc', 'mc.id', '=', 'ju.id_coa_kredit')
            ->selectRaw("DATE_FORMAT(ju.tanggal_debet, '%Y-%m') as ym, SUM(COALESCE(ju.kredit,0)) as total_out")
            ->when($email, fn($q) => $q->where('ju.email', $email))
            ->whereBetween('ju.tanggal_debet', [$start, $end])
            ->groupBy('ym')
            ->pluck('total_out', 'ym');

        // =========================
        // SUSUN HISTORY BULANAN
        // =========================
        $cursor  = $start->copy();
        $labels  = [];
        $history = [];   // net per bulan

        while ($cursor->lte($end)) {
            $ym  = $cursor->format('Y-m');
            $in  = ($penjualanIn[$ym] ?? 0) + ($penerimaanIn[$ym] ?? 0) + ($jurnalIn[$ym] ?? 0);
            $out = ($pembelianOut[$ym] ?? 0) + ($pengeluaranOut[$ym] ?? 0) + ($jurnalOut[$ym] ?? 0);
            $net = (float)$in - (float)$out;

            $labels[]  = $ym;
            $history[] = $net;

            $cursor->addMonth()->startOfMonth();
        }

        // =========================
        // CASH-ON-HAND APPROX & AVG OUTFLOW
        // =========================
        $cashOnHandApprox = array_sum($history);

        $avgOutflow = 0.0;
        if (count($history) > 0) {
            $cursor2 = $start->copy();
            $outs = [];
            while ($cursor2->lte($end)) {
                $ym2 = $cursor2->format('Y-m');
                $outs[] = (float) (($pembelianOut[$ym2] ?? 0) + ($pengeluaranOut[$ym2] ?? 0) + ($jurnalOut[$ym2] ?? 0));
                $cursor2->addMonth()->startOfMonth();
            }
            $avgOutflow = count($outs) ? array_sum($outs) / count($outs) : 0.0;
        }

        // =========================
        // CALL FLASK AI
        // =========================
        try {
            $client = new Client([
                'base_uri' => rtrim(env('PY_AI_URL'), '/'),
                'timeout'  => 25,
            ]);

            $resp = $client->post('/ai/predict-and-detect', [
                'json' => [
                    'history'      => $history,
                    'horizon'      => $horizon,
                    'cash_on_hand' => (float)$cashOnHandApprox,
                    'avg_expense'  => (float)$avgOutflow,
                ]
            ]);

            $ai = json_decode($resp->getBody(), true);
        } catch (\Throwable $e) {
            Log::error('AI call failed: ' . $e->getMessage());
            $ai = ['error' => 'AI service unavailable'];
        }

        return response()->json([
            'input' => [
                'email'     => $email,
                'months'    => $months,
                'horizon'   => $horizon,
                'labels'    => $labels,
                'cash_on_hand_approx' => $cashOnHandApprox,
                'avg_outflow' => $avgOutflow,
            ],
            'history' => $history, // net cash per month
            'ai'      => $ai,      // hasil Flask (prediction + risk)
        ]);
    }
}
