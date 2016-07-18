@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="productsJVE">
            <a ui-sref="Productos JVE"><i class="md md-shop-two"></i>Kardex</a>
        </li>
        <li id="stock">
            <a href="#" ui-sref="Stock"><i class="md md-archive"></i>Stock de Productos</a>
        </li>
        <li id="movimientos">
            <a href="#" ui-sref="Movimientos"><i class="md md-trending-neutral"></i>Generar Salida</a>
        </li>
        <li id="movimientos2">
            <a href="#" ui-sref="Movimientos2"><i class="md md-swap-horiz"></i>Generar Retorno - Venta</a>
        </li>
        <li id="movimientos_outfit">
            <a href="#" ui-sref="Movimientos Out Fit"><i class="md md-trending-neutral"></i>Generar Salida Out Fit</a>
        </li>
        <li id="movimientos_outfit2">
            <a href="#" ui-sref="Retorno Out Fit"><i class="md md-swap-horiz"></i>Generar Retorno - Venta de OutFit</a>
        </li>
        <li id="publicity">
            <a href="#" ui-sref="Publicidad Ventas"><i class="md md-perm-media"></i>Gestionar publicidad</a>
        </li>
        <li id="indicator7">
            <a href="#" ui-sref="Indicator7"><i class="md md-assessment"></i>Reporte General de movimientos</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    <!-- Dependencias en Resource/assets/js/app - Gulpfile.js -->
    <script src="{{ elixir('app/controllers/compile/coordinadorControllers.js') }}"></script>
 @stop