@extends('layouts.app')
@section('title', 'Dashboard Santri')
@section('content')
@include('layouts.hero')
<div class="stats">
  <div class="stat"><b>{{ $stat['total'] }}</b><span>Total iqob</span></div>
  <div class="stat"><b>{{ $stat['belum'] }}</b><span>Belum dikumpulkan</span></div>
  <div class="stat"><b>{{ $stat['menunggu'] }}</b><span>Menunggu verifikasi</span></div>
  <div class="stat"><b>{{ $stat['selesai'] }}</b><span>Selesai</span></div>
</div>
<div class="card">
  <h2>Iqob yang harus segera dikumpulkan</h2>
  @forelse ($deadline as $i)
    <p style="margin:0 0 10px"><strong>{{ $i->jenis_label }}</strong> ({{ $i->tingkat_label }}) — batas
      {{ $i->batas_waktu->translatedFormat('d F Y, H:i') }} <span class="badge {{ $i->status_class }}">{{ $i->status_label }}</span></p>
  @empty
    <div class="empty">Tidak ada iqob yang menunggu. Barakallahu fiik.</div>
  @endforelse
  @if ($deadline->count()) <a class="btn" href="{{ route('santri.upload') }}">Upload iqob</a> @endif
</div>
@endsection
