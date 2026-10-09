<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk · Laporan Iqob</title>
<style>
*{box-sizing:border-box}
body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0a4338;font:15px/1.5 "Segoe UI",system-ui,sans-serif;color:#18231f;padding:20px}
.box{background:#fff;border-radius:16px;width:100%;max-width:420px;padding:30px;box-shadow:0 20px 50px rgba(0,0,0,.3)}
h1{font:700 26px Georgia,serif;margin:0}p{color:#5d6b65;margin:4px 0 20px}
.tabs{display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;background:#eef2f0;border-radius:10px;padding:4px;margin-bottom:12px}
.tabs input{position:absolute;opacity:0}
.tabs label{margin:0;text-align:center;padding:9px;border-radius:8px;cursor:pointer;font-weight:600;color:#5d6b65}
.tabs input:checked+label{background:#0e5a4b;color:#fff}
.tabs input:focus-visible+label{outline:2px solid #b8892b}
label.f{display:block;font-weight:600;margin:12px 0 4px;font-size:14px}
input.t{width:100%;padding:11px 12px;border:1px solid #c5cfca;border-radius:8px;font:inherit}
input.t:focus{outline:2px solid #b8892b;outline-offset:1px}
button{width:100%;margin-top:18px;background:#0e5a4b;color:#fff;border:0;padding:12px;border-radius:8px;font:600 15px inherit;cursor:pointer}
.err{background:#f9dcdc;color:#8e1f1f;padding:10px 12px;border-radius:8px;margin-bottom:10px}
</style>
</head>
<body>
<form class="box" method="post" action="{{ url('/login') }}">
  @csrf
  <h1>Laporan Iqob</h1>
  <p>Pilih peran (Santri, Musrif, atau Admin), lalu masukkan nama lengkap dan NIM.</p>
  @error('login')<div class="err">{{ $message }}</div>@enderror
  @if ($errors->has('nama_lengkap') || $errors->has('nim') || $errors->has('role'))<div class="err">Semua kolom wajib diisi.</div>@endif
  <div class="tabs">
    <input type="radio" name="role" id="r1" value="santri" {{ old('role', 'santri') === 'santri' ? 'checked' : '' }}><label for="r1">Santri</label>
    <input type="radio" name="role" id="r2" value="musrif" {{ old('role') === 'musrif' ? 'checked' : '' }}><label for="r2">Musrif</label>
    <input type="radio" name="role" id="r3" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}><label for="r3">Admin</label>
  </div>
  <label class="f" for="nama">Nama lengkap</label>
  <input class="t" id="nama" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autocomplete="off">
  <label class="f" for="nim">NIM</label>
  <input class="t" id="nim" name="nim" required autocomplete="off">
  <button>Masuk</button>
</form>
</body>
</html>
