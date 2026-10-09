@extends('layouts.app')
@section('title', 'Dashboard Super Admin')
@section('content')
@include('layouts.hero')
<div class="stats">
  <div class="stat"><b>{{ $stat['musrif'] }}</b><span>Akun musrif</span></div>
  <div class="stat"><b>{{ $stat['santri'] }}</b><span>Akun santri</span></div>
  <div class="stat"><b>{{ $stat['iqob'] }}</b><span>Total iqob tercatat</span></div>
  <div class="stat"><b>{{ $stat['motivasi'] }}</b><span>Kata motivasi</span></div>
</div>
<div class="card wrap">
  <h2>Ringkasan per mabna</h2>
  <table>
    <tr><th>Mabna</th><th>Kamar</th><th>Musrif</th><th>Santri</th></tr>
    @forelse ($ringkasan as $m)
    <tr><td>{{ $m->nama }}</td><td>{{ $m->daftar_kamar ?: '-' }} ({{ $m->jml_kamar }})</td><td>{{ $m->jml_musrif }}</td><td>{{ $m->jml_santri }}</td></tr>
    @empty
    <tr><td colspan="4" class="empty">Belum ada mabna.</td></tr>
    @endforelse
  </table>
</div>
<div class="card">
  <h2>Kontrol sistem</h2>
  <p><a class="btn" href="{{ route('admin.mabna.index') }}">Kelola mabna</a>
     <a class="btn out" href="{{ route('admin.musrif.index') }}">Kelola musrif</a>
     <a class="btn out" href="{{ route('admin.santri.index') }}">Kelola santri</a>
     <a class="btn out" href="{{ route('admin.motivasi.index') }}">Kata motivasi</a></p>
</div>
@endsection
