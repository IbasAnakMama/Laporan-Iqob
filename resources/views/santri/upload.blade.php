@extends('layouts.app')
@section('title', 'Upload Iqob')
@section('content')
<h1>Upload iqob</h1>
<p class="sub">Unduh surat pernyataan (jika ada), cetak atau tulis tangan, lalu unggah hasilnya. Format: PDF, JPG, PNG, DOC, DOCX (maks. 10 MB).</p>
@forelse ($iqobs as $i)
<div class="card">
  <h2>{{ $i->jenis_label }} · <span class="badge {{ $i->status_class }}">{{ $i->tingkat_label }}</span></h2>
  <p class="muted" style="margin-top:-6px">Batas pengumpulan: <strong>{{ $i->batas_waktu->translatedFormat('l, d F Y H:i') }}</strong>
    <span class="badge {{ $i->status_class }}">{{ $i->status_label }}</span></p>
  @if ($i->keterangan)<p>{{ $i->keterangan }}</p>@endif
  @if ($i->catatan_musrif)<p><strong>Catatan musrif:</strong> {{ $i->catatan_musrif }}</p>@endif
  @if ($i->surat_pernyataan)<p><a class="btn sm out" href="{{ route('file', ['surat', $i->id]) }}" target="_blank">Unduh surat pernyataan</a></p>@endif
  @if ($i->file_pengumpulan)<p class="muted">Terkirim: {{ $i->file_nama }} ({{ $i->tanggal_dikumpulkan->translatedFormat('d M Y H:i') }})</p>@endif

  @if ($i->bisa_upload)
  <form method="post" action="{{ route('santri.upload.store', $i) }}" enctype="multipart/form-data">
    @csrf
    <label for="f{{ $i->id }}">{{ $i->file_pengumpulan ? 'Ganti file' : 'Pilih file iqob' }}</label>
    <input type="file" id="f{{ $i->id }}" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
    <p><button class="btn">Kumpulkan</button></p>
  </form>
  @else
  <p class="flash err">Batas waktu sudah lewat. Hubungi musrif untuk perpanjangan.</p>
  @endif
</div>
@empty
<div class="card empty">Tidak ada iqob yang perlu dikumpulkan.</div>
@endforelse
@endsection
