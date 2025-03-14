<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komplain;
use Carbon\Carbon;

class KomplainController extends Controller
{
    //
    public function index()
    {

        return view('komplain.index');
    }

    public function create()
    {

        return view('komplain.create');
    }

    public function store(Request $request)
    {
        $nama_image = null;
        if ($request->file('gambar')) {
            $image = $request->file('gambar');
            $nama_image = 'gambar-' . uniqid() . '-' . $image->getClientOriginalName();
            $dir = 'img/produk';
            $image->move(public_path($dir), $nama_image);
        }

        $storeData = [
            'user_id' => auth()->id(),
            'pesan' => $request->input('pesan'),
            'gambar' => $nama_image,
            'sts' => 0,
        ];
        Komplain::create($storeData);
        return redirect('komplain')->with('alert-success', 'Success Tambah Data');
    }

    public function getKomplain(Request $request)
    {
        $sort = $request->query('sort', 'newest'); // Default sort: newest

        $query = Komplain::query();

        if ($sort === 'oldest') {
            $query->orderByRaw('COALESCE(updated_at, created_at) ASC');
        } elseif ($sort === 'newest') {
            $query->orderByRaw('COALESCE(updated_at, created_at) DESC');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $komplain = $query->paginate(6); // 6 data per halaman

        // Format tanggal tanpa menghilangkan pagination
        $komplain->getCollection()->map(function ($item) {
            $item->created_at_formatted = Carbon::parse($item->created_at)->format('d M Y H:i');
            if ($item->updated_at) {
                $item->updated_at_formatted = Carbon::parse($item->updated_at)->format('d M Y H:i');
            }

            return $item;
        });

        return response()->json($komplain);
    }
}
