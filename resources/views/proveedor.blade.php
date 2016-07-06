@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio </a></li>
    </ul>
    <ul class="main-menu">
       <li><a ui-sref="Socios"><i class="md md-now-widgets"></i> Ventas Proveedor </a></li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/PartnerCtrl.js"></script>
@stop