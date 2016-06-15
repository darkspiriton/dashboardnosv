@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="alarma">
            <a ui-sref="Alarma"><i class="md md-shop-two"></i> Alarma de productos </a>
        </li>
        <li id="outs">
            <a ui-sref="outs"><i class="md md-shop-two"></i> Productos en stock-salida-ventas de hoy</a>
        </li>
        <li id="products">
            <a href="#" ui-sref="Productos"><i class="md md-shop-two"></i> Gestionar Productos</a>
        </li>
        <li id="out_fit">
            <a href="#" ui-sref="out_fit"><i class="md md-shop-two"></i> Gestionar Out Fit</a>
        </li>
        <li id="liquidation">
            <a href="#" ui-sref="Liquidacion"><i class="md md-shop-two"></i> Gestionar Liquidaciones</a>
        </li>

        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Vista Coordinador </a>
            <ul>
                <li id="stock">
                    <a ui-sref="Stock"><i class="md md-shop-two"></i>Stock de Productos</a>
                </li>
                <li id="movimientos">
                    <a href="#" ui-sref="Movimientos"><i class="md md-shop-two"></i>Generar Salida</a>
                </li>
                <li id="movimientos2">
                    <a href="#" ui-sref="Movimientos2"><i class="md md-shop-two"></i>Generar Retorno - Venta</a>
                </li>
                <li id="movimientos_outfit">
                    <a href="#" ui-sref="Movimientos Out Fit"><i class="md md-shop-two"></i>Generar Salida Out Fit</a>
                </li>
                <li id="movimientos_outfit2">
                    <a href="#" ui-sref="Retorno Out Fit"><i class="md md-shop-two"></i>Generar Retorno - Venta de OutFit</a>
                </li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Vista publicidad </a>
            <ul>
                <li id="stock">
                    <a ui-sref="Esquemas"><i class="md md-shop-two"></i>Esquema de pico de envio</a>
                </li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Inidcadores </a>
            <ul>
                <li id="indicator1"><a ui-sref="Indicator1"> Stock General  </a></li>
                <li id="indicator2"><a ui-sref="Indicator2"> Stock Por Talla  </a></li>
                <li id="indicator3"><a ui-sref="Indicator3"> Stock Por color </a></li>
                <li id="indicator4"><a ui-sref="Indicator4"> Stock por proveedor </a></li>
                <li id="indicator5"><a ui-sref="Indicator5"> Stock de Productos por provedor </a></li>
                <li id="indicator6"><a ui-sref="Indicator6"> Lista de Proveedores </a></li>
                <li id="Indicator7"><a ui-sref="Indicator7"> Reporte de movimiento entre fechas </a></li>
            </ul>
        </li>
        <li id="users">
            <a href="#" ui-sref="Usuarios"><i class="md md-security"></i> Gestion de usuarios</a>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Gestionar planilla </a>
            <ul>
                <li id="employees">
                    <a ui-sref="Employees"><i class="md md-security"></i> Gestionar empleados</a>
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
            <a href="#"><i class="md md-now-widgets"></i> Gestión de Cuestionarios </a>
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
            <a href="#" ui-sref="Comentarios"><i class="md md-messenger"></i> Gestionar comentarios</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    <script src="app/controllers/auxStockCtrl.js"></script>
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/auxAlarmCtrl.js"></script>
    <script src="app/controllers/auxProductCtrl.js"></script>
    <script src="app/controllers/auxIndicator1Ctrl.js"></script>
    <script src="app/controllers/auxIndicator2Ctrl.js"></script>
    <script src="app/controllers/auxIndicator3Ctrl.js"></script>
    <script src="app/controllers/auxIndicator4Ctrl.js"></script>
    <script src="app/controllers/auxIndicator5Ctrl.js"></script>
    <script src="app/controllers/auxIndicator6Ctrl.js"></script>
    <script src="app/controllers/auxIndicator7Ctrl.js"></script>
    <script src="app/controllers/usersCtrl.js"></script>
    <script src="app/controllers/commentsCtrl.js"></script>
    <script src="app/controllers/employeesCtrl.js"></script>
    <script src="app/controllers/payrollEntryCtrl.js"></script>
    <script src="app/controllers/godEmployeeAssistsCtrl.js"></script>
    <script src="app/controllers/indicatorPayRoleCtrl.js"></script>
    <script src="app/controllers/products_out.js"></script>
    <script src="app/controllers/q_questionnairesCtrl.js"></script>
    <script src="app/controllers/q_questionsCtrl.js"></script>
    <script src="app/controllers/q_categoriesCtrl.js"></script>
    <script src="app/controllers/q_AnswerIndicator.js"></script>
    <script src="app/controllers/q_productsCtrl.js"></script>
    <script src="app/controllers/q_customersCtrl.js"></script>
    <script src="app/controllers/outFitCtrl.js"></script>
    <script src="app/controllers/liquidationCtrl.js"></script>

    <script src="app/controllers/auxMovementCtrl.js"></script>
    <script src="app/controllers/auxMovement2Ctrl.js"></script>
    <script src="app/controllers/auxMovementOutFitCtrl.js"></script>
    <script src="app/controllers/auxMovementOutFit2Ctrl.js"></script>

    <script src="app/controllers/auxEsquemaCtrl.js"></script>
@stop