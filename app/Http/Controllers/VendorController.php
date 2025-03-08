<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::all();
        return view('master.vendor.index', compact('vendor'));
    }

    public function create()
    {

        return view('master.vendor.create');
    }

    public function store(Request $request)
    {


        $storeData = [
            'nama_vendor' => $request->input('nama_vendor'),
            'telp' => $request->input('telp'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
        ];
        Vendor::create($storeData);
        return redirect('vendor_app')->with('alert-success', 'Success Tambah Data');
    }


    public function edit($id)
    {
        //$foto = Slider::all();
        //dd($sejarah);
        $vendor = Vendor::findOrFail($id);

        return view('master.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {


        $updateData = [
            'nama_vendor' => $request->input('nama_vendor'),
            'telp' => $request->input('telp'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
        ];
        Vendor::where('id', $id)->update($updateData);
        return redirect('vendor_app')->with('alert-success', 'Success Update Data');
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return redirect('vendor_app')->with('alert-success', 'Success deleted data');
    }
}
