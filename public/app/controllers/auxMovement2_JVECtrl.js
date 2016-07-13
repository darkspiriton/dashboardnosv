angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Movimientos2', {
                url: '/Generar-retorno-y-ventas',
                templateUrl: 'app/partials/auxMovement2.html',
                controller : 'auxMovement2Ctrl'
            });
    })
    .controller('auxMovement2Ctrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('movimientos2');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha de creación", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Fecha de salida", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Codigo", "bSortable" : true},
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : false},
                {"sTitle": "Color", "bSortable" : false},
                {"sTitle": "Precio  (S/)", "bSortable" : false},
                {"sTitle": "Descuento (S/)", "bSortable" : false},
                {"sTitle": "Precio Final (S/)", "bSortable" : false},
                {"sTitle": "Tipo", "bSortable" : false, "sWidth": "80px"},
                {"sTitle": "Acción" , "bSortable" : false, "bSearchable": false , "sWidth": "420px"}
            ],
            actions	:  	[
                ['status',   {
                    0 : { txt : 'regular' , cls : 'bgm-green', dis : false },
                    1 : { txt : 'liquidacion' ,  cls : 'btn-info',dis: false}
                }
                ],
                ['actions', [
                    ['Retornado', 'prdReturn' ,'bgm-teal'],
                    ['Vendido', 'prdSale' ,'bgm-blue'],
                    ['reprogramar', 'reprogramar' ,'bgm-purple']
                ]
                ]
            ],
            data    :   ['created_at','movement.date_shipment','cod','name','size.name','color.name','price',
                            'movement.discount','pricefinal','status','actions'
                        ],
            configStatus : 'liquidation'
        };

        var alertConfig = {
            title: "¿Esta seguro?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonColor: "#212121",
            cancelButtonText: "CANCELAR",
            closeOnConfirm: true,
            html: true
        };

        $scope.productClear = {
            id: null,
            situation: null
        };

        $scope.situations = [
            {id: 1, name:'No le gusto' },
            {id: 2, name:'La foto no es igual al producto' },
            {id: 3, name:'Producto dañado' },
            {id: 4, name:'No se encontro al cliente' },
            {id: 5, name:'No es la talla' },
            {id: 6, name:'No se encontro el código' },
            {id: 7, name:'No llegamos al cliente' }
        ];

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxmovement/get/movement')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.prdSale = function(i){
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>P. Final</th>
                                            <th>Descuento</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th>${$scope.tableData[i].cod}</th>
                                            <th>${$scope.tableData[i].name}</th>
                                            <td>${$scope.tableData[i].size.name}</td>
                                            <td>${$scope.tableData[i].color.name}</td>
                                            <td>${$scope.tableData[i].price}</td>
                                            <td>${$scope.tableData[i].movement.discount}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    petition.post('api/auxmovement/set/sale', {id: $scope.tableData[i].product_id})
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        $scope.prdReturn = function(i){
            $scope.product.id = $scope.tableData[i].product_id;
            $scope.product.cod = $scope.tableData[i].cod;
            $scope.product.name = $scope.tableData[i].name;
            $scope.product.size = $scope.tableData[i].size.name;
            $scope.product.color = $scope.tableData[i].color.name;
            $scope.product.situation = null;
            util.modal();
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Motivo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th>${$scope.product.cod}</th>
                                            <th>${$scope.product.name}</th>
                                            <td>${$scope.product.size}</td>
                                            <td>${$scope.product.color}</td>
                                            <td>${( function(){
                                                var situation = "";                                                
                                                switch ($scope.product.situation){
                                                    case 1:
                                                        situation="No le gusto";
                                                        break;
                                                    case 2:
                                                        situation="La foto no es igual al producto";
                                                        break;
                                                    case 3:
                                                        situation="Producto dañado";
                                                        break;
                                                    case 4:
                                                        situation="No se encontro cliente";
                                                        break;
                                                    case 5:
                                                        situation="No es la talla";
                                                        break;
                                                    case 6:
                                                        situation="No se encontro el código";
                                                        break;
                                                    case 7:
                                                        situation="No llegamos al cliente";
                                                        break;
                                                }
                                                return situation; })()}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    petition.post('api/auxmovement', $scope.product)
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                            util.modalClose();
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };

        $scope.cancel = function(){
          util.modalClose();
        };

                /**
         *  Reprogramar salida de producto
         *  @return movement
         */

        $scope.prdProgram = function(){
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Cod</th>
                                            <th>Producto</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Fecha salida</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>${$scope.tableData[$scope.index].cod}</th>
                                                <th>${$scope.tableData[$scope.index].name}</th>
                                                <td>${$scope.tableData[$scope.index].size.name}</td>
                                                <td>${$scope.tableData[$scope.index].color.name}</td>
                                                <td>${(function(){ 
                                                    var day = $scope.programDate.getDate();
                                                    var month = ($scope.programDate.getMonth().toString().length == 1)?
                                                                '0'.concat(($scope.programDate.getMonth() + 1).toString()):
                                                                ($scope.programDate.getMonth() + 1).toString();
                                                    var year = $scope.programDate.getFullYear();
                                                    var date = '';
                                                    return date.concat(day,'-',month,'-',year)})()}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            swal(alertConfig,
                function () {
                    var movement_id = $scope.tableData[$scope.index].movement.id;
                    petition.put('api/auxmovement/' + movement_id, { date:$scope.programDate})
                        .then(function (data) {
                            toastr.success(data.message);
                            $scope.formSubmit = false;
                            $scope.list();
                            util.modalClose('reProgram');
                        }, function (error) {
                            toastr.error('Uyuyuy dice: ' + error.data.message);
                            $scope.formSubmit = false;
                        });
                });
        };
        
        $scope.reprogramar = function (i){
            $scope.index = i;
            $scope.programDate = null;
            util.modal("reProgram");
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    });