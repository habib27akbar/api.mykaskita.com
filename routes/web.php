<?php

<<<<<<< HEAD

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Auth\EmailVerifyController;

=======
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KomplainController;
use App\Http\Controllers\KunjunganController;
>>>>>>> e92709dadf761bb5743b7595b7e4d812ec08228e

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');
<<<<<<< HEAD

=======
Route::get('/home', [HomeController::class, 'index'])->name('home');
>>>>>>> e92709dadf761bb5743b7595b7e4d812ec08228e
Route::post('/login', [AuthController::class, 'login'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/captcha-refresh', function () {
//     return response()->json(['captcha' => captcha_src()]);
// })->name('captcha.refresh');



Route::get('/home', function (Request $request) {
    // if (!session('email')) {
    //     return redirect('/'); // redirect ke login jika belum login
    // }

    // Jika sudah login, panggil controller seperti biasa
    return app(HomeController::class)->index($request);
})->name('home');

<<<<<<< HEAD
Route::get('/verify/{token}', [EmailVerifyController::class, 'verify'])
    ->middleware('web')
    ->name('verify.email');
=======
    Route::resource('auth', AuthController::class);
    Route::resource('komplain', KomplainController::class);
    Route::get('/api/komplain', [KomplainController::class, 'getKomplain']);
    Route::resource('kunjungan', KunjunganController::class);
    Route::get('/api/kunjungan', [KunjunganController::class, 'getKunjungan']);
    Route::post('/kunjungan/{kunjungan}/absen', [KunjunganController::class, 'absen'])
        ->name('kunjungan.absen');
});
>>>>>>> e92709dadf761bb5743b7595b7e4d812ec08228e
