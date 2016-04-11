@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-format-underline"></i> Indicadores</a>
            <ul>
                <li><a href="widget-templates.html"> Indicadores de ventas</a></li>
            </ul>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Pedidos confirmados</a>
            <ul>
                <li><a href="widget-templates.html"> Reporte de ventas</a></li>
                <li><a href="widgets.html"> Reporte de ventas por producto</a></li>
            </ul>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-format-underline"></i> Hoja de Rutas</a>
            <ul>
                <li><a href="widget-templates.html"> Registro de seguimiento</a></li>
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
@stop