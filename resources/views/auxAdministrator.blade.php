@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="outs">
            <a ui-sref="outs"><i class="md md-swap-horiz"></i> Productos en stock-salida-ventas de hoy</a>
        </li>
        <li id="products">
            <a href="#" ui-sref="Productos"><i class="md md-shopping-cart"></i> Administrar Productos</a>
        </li>
        <li id="stock">
            <a href="#" ui-sref="Stock"><i class="md md-shop-two"></i>Stock de Productos</a>
        </li>
        <li id="movimientos">
            <a href="#" ui-sref="Movimientos"><i class="md md-trending-neutral"></i>Generar Salida</a>
        </li>
        <li id="movimientos2">
            <a href="#" ui-sref="Movimientos2"><i class="md md-swap-horiz"></i>Generar Retorno - Venta</a>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-trending-up"></i> Inidcadores </a>
            <ul>
                <li id="indicator1"><a  ui-sref="Indicator1"> Stock General  </a></li>
                <li id="indicator2"><a  ui-sref="Indicator2"> Stock Por Talla  </a></li>
                <li id="indicator3"><a  ui-sref="Indicator3"> Stock Por color </a></li>
                <li id="indicator4"><a  ui-sref="Indicator4"> Stock por proveedor </a></li>
                <li id="indicator5"><a  ui-sref="Indicator5"> Stock de Productos por provedor </a></li>
                <li id="indicator6"><a  ui-sref="Indicator6"> Lista de Proveedores </a></li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#"><i class="md md-local-parking"></i> Administracion de planilla </a>
            <ul>
                <li id="employees">
                    <a ui-sref="Employees"><i class="md md-security"></i> Administrar empleados</a>
                </li>
                <li id="payrollEntry">
                    <a ui-sref="PayrollEntry"><i class="md md-security"></i> Registro de asistencias </a>
                </li>
                <li id="reportePlanilla">
                    <a ui-sref="ReportePlanilla"><i class="md md-security"></i> Reporte de planilla entre fechas </a>
                </li>
            </ul>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-assignment"></i> Gestión de Cuestionarios </a>
            <ul>
                <li id="categories">
                    <a ui-sref="categories"><i class="md md-security"></i> Categorías de cuestionarios</a>
                </li>
                <li id="questions">
                    <a ui-sref="questions"><i class="md md-security"></i> Crear preguntas </a>
                </li>
                <li id="questionnaires">
                    <a ui-sref="questionnaires"><i class="md md-security"></i> Crear cuestionario</a>
                </li>
                <li id="q_products">
                    <a ui-sref="q_products"><i class="md md-security"></i> Crear cuestionario de producto</a>
                </li>
                <li id="q_customers">
                    <a ui-sref="q_customers"><i class="md md-security"></i> Crear cuestionario de cliente</a>
                </li>
                <li id="q_AnswerIndicator">
                    <a ui-sref="q_AnswerIndicator"><i class="md md-security"></i> Indicador de coincidencias</a>
                </li>
            </ul>
        </li>
        <li id="comments">
            <a href="#" ui-sref="Comentarios"><i class="md md-messenger"></i> Administrar comentarios</a>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-view-carousel"></i> Gestion de Ventas Web </a>
            <ul>
                <li id="RequestProduct"><a ui-sref="RequestProduct"> Gestion de Ventas Individuales </a></li>
                <li id="RequestApplication"><a ui-sref="RequestApplication"> Gestion de Ventas Empresas </a></li>               
            </ul>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    {{-- Dependencias en Resource/assets/js/app - Gulpfile.js --}}
    <script src="{{ elixir('app/controllers/compile/admControllers.js') }}"></script>
@stop