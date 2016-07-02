@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
    </ul>

    <li class="sub-menu">
        <a ui-sref="Socios"><i class="md md-now-widgets"></i> Ventas Proveedor </a>
    </li>

@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/PartnerCtrl.js"></script>
@stop