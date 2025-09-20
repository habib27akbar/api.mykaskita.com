{{-- resources/views/emails/verify.blade.php --}}
<!doctype html>
<html>
<body>
  <p>Halo {{ $nama }},</p>
  <p>Terima kasih telah mendaftar kaskita. Silakan verifikasi email Anda dengan menekan tombol di bawah ini:</p>
  <p><a href="{{ $link }}" style="background:#2563eb;color:#fff;padding:10px 16px;text-decoration:none;border-radius:6px">Verifikasi Email</a></p>
  <p>Jika tombol tidak berfungsi, salin dan buka tautan ini di browser:<br>{{ $link }}</p>
  <p>Link verifikasi dapat kadaluarsa. Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
  <p>Setelah email berhasil terverifikasi silahkan masuk kedalam aplikasi</p>
</body>
</html>
