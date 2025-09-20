<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Coa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CoaController extends Controller
{
    public function index()
    {
        $data = Coa::orderBy('nomor_akun', 'asc')->get();
        return response()->json($data);
    }
}
