@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li id="products">
            <a href="#" ui-sref="Productos"><i class="md md-shop-two"></i> Administrar Productos</a>
        </li>
        <li id="stock">
            <a href="#" ui-sref="Stock"><i class="md md-shop-two"></i>Stock de Productos</a>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-now-widgets"></i> Inidcadores </a>
            <ul>
                <li id="indicator1"><a  ui-sref="Indicator1"> Stock General  </a></li>
                <li id="indicator2"><a  ui-sref="Indicator2"> Stock Por Talla  </a></li>
                <li id="indicator3"><a  ui-sref="Indicator3"> Stock Por color </a></li>
                <li id="indicator4"><a  ui-sref="Indicator4"> Stock por proveedor </a></li>
                <li id="indicator5"><a  ui-sref="Indicator5"> Stock de Productos por provedor </a></li>
                <li id="indicator6"><a  ui-sref="Indicator6"> Lista de Proveedores </a></li>
            </ul>
        </li>
        <li id="comments">
            <a href="#" ui-sref="Comentarios"><i class="md md-messenger"></i> Administrar comentarios</a>
        </li>
    </ul>
@stop

@section('dashCtrls')
    <script src="app/controllers/homeCtrl.js"></script>
    <script src="app/controllers/auxProductCtrl.js"></script>
    <script src="app/controllers/auxIndicator1Ctrl.js"></script>
    <script src="app/controllers/auxIndicator2Ctrl.js"></script>
    <script src="app/controllers/auxIndicator3Ctrl.js"></script>
    <script src="app/controllers/auxIndicator4Ctrl.js"></script>
    <script src="app/controllers/auxIndicator5Ctrl.js"></script>
    <script src="app/controllers/auxIndicator6Ctrl.js"></script>
    <script src="app/controllers/usersCtrl.js"></script>
    <script src="app/controllers/commentsCtrl.js"></script>
    <script src="app/controllers/auxStockCtrl.js"></script>
@stop