@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li><a href="#" ui-sref="ProductosStore"><i class="md md-format-underline"></i>Kardex</a></li>
        <li id="pedidos"><a href="#" ui-sref="Stock Ventas"><i class="md md-format-underline"></i>Stock de Productos</a></li>        
    </ul>
@stop

@section('dashCtrls')
    <script src="{{elixir('app/controllers/compile/almacenControllers.js')}}"></script>   
@stop
