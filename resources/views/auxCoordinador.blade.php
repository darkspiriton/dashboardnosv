@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> Ventacorp </a>
            <ul> 
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Coordinador </a>
                    <ul>
                        <li id="productsJVE">
                            <a ui-sref="Productos JVE"><i class="md md-content-paste"></i>Kardex</a>
                        </li>
                        <li id="stock">
                            <a ui-sref="Stock"><i class="md md-local-offer"></i>Stock de Productos</a>
                        </li>
                        <li id="movimientos">
                            <a href="#" ui-sref="Movimientos"><i class="md md-remove-circle-outline"></i>Generar Salida</a>
                        </li>
                        <li id="Movimientos2JVE">
                            <a href="#" ui-sref="Movimientos2JVE"><i class="md md-swap-vert-circle"></i>Generar Retorno - Venta</a>
                        </li>
                        <li id="out_fit">
                            <a href="#" ui-sref="out_fit"><i class="md md-settings-input-component"></i>Gestionar Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit">
                            <a href="#" ui-sref="Movimientos Out Fit"><i class="md md-remove-circle-outline"></i>Generar Salida de Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit2">
                            <a href="#" ui-sref="Retorno Out Fit"><i class="md md-swap-vert-circle"></i>Generar Retorno - Venta de Combinaciones</a>
                        </li>
                        <li id="Indicator7">
                            <a ui-sref="Indicator7"><i class="md md-filter-none"></i>Reporte General de movimientos</a>
                        </li>                       
                        <li id="publicity">
                            <a href="#" ui-sref="Publicidad Ventas"><i class="md md-local-see"></i>Gestionar publicidad</a>
                        </li>
                        <li id="PrestaShop">
                            <a ui-sref="PrestaShop"><i class="md md-subtitles"></i>Pedidos Web</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Equipo </a>
                    <ul>
                        <li id="productsVEN">
                            <a ui-sref="Stock Ventas"><i class="md md-local-offer"></i>Stock de Productos</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    <!-- Dependencias en Resource/assets/js/app - Gulpfile.js -->
    <script src="{{ elixir('app/controllers/compile/coordinadorControllers.js') }}"></script>
 @stop
