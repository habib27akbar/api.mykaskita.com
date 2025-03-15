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

    public function edit($id)
    {
        //$foto = Slider::all();
        //dd($sejarah);
        $komplain = Komplain::findOrFail($id);

        return view('komplain.edit', compact('komplain'));
    }

    public function store(Request $request)
    {
        $nama_image = null;
        if ($request->file('gambar')) {
            $image = $request->file('gambar');
            $nama_image = 'gambar-' . uniqid() . '-' . $image->getClientOriginalName();
            $dir = 'img/komplain';
            $image->move(public_path($dir), $nama_image);
        }

        $nama_image_galeri = null;
        if ($request->file('gambar_galeri')) {
            $image_galeri = $request->file('gambar_galeri');
            $nama_image_galeri = 'gambar_galeri-' . uniqid() . '-' . $image_galeri->getClientOriginalName();
            $dir = 'img/kunjungan';
            $image_galeri->move(public_path($dir), $nama_image_galeri);
        }

        $storeData = [
            'user_id' => auth()->id(),
            'pesan' => $request->input('pesan'),
            'gambar' => $nama_image,
            'gambar_galeri' => $nama_image_galeri,
            'sts' => 0,
        ];
        Komplain::create($storeData);
        return redirect('komplain')->with('alert-success', 'Success Tambah Data');
    }

    public function update(Request $request, $id)
    {

        $nama_image = $request->input('gambar_old');
        if ($request->file('gambar')) {
            $image = $request->file('gambar');
            $nama_image = 'gambar-' . uniqid() . '-' . $image->getClientOriginalName();
            $dir = 'img/komplain';
            $image->move(public_path($dir), $nama_image);
        }

        $nama_image_galeri = $request->input('gambar_galeri_old');
        if ($request->file('gambar_galeri')) {
            $image_galeri = $request->file('gambar_galeri');
            $nama_image_galeri = 'gambar_galeri-' . uniqid() . '-' . $image_galeri->getClientOriginalName();
            $dir = 'img/komplain';
            $image_galeri->move(public_path($dir), $nama_image_galeri);
        }

        $updateData = [
            'user_id' => auth()->id(),
            'pesan' => $request->input('pesan'),
            'gambar' => $nama_image,
            'gambar_galeri' => $nama_image_galeri
        ];
        Komplain::where('id', $id)->update($updateData);
        return redirect('komplain')->with('alert-success', 'Success Update Data');
    }

    public function destroy($id)
    {
        Komplain::findOrFail($id)->delete();
        return redirect('komplain')->with('alert-success', 'Success deleted data');
    }

    public function getKomplain(Request $request)
    {
        $sort = $request->query('sort', 'newest'); // Default sort: newest

        $query = Komplain::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search); // Konversi ke huruf kecil untuk pencarian tidak case-sensitive
            $query->whereRaw('LOWER(pesan) LIKE ?', ["%{$search}%"]);
        }

        if ($sort === 'oldest') {
            $query->orderByRaw('COALESCE(updated_at, created_at) ASC');
        } elseif ($sort === 'newest') {
            $query->orderByRaw('COALESCE(updated_at, created_at) DESC');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $komplain = $query->paginate(10); // 6 data per halaman

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
