angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Productos Eliminados', {
                url: '/listado-de-productos-eliminado',
                template: 
                `<div class="card" >
                    <div class="card-header bgm-blue">
                        <h2>Productos Eliminados <small>Registro de los ultimos 7 días</small></h2>
                    </div>
                    <div class="card-body card-padding table-responsive">
                        <div class="col-sm-12">
                            <table id="table" class="table table-bordered table-striped w-100" style="text-align:center;"></table>
                        </div><br>
                    </div>
                </div>`,
                controller : 'productsHelpCtrl'
            });
    }])
    .controller('productsHelpCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toastr",
        function($scope, $compile, $state, $log, util, petition, toastr){

        /*
         |  END
         */
        util.liPage('productsHELP');

        var actions = [                            
                        ['restaurar','restore','bgm-green'],
                    ];

        var status = {
                    0 : ['salida','btn-danger', false],
                    1 : ['disponible', 'btn-success', false],
                    2 : ['vendido', 'bgm-teal', false],
                    3 : ['reservado', 'bgm-black', false],                    
                    4 : ['observado', 'bgm-black']
                };

        var statusForSale = {
                    0 : ['normal', 'btn-success', false],
                    1 : ['liquidacion', 'btn-info', false]
                };


        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '160px'},
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Nombre", "bSortable" : true, 'sWidth': '250px'},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Tipos", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Estado V." , "bSearchable": false},
                {"sTitle": "P. Real" , "bSearchable": false},
                {"sTitle": "Precio" , "bSearchable": false},
                {"sTitle": "Status", "bSortable" : false, "bSearchable": true},
                {"sTitle": "Accion", "bSortable" : false, "bSearchable": false, "sWidth" : "230px"}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'obseveDetail', column: 'status', render: status},
                            { name: 'statusForSale', column: 'liquidation', render: statusForSale},
                        ]
                    },
                    {
                        type: 'actions',
                        list: [
                            { name: 'actions', render: actions}
                        ]
                    }
                ],
            data  	: 	['deleted_at','cod','name','provider','size','color','types','statusForSale','price_real','precio','obseveDetail','actions'],
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

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/filter/get/delete')
                .then(function(data){
                    $scope.tableData = data;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        // End events

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params Int
         *  @return Object:String   Confirmation
         */

        $scope.restore = function(i){
            alertConfig.title = '¿Esta seguro?';
            swal(alertConfig ,
                function() {
                    var id = $scope.tableData[i].id;
                    petition.post('api/auxproduct/delete/restore/' + id)
                        .then(function(data){
                            $scope.list();
                            toastr.success(data.message);
                        }, function(error){
                            $scope.list();
                            toastr.error('Huy Huy dice: ' + error.data.message);
                        });
            });
        };

        angular.element(document).ready(function(){
            $scope.list();
        });
    }]);
