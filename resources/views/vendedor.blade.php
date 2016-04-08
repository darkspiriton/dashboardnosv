@extends('layouts.dashboard')

@section('dashMenu')
    <aside id="sidebar">
        <div class="sidebar-inner c-overflow">
            <div class="profile-menu">
                <a href="#">
                    <div class="profile-pic">
                        <img src="img/profile-pics/1.jpg" alt="">
                    </div>

                    <div class="profile-info">
                        Malinda Hollaway

                        <i class="md md-arrow-drop-down"></i>
                    </div>
                </a>

                <ul class="main-menu">
                    <li>
                        <a href="#"><i class="md md-person"></i> Ver Perfil</a>
                    </li>
                    <li>
                        <a href="#"><i class="md md-history"></i> Cerrar Sesion</a>
                    </li>
                </ul>
            </div>

            <ul class="main-menu">
                <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
                <li class="active"><a href="#/"><i class="md md-format-underline"></i> Consultar Productos</a></li>
                <li class="active"><a href="#/"><i class="md md-format-underline"></i> Registro de Alcance</a></li>
                <li class="active"><a href="#/"><i class="md md-now-widgets"></i> Registro de Usuario</a></li>
                <li class="active"><a href="#/"><i class="md md-now-widgets"></i> Registro de Interes</a></li>
                <li class="active"><a href="#/"><i class="md md-now-widgetse"></i> Registro de Pedido</a></li>
            </ul>
        </div>
    </aside>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
@stop