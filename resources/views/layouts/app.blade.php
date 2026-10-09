@php
  $role = auth()->user()->role;
  $menus = [
    'santri' => [
      ['santri.dashboard', 'Dashboard'],
      ['santri.laporan', 'Lihat Laporan Iqob'],
      ['santri.upload', 'Upload Iqob'],
      ['santri.persyaratan', 'Persyaratan Iqob'],
    ],
    'musrif' => [
      ['musrif.dashboard', 'Dashboard'],
      ['musrif.iqob.create', 'Beri Iqob'],
      ['musrif.iqob.index', 'Daftar Iqob'],
      ['musrif.santri.index', 'Data Santri'],
      ['musrif.laporan', 'Laporan Bulanan'],
      ['musrif.persyaratan', 'Persyaratan Iqob'],
    ],
    'admin' => [
      ['admin.dashboard', 'Dashboard'],
      ['admin.mabna.index', 'Kelola Mabna'],
      ['admin.musrif.index', 'Kelola Musrif'],
      ['admin.santri.index', 'Kelola Santri'],
      ['admin.laporan', 'Laporan Bulanan'],
      ['admin.motivasi.index', 'Kata Motivasi'],
    ],
  ];
  $menus[$role][] = ['profil', 'Profil Saya'];
  $judul = ['santri' => 'Panel Santri', 'musrif' => 'Panel Musrif', 'admin' => 'Panel Super Admin'][$role];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Laporan Iqob')</title>
<script>try{if(localStorage.getItem('tema-{{ $role }}')==='dark')document.documentElement.dataset.theme='dark'}catch(e){}</script>
@include('layouts.style')
</head>
<body class="role-{{ $role }}">
<input type="checkbox" id="nav" class="nav-toggle">
<label for="nav" class="burger" aria-label="Buka menu">☰</label>
<aside class="side">
  <div class="brand">Laporan Iqob<small>{{ $judul }}</small></div>
  <a class="chip" href="{{ route('profil') }}">@include('layouts.avatar', ['u' => auth()->user(), 'size' => 40])<span>{{ auth()->user()->nama_lengkap }}</span></a>
  <nav>
    @foreach ($menus[$role] as [$r, $label])
      <a href="{{ route($r) }}" class="{{ request()->routeIs($r) ? 'on' : '' }}">{{ $label }}</a>
    @endforeach
  </nav>
  <button type="button" class="tema" id="tema">Mode gelap</button>
  <form method="post" action="{{ route('logout') }}">@csrf<button class="logout">Keluar</button></form>
</aside>
<main class="main">
  @if (session('ok')) <div class="flash ok">{{ session('ok') }}</div> @endif
  @if (session('error')) <div class="flash err">{{ session('error') }}</div> @endif
  @if ($errors->any()) <div class="flash err">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div> @endif
  @yield('content')
</main>
<script>
(function(){
  var k='tema-{{ $role }}',b=document.getElementById('tema'),d=document.documentElement;
  function label(){b.textContent=d.dataset.theme==='dark'?'Mode terang':'Mode gelap';}
  label();
  b.addEventListener('click',function(){
    var n=d.dataset.theme==='dark'?'light':'dark';
    d.dataset.theme=n;
    try{localStorage.setItem(k,n)}catch(e){}
    label();
  });
})();
</script>
</body>
</html>
