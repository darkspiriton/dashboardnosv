@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="publicity">
            <a ui-sref="Publicidad"><i class="md md-public"></i> Cuadro de publicidad</a>
        </li>
        <li id="esquemas">
            <a ui-sref="Esquemas"><i class="md md-poll"></i> Esquema de pico de envio</a>
        </li>
        <li id="Facebook">
            <a ui-sref="Facebook"><i class="md md-whatshot"></i> Facebook publicidades</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="{{ elixir('app/controllers/compile/adminPubControllers.js') }}"></script>
@stop
