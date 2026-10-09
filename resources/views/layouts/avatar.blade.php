@php $s = $size ?? 40; @endphp
@if ($u->foto_url)<img class="av" src="{{ $u->foto_url }}" alt="" style="width:{{ $s }}px;height:{{ $s }}px">@else<span class="av" style="width:{{ $s }}px;height:{{ $s }}px;font-size:{{ round($s * .4) }}px">{{ $u->inisial }}</span>@endif
