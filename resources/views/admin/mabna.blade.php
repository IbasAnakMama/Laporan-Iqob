@extends('layouts.app')
@section('title', 'Kelola Mabna')
@section('content')
<h1>Kelola mabna</h1>
<p class="sub">Tentukan kamar apa saja yang masuk tiap mabna. Satu kamar hanya masuk satu mabna. Setelah itu bagi musrif di menu Kelola Musrif.</p>
@if ($bebas !== '')<div class="flash err">Kamar yang belum masuk mabna mana pun: {{ $bebas }}</div>@endif
<form class="card" method="post" action="{{ route('admin.mabna.store') }}">
  @csrf
  <div class="row">
    <div><label for="n">Nama mabna</label><input id="n" name="nama" placeholder="Mabna ..." required></div>
    <div><label for="k">Kamar (contoh: 1-8 atau 1,2,5)</label><input id="k" name="kamar"></div>
  </div>
  <p><button class="btn">Tambah mabna</button></p>
</form>
<div class="card wrap">
<table>
  <tr><th>Nama mabna</th><th>Kamar</th><th>Jml kamar</th><th>Musrif</th><th>Santri</th><th></th></tr>
  @forelse ($mabna as $m)
  <tr>
    <td><input form="u{{ $m->id }}" name="nama" value="{{ $m->nama }}" aria-label="Nama mabna" required></td>
    <td><input form="u{{ $m->id }}" name="kamar" value="{{ $m->daftar_kamar }}" placeholder="kosong" aria-label="Kamar {{ $m->nama }}"></td>
    <td>{{ $m->jml_kamar }}</td><td>{{ $m->jml_musrif }}</td><td>{{ $m->jml_santri }}</td>
    <td>
      <form id="u{{ $m->id }}" class="inline" method="post" action="{{ route('admin.mabna.update', $m) }}">@csrf @method('PUT')<button class="btn sm">Simpan</button></form>
      <form class="inline" method="post" action="{{ route('admin.mabna.destroy', $m) }}" onsubmit="return confirm('Hapus mabna ini?')">@csrf @method('DELETE')<button class="btn sm red">Hapus</button></form>
    </td>
  </tr>
  @empty
  <tr><td colspan="6" class="empty">Belum ada mabna.</td></tr>
  @endforelse
</table>
</div>
@endsection
