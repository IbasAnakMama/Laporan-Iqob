@extends('layouts.app')
@section('title', 'Kelola Iqob')
@section('content')
<h1>Kelola iqob</h1>
<p class="sub">{{ $iqob->santri->nama_lengkap }} (Kamar {{ $iqob->kamar }}) · {{ $iqob->jenis_label }} · {{ $iqob->tingkat_label }}</p>
<div class="card">
  @if ($iqob->keterangan)<p>{{ $iqob->keterangan }}</p>@endif
  @if ($iqob->surat_pernyataan)<a class="btn sm out" href="{{ route('file', ['surat', $iqob->id]) }}" target="_blank">Surat pernyataan</a>@endif
  @if ($iqob->file_pengumpulan)
    <a class="btn sm" href="{{ route('file', ['pengumpulan', $iqob->id]) }}" target="_blank">Buka file santri</a>
    <span class="muted">{{ $iqob->file_nama }} · {{ $iqob->tanggal_dikumpulkan->translatedFormat('d M Y H:i') }}</span>
  @else <span class="muted">Santri belum mengumpulkan file.</span> @endif
</div>
<form class="card" method="post" action="{{ route('musrif.iqob.update', $iqob) }}">
  @csrf @method('PUT')
  <div class="row">
    <div><label for="status">Status</label><select id="status" name="status">
      <option value="belum_dikumpulkan" @selected($iqob->status == 'belum_dikumpulkan')>Belum dikumpulkan (minta revisi)</option>
      <option value="dikumpulkan" @selected($iqob->status == 'dikumpulkan')>Menunggu verifikasi</option>
      <option value="selesai" @selected($iqob->status == 'selesai')>Selesai (disetujui)</option></select></div>
    <div><label for="bw">Batas pengumpulan</label>
      <input type="datetime-local" id="bw" name="batas_waktu" value="{{ $iqob->batas_waktu->format('Y-m-d\TH:i') }}" required></div>
  </div>
  <label for="cat">Catatan untuk santri</label>
  <textarea id="cat" name="catatan_musrif" rows="3">{{ old('catatan_musrif', $iqob->catatan_musrif) }}</textarea>
  <p><button class="btn">Simpan</button> <a class="btn out" href="{{ route('musrif.iqob.index') }}">Kembali</a></p>
</form>
@endsection
