@extends('layouts.app')
@section('title', 'Kata Motivasi')
@section('content')
<h1>Kata motivasi</h1>
<p class="sub">Salah satu kata di bawah ditampilkan acak setiap hari di dashboard semua pengguna.</p>
<form class="card" method="post" action="{{ route('admin.motivasi.store') }}">
  @csrf
  <label for="t">Teks</label><textarea id="t" name="teks" rows="2" required></textarea>
  <label for="s">Sumber (misal: QS. Al-Baqarah: 153)</label><input id="s" name="sumber">
  <p><button class="btn">Tambah</button></p>
</form>
<div class="card wrap">
<table>
  <tr><th>Teks</th><th>Sumber</th><th></th></tr>
  @forelse ($daftar as $d)
  <tr><td>{{ $d->teks }}</td><td>{{ $d->sumber }}</td>
    <td><form class="inline" method="post" action="{{ route('admin.motivasi.destroy', $d) }}" onsubmit="return confirm('Hapus kata ini?')">
      @csrf @method('DELETE')<button class="btn sm red">Hapus</button></form></td></tr>
  @empty
  <tr><td colspan="3" class="empty">Belum ada kata motivasi.</td></tr>
  @endforelse
</table>
</div>
@endsection
