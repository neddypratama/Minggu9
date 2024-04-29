@extends('layout.template')

@section('content')
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Halo, Apakabar!!!</h3>
        <div class="card-tools"></div>
      </div>
      <div class="card-body">
        Selamat datang semua, ini adalah halaman utama dari aplikasi ini
      </div>
      <div>
        {!! $countUser->container() !!}
        {!! $profitBarang->container() !!}
      </div>
    </div>
    <script src="{{ $countUser->cdn() }}"></script>
    <script src="{{ $profitBarang->cdn() }}"></script>
    {{ $countUser->script() }}
    {{ $profitBarang->script() }}
@endsection