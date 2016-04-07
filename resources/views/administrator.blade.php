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
                <li class="active"><a href="#/"><i class="md md-home"></i> Home</a></li>
                <li class="sub-menu">
                    <a href="#"><i class="md md-format-underline"></i> Indicadores</a>
                    <ul>
                        <li><a href="widget-templates.html"> Indicadores de ventas</a></li>
                        <li><a href="widgets.html"> Indicadores de ventas por producto</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="#"><i class="md md-now-widgets"></i> Reportes</a>
                    <ul>
                        <li><a href="widget-templates.html"> Reporte de ventas</a></li>
                        <li><a href="widgets.html"> Reporte de ventas por producto</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="md md-view-list"></i> Usuarios de sistema</a>
                </li>
            </ul>
        </div>
    </aside>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
@stop