<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        return view('master.customer.index', compact('customer'));
    }

    public function create()
    {

        return view('master.customer.create');
    }

    public function store(Request $request)
    {


        $storeData = [
            'nama_customer' => $request->input('nama_customer'),
            'telp' => $request->input('telp'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
        ];
        Customer::create($storeData);
        return redirect('customer')->with('alert-success', 'Success Tambah Data');
    }


    public function edit($id)
    {
        //$foto = Slider::all();
        //dd($sejarah);
        $customer = Customer::findOrFail($id);

        return view('master.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {


        $updateData = [
            'nama_customer' => $request->input('nama_customer'),
            'telp' => $request->input('telp'),
            'email' => $request->input('email'),
            'alamat' => $request->input('alamat'),
        ];
        Customer::where('id', $id)->update($updateData);
        return redirect('customer')->with('alert-success', 'Success Update Data');
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect('customer')->with('alert-success', 'Success deleted data');
    }
}
