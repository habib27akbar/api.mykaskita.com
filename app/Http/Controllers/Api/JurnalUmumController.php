<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\JurnalUmum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $query = JurnalUmum::query()
            ->leftJoin('master_coa as coa_debet', 'coa_debet.id', '=', 'jurnal_umum.id_coa_debet')
            ->leftJoin('master_coa as coa_kredit', 'coa_kredit.id', '=', 'jurnal_umum.id_coa_kredit')
            ->select(
                'jurnal_umum.*',
                'coa_debet.nama_akun_ind as nama_coa_debet',
                'coa_kredit.nama_akun_ind as nama_coa_kredit'
            );
        // Filter berdasarkan email
        if ($request->has('email')) {
            $query->where('email', $request->email);
        }

        // Pencarian optional
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('tanggal_debet', 'like', '%' . $request->search . '%')
                    ->orWhere('debet', 'like', '%' . $request->search . '%')
                    ->orWhere('kredit', 'like', '%' . $request->search . '%');
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
        $data = JurnalUmum::leftJoin('master_coa as coa_debet', 'coa_debet.id', '=', 'jurnal_umum.id_coa_debet')
            ->leftJoin('master_coa as coa_kredit', 'coa_kredit.id', '=', 'jurnal_umum.id_coa_kredit')
            ->whereBetween('jurnal_umum.tanggal_debet', [$tanggalMulai, $tanggalAkhir])
            ->where('jurnal_umum.email', $email)
            ->orderBy('jurnal_umum.tanggal_debet', 'asc')
            ->get([
                'jurnal_umum.*',
                'coa_debet.nama_akun_ind as nama_coa_debet',
                'coa_kredit.nama_akun_ind as nama_coa_kredit',
            ]);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->has('local_id')) {
            $existing = JurnalUmum::where('local_id', $request->local_id)->first();
            if ($existing) {
                return response()->json(['message' => 'Data sudah ada', 'data' => $existing], 200);
            }
        }
        $storeData = [
            'email' => $request->email,
            'tanggal_debet' =>  $request->tanggal_debet,
            'id_coa_debet' =>  $request->id_coa_debet,
            'id_coa_kredit' =>  $request->id_coa_kredit,
            'debet' =>  $request->debet,
            'kredit' =>  $request->kredit,
            'local_id' => $request->local_id,
        ];
        JurnalUmum::create($storeData);
    }

    public function update(Request $request, $id)
    {
        $updateData = [
            'email' => $request->email,
            'tanggal_debet' =>  $request->tanggal_debet,
            'id_coa_debet' =>  $request->id_coa_debet,
            'id_coa_kredit' =>  $request->id_coa_kredit,
            'debet' =>  $request->debet,
            'kredit' =>  $request->kredit,
        ];
        JurnalUmum::where('id', $id)->update($updateData);
    }

    public function destroy($id)
    {
        JurnalUmum::findOrFail($id)->delete();
    }
}
