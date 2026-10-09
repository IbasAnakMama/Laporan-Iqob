@extends('layouts.app')
@section('title', 'Kelola Musrif')
@section('content')
<h1>Kelola musrif</h1>
<p class="sub">Tempatkan musrif di sebuah mabna, lalu bagi kamar yang dipegang. Musrif hanya bisa mengelola santri di kamarnya.</p>
@foreach ($belum as $nama => $kamar)<div class="flash err">{{ $nama }}: kamar {{ $kamar }} belum ada musrif.</div>@endforeach
<form class="card" method="post" action="{{ route('admin.musrif.store') }}">
  @csrf
  <div class="row">
    <div><label for="n">Nama lengkap</label><input id="n" name="nama_lengkap" required></div>
    <div><label for="m">NIM</label><input id="m" name="nim" required></div>
    <div><label for="mb">Mabna</label><select id="mb" name="mabna_id"><option value="">Pilih mabna</option>
      @foreach ($mabnaList as $mb)<option value="{{ $mb->id }}">{{ $mb->nama }}</option>@endforeach</select></div>
    <div><label for="k">Kamar (contoh: 40-43)</label><input id="k" name="kamar" placeholder="40-43"></div>
  </div>
  <p><button class="btn">Tambah musrif</button></p>
</form>
<form class="card" method="get"><div class="row"><div><label for="fm">Filter mabna</label>
  <select id="fm" name="mabna" onchange="this.form.submit()"><option value="">Semua</option>
  @foreach ($mabnaList as $mb)<option value="{{ $mb->id }}" @selected(request('mabna') == $mb->id)>{{ $mb->nama }}</option>@endforeach</select></div></div></form>
<div class="card wrap">
<table>
  <tr><th>Nama lengkap</th><th>NIM</th><th>Mabna dan kamar yang dipegang</th><th>Iqob</th><th></th></tr>
  @forelse ($musrif as $m)
  <tr>
    <td>@include('layouts.avatar', ['u' => $m, 'size' => 30]) {{ $m->nama_lengkap }}</td>
    <td>{{ $m->nim }}</td>
    <td>
      <form class="kamar-form" method="post" action="{{ route('admin.musrif.kamar', $m) }}">
        @csrf
        <select name="mabna_id" aria-label="Mabna {{ $m->nama_lengkap }}"><option value="">Tanpa mabna</option>
          @foreach ($mabnaList as $mb)<option value="{{ $mb->id }}" @selected($m->mabna_id == $mb->id)>{{ $mb->nama }}</option>@endforeach</select>
        <input name="kamar" value="{{ $kamarMusrif[$m->id] ?? '' }}" placeholder="kamar" aria-label="Kamar {{ $m->nama_lengkap }}">
        <button class="btn sm">Simpan</button>
      </form>
    </td>
    <td>{{ $jumlah[$m->id] ?? 0 }}</td>
    <td><form class="inline" method="post" action="{{ route('admin.musrif.destroy', $m) }}" onsubmit="return confirm('Hapus akun musrif ini?')">
      @csrf @method('DELETE')<button class="btn sm red">Hapus</button></form></td>
  </tr>
  @empty
  <tr><td colspan="5" class="empty">Belum ada musrif.</td></tr>
  @endforelse
</table>
</div>
@endsection
