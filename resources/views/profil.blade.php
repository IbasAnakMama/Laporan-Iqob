@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
@php $u = auth()->user(); @endphp
<h1>Profil saya</h1>
<p class="sub">Foto profil tampil di dashboard dan daftar santri.</p>
<div class="card prof">
  @include('layouts.avatar', ['u' => $u, 'size' => 120])
  <div>
    <h2 style="margin-bottom:4px">{{ $u->nama_lengkap }}</h2>
    <div class="muted">NIM {{ $u->nim }}</div>
    <div class="muted">
      @if ($u->role === 'santri') Santri · {{ $u->mabna_nama ? $u->mabna_nama . ' · ' : '' }}Kamar {{ $u->kamar }}
      @elseif ($u->role === 'musrif') Musrif · {{ $u->mabna_nama ? $u->mabna_nama . ' · ' : '' }}Kamar {{ $u->kamarRingkas() ?: 'belum ditentukan' }}
      @else Super Admin @endif
    </div>
  </div>
</div>
<div class="card">
  <h2>Ganti foto</h2>
  <form method="post" action="{{ route('profil.update') }}" enctype="multipart/form-data">
    @csrf
    <label for="foto">Pilih foto (JPG, PNG, WEBP, maks. 2 MB)</label>
    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" required>
    <p><button class="btn">Simpan foto</button></p>
  </form>
  @if ($u->foto)
  <form method="post" action="{{ route('profil.hapus') }}" onsubmit="return confirm('Hapus foto profil?')">
    @csrf @method('DELETE')<button class="btn red sm">Hapus foto</button>
  </form>
  @endif
</div>
@endsection
