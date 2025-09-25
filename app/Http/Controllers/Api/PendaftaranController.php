<?php
// app/Http/Controllers/PendaftaranController.php
namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\VerifyEmail;
use App\Models\UserRegist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Mail\PasswordEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PendaftaranController extends Controller
{
    public function store(Request $req)
    {
        // ambil field yang diperbolehkan
        $data = $req->only([
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
            'status'
        ]);

        // cek user by email
        $user = UserRegist::find($data['email'] ?? '');

        // jika belum ada -> buat baru (status 0, belum verif)
        if (!$user) {
            $data['status'] = 0;
            if (!empty($data['password'])) $data['password'] = $data['password'];
            $user = UserRegist::create($data);
            // kirim verifikasi
            $this->sendVerification($user);
            return response()->json(['message' => 'Registrasi dibuat. Cek email untuk verifikasi.'], 201);
        }

        // jika ada tapi belum verifikasi -> kirim ulang verifikasi (opsional update data)
        if (is_null($user->email_verified_at)) {
            // update beberapa field dari form jika ingin
            $user->update(array_filter([
                'nama' => $data['nama'] ?? null,
                'nama_usaha' => $data['nama_usaha'] ?? null,
                'jenis_usaha' => $data['jenis_usaha'] ?? null,
                'profesi_pekerjaan' => $data['profesi_pekerjaan'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
                'instagram' => $data['instagram'] ?? null,
                'facebook' => $data['facebook'] ?? null,
                'website' => $data['website'] ?? null,
            ], fn($v) => !is_null($v)));

            $this->sendVerification($user, /*resend*/ true);
            return response()->json(['message' => 'Email belum terverifikasi. Link verifikasi telah dikirim ulang.'], 202);
        }

        // jika sudah verifikasi -> tolak
        return response()->json(['message' => 'Email sudah terdaftar & terverifikasi.'], 409);
    }

    public function show(string $email): JsonResponse
    {
        $email = urldecode(trim($email));

        // 1) Cari di users (sudah terverifikasi)
        $user = User::where('email', $email)->first();

        // 2) Jika belum ada, cek user_regist (belum verifikasi)
        if (!$user) {
            // catatan: pastikan primaryKey model UserRegist = 'email'
            $user = UserRegist::find($email);
        }

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Normalisasi payload agar konsisten dengan kebutuhan FE
        return response()->json([
            'message' => 'OK',
            'data' => [
                'email'              => $user->email,
                'nama'               => $user->nama,
                'nama_usaha'         => $user->nama_usaha,
                'jenis_usaha'        => $user->jenis_usaha,
                'profesi_pekerjaan'  => $user->profesi_pekerjaan ?? $user->profesi ?? null,
                'alamat'             => $user->alamat,
                'no_hp'              => $user->no_hp,
                'instagram'          => $user->instagram,
                'facebook'           => $user->facebook,
                'website'            => $user->website,
                'password'            => $user->password,
                'created_at'         => $user->created_at ?? null,
                'updated_at'         => $user->updated_at ?? null,
            ]
        ], 200);
    }

    public function update(Request $request, $email)
    {
        if ($request->password) {
            $updateData = [
                'nama' =>  $request->nama,
                'nama_usaha' =>  $request->nama_usaha,
                'jenis_usaha' =>  $request->jenis_usaha,
                'profesi_pekerjaan' =>  $request->profesi_pekerjaan,
                'alamat' =>  $request->alamat,
                'no_hp' =>  $request->no_hp,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'password' => $request->password,
                'website' => $request->website,
            ];
        } else {
            $updateData = [
                'nama' =>  $request->nama,
                'nama_usaha' =>  $request->nama_usaha,
                'jenis_usaha' =>  $request->jenis_usaha,
                'profesi_pekerjaan' =>  $request->profesi_pekerjaan,
                'alamat' =>  $request->alamat,
                'no_hp' =>  $request->no_hp,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'website' => $request->website,
            ];
        }

        User::where('email', $email)->update($updateData);
    }

    public function index(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email tidak terdaftar']);
        } else {
            Mail::to($user->email)->send(new PasswordEmail($user->password, $user->nama ?? $user->email));
            return response()->json(['message' => 'Password sudah dikirim melalui email yang terdaftar']);
        }
    }

    protected function sendVerification(UserRegist $user, bool $resend = false): void
    {
        // generate token baru (override jika resend)
        $token = Str::random(64);
        EmailVerification::updateOrCreate(
            ['email' => $user->email],
            ['token' => $token, 'expires_at' => Carbon::now()->addHours(24)]
        );

        //$base = config('app.url'); // contoh: https://apivue.mykaskita.com
        $link = 'https://api.mykaskita.com/verify/' . $token;

        Mail::to($user->email)->send(new VerifyEmail($link, $user->nama ?? $user->email));
    }
}
