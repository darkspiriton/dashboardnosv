@extends('layouts.dashboardBoss')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> NosVenden </a>
            <ul> 
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i>Dirección </a>
                    <ul>
                        <li id="outs">
                            <a ui-sref="outs"><i class="md md-today"></i>Movimientos del dia</a>
                        </li>
                        <li>
                            <a ui-sref="SociosPanel"><i class="md md-account-balance"></i>Proveedores Ventas</a>
                        </li>
                        <li>
                            <a ui-sref="pagos"><i class="md md-nature-people"></i>Proveedores Crear Pagos</a>
                        </li>
                        <li>
                            <a ui-sref="listapagos"><i class="md md-attach-money"></i>Proveedores Lista de Pagos</a>
                        </li>
                        <li id="employees">
                            <a ui-sref="Employees"><i class="md md-mood"></i>Creación de empleado</a>
                        </li>
                        <li id="payrollEntry">
                            <a ui-sref="PayrollEntry"><i class="md md-today"></i>Creación de horario de empleado</a>
                        </li>
                        <li id="reportePlanilla">
                            <a ui-sref="ReportePlanilla"><i class="md md-recent-actors"></i>Reporte de planilla de empleados </a>
                        </li>
                        <li id="categories">
                            <a ui-sref="categories"><i class="md md-assignment-turned-in"></i>Crear categoria de cuestionario</a>
                        </li>
                        <li id="questions">
                            <a ui-sref="questions"><i class="md md-assignment-turned-in"></i>Crear preguntas para cuestionario</a>
                        </li>
                        <li id="questionnaires">
                            <a ui-sref="questionnaires"><i class="md md-assignment-turned-in"></i>Crear cuestionario</a>
                        </li>
                        <li id="q_products">
                            <a ui-sref="q_products"><i class="md md-assignment-turned-in"></i>Crear cuestionario de producto</a>
                        </li>
                        <li id="q_customers">
                            <a ui-sref="q_customers"><i class="md md-assignment-turned-in"></i>Crear cuestionario de cliente</a>
                        </li>
                        <li id="q_AnswerIndicator">
                            <a ui-sref="q_AnswerIndicator"><i class="md md-label"></i>Indicador de coincidencia</a>
                        </li>
                        <li id="indicator1">
                            <a ui-sref="Indicator1"><i class="md md-poll"></i> Stock General</a>
                        </li>
                        <li id="indicator2">
                            <a ui-sref="Indicator2"><i class="md md-poll"></i> Stock Por Talla</a>
                        </li>
                        <li id="indicator3">
                            <a ui-sref="Indicator3"><i class="md md-poll"></i> Stock Por Color</a>
                        </li>
                        <li id="indicator4">
                            <a ui-sref="Indicator4"><i class="md md-poll"></i> Stock por Proveedor </a>
                        </li>
                        <li id="indicator5">
                            <a ui-sref="Indicator5"><i class="md md-poll"></i> Stock de Productos por Proveedor </a>
                        </li>
                        <li id="indicator6">
                            <a ui-sref="Indicator6"><i class="md md-group"></i> Lista de Proveedores </a>
                        </li>
                        <li id="users">
                            <a href="#" ui-sref="Usuarios"><i class="md md-account-circle"></i>Crear Usuario Dash</a>
                        </li>
                        <li id="comments">
                            <a href="#" ui-sref="Comentarios"><i class="md md-comment"></i>Comentarios Web</a>
                        </li>
                        <li id="productsHELP">
                            <a href="#" ui-sref="Productos Eliminados"><i class="md md-comment"></i>Productos eliminados</a>
                        </li>   
                    </ul>
                </li>
            </ul>
        </li>

        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> Ventacorp </a>
            <ul>
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Dirección </a>
                    <ul>
                        <li id="products">
                            <a href="#" ui-sref="Productos"><i class="md md-content-paste"></i>Kardex</a>
                        </li>
                        <li id="stock">
                            <a ui-sref="Stock"><i class="md md-local-offer"></i>Stock de Productos</a>
                        </li>
                        <li id="movimientos">
                            <a href="#" ui-sref="Movimientos"><i class="md md-remove-circle-outline"></i>Generar Salida</a>
                        </li>
                        <li id="Movimientos2">
                            <a href="#" ui-sref="Movimientos2"><i class="md md-swap-vert-circle"></i>Generar Retorno - Venta</a>
                        </li>
                        <li id="out_fit">
                            <a href="#" ui-sref="out_fit"><i class="md md-settings-input-component"></i>Gestionar Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit">
                            <a href="#" ui-sref="Movimientos Out Fit"><i class="md md-remove-circle-outline"></i>Generar Salida de Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit2">
                            <a href="#" ui-sref="Retorno Out Fit"><i class="md md-swap-vert-circle"></i>Generar Retorno - Venta de Combinaciones</a>
                        </li>
                        <li id="Indicator7">
                            <a ui-sref="Indicator7"><i class="md md-filter-none"></i>Reporte General de movimientos</a>
                        </li>
                        <li id="liquidation">
                            <a href="#" ui-sref="Liquidacion"><i class="md md-flash-on"></i>Gestionar Promociones</a>
                        </li>
                        <li id="publicity">
                            <a href="#" ui-sref="Publicidad Ventas"><i class="md md-local-see"></i>Gestionar publicidad</a>
                        </li>
                        <li id="RequestProduct">
                            <a ui-sref="RequestProduct"><i class="md md-web"></i>Ventas Individuales Web</a>
                        </li>
                        <li id="RequestApplication">
                            <a ui-sref="RequestApplication"><i class="md md-web"></i>Ventas Empresas Web</a>
                        </li>  
                        <li id="PrestaShop">
                            <a ui-sref="PrestaShop"><i class="md md-subtitles"></i>Pedidos Web</a>
                        </li>     
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Coordinador </a>
                    <ul>
                        <li id="productsJVE">
                            <a ui-sref="Productos JVE"><i class="md md-content-paste"></i>Kardex</a>
                        </li>
                        <li id="stock">
                            <a ui-sref="Stock"><i class="md md-local-offer"></i>Stock de Productos</a>
                        </li>
                        <li id="movimientos">
                            <a href="#" ui-sref="Movimientos"><i class="md md-remove-circle-outline"></i>Generar Salida</a>
                        </li>
                        <li id="Movimientos2JVE">
                            <a href="#" ui-sref="Movimientos2JVE"><i class="md md-swap-vert-circle"></i>Generar Retorno - Venta</a>
                        </li>
                        <li id="out_fit">
                            <a href="#" ui-sref="out_fit"><i class="md md-settings-input-component"></i>Gestionar Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit">
                            <a href="#" ui-sref="Movimientos Out Fit"><i class="md md-remove-circle-outline"></i>Generar Salida de Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit2">
                            <a href="#" ui-sref="Retorno Out Fit"><i class="md md-swap-vert-circle"></i>Generar Retorno - Venta de Combinaciones</a>
                        </li>
                        <li id="Indicator7">
                            <a ui-sref="Indicator7"><i class="md md-filter-none"></i>Reporte General de movimientos</a>
                        </li>                       
                        <li id="publicity">
                            <a href="#" ui-sref="Publicidad Ventas"><i class="md md-local-see"></i>Gestionar publicidad</a>
                        </li>
                        <li id="PrestaShop">
                            <a ui-sref="PrestaShop"><i class="md md-subtitles"></i>Pedidos Web</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Equipo </a>
                    <ul>
                        <li id="productsVEN">
                            <a ui-sref="Stock Ventas"><i class="md md-local-offer"></i>Stock de Productos</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> The Box </a>
            <ul>
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Dirección </a>
                    <ul>
                        <li id="publicity">
                            <a ui-sref="Publicidad"><i class="md md-public"></i>Cuadro de publicidad</a>
                        </li>
                        <li id="esquemas">
                            <a ui-sref="Esquemas"><i class="md  md-poll"></i>Esquema de pico de envio</a>
                        </li>
                        <li id="Facebook">
                            <a ui-sref="Facebook"><i class="md md-whatshot"></i>Facebook publicidades</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Coordinador </a>
                    <ul>
                        <li id="publicity">
                            <a ui-sref="Publicidad"><i class="md md-public"></i>Cuadro de publicidad</a>
                        </li>
                        <li id="esquemas">
                            <a ui-sref="Esquemas"><i class="md  md-poll"></i>Esquema de pico de envio</a>
                        </li>
                        <li id="Facebook">
                            <a ui-sref="Facebook"><i class="md md-whatshot"></i>Facebook publicidades</a>
                        </li>                        
                    </ul>
                </li>

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
    <script src="app/lib/filesave.js"></script>
    {{-- # Dependencias en Resource/assets/js/app - Gulpfile.js --}}
    <script src="{{ elixir('app/controllers/compile/godControllers.js') }}"></script>    
@stop
