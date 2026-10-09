@extends('layouts.app')
@section('title', 'Persyaratan Iqob')
@section('content')
<h1>Persyaratan iqob</h1>
<p class="sub">Aturan dan ketentuan iqob dalam bentuk PDF.</p>
@if (auth()->user()->role === 'musrif')
<div class="card">
  <h2>Unggah persyaratan baru</h2>
  <form method="post" action="{{ route('musrif.persyaratan.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
      <div><label for="judul">Judul</label><input id="judul" name="judul" required></div>
      <div><label for="file">File PDF</label><input type="file" id="file" name="file" accept=".pdf" required></div>
    </div>
    <p><button class="btn">Unggah</button></p>
  </form>
</div>
@endif
<div class="card wrap">
<table>
  <tr><th>Judul</th><th>Diunggah</th><th></th></tr>
  @forelse ($daftar as $p)
  <tr>
    <td>{{ $p->judul }}</td><td>{{ $p->created_at->translatedFormat('d M Y') }}</td>
    <td>
      <a class="btn sm" href="{{ route('file', ['persyaratan', $p->id]) }}" target="_blank">Buka PDF</a>
      @if (auth()->user()->role === 'musrif')
      <form class="inline" method="post" action="{{ route('musrif.persyaratan.destroy', $p) }}" onsubmit="return confirm('Hapus persyaratan ini?')">
        @csrf @method('DELETE')<button class="btn sm red">Hapus</button></form>
      @endif
    </td>
  </tr>
  @empty
  <tr><td colspan="3" class="empty">Belum ada dokumen persyaratan.</td></tr>
  @endforelse
</table>
</div>
@endsection
