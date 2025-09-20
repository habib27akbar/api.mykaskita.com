<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Auth\EmailVerifyController;


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

Route::get('/verify/{token}', [EmailVerifyController::class, 'verify'])
    ->middleware('web')
    ->name('verify.email');
