@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class=""><a href="#/"><i class="md md-format-underline"></i> Consultar Productos</a></li>
        <li class=""><a href="#/"><i class="md md-format-underline"></i> Registro de Alcance</a></li>
        <li class=""><a href="#/"><i class="md md-now-widgets"></i> Registro de Usuario</a></li>
        <li class=""><a href="#/"><i class="md md-now-widgets"></i> Registro de Interes</a></li>
        <li class=""><a href="#/"><i class="md md-now-widgetse"></i> Registro de Pedido</a></li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
@stop