@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> VentaCorp </a>
            <ul> 
                @include('menu.menuDVenta');
                @include('menu.menuCVenta');
                @include('menu.menuEVenta');
                @include('menu.menuLVenta');                
            </ul>
       </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    {{-- Dependencias en Resource/assets/js/app - Gulpfile.js --}}
    <script src="{{ elixir('app/controllers/compile/admControllers.js') }}"></script>
@stop
