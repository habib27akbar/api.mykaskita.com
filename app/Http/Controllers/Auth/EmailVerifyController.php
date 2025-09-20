<?php
// app/Http/Controllers/Auth/EmailVerifyController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\UserRegist;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class EmailVerifyController extends Controller
{
    public function verify(string $token)
    {
        $rec = EmailVerification::where('token', $token)->first();

        if (!$rec) {
            // Token tidak ditemukan / sudah dipakai
            return response()->view('auth.verify-result', [
                'title'   => 'Token Tidak Valid',
                'status'  => 'error',
                'message' => 'Token verifikasi tidak valid atau sudah digunakan.',
            ], Response::HTTP_NOT_FOUND);
        }

        $reg = UserRegist::find($rec->email);
        if (!$reg) {
            return response()->view('auth.verify-result', [
                'title'   => 'Pengguna Tidak Ditemukan',
                'status'  => 'error',
                'message' => 'Data pendaftaran tidak ditemukan.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Kalau sudah terverifikasi sebelumnya
        if ($reg->email_verified_at) {
            // Token lama dihapus agar tidak bisa dipakai ulang
            $rec->delete();
            return response()->view('auth.verify-result', [
                'title'   => 'Sudah Terverifikasi',
                'status'  => 'info',
                'message' => 'Email Anda sudah terverifikasi. Silakan login.',
            ]);
        }

        DB::transaction(function () use ($reg, $rec) {
            // 1) Tandai terverifikasi di tabel registrasi
            $reg->email_verified_at = Carbon::now();
            $reg->status = 1;
            $reg->save();

            // 2) Salin/Upsert ke users
            $copy = $reg->only([
                'email',
                'nama',
                'nama_usaha',
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

            User::updateOrCreate(['email' => $reg->email], $copy);

            // 3) Hapus token supaya sekali pakai
            $rec->delete();
        });

        return view('auth.verify-result', [
            'title'   => 'Verifikasi Berhasil',
            'status'  => 'success',
            'message' => 'Akun Anda sudah aktif. Silakan login.',
            // optional: 'redirectTo' => route('login'),
        ]);
    }
}
