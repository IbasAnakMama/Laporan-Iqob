<div class="hero">
  <h1 style="display:flex;align-items:center;gap:14px">@include('layouts.avatar', ['u' => auth()->user(), 'size' => 56]) <span>Selamat datang, {{ auth()->user()->nama_lengkap }}</span></h1>
  <div class="meta">
    @if (auth()->user()->role === 'santri')<span>{{ auth()->user()->mabna_nama ? auth()->user()->mabna_nama . ' · ' : '' }}Kamar {{ auth()->user()->kamar }}</span>@else<span>{{ auth()->user()->role === 'admin' ? 'Super Admin' : 'Musrif · ' . (auth()->user()->mabna_nama ? auth()->user()->mabna_nama . ' · ' : '') . 'Kamar ' . (auth()->user()->kamarRingkas() ?: 'belum ditentukan') }}</span>@endif
    <span>NIM {{ auth()->user()->nim }}</span>
    <span id="tgl"></span><strong id="jam"></strong>
  </div>
  @if ($motivasi)
    <div class="quote">“{{ $motivasi->teks }}”<cite>{{ $motivasi->sumber }}</cite></div>
  @endif
</div>
<script>
(function(){
  function t(){var d=new Date();
    document.getElementById('tgl').textContent=d.toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
    document.getElementById('jam').textContent=d.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}).replace(/\./g,':');}
  t();setInterval(t,1000);
})();
</script>
