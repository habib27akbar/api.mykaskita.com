<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('master.produk.index', compact('produk'));
    }

    public function create()
    {

        return view('master.produk.create');
    }

    public function store(Request $request)
    {


        $storeData = [
            'nama_produk' => $request->input('nama_produk')
        ];
        Produk::create($storeData);
        return redirect('produk')->with('alert-success', 'Success Tambah Data');
    }


    public function edit($id)
    {
        //$foto = Slider::all();
        //dd($sejarah);
        $produk = Produk::findOrFail($id);

        return view('master.produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {


        $updateData = [
            'nama_produk' => $request->input('nama_produk')
        ];
        Produk::where('id', $id)->update($updateData);
        return redirect('produk')->with('alert-success', 'Success Update Data');
    }

    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();
        return redirect('produk')->with('alert-success', 'Success deleted data');
    }
}
