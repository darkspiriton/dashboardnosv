@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="movimientos">
            <a href="#" ui-sref="Movimientos"><i class="md md-shop-two"></i>Generar Salida</a>
        </li>
        <li id="movimientos2">
            <a href="#" ui-sref="Movimientos2"><i class="md md-shop-two"></i>Generar Retorno - Venta</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/auxMovementCtrl.js"></script>
    <script src="app/controllers/auxMovement2Ctrl.js"></script>
@stop