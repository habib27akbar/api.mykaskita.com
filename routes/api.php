<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\NeracaController;
use App\Http\Controllers\Api\FinanceController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\PembelianController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\AiOrchestratorController;
use App\Http\Controllers\Api\JurnalUmumController;
use App\Http\Controllers\Api\PenerimaanController;
use App\Http\Controllers\Api\NeracaSaldoController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Api\PengeluaranController;
use App\Http\Controllers\Api\VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/pembelian', [PembelianController::class, 'index']);
Route::post('/pembelian', [PembelianController::class, 'store']);
Route::put('/pembelian/{id}', [PembelianController::class, 'update']);
Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy']);
Route::get('/laporan/pembelian', [PembelianController::class, 'laporan']);



Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::post('/penjualan', [PenjualanController::class, 'store']);
Route::put('/penjualan/{id}', [PenjualanController::class, 'update']);
Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy']);
Route::get('/laporan/penjualan', [PenjualanController::class, 'laporan']);


Route::get('/penerimaan', [PenerimaanController::class, 'index']);
Route::post('/penerimaan', [PenerimaanController::class, 'store']);
Route::put('/penerimaan/{id}', [PenerimaanController::class, 'update']);
Route::delete('/penerimaan/{id}', [PenerimaanController::class, 'destroy']);
Route::get('/laporan/penerimaan', [PenerimaanController::class, 'laporan']);

Route::get('/jurnal_umum', [JurnalUmumController::class, 'index']);
Route::post('/jurnal_umum', [JurnalUmumController::class, 'store']);
Route::put('/jurnal_umum/{id}', [JurnalUmumController::class, 'update']);
Route::delete('/jurnal_umum/{id}', [JurnalUmumController::class, 'destroy']);
Route::get('/laporan/jurnal_umum', [JurnalUmumController::class, 'laporan']);


Route::get('/pengeluaran', [PengeluaranController::class, 'index']);
Route::post('/pengeluaran', [PengeluaranController::class, 'store']);
Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update']);
Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy']);
Route::get('/laporan/pengeluaran', [PengeluaranController::class, 'laporan']);

Route::get('/coa', [CoaController::class, 'index']);

Route::get('/neraca-awal', [NeracaController::class, 'index']);
Route::post('/neraca-awal', [NeracaController::class, 'store']);

Route::get('/neraca-saldo', [NeracaSaldoController::class, 'index']);

Route::get('/laporan/neraca', [LaporanController::class, 'neraca']);
Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi']);

Route::get('/laporan/arus-kas', [FinanceController::class, 'arusKas']);

Route::get('/berita', [BeritaController::class, 'index']);

Route::get('/event', [EventController::class, 'index']);

Route::post('/register', [PendaftaranController::class, 'store']);
Route::get('/profil/{email}', [PendaftaranController::class, 'show']);
Route::put('/profil/{email}', [PendaftaranController::class, 'update']);

Route::post('/forgot-password', [PendaftaranController::class, 'index']);

Route::get('/verify/{token}', [VerificationController::class, 'verify'])
    ->name('veriry.email');

Route::post('/ai/analyze', [AiOrchestratorController::class, 'analyze']); // tambahkan auth jika perlu
