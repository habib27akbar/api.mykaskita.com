<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    public function index(Request $request)
    {
        $bulanTahun = $request->input('bln_tahun') ?: date('Y-m'); // format: YYYY-MM
        $email = $request->input('email');

        // cari bulan sebelumnya
        $bulanSebelumnya = date('Y-m', strtotime($bulanTahun . " -1 month"));

        // --- Neraca awal bulan sebelumnya (sebagai saldo awal bulan ini) ---
        // $neracaAwal = DB::table('neraca_awal')
        //     ->select('id_coa', 'debet', 'kredit')
        //     ->where('bln_tahun', $bulanSebelumnya)
        //     ->when($email, fn($q) => $q->where('email', $email));
        $neracaAwalAda = DB::table('neraca_awal')
            ->where('bln_tahun', $bulanTahun)
            ->when($email, fn($q) => $q->where('email', $email))
            ->exists();

        // kalau ada, pakai bulan ini
        if ($neracaAwalAda) {
            $neracaAwal = DB::table('neraca_awal')
                ->select('id_coa', 'debet', 'kredit')
                ->where('bln_tahun', $bulanTahun)
                ->when($email, fn($q) => $q->where('email', $email));

            $union = $neracaAwal;
        } else {
            // fallback ke bulan sebelumnya
            $neracaAwal = DB::table('neraca_awal')
                ->select('id_coa', 'debet', 'kredit')
                ->where('bln_tahun', $bulanSebelumnya)
                ->when($email, fn($q) => $q->where('email', $email));

            // pembelian (Persediaan - Debet, Hutang Dagang - Kredit)
            $pembelian = DB::table('pembelian')
                ->selectRaw("
        6 as id_coa, 
        SUM((persediaan_barang + biaya_angkut + ppn_masukan) - ((persediaan_barang + biaya_angkut + ppn_masukan) * (discount/100))) as debet, 
        0 as kredit
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

            // penjualan (Piutang Dagang - Debet, Pendapatan - Kredit)
            $penjualan = DB::table('penjualan')
                ->selectRaw('4 as id_coa, SUM(piutang_dagang) as debet, 0 as kredit')
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email))
                ->unionAll(
                    DB::table('penjualan')
                        ->selectRaw('24 as id_coa, 0 as debet, SUM((penjualan + biaya_angkut + ppn_keluaran) - ((penjualan + biaya_angkut + ppn_keluaran) * (discount/100))) as kredit')
                        ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanTahun])
                        ->when($email, fn($q) => $q->where('email', $email))
                );

            // pengeluaran debet
            $pengeluaranDebet = DB::table('pengeluaran')
                ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
                ->whereRaw("DATE_FORMAT(tanggal_kredit, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email));

            // pengeluaran kredit
            $pengeluaranKredit = DB::table('pengeluaran')
                ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
                ->whereRaw("DATE_FORMAT(tanggal_kredit, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email));

            // penerimaan debet
            $penerimaanDebet = DB::table('penerimaan')
                ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
                ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email));

            // penerimaan kredit
            $penerimaanKredit = DB::table('penerimaan')
                ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
                ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email));

            // jurnal umum debet
            $jurnalDebet = DB::table('jurnal_umum')
                ->select('id_coa_debet as id_coa', 'debet', DB::raw('0 as kredit'))
                ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email));

            // jurnal umum kredit
            $jurnalKredit = DB::table('jurnal_umum')
                ->select('id_coa_kredit as id_coa', DB::raw('0 as debet'), 'kredit')
                ->whereRaw("DATE_FORMAT(tanggal_debet, '%Y-%m') = ?", [$bulanTahun])
                ->when($email, fn($q) => $q->where('email', $email));

            // --- Union semua ---
            $union = $neracaAwal
                ->unionAll($pembelian)
                ->unionAll($penjualan)
                ->unionAll($pengeluaranDebet)
                ->unionAll($pengeluaranKredit)
                ->unionAll($penerimaanDebet)
                ->unionAll($penerimaanKredit)
                ->unionAll($jurnalDebet)
                ->unionAll($jurnalKredit);
        }




        $subquery = DB::table(DB::raw("({$union->toSql()}) as data"))
            ->mergeBindings($union)
            ->select('id_coa', DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->groupBy('id_coa');

        // --- LEFT JOIN dengan master_coa agar semua akun muncul ---
        $result = DB::table('master_coa as coa')
            ->leftJoinSub($subquery, 't', 'coa.id', '=', 't.id_coa')
            ->select(
                'coa.id as id_coa',
                'coa.nomor_akun',
                'coa.nama_akun_ind as nama_akun',
                DB::raw('IFNULL(t.total_debet,0) as total_debet'),
                DB::raw('IFNULL(t.total_kredit,0) as total_kredit')
            )
            ->orderBy('coa.nomor_akun')
            ->get();

        return response()->json($result);
    }

    public function neracaSaldo(Request $request)
    {
        $bulanTahun = $request->input('bln_tahun') ?: date('Y-m'); // format: YYYY-MM
        $email = $request->input('email');

        // bulan sebelumnya untuk saldo awal
        $bulanSebelumnya = date('Y-m', strtotime($bulanTahun . " -1 month"));

        // --- Ambil saldo awal dari neraca_awal bulan sebelumnya ---
        $neracaAwal = DB::table('neraca_awal')
            ->select('id_coa', 'debet', 'kredit')
            ->where('bln_tahun', $bulanSebelumnya)
            ->when($email, fn($q) => $q->where('email', $email));

        // --- Transaksi bulan ini ---

        // pembelian (Persediaan - Debet, Hutang Dagang - Kredit)
        $pembelian = DB::table('pembelian')
            ->selectRaw("
            6 as id_coa, 
            SUM((persediaan_barang + biaya_angkut + ppn_masukan) - 
                ((persediaan_barang + biaya_angkut + ppn_masukan) * (discount/100))) as debet, 
            0 as kredit
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

        // penjualan (Piutang Dagang - Debet, Pendapatan - Kredit)
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

        // --- Union semua ---
        $union = $neracaAwal
            ->unionAll($pembelian)
            ->unionAll($penjualan)
            ->unionAll($pengeluaranDebet)
            ->unionAll($pengeluaranKredit)
            ->unionAll($penerimaanDebet)
            ->unionAll($penerimaanKredit)
            ->unionAll($jurnalDebet)
            ->unionAll($jurnalKredit);

        // Subquery hitung total per akun
        $subquery = DB::table(DB::raw("({$union->toSql()}) as data"))
            ->mergeBindings($union)
            ->select('id_coa', DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(kredit) as total_kredit'))
            ->groupBy('id_coa');

        // LEFT JOIN dengan master_coa supaya semua akun muncul
        $result = DB::table('master_coa as coa')
            ->leftJoinSub($subquery, 't', 'coa.id', '=', 't.id_coa')
            ->select(
                'coa.id as id_coa',
                'coa.nomor_akun',
                'coa.nama_akun_ind as nama_akun',
                DB::raw('IFNULL(t.total_debet,0) as total_debet'),
                DB::raw('IFNULL(t.total_kredit,0) as total_kredit')
            )
            ->orderBy('coa.nomor_akun')
            ->get();

        return response()->json($result);
    }


    public function store(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'bln_tahun' => 'required|date_format:Y-m', // format YYYY-MM
            'data'      => 'required|array',
            'data.*.id_coa' => 'required|integer|exists:master_coa,id',
            'data.*.debet'  => 'nullable|numeric',
            'data.*.kredit' => 'nullable|numeric',
        ]);

        $email = $request->input('email');
        $blnTahun = $request->input('bln_tahun');
        $data = $request->input('data');

        foreach ($data as $row) {
            DB::table('neraca_awal')->updateOrInsert(
                [
                    'email'     => $email,
                    'bln_tahun' => $blnTahun,
                    'id_coa'    => $row['id_coa'],
                ],
                [
                    'debet'  => $row['debet'] ?? 0,
                    'kredit' => $row['kredit'] ?? 0,
                ]
            );
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Neraca awal berhasil disimpan',
        ]);
    }
}
