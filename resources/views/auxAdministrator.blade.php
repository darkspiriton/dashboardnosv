@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active">
            <a href="#/"><i class="md md-home"></i> Inicio</a>
        </li>
        <li id="Productos">
            <a ui-sref="Productos"><i class="md md-swap-horiz"></i>Kardex</a>
        </li>
        <li id="Stock">
            <a href="#" ui-sref="Stock"><i class="md md-shopping-cart"></i>Stock de Productos</a>
        </li>
        <li id="Movimientos">
            <a href="#" ui-sref="Movimientos"><i class="md md-shop-two"></i>Generar Salida</a>
        </li>
        <li id="Movimientos2">
            <a href="#" ui-sref="Movimientos2"><i class="md md-trending-neutral"></i>Generar Retorno - Venta</a>
        </li>
        <li id="out_fit">
            <a href="#" ui-sref="out_fit"><i class="md md-swap-horiz"></i>Gestionar Combinaciones</a>
        </li>
        <li id="movimientos_outfit">
            <a href="#" ui-sref="Movimientos Out Fit">Generar Salida de Combinaciones</a>
        </li>
        <li id="movimientos_outfit2">
            <a href="#" ui-sref="Retorno Out Fit">Generar Retorno - Venta de Combinaciones</a>
        </li>
        <li id="Indicator7">
            <a ui-sref="Indicator7"> Reporte General de movimientos</a>
        </li>
        <li id="liquidation">
            <a href="#" ui-sref="Liquidacion"> Gestionar Promociones</a>
        </li>
        <li id="publicity">
            <a href="#" ui-sref="Publicidad Ventas">Gestionar publicidad</a>
        </li>
        <li id="RequestProduct">
            <a ui-sref="RequestProduct">Ventas Individuales Web</a>
        </li>
        <li id="RequestApplication">
            <a ui-sref="RequestApplication">Ventas Empresas Web</a>
        </li>  
        <li id="PrestaShop">
            <a ui-sref="PrestaShop">Pedidos Web</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    {{-- Dependencias en Resource/assets/js/app - Gulpfile.js --}}
    <script src="{{ elixir('app/controllers/compile/admControllers.js') }}"></script>
@stop
