<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\UserRegist;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;


class EmailVerificationController extends Controller
{
    public function verify(string $token)
    {
        // 1) Cari token
        $rec = EmailVerification::where('token', $token)->first();

        if (!$rec) {
            // Token tidak ada / sudah dipakai
            return view('auth.verify-result', [
                'title'   => 'Token Tidak Valid',
                'status'  => 'error',
                'message' => 'Token verifikasi tidak valid atau sudah digunakan.',
            ]);
        }

        // 2) Ambil record registrasi berdasarkan email di tabel verifikasi
        $reg = UserRegist::find($rec->email);
        if (!$reg) {
            return view('auth.verify-result', [
                'title'   => 'Pengguna Tidak Ditemukan',
                'status'  => 'error',
                'message' => 'Data pendaftaran tidak ditemukan.',
            ]);
        }

        // 3) Jika sudah terverifikasi sebelumnya → idempotent
        if ($reg->email_verified_at) {
            // Hapus token lama agar tidak bisa dipakai ulang
            try {
                $rec->delete();
            } catch (\Throwable $e) {
                //Log::warning('Delete token fail: ' . $e->getMessage());
            }
            return view('auth.verify-result', [
                'title'   => 'Sudah Terverifikasi',
                'status'  => 'info',
                'message' => 'Email Anda sudah terverifikasi. Silakan login.',
            ]);
        }

        // 4) Proses verifikasi dalam transaksi (dengan kunci agar aman dari balapan)
        try {
            DB::transaction(function () use ($reg, $rec) {
                // Lock baris registrasi saat update
                $reg = UserRegist::where('email', $reg->email)->lockForUpdate()->first();

                $now = Carbon::now();

                // Tandai terverifikasi
                $reg->email_verified_at = $now;
                $reg->status            = 1;
                $reg->save();

                // Siapkan data untuk upsert ke users
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

                // Penting: pastikan password di UserRegist sudah HASH.
                // Jika di UserRegist masih plain, lakukan hashing di sini.
                // $copy['password'] = Hash::needsRehash($copy['password']) ? Hash::make($copy['password']) : $copy['password'];

                // var_dump($copy);
                // exit;

                User::updateOrCreate(['email' => $reg->email], $copy);

                // Token sekali pakai
                $rec->delete();
            });
        } catch (\Throwable $e) {
            //Log::error('Email verify failed: ' . $e->getMessage());
            return view('auth.verify-result', [
                'title'   => 'Terjadi Kesalahan',
                'status'  => 'error',
                'message' => 'Maaf, terjadi kesalahan saat memverifikasi akun. Coba beberapa saat lagi.',
            ]);
        }
        exit;
        // 5) Sukses
        return view('auth.verify-result', [
            'title'   => 'Verifikasi Berhasil',
            'status'  => 'success',
            'message' => 'Akun Anda sudah aktif. Silakan login.',
            // 'redirectTo' => route('login'), // aktifkan jika ingin auto-redirect di Blade
        ]);
    }
}
