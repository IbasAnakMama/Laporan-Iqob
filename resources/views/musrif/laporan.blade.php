@extends('layouts.app')
@section('title', 'Laporan Bulanan')
@section('content')
<h1>Laporan bulanan</h1>
<p class="sub">Rekap iqob per bulan, bisa diunduh sebagai spreadsheet (dibuka di Excel / Google Sheets).</p>
<form class="card" method="get">
  <div class="row">
    <div><label for="b">Bulan</label><input type="month" id="b" name="bulan" value="{{ $bulan->format('Y-m') }}"></div>
    @if (auth()->user()->role === 'admin')
    <div><label for="mb">Mabna</label><select id="mb" name="mabna"><option value="">Semua</option>
      @foreach (\App\Models\Mabna::orderBy('nama')->get() as $mb)<option value="{{ $mb->id }}" @selected(request('mabna') == $mb->id)>{{ $mb->nama }}</option>@endforeach</select></div>
    @endif
    <div><label for="k">Kamar</label><select id="k" name="kamar"><option value="">Semua</option>
      @foreach (auth()->user()->kamarSaya() as $k)<option value="{{ $k }}" @selected(request('kamar') == $k)>{{ $k }}</option>@endforeach</select></div>
  </div>
  <p><button class="btn">Tampilkan</button>
    <a class="btn out" href="{{ route(auth()->user()->role . '.laporan.export', request()->query()) }}">Unduh spreadsheet</a></p>
</form>
<div class="card wrap">
  <h2>{{ $bulan->translatedFormat('F Y') }} · {{ $iqobs->count() }} iqob</h2>
  <table>
    <tr><th>No</th><th>Tanggal</th><th>Nama</th><th>NIM</th><th>Mabna</th><th>Kamar</th><th>Tingkat</th><th>Jenis</th><th>Batas</th><th>Status</th></tr>
    @forelse ($iqobs as $n => $i)
    <tr><td>{{ $n + 1 }}</td><td>{{ $i->created_at->format('d/m/Y') }}</td><td>{{ $i->santri->nama_lengkap }}</td><td>{{ $i->santri->nim }}</td>
      <td>{{ $i->santri->mabna_nama ?? '-' }}</td><td>{{ $i->kamar }}</td><td>{{ $i->tingkat_label }}</td><td>{{ $i->jenis_label }}</td>
      <td>{{ $i->batas_waktu->format('d/m/Y H:i') }}</td><td>{{ $i->status_label }}</td></tr>
    @empty
    <tr><td colspan="10" class="empty">Tidak ada iqob pada bulan ini.</td></tr>
    @endforelse
  </table>
</div>
@endsection
