@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active">
            <a href="#/"><i class="md md-home"></i> Inicio</a>
        </li>
        <li id="outs">
            <a ui-sref="outs">Movimientos del dia</a>
        </li>
        <li>
            <a ui-sref="SociosPanel">Proveedores Ventas</a>
        </li>
        <li>
            <a ui-sref="pagos">Proveedores Crear Pagos</a>
        </li>
        <li>
            <a ui-sref="listapagos">Proveedores Lista de Pagos</a>
        </i>
        <li id="employees">
            <a ui-sref="Employees">Creación de empleado</a>
        </li>
        <li id="payrollEntry">
            <a ui-sref="PayrollEntry">Creación de horario de empleado</a>
        </li>
        <li id="reportePlanilla">
            <a ui-sref="ReportePlanilla">Reporte de planilla de empleados </a>
        </li>
        <li id="categories">
            <a ui-sref="categories">Crear categoria de cuestionario</a>
        </li>
        <li id="questions">
            <a ui-sref="questions">Crear preguntas para cuestionario</a>
        </li>
        <li id="questionnaires">
            <a ui-sref="questionnaires">Crear cuestionario</a>
        </li>
        <li id="q_products">
            <a ui-sref="q_products">Crear cuestionario de producto</a>
        </li>
        <li id="q_customers">
            <a ui-sref="q_customers">Crear cuestionario de cliente</a>
        </li>
        <li id="q_AnswerIndicator">
            <a ui-sref="q_AnswerIndicator">Indicador de coincidencia</a>
        </li>
        <li id="indicator1">
            <a ui-sref="Indicator1"> Stock General</a>
        </li>
        <li id="indicator2">
            <a ui-sref="Indicator2"> Stock Por Talla</a>
        </li>
        <li id="indicator3">
            <a ui-sref="Indicator3"> Stock Por Color</a>
        </li>
        <li id="indicator4">
            <a ui-sref="Indicator4"> Stock por Proveedor </a>
        </li>
        <li id="indicator5">
            <a ui-sref="Indicator5"> Stock de Productos por Proveedor </a>
        </li>
        <li id="indicator6">
            <a ui-sref="Indicator6"> Lista de Proveedores </a>
        </li>
        <li id="users">
            <a href="#" ui-sref="Usuarios">Crear Usuario Dash</a>
        </li>
        <li id="comments">
            <a href="#" ui-sref="Comentarios">Comentarios Web</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    <!-- Dependencias en Resource/assets/js/app - Gulpfile.js -->
    <script src="{{ elixir('app/controllers/compile/admNosControllers.js') }}"></script>    
@stop
