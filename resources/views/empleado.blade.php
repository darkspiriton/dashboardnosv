@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active" id="home"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="assists">
            <a href="#" ui-sref="assists"><i class="md md-shop-two"></i>Ver mis asistencias</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/employeeAssistsCtrl.js"></script>
@stop