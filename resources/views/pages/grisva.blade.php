@extends('layouts.app')

@section('content')

    <a class="gv-back" href="/index.html">← Beranda</a>
<noscript>GrisVA membutuhkan JavaScript. Aktifkan JavaScript di browser kamu.</noscript>
<script>window.GRISVA_PAGE = true; // tampil sebagai halaman penuh</script>
<script src="{{ asset('/grisVA.js') }}" defer="">
</script>



@endsection