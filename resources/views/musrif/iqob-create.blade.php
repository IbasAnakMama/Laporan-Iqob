@extends('layouts.app')
@section('title', 'Beri Iqob')
@section('content')
<h1>Beri iqob</h1>
@if (!count(auth()->user()->kamarSaya()))<div class="flash err">Anda belum memegang kamar. Hubungi super admin untuk pembagian kamar.</div>@endif
<p class="sub">Pilih kamar, centang santri, tentukan jenis dan tingkat iqob.</p>
<form class="card" method="post" action="{{ route('musrif.iqob.store') }}" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div><label for="kamar">Kamar</label>
      <select id="kamar" name="kamar" required>
        <option value="">Pilih kamar</option>
        @foreach (auth()->user()->kamarSaya() as $k)<option value="{{ $k }}" @selected(old('kamar') == $k)>Kamar {{ $k }}</option>@endforeach
      </select></div>
    <div><label for="jenis">Jenis iqob</label>
      <select id="jenis" name="jenis" required>
        @foreach (\App\Models\Iqob::JENIS as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
      </select></div>
    <div><label for="tingkat">Tingkat</label>
      <select id="tingkat" name="tingkat" required>
        @foreach (\App\Models\Iqob::TINGKAT as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
      </select></div>
    <div><label for="bw">Batas pengumpulan</label><input type="datetime-local" id="bw" name="batas_waktu" value="{{ old('batas_waktu') }}" required></div>
  </div>

  <label>Santri <span class="muted" id="hint">(pilih kamar dulu)</span></label>
  <p style="margin:0"><button type="button" class="btn sm out" id="all">Pilih semua</button></p>
  <div class="santri-list" id="list"></div>

  <label for="ket">Keterangan / pelanggaran</label>
  <textarea id="ket" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
  <label for="surat">Surat pernyataan (PDF, opsional) — dicetak atau ditulis ulang oleh santri</label>
  <input type="file" id="surat" name="surat" accept=".pdf">
  <p><button class="btn">Berikan iqob</button></p>
</form>
<script>
var kamar=document.getElementById('kamar'),list=document.getElementById('list'),hint=document.getElementById('hint');
kamar.addEventListener('change',function(){
  list.innerHTML='';
  if(!kamar.value){hint.textContent='(pilih kamar dulu)';return;}
  fetch('{{ url('/musrif/kamar') }}/'+kamar.value+'/santri',{headers:{'Accept':'application/json'}})
   .then(function(r){return r.json()}).then(function(d){
     hint.textContent=d.length?'':'(belum ada santri di kamar ini)';
     d.forEach(function(s){
       var l=document.createElement('label'),c=document.createElement('input');
       c.type='checkbox';c.name='santri_ids[]';c.value=s.id;
       l.appendChild(c);l.appendChild(document.createTextNode(s.nama_lengkap+' ('+s.nim+')'));list.appendChild(l);
     });
   });
});
document.getElementById('all').addEventListener('click',function(){
  list.querySelectorAll('input').forEach(function(c){c.checked=true});
});
if(kamar.value){kamar.dispatchEvent(new Event('change'))}
</script>
@endsection
