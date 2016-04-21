@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li><a href="#"><i class="md md-format-underline"></i> Consultar Productos</a></li>
        <li><a href="#"><i class="md md-format-underline"></i> Registro de Alcance</a></li>
        <li><a href="#"><i class="md md-now-widgets"></i> Registro de Interes</a></li>
        <li><a href="#"><i class="md md-now-widgetse"></i> Registro de Pedido</a></li>

        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Administracion de Clientes</a>
            <ul>
                <li id="clientes"><a href="#" ui-sref="Clientes"><i class="md md-now-widgets"></i> Clientes</a></li>
                <li id="direcciones"><a href="#" ui-sref="Direcciones"><i class="md md-now-widgets"></i> Direcciones</a></li>
                <li id="telefonos"><a href="#" ui-sref="Telefonos"><i class="md md-now-widgets"></i> Telefonos</a></li>
                <li id="socials"><a href="#" ui-sref="socials"><i class="md md-now-widgets"></i> Redes Sociales</a></li>
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/customersCtrl.js"></script>
@stop