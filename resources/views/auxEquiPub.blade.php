@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> The Box </a>
            <ul>  
                @include('menu.menuEBox');
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="{{ elixir('app/controllers/compile/equiPubControllers.js') }}"></script>
@stop
