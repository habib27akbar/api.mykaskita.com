<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()->where('status', '1');


        // Pencarian optional
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhere('isi', 'like', '%' . $request->search . '%')
                    ->orWhere('tgl', 'like', '%' . $request->search . '%')
                    ->orWhere('sumber', 'like', '%' . $request->search . '%');
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
}
