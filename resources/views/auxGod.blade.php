@extends('layouts.dashboard')

@section('dashMenu')
    <ul class="main-menu">
        <li class="active"><a href="#/"><i class="md md-home"></i> Inicio</a></li>

<!--         <li id="alarma">
            <a ui-sref="Alarma"><i class="md md-alarm-on"></i> Alarma de productos </a>
        </li> -->

        <li class="sub-menu">
            <a href="#"><i class="md md-redeem"></i> NosVenden </a>
            <ul> 
                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Dirección </a>
                    <ul>
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
                            <a href="#" ui-sref="Productos"> Kardex</a>
                        </li>
                        <li id="stock">
                            <a ui-sref="Stock">Stock de Productos</a>
                        </li>
                        <li id="movimientos">
                            <a href="#" ui-sref="Movimientos">Generar Salida</a>
                        </li>
                        <li id="movimientos2">
                            <a href="#" ui-sref="Movimientos2">Generar Retorno - Venta</a>
                        </li>
<!--                         <li id="movementday">
                            <a href="#" ui-sref="MovementDay"> Movimientos Diarios</a>
                        </li> -->
                        <li id="out_fit">
                            <a href="#" ui-sref="out_fit">Gestionar Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit">
                            <a href="#" ui-sref="Movimientos Out Fit">Generar Salida de Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit2">
                            <a href="#" ui-sref="Retorno Out Fit">Generar Retorno - Venta de Combinaciones</a>
                        </li>
                        <li id="Indicator7">
                            <a ui-sref="Indicator7"> Reporte General de movimientos</a>
                        </li>
                        <li id="liquidation">
                            <a href="#" ui-sref="Liquidacion"> Gestionar Promociones</a>
                        </li>
                        <li id="publicity">
                            <a href="#" ui-sref="Publicidad Ventas">Gestionar publicidad</a>
                        </li>
                        <li id="RequestProduct">
                            <a ui-sref="RequestProduct">Ventas Individuales Web</a>
                        </li>
                        <li id="RequestApplication">
                            <a ui-sref="RequestApplication">Ventas Empresas Web</a>
                        </li>  
                        <li id="PrestaShop">
                            <a ui-sref="PrestaShop">Pedidos Web</a>
                        </li>      
  
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Coordinador </a>
                    <ul>
                        <li id="productsJVE">
                            <a ui-sref="Productos JVE">Kardex</a>
                        </li>
                        <li id="stock">
                            <a ui-sref="Stock">Stock de Productos</a>
                        </li>
                        <li id="movimientos">
                            <a href="#" ui-sref="Movimientos">Generar Salida</a>
                        </li>
                        <li id="Movimientos2JVE">
                            <a href="#" ui-sref="Movimientos2JVE">Generar Retorno - Venta</a>
                        </li>
                        <li id="out_fit">
                            <a href="#" ui-sref="out_fit">Gestionar Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit">
                            <a href="#" ui-sref="Movimientos Out Fit">Generar Salida de Combinaciones</a>
                        </li>
                        <li id="movimientos_outfit2">
                            <a href="#" ui-sref="Retorno Out Fit">Generar Retorno - Venta de Combinaciones</a>
                        </li>
                        <li id="Indicator7">
                            <a ui-sref="Indicator7">Reporte General de movimientos</a>
                        </li>                       
                        <li id="publicity">
                            <a href="#" ui-sref="Publicidad Ventas">Gestionar publicidad</a>
                        </li>
                        <li id="PrestaShop">
                            <a ui-sref="PrestaShop">Pedidos Web</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Equipo </a>
                    <ul>
                        <li id="productsVEN">
                            <a ui-sref="Stock Ventas">Stock de Productos</a>
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
                            <a ui-sref="Publicidad">Cuadro de publicidad</a>
                        </li>
                        <li id="esquemas">
                            <a ui-sref="Esquemas">Esquema de pico de envio</a>
                        </li>
                        <li id="Facebook">
                            <a ui-sref="Facebook">Facebook publicidades</a>
                        </li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Coordinador </a>
                    <ul>
                        <li id="publicity">
                            <a ui-sref="Publicidad">Cuadro de publicidad</a>
                        </li>
                        <li id="esquemas">
                            <a ui-sref="Esquemas">Esquema de pico de envio</a>
                        </li>
                        <li id="Facebook">
                            <a ui-sref="Facebook">Facebook publicidades</a>
                        </li>                        
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#"><i class="md md-redeem"></i> Equipo </a>
                    <ul>
                        <li id="publicity">
                            <a ui-sref="Publicidad">Cuadro de publicidad</a>
                        </li>
                        <li id="Facebook">
                            <a ui-sref="Facebook">Facebook publicidades</a>
                        </li>                        
                    </ul>
                </li>
            </ul>
        </li>

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-assignment-ind"></i> Vista Coordinador </a>
            <ul>
                <li id="products">
                    <a href="#" ui-sref="Productos"> Kardex</a>
                </li>
                <li id="stock">
                    <a ui-sref="Stock">Stock de Productos</a>
                </li>
                <li id="movimientos">
                    <a href="#" ui-sref="Movimientos">Generar Salida</a>
                </li>
                <li id="movimientos2">
                    <a href="#" ui-sref="Movimientos2">Generar Retorno - Venta</a>
                </li>
                <li id="movimientos_outfit">
                    <a href="#" ui-sref="Movimientos Out Fit">Generar Salida de Combinaciones</a>
                </li>
                <li id="movimientos_outfit2">
                    <a href="#" ui-sref="Retorno Out Fit">Generar Retorno - Venta de Combinaciones</a>
                </li>
                <li id="out_fit">
                    <a href="#" ui-sref="out_fit"> Gestionar Combinaciones</a>
                </li>
                <li id="liquidation">
                    <a href="#" ui-sref="Liquidacion"> Gestionar Promociones</a>
                </li>
                <li id="movementday">
                    <a href="#" ui-sref="MovementDay"> Movimientos Diarios</a>
                </li>
            </ul>
        </li> -->

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-panorama"></i> Vista publicidad </a>
            <ul>
                <li id="publicity">
                    <a ui-sref="Publicidad"> Cuadro de publicidad</a>
                </li>
                <li id="publicityJVE">
                    <a href="#" ui-sref="Publicidad Ventas"> Gestionar publicidad</a>
                </li>
                <li id="esquemas">
                    <a ui-sref="Esquemas">Esquema de pico de envio</a>
                </li>
                <li id="Facebook">
                    <a ui-sref="Facebook">Facebook publicidades</a>
                </li>
            </ul>
        </li> -->

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-shopping-cart"></i>Vista Proveedor</a>
            <ul>
                <li><a ui-sref="SociosPanel"><i class="md md-now-widgets"></i> Ventas Proveedores</a></li>
                <li><a ui-sref="pagos"><i class="md md-now-widgets"></i> Pago a Proveedores</a></li>
                <li><a ui-sref="listapagos"><i class="md md-now-widgets"></i> Gestion de Pagos Realizados</a></li>
            </ul>
        </li> -->

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-local-parking"></i> Gestionar planilla </a>
            <ul>
                <li id="employees">
                    <a ui-sref="Employees">Gestionar empleados</a>
                </li>
                <li id="payrollEntry">
                    <a ui-sref="PayrollEntry"> Registro de asistencias </a>
                </li>
                <li id="reportePlanilla">
                    <a ui-sref="ReportePlanilla"> Reporte de planilla entre fechas </a>
                </li>
            </ul>
        </li> -->

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-assignment"></i> Gestión de Cuestionarios </a>
            <ul>
                <li id="categories">
                    <a ui-sref="categories"> Categorías de cuestionarios</a>
                </li>
                <li id="questions">
                    <a ui-sref="questions"> Crear preguntas </a>
                </li>
                <li id="questionnaires">
                    <a ui-sref="questionnaires"> Crear cuestionario</a>
                </li>
                <li id="q_products">
                    <a ui-sref="q_products"> Crear cuestionario de producto</a>
                </li>
                <li id="q_customers">
                    <a ui-sref="q_customers"> Crear cuestionario de cliente</a>
                </li>
                <li id="q_AnswerIndicator">
                    <a ui-sref="q_AnswerIndicator"> Indicador de coincidencias</a>
                </li>
            </ul>
        </li> -->

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-trending-up"></i> Indicadores </a>
            <ul>
                <li id="indicator1"><a ui-sref="Indicator1"> Stock General  </a></li>
                <li id="indicator2"><a ui-sref="Indicator2"> Stock Por Talla  </a></li>
                <li id="indicator3"><a ui-sref="Indicator3"> Stock Por color </a></li>
                <li id="indicator4"><a ui-sref="Indicator4"> Stock por proveedor </a></li>
                <li id="indicator5"><a ui-sref="Indicator5"> Stock de Productos por provedor </a></li>
                <li id="indicator6"><a ui-sref="Indicator6"> Lista de Proveedores </a></li>
                <li id="Indicator7"><a ui-sref="Indicator7"> Reporte de movimiento entre fechas </a></li>
            </ul>
        </li> -->


<!--         <li class="sub-menu">
            <a href="#"><i class="md md-view-carousel"></i> Gestion de Ventas Web </a>
            <ul>
                <li id="RequestProduct"><a ui-sref="RequestProduct"> Gestion de Ventas Individuales </a></li>
                <li id="RequestApplication"><a ui-sref="RequestApplication"> Gestion de Ventas Empresas </a></li>               
            </ul>
        </li> -->

<!--         <li class="sub-menu">
            <a href="#"><i class="md md-view-carousel"></i> Gestion de PrestaShop </a>
            <ul>
                <li id="PrestaShop"><a ui-sref="PrestaShop"> Gestion Pedidos PrestaShop </a></li>            
            </ul>
        </li> -->

    </ul>
@stop

@section('dashCtrls')
    <script src="app/lib/filesave.js"></script>
    <!-- Dependencias en Resource/assets/js/app - Gulpfile.js -->
    <script src="{{ elixir('app/controllers/compile/godControllers.js') }}"></script>    
@stop
