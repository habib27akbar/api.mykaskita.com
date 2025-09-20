<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::query();

        // Filter berdasarkan email
        if ($request->has('email')) {
            $query->where('email', $request->email);
        }

        // Pencarian optional
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('no_faktur', 'like', '%' . $request->search . '%')
                    ->orWhere('keterangan', 'like', '%' . $request->search . '%')
                    ->orWhere('tanggal', 'like', '%' . $request->search . '%');
            });
        }

        // Urutan
        if ($request->has('sortBy')) {
            $query->orderBy($request->sortBy, $request->sortDesc === 'true' ? 'desc' : 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $data = $query->paginate($request->perPage ?? 10);

        return response()->json($data);
    }

    public function laporan(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;
        $email = $request->email;
        $data = Pembelian::whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir])
            ->where('email', $email)
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->has('local_id')) {
            $existing = Pembelian::where('local_id', $request->local_id)->first();
            if ($existing) {
                return response()->json(['message' => 'Data sudah ada', 'data' => $existing], 200);
            }
        }
        $storeData = [
            'email' => $request->email,
            'tanggal' =>  $request->tanggal,
            'tanggal_bayar' =>  $request->tanggal_bayar,
            'no_faktur' =>  $request->no_faktur,
            'keterangan' =>  $request->keterangan,
            'ref' =>  $request->ref,
            'biaya_angkut' => str_replace(".", "", $request->biaya_angkut),
            'ppn_persen' => $request->ppn_persen,
            'ppn_masukan' => str_replace(".", "", $request->ppn_masukan),
            'persediaan_barang' => str_replace(".", "", $request->persediaan_barang),
            'hutang_dagang' => str_replace(".", "", $request->hutang_dagang),
            'discount' => $request->discount ? $request->discount : '0',
            'local_id' => $request->local_id,
        ];
        Pembelian::create($storeData);
    }

    public function update(Request $request, $id)
    {
        $updateData = [
            'email' => $request->email,
            'tanggal' =>  $request->tanggal,
            'tanggal_bayar' =>  $request->tanggal_bayar,
            'no_faktur' =>  $request->no_faktur,
            'keterangan' =>  $request->keterangan,
            'ref' =>  $request->ref,
            'biaya_angkut' => str_replace(".", "", $request->biaya_angkut),
            'ppn_persen' => $request->ppn_persen,
            'ppn_masukan' => str_replace(".", "", $request->ppn_masukan),
            'persediaan_barang' => str_replace(".", "", $request->persediaan_barang),
            'hutang_dagang' => str_replace(".", "", $request->hutang_dagang),
            'discount' =>  $request->discount ? $request->discount : '0',
        ];
        Pembelian::where('id', $id)->update($updateData);
    }

    public function destroy($id)
    {
        Pembelian::findOrFail($id)->delete();
    }
}
