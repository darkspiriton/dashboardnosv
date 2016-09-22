@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">            
            <a href="#"><i class="md md-redeem">Logística</i></a>
            <ul>
                @include('menu.menuLVenta');
            </ul>
        </li>        
    </ul>
@stop

@section('dashCtrls')
    <script src="{{elixir('app/controllers/compile/almacenControllers.js')}}"></script>   
@stop
