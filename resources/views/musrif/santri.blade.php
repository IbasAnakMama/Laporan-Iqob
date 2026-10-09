@extends('layouts.app')
@section('title', 'Data Santri')
@section('content')
<h1>Data santri</h1>
<p class="sub">Tambahkan santri agar bisa masuk dengan nama lengkap dan NIM.</p>
<form class="card" method="post" action="{{ route(auth()->user()->role . '.santri.store') }}">
  @csrf
  <div class="row">
    <div><label for="n">Nama lengkap</label><input id="n" name="nama_lengkap" required></div>
    <div><label for="m">NIM</label><input id="m" name="nim" required></div>
    <div><label for="k">Kamar</label><select id="k" name="kamar" required>
      @if (auth()->user()->role === 'admin')
      @foreach ($mabnaList as $mb)@if ($mb->kamars->count())<optgroup label="{{ $mb->nama }}">@foreach ($mb->kamars as $kk)<option value="{{ $kk->nomor }}">Kamar {{ $kk->nomor }}</option>@endforeach</optgroup>@endif @endforeach
      @else
      @foreach (auth()->user()->kamarSaya() as $k)<option value="{{ $k }}">Kamar {{ $k }}</option>@endforeach
      @endif</select></div>
  </div>
  <p><button class="btn">Tambah santri</button></p>
</form>
<form class="card" method="get"><div class="row">
  @if (auth()->user()->role === 'admin')
  <div><label for="fm">Filter mabna</label><select id="fm" name="mabna" onchange="this.form.submit()"><option value="">Semua</option>
    @foreach ($mabnaList as $mb)<option value="{{ $mb->id }}" @selected(request('mabna') == $mb->id)>{{ $mb->nama }}</option>@endforeach</select></div>
  @endif
  <div><label for="fk">Filter kamar</label><select id="fk" name="kamar" onchange="this.form.submit()"><option value="">Semua</option>
    @foreach (auth()->user()->kamarSaya() as $k)<option value="{{ $k }}" @selected(request('kamar') == $k)>{{ $k }}</option>@endforeach</select></div></div></form>
<div class="card wrap">
<table>
  <tr><th>Nama lengkap</th><th>NIM</th><th>Mabna</th><th>Kamar</th><th></th></tr>
  @forelse ($santri as $s)
  <tr><td>@include('layouts.avatar', ['u' => $s, 'size' => 30]) {{ $s->nama_lengkap }}</td><td>{{ $s->nim }}</td><td>{{ $s->mabna_nama ?? '-' }}</td><td>{{ $s->kamar }}</td>
    <td><form class="inline" method="post" action="{{ route(auth()->user()->role . '.santri.destroy', $s) }}" onsubmit="return confirm('Hapus santri beserta seluruh iqob-nya?')">
      @csrf @method('DELETE')<button class="btn sm red">Hapus</button></form></td></tr>
  @empty
  <tr><td colspan="5" class="empty">Belum ada santri.</td></tr>
  @endforelse
</table>
</div>
{{ $santri->links() }}
@endsection
