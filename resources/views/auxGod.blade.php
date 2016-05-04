@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="products">
            <a href="#" ui-sref="Productos"><i class="md md-shop-two"></i> Administrar Productos</a>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/auxProductCtrl.js"></script>
@stop