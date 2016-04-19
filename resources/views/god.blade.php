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
        <li id="products">
            <a href="#" ui-sref="Productos"><i class="md md-shop-two"></i> Administrar Productos</a>
        </li>
        <li id="users">
            <a href="#" ui-sref="Usuarios"><i class="md md-security"></i> Administrar usuarios</a>
        </li>
        <li id="comments">
            <a href="#" ui-sref="Comentarios"><i class="md md-messenger"></i> Administrar comentarios</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/usersCtrl.js"></script>
    <script src="app/controllers/commentsCtrl.js"></script>
    <script src="app/controllers/productsCtrl.js"></script>
@stop