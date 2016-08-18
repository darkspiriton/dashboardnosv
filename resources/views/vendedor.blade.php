@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> Ventacorp </a>
            <ul> 
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Equipo </a>
                    <ul>
                        <li id="productsVEN">
                            <a ui-sref="Stock Ventas"><i class="md md-archive"></i>Stock de Productos</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <!-- Dependencias en Resource/assets/js/app - Gulpfile.js -->
    <script src="{{ elixir('app/controllers/compile/vendedorControllers.js') }}"></script>
@stop
