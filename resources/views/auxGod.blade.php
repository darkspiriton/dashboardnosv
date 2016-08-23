@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> NosVenden </a>
            <ul> 
                @include('menu.menuDNos');
            </ul>

        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> Ventacorp </a>
            <ul>
                @include('menu.menuDVenta');
                @include('menu.menuCVenta');
                @include('menu.menuEVenta');
            </ul>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> The Box </a>
            <ul>
                @include('menu.menuDBox');
                @include('menu.menuCBox');
                @include('menu.menuEBox');
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    {{-- # Dependencias en Resource/assets/js/app - Gulpfile.js --}}
    <script src="{{ elixir('app/controllers/compile/godControllers.js') }}"></script>    
@stop
