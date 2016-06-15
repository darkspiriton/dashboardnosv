@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="stock">
            <a href="#" ui-sref="Stock"><i class="md md-shop-two"></i>Stock de Productos</a>
        </li>
        <li id="movimientos">
            <a href="#" ui-sref="Movimientos"><i class="md md-shop-two"></i>Generar Salida</a>
        </li>
        <li id="movimientos2">
            <a href="#" ui-sref="Movimientos2"><i class="md md-shop-two"></i>Generar Retorno - Venta</a>
        </li>
        <li id="movimientos_outfit">
            <a href="#" ui-sref="Movimientos Out Fit"><i class="md md-shop-two"></i>Generar Salida Out Fit</a>
        </li>
        <li id="movimientos_outfit2">
            <a href="#" ui-sref="Retorno Out Fit"><i class="md md-shop-two"></i>Generar Retorno - Venta de OutFit</a>
        </li>
        <li id="publicity">
            <a href="#" ui-sref="Publicidad Ventas"><i class="md md-shop-two"></i>Gestionar publicidad</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/auxMovementCtrl.js"></script>
    <script src="app/controllers/auxMovement2Ctrl.js"></script>
    <script src="app/controllers/auxStockCtrl.js"></script>
    <script src="app/controllers/auxMovementOutFitCtrl.js"></script>
    <script src="app/controllers/auxMovementOutFit2Ctrl.js"></script>
    <script src="app/controllers/publicityJVECtrl.js"></script>
@stop