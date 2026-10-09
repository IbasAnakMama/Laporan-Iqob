@extends('layouts.app')
@section('title', 'Laporan Iqob Saya')
@section('content')
<h1>Laporan iqob saya</h1>
<p class="sub">Seluruh iqob yang pernah diberikan kepada Anda.</p>
<div class="card wrap">
<table>
  <tr><th>Diberikan</th><th>Jenis</th><th>Tingkat</th><th>Batas waktu</th><th>Status</th><th>Berkas</th><th>Catatan musrif</th></tr>
  @forelse ($iqobs as $i)
  <tr>
    <td>{{ $i->created_at->translatedFormat('d M Y') }}</td>
    <td>{{ $i->jenis_label }}<div class="muted">{{ $i->keterangan }}</div></td>
    <td>{{ $i->tingkat_label }}</td>
    <td>{{ $i->batas_waktu->translatedFormat('d M Y, H:i') }}</td>
    <td><span class="badge {{ $i->status_class }}">{{ $i->status_label }}</span></td>
    <td>
      @if ($i->surat_pernyataan)<a href="{{ route('file', ['surat', $i->id]) }}" target="_blank">Surat pernyataan</a><br>@endif
      @if ($i->file_pengumpulan)<a href="{{ route('file', ['pengumpulan', $i->id]) }}" target="_blank">File saya</a>@endif
    </td>
    <td>{{ $i->catatan_musrif ?: '-' }}</td>
  </tr>
  @empty
  <tr><td colspan="7" class="empty">Belum ada iqob. Pertahankan.</td></tr>
  @endforelse
</table>
</div>
@endsection
