angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Retorno Out Fit', {
                url: '/Generar-retorno-y-ventas-de-out-fit',
                templateUrl: 'app/partials/auxMovementOutFit2.html',
                controller : 'auxMovementOutFit2Ctrl'
            });
    })
    .controller('auxMovementOutFit2Ctrl', function($scope, $compile, $state, $log, util, petition, toastr){

        util.liPage('movimientos_outfit2');

        var s1 = {
            1 : ['OutFit','bgm-teal',false],
            2 : ['Pack','bgm-green',false]
        };

        var s2 = {
            'salida' : ['Salida','bgm-teal',false],
            'retornado' : ['Retornado','bgm-green',false],
            'vendido' : ['Vendido','bgm-green',false]
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha de salida", "bSortable" : true, "sWidth": "90px"},
                {"sTitle": "Codigo", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Out Fit", "bSortable" : true},
                {"sTitle": "Precio S/.", "bSortable" : true},
                {"sTitle": "Tipo", "bSortable" : true},
                {"sTitle": "Estado", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Acción" , "bSearchable": false , "sWidth": "270px"}
            ],

            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'outfit.type', column: 'outfit.type', render : s1},
                            { name: 'status', column: 'status', render : s2}
                        ]
                    },
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render:
                                [
                                    ['Detalle','detail','bgm-teal'],
                                    ['Retornado','outfitReturn','btn-info'],
                                    ['Vendido', 'outfitSale' ,'bgm-green']
                                ]
                            }
                        ]
                    }
                ],

            data  	: 	['date_shipment','outfit.cod','outfit.name','outfit.price','outfit.type','status','actions'],
            configStatus : 'status'
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
            html:true
        };

        $scope.movementClear = {
            id: null,
            situation: null,
            case: null
        };

        $scope.situations = [
            {id: 1, name:'No le gusto' },
            {id: 2, name:'La foto no es igual al producto' },
            {id: 3, name:'Producto dañado' },
            {id: 4, name:'No se encontro cliente' },
            {id: 5, name:'No es la talla' }
        ];

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxmovements-outfit')
                .then(function(data){
                    $scope.tableData = data.outfits;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.detail = function( ind ){
            $scope.outfitDetail = {};
            var id = $scope.tableData[ind].id;
            petition.get('api/auxmovements-outfit/' + id )
                .then(function(data){
                    $scope.outfitDetail = data.movement;
                    util.modal('Detail');
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.outfitSale = function(i){
            var id = $scope.tableData[i].id;
            swal(alertConfig,
                function () {
                    petition.put('api/auxmovements-outfit/' + id, {case: 1})
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

        $scope.outfitReturn = function(i){
            $scope.movement.id = $scope.tableData[i].id;
            $scope.movement.cod = $scope.tableData[i].outfit.cod;
            $scope.movement.name = $scope.tableData[i].outfit.name;
            $scope.movement.price = $scope.tableData[i].outfit.price;
            $scope.movement.situation = null;
            $scope.movement.case = 2;
            util.modal();
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            alertConfig.text=`<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>                                            
                                            <th>Cod</th>                                            
                                            <th>Outfit</th>
                                            <th>Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>${$scope.movement.cod}</td>                                                                          
                                            <td>${$scope.movement.name}</td>
                                            <td>${$scope.movement.price}</td>
                                        </tr>
                                        </tbody>
                                    </table>`;
            var id = $scope.movement.id;
            swal(alertConfig,
                function () {
                    petition.put('api/auxmovements-outfit/'+ id, $scope.movement)
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

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.movement = angular.copy($scope.movementClear);
            $scope.list();
        });
    });