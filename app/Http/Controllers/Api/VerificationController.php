<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserRegist;
use App\Models\EmailVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    public function verify(string $token): JsonResponse
    {
        //exit;
        $rec = EmailVerification::where('token', $token)->first();
        if (!$rec) return response()->json(['message' => 'Token verifikasi tidak valid'], 404);

        $reg = UserRegist::find($rec->email);
        if (!$reg) return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);



        DB::transaction(function () use ($reg, $rec) {

            // 1) Tandai terverifikasi di tabel registrasi
            $reg->email_verified_at = Carbon::now();
            $reg->status = 1;
            $reg->save();

            // 2) Salin data ke tabel users (idempotent)
            $copy = $reg->only([
                'email',
                'nama',
                'nama_usaha',
                'jenis_usaha',
                'profesi_pekerjaan',
                'alamat',
                'no_hp',
                'instagram',
                'facebook',
                'website',
                'alasan',
                'password',
                'foto',
                'status',
                'email_verified_at'
            ]);

            // var_dump($copy);
            // exit;

            User::updateOrCreate(
                ['email' => $reg->email], // kunci upsert
                $copy
            );
        });

        return response()->json(['message' => 'Verifikasi berhasil, akun aktif.']);
    }

    // public function verify(string $token)
    // {
    //     $rec = EmailVerification::where('token', $token)->first();

    //     if (!$rec) {
    //         // Token tidak ditemukan / sudah dipakai
    //         return response()->view('auth.verify-result', [
    //             'title'   => 'Token Tidak Valid',
    //             'status'  => 'error',
    //             'message' => 'Token verifikasi tidak valid atau sudah digunakan.',
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     $reg = UserRegist::find($rec->email);
    //     if (!$reg) {
    //         return response()->view('auth.verify-result', [
    //             'title'   => 'Pengguna Tidak Ditemukan',
    //             'status'  => 'error',
    //             'message' => 'Data pendaftaran tidak ditemukan.',
    //         ], Response::HTTP_NOT_FOUND);
    //     }

    //     // Kalau sudah terverifikasi sebelumnya
    //     if ($reg->email_verified_at) {
    //         // Token lama dihapus agar tidak bisa dipakai ulang
    //         $rec->delete();
    //         return response()->view('auth.verify-result', [
    //             'title'   => 'Sudah Terverifikasi',
    //             'status'  => 'info',
    //             'message' => 'Email Anda sudah terverifikasi. Silakan login.',
    //         ]);
    //     }

    //     DB::transaction(function () use ($reg, $rec) {
    //         // 1) Tandai terverifikasi di tabel registrasi
    //         $reg->email_verified_at = Carbon::now();
    //         $reg->status = 1;
    //         $reg->save();

    //         // 2) Salin/Upsert ke users
    //         $copy = $reg->only([
    //             'email',
    //             'nama',
    //             'nama_usaha',
    //             'profesi_pekerjaan',
    //             'alamat',
    //             'no_hp',
    //             'instagram',
    //             'facebook',
    //             'website',
    //             'alasan',
    //             'password',
    //             'foto',
    //             'status',
    //             'email_verified_at'
    //         ]);

    //         User::updateOrCreate(['email' => $reg->email], $copy);

    //         // 3) Hapus token supaya sekali pakai
    //         $rec->delete();
    //     });

    //     return view('auth.verify-result', [
    //         'title'   => 'Verifikasi Berhasil',
    //         'status'  => 'success',
    //         'message' => 'Akun Anda sudah aktif. Silakan login.',
    //         // optional: 'redirectTo' => route('login'),
    //     ]);
    // }
}
