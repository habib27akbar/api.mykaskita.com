<?php


namespace App\Http\Controllers\Api;

use App\Models\Coa;
use App\Models\User;
use App\Models\HeaderCoa;
use App\Models\MasterCoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
{
    // Neraca
    // public function neraca()
    // {
    //     $data = MasterCoa::with('header')
    //         ->whereIn('id_header', [1, 2, 3, 4, 5]) // Assets, Liabilities, Equity
    //         ->get()
    //         ->groupBy(fn($item) => $item->header->nama_header);

    //     return response()->json([
    //         'status' => 'success',
    //         'laporan' => 'Neraca',
    //         'data' => $data
    //     ]);
    // }

    public function neraca(Request $request)
    {
        $bulanTahun = $request->query('bulan', date('Y-m'));
        $email = $request->input('email');

        $neracaAwal = DB::table('neraca_awal')
            ->select('id_coa', 'debet', 'kredit')
            ->where('bln_tahun', $bulanTahun)
            ->when($email, fn($q) => $q->where('email', $email));

        // pembelian
        $pembelian = DB::table('pembelian')
            ->selectRaw("
            6 as id_coa, 
            SUM((persediaan_barang + biaya_angkut + ppn_masukan) - 
                ((persediaan_barang + biaya_angkut + ppn_masukan) * (discount/100))) as kredit, 
            0 as debet
        ")
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email))
            ->unionAll(
                DB::table('pembelian')
                    ->selectRaw("
                    15 as id_coa, 
                    0 as debet, 
                    SUM(hutang_dagang) as kredit
                ")
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
                    ->when($email, fn($q) => $q->where('email', $email))
            );

        // penjualan
        $penjualan = DB::table('penjualan')
            ->selectRaw('4 as id_coa, SUM(piutang_dagang) as debet, 0 as kredit')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email))
            ->unionAll(
                DB::table('penjualan')
                    ->selectRaw('24 as id_coa, 0 as debet, 
                SUM((penjualan + biaya_angkut + ppn_keluaran) - 
                    ((penjualan + biaya_angkut + ppn_keluaran) * (discount/100))) as kredit')
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
                    ->when($email, fn($q) => $q->where('email', $email))
            );

        // pengeluaran
        $pengeluaranDebet = DB::table('pengeluaran')
            ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
            ->whereRaw("DATE_FORMAT(tanggal_kredit, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        $pengeluaranKredit = DB::table('pengeluaran')
            ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
            ->whereRaw("DATE_FORMAT(tanggal_kredit, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        // penerimaan
        $penerimaanDebet = DB::table('penerimaan')
            ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        $penerimaanKredit = DB::table('penerimaan')
            ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        // jurnal umum
        $jurnalDebet = DB::table('jurnal_umum')
            ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        $jurnalKredit = DB::table('jurnal_umum')
            ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        // ========================
        // Gabungkan semua transaksi
        // ========================
        $transaksi = DB::query()
            ->fromSub(
                $neracaAwal
                    ->unionAll($pembelian)
                    ->unionAll($penjualan)
                    ->unionAll($pengeluaranDebet)
                    ->unionAll($pengeluaranKredit)
                    ->unionAll($penerimaanDebet)
                    ->unionAll($penerimaanKredit)
                    ->unionAll($jurnalDebet)
                    ->unionAll($jurnalKredit),
                't'
            )
            ->select('id_coa', DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->groupBy('id_coa')
            ->get();

        $headerNeraca = HeaderCoa::with('masterCoa')
            ->whereIn('id', [1, 2, 3, 4, 5, 6])
            ->get()
            ->map(function ($header) use ($transaksi) {
                $header->masterCoa = $header->masterCoa->map(function ($coa) use ($transaksi) {
                    $match = $transaksi->firstWhere('id_coa', $coa->id);
                    $coa->debet = $match->total_debet ?? 0;
                    $coa->kredit = $match->total_kredit ?? 0;
                    return $coa;
                });
                return $header;
            });

        return response()->json([
            'status' => 'success',
            'laporan' => 'Neraca',
            'bulan' => $bulanTahun,
            'email' => $email,
            'data' => $headerNeraca
        ]);
    }

    // Laba Rugi
    // public function labaRugi()
    // {
    //     $data = MasterCoa::with('header')
    //         ->whereIn('id_header', [6, 7, 8, 9]) // Revenues & Expenses
    //         ->get()
    //         ->groupBy(fn($item) => $item->header->nama_header);

    //     // Hitung total
    //     $totalPendapatan = MasterCoa::whereIn('id_header', [6, 8])->sum('m_kredit');
    //     $totalBeban      = MasterCoa::whereIn('id_header', [7, 9])->sum('m_debet');
    //     $labaRugi        = $totalPendapatan - $totalBeban;

    //     return response()->json([
    //         'status' => 'success',
    //         'laporan' => 'Laba Rugi',
    //         'data' => $data,
    //         'total_pendapatan' => $totalPendapatan,
    //         'total_beban' => $totalBeban,
    //         'laba_rugi_bersih' => $labaRugi
    //     ]);
    // }

    public function labaRugi(Request $request)
    {
        $bulanTahun = $request->query('bulan', date('Y-m'));
        $email = $request->input('email');

        $neracaAwal = DB::table('neraca_awal')
            ->select('id_coa', 'debet', 'kredit')
            ->where('bln_tahun', $bulanTahun)
            ->when($email, fn($q) => $q->where('email', $email));

        // pembelian
        $pembelian = DB::table('pembelian')
            ->selectRaw("
            6 as id_coa, 
            SUM((persediaan_barang + biaya_angkut + ppn_masukan) - 
                ((persediaan_barang + biaya_angkut + ppn_masukan) * (discount/100))) as kredit, 
            0 as debet
        ")
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email))
            ->unionAll(
                DB::table('pembelian')
                    ->selectRaw("
                    15 as id_coa, 
                    0 as debet, 
                    SUM(hutang_dagang) as kredit
                ")
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
                    ->when($email, fn($q) => $q->where('email', $email))
            );

        // penjualan
        $penjualan = DB::table('penjualan')
            ->selectRaw('4 as id_coa, SUM(piutang_dagang) as debet, 0 as kredit')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email))
            ->unionAll(
                DB::table('penjualan')
                    ->selectRaw('24 as id_coa, 0 as debet, 
                SUM((penjualan + biaya_angkut + ppn_keluaran) - 
                    ((penjualan + biaya_angkut + ppn_keluaran) * (discount/100))) as kredit')
                    ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
                    ->when($email, fn($q) => $q->where('email', $email))
            );

        // pengeluaran
        $pengeluaranDebet = DB::table('pengeluaran')
            ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
            ->whereRaw("DATE_FORMAT(tanggal_kredit, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        $pengeluaranKredit = DB::table('pengeluaran')
            ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
            ->whereRaw("DATE_FORMAT(tanggal_kredit, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        // penerimaan
        $penerimaanDebet = DB::table('penerimaan')
            ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        $penerimaanKredit = DB::table('penerimaan')
            ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        // jurnal umum
        $jurnalDebet = DB::table('jurnal_umum')
            ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        $jurnalKredit = DB::table('jurnal_umum')
            ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
            ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
            ->when($email, fn($q) => $q->where('email', $email));

        // ========================
        // Gabungkan semua transaksi
        // ========================
        $transaksi = DB::query()
            ->fromSub(
                $neracaAwal
                    ->unionAll($pembelian)
                    ->unionAll($penjualan)
                    ->unionAll($pengeluaranDebet)
                    ->unionAll($pengeluaranKredit)
                    ->unionAll($penerimaanDebet)
                    ->unionAll($penerimaanKredit)
                    ->unionAll($jurnalDebet)
                    ->unionAll($jurnalKredit),
                't'
            )
            ->select('id_coa', DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->groupBy('id_coa')
            ->get();

        $headerNeraca = HeaderCoa::with('masterCoa')
            ->whereIn('id', [4, 6, 7, 8, 9])
            ->get()
            ->map(function ($header) use ($transaksi) {
                $header->masterCoa = $header->masterCoa->map(function ($coa) use ($transaksi) {
                    $match = $transaksi->firstWhere('id_coa', $coa->id);
                    $coa->debet = $match->total_debet ?? 0;
                    $coa->kredit = $match->total_kredit ?? 0;
                    return $coa;
                });
                return $header;
            });

        return response()->json([
            'status' => 'success',
            'laporan' => 'Neraca',
            'bulan' => $bulanTahun,
            'email' => $email,
            'data' => $headerNeraca
        ]);
    }
}
