@extends('layouts.app')
@section('title', 'Daftar Iqob')
@section('content')
<h1>Daftar iqob</h1>
<p class="sub">Semua iqob yang telah diberikan.</p>
<form class="card" method="get">
  <div class="row">
    <div><label for="kamar">Kamar</label><select id="kamar" name="kamar"><option value="">Semua</option>
      @foreach (auth()->user()->kamarSaya() as $k)<option value="{{ $k }}" @selected(request('kamar') == $k)>{{ $k }}</option>@endforeach</select></div>
    <div><label for="jenis">Jenis</label><select id="jenis" name="jenis"><option value="">Semua</option>
      @foreach (\App\Models\Iqob::JENIS as $v => $l)<option value="{{ $v }}" @selected(request('jenis') == $v)>{{ $l }}</option>@endforeach</select></div>
    <div><label for="tingkat">Tingkat</label><select id="tingkat" name="tingkat"><option value="">Semua</option>
      @foreach (\App\Models\Iqob::TINGKAT as $v => $l)<option value="{{ $v }}" @selected(request('tingkat') == $v)>{{ $l }}</option>@endforeach</select></div>
    <div><label for="status">Status</label><select id="status" name="status"><option value="">Semua</option>
      <option value="belum_dikumpulkan" @selected(request('status') == 'belum_dikumpulkan')>Belum dikumpulkan</option>
      <option value="dikumpulkan" @selected(request('status') == 'dikumpulkan')>Menunggu verifikasi</option>
      <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option></select></div>
  </div>
  <p><button class="btn">Terapkan filter</button></p>
</form>
<div class="card wrap">
<table>
  <tr><th>Santri</th><th>Kamar</th><th>Jenis</th><th>Tingkat</th><th>Batas waktu</th><th>Status</th><th>Berkas</th><th></th></tr>
  @forelse ($iqobs as $i)
  <tr>
    <td>{{ $i->santri->nama_lengkap }}<div class="muted">{{ $i->santri->nim }}</div></td>
    <td>{{ $i->kamar }}</td><td>{{ $i->jenis_label }}</td><td>{{ $i->tingkat_label }}</td>
    <td>{{ $i->batas_waktu->translatedFormat('d M Y H:i') }}</td>
    <td><span class="badge {{ $i->status_class }}">{{ $i->status_label }}</span></td>
    <td>@if ($i->file_pengumpulan)<a href="{{ route('file', ['pengumpulan', $i->id]) }}" target="_blank">File santri</a>@else - @endif</td>
    <td>
      <a class="btn sm" href="{{ route('musrif.iqob.edit', $i) }}">Kelola</a>
      <form class="inline" method="post" action="{{ route('musrif.iqob.destroy', $i) }}" onsubmit="return confirm('Hapus iqob ini?')">
        @csrf @method('DELETE')<button class="btn sm red">Hapus</button></form>
    </td>
  </tr>
  @empty
  <tr><td colspan="8" class="empty">Tidak ada data.</td></tr>
  @endforelse
</table>
</div>
{{ $iqobs->links() }}
@endsection
