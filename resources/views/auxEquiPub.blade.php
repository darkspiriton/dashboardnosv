@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> The Box </a>
            <ul>  
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Equipo </a>
                    <ul>
                        <li id="publicity">
                            <a ui-sref="Publicidad"><i class="md md-public"></i>Cuadro de publicidad</a>
                        </li>
                        <li id="Facebook">
                            <a ui-sref="Facebook"><i class="md md-whatshot"></i>Facebook publicidades</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="{{ elixir('app/controllers/compile/equiPubControllers.js') }}"></script>
@stop