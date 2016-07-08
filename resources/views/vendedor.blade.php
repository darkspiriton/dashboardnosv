@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="productsJVE">
            <a ui-sref="Productos JVE"><i class="md md-shop-two"></i>Kardex</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <<script src="app/controllers/auxProductJVECtrl.js"></script>
@stop