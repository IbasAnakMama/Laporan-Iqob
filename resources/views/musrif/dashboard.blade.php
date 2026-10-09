@extends('layouts.app')
@section('title', 'Dashboard Musrif')
@section('content')
@include('layouts.hero')
<div class="stats">
  <div class="stat"><b>{{ $stat['santri'] }}</b><span>Santri terdaftar</span></div>
  <div class="stat"><b>{{ $stat['aktif'] }}</b><span>Iqob belum dikumpulkan</span></div>
  <div class="stat"><b>{{ $stat['menunggu'] }}</b><span>Perlu diverifikasi</span></div>
  <div class="stat"><b>{{ $stat['selesai'] }}</b><span>Iqob selesai</span></div>
</div>
<div class="card wrap">
  <h2>Menunggu verifikasi</h2>
  <table>
    <tr><th>Santri</th><th>Kamar</th><th>Jenis</th><th>Dikumpulkan</th><th></th></tr>
    @forelse ($perlu as $i)
    <tr><td>{{ $i->santri->nama_lengkap }}</td><td>{{ $i->kamar }}</td><td>{{ $i->jenis_label }}</td>
      <td>{{ $i->tanggal_dikumpulkan->translatedFormat('d M Y H:i') }}</td>
      <td><a class="btn sm" href="{{ route('musrif.iqob.edit', $i) }}">Periksa</a></td></tr>
    @empty
    <tr><td colspan="5" class="empty">Tidak ada kiriman yang menunggu.</td></tr>
    @endforelse
  </table>
</div>
@endsection
