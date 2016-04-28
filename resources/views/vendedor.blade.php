@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li><a href="#"><i class="md md-format-underline"></i> Consultar Productos</a></li>
        <li><a href="#"><i class="md md-format-underline"></i> Registro de Pedido</a></li>
        <li id="alcanses"><a href="#" ui-sref="Alcanses"><i class="md md-now-widgets"></i> Registro de Alcance</a></li>
        <li id="intereses"><a href="#" ui-sref="Intereses"><i class="md md-now-widgets"></i> Registro de Interes</a></li>
        <li id="clientes"><a href="#" ui-sref="Clientes"><i class="md md-now-widgets"></i> Administracion de Clientes</a></li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/customersCtrl.js"></script>
    <script src="app/controllers/addressesCtrl.js"></script>
    <script src="app/controllers/phonesCtrl.js"></script>
    <script src="app/controllers/socialsCtrl.js"></script>
    <script src="app/controllers/scopesCtrl.js"></script>
    <script src="app/controllers/interestsCtrl.js"></script>
@stop