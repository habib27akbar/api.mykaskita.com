{{-- resources/views/auth/verify-result.blade.php --}}
@php
  $title   = $title   ?? 'Verifikasi Email';
  $status  = $status  ?? 'info';     // success | error | info
  $message = $message ?? '';

  $palette = [
    'success' => ['bg' => '#E6F4EA', 'fg' => '#1E7E34', 'icon' => '✓'],
    'error'   => ['bg' => '#FDECEA', 'fg' => '#B02A37', 'icon' => '!'],
    'info'    => ['bg' => '#E7F1FF', 'fg' => '#0B5ED7', 'icon' => 'i'],
  ][$status] ?? ['bg' => '#E7F1FF', 'fg' => '#0B5ED7', 'icon' => 'i'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title }}</title>
  <style>
    :root{
      --card-max: 680px;
      --radius: 12px;
      --shadow: 0 8px 24px rgba(0,0,0,.08);
      --gap: 16px;
      --primary: #4F46E5; /* indigo untuk tombol */
      --primary-hover: #4338CA;
      --text: #1F2937;
      --muted: #6B7280;
    }
    html,body{margin:0;padding:0;background:#0F172A;color:var(--text);font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,"Helvetica Neue",Arial,"Noto Sans","Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";line-height:1.5}
    .wrap{min-height:100svh;display:grid;place-items:center;padding:24px}
    .card{width:100%;max-width:var(--card-max);background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:24px}
    .head{display:flex;align-items:center;gap:12px;margin-bottom:12px}
    .icon{width:40px;height:40px;border-radius:999px;display:grid;place-items:center;font-weight:700}
    .title{font-size:20px;font-weight:700;margin:0}
    .msg{color:var(--muted);margin:8px 0 0}
    .actions{margin-top:20px;display:flex;gap:12px;flex-wrap:wrap}
    .btn{appearance:none;border:0;border-radius:10px;background:var(--primary);color:#fff;padding:10px 16px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;cursor:pointer}
    .btn:hover{background:var(--primary-hover)}
    /* responsif kecil */
    @media (max-width:480px){
      .card{padding:20px}
      .title{font-size:18px}
      .icon{width:36px;height:36px}
    }
    /* badge warna dinamis via inline style */
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="head">
        <div class="icon" style="background: {{ $palette['bg'] }}; color: {{ $palette['fg'] }};">{{ $palette['icon'] }}</div>
        <h1 class="title">{{ $title }}</h1>
      </div>

      <p class="msg">{{ $message }}</p>

      <div class="actions">
        <a class="btn" href="https://app.mykaskita.com/">Ke Halaman Login</a>
      </div>
    </div>
  </div>

  {{-- Optional auto-redirect: aktifkan jika perlu --}}
  {{-- 
  @if(!empty($redirectTo))
  <script>
    setTimeout(function(){ window.location.href = @json($redirectTo); }, 3000);
  </script>
  @endif
  --}}
</body>
</html>
