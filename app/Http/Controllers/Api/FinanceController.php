<?php


namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Coa;
use App\Models\User;
use App\Models\HeaderCoa;
use App\Models\MasterCoa;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\JurnalUmum;
use App\Models\Penerimaan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FinanceController extends Controller
{

    public function arusKas(Request $request)
    {
        $email = $request->user()->email ?? $request->query('email', null);
        $startMonth = Carbon::parse($request->query('start', $request->query('bulan', date('Y-m'))));

        $data = [];

        for ($i = 0; $i < 12; $i++) {
            $bulan = $startMonth->copy()->addMonths($i);
            $year = $bulan->year;
            $month = $bulan->month;

            // Hitung kas masuk
            $penjualan = Penjualan::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('email', $email)
                ->sum(DB::raw('penjualan + piutang_dagang + biaya_angkut + ppn_keluaran - discount'));

            $penerimaan = Penerimaan::whereYear('tanggal_debet', $year)
                ->whereMonth('tanggal_debet', $month)
                ->where('email', $email)
                ->sum('debet');

            $jurnal_debet = JurnalUmum::whereYear('tanggal_debet', $year)
                ->whereMonth('tanggal_debet', $month)
                ->where('email', $email)
                ->sum('debet');

            // Hitung kas keluar
            $pembelian = Pembelian::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('email', $email)
                ->sum(DB::raw('persediaan_barang + biaya_angkut + ppn_masukan - discount'));

            $pengeluaran = Pengeluaran::whereYear('tanggal_kredit', $year)
                ->whereMonth('tanggal_kredit', $month)
                ->where('email', $email)
                ->sum('kredit');

            $jurnal_kredit = JurnalUmum::whereYear('tanggal_debet', $year)
                ->whereMonth('tanggal_debet', $month)
                ->where('email', $email)
                ->sum('kredit');

            $kas_masuk = $penjualan + $penerimaan + $jurnal_debet;
            $kas_keluar = $pembelian + $pengeluaran + $jurnal_kredit;

            $saldo_akhir = $kas_masuk - $kas_keluar;

            // Deteksi keuangan sederhana
            $alerts = [];
            if ($kas_keluar > $kas_masuk) {
                $alerts[] = 'Kas keluar lebih besar dari kas masuk!';
            }
            if ($kas_masuk - $kas_keluar > 100000000) {
                $alerts[] = 'Transaksi besar melebihi 100 juta!';
            }

            $data[] = [
                'bulan' => $bulan->format('Y-m'),
                'kas_masuk' => $kas_masuk,
                'kas_keluar' => $kas_keluar,
                'saldo_akhir' => $saldo_akhir,
                'alerts' => $alerts
            ];
        }

        return response()->json($data);
    }
}
