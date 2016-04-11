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
            <a href="#"><i class="md md-now-widgets"></i> Reportes</a>
            <ul>
                <li><a href="widget-templates.html"> Reporte de ventas</a></li>
                <li><a href="widgets.html"> Reporte de segumiento</a></li>
            </ul>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Usuarios</a>
            <ul>
                <li><a href="widget-templates.html"> Crear Usuarios</a></li>
                <li><a href="widgets.html"> Editar Usuarios</a></li>
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
@stop