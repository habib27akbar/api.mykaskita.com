<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitKerja;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unit_kerja = UnitKerja::all();
        return view('master.unit_kerja.index', compact('unit_kerja'));
    }

    public function create()
    {

        return view('master.unit_kerja.create');
    }

    public function store(Request $request)
    {


        $storeData = [
            'unit_kerja' => $request->input('unit_kerja')
        ];
        UnitKerja::create($storeData);
        return redirect('unit_kerja')->with('alert-success', 'Success Tambah Data');
    }


    public function edit($id)
    {
        //$foto = Slider::all();
        //dd($sejarah);
        $unit_kerja = UnitKerja::findOrFail($id);

        return view('master.unit_kerja.edit', compact('unit_kerja'));
    }

    public function update(Request $request, $id)
    {


        $updateData = [
            'unit_kerja' => $request->input('unit_kerja')
        ];
        UnitKerja::where('id', $id)->update($updateData);
        return redirect('unit_kerja')->with('alert-success', 'Success Update Data');
    }

    public function destroy($id)
    {
        UnitKerja::findOrFail($id)->delete();
        return redirect('unit_kerja')->with('alert-success', 'Success deleted data');
    }
}
