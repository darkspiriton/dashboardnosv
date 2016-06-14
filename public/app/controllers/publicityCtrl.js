angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Publicidad', {
                url: '/Gestion-de-publicidad',
                templateUrl: 'app/partials/publicity.html',
                controller : 'publicityCtrl'
            });
    })
    .controller('publicityCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('publicity');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '90px'},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color" , "bSearchable": true},
                {"sTitle": "proceso" , "bSearchable": true},
                {"sTitle": "foto" , "bSearchable": true},
                {"sTitle": "envio" , "bSearchable": true},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:   	[
                ['status',   {
                    0 : { txt : 'salida' , cls : 'btn-danger', dis : false},
                    1 : { txt : 'disponible' ,  cls : 'btn-success', dis : false},
                    2 : { txt : 'vendido' ,  cls : 'bgm-teal', dis : false}
                }
                ],
                ['actions', [
                    ['proceso >', 'nextProcess' ,'bgm-red']
                ]
                ]
            ],
            data  	: 	[['product.created_at',10],'product.cod','product.name','product.provider.name','product.size.name','product.color.name','price','status','actions'],
            configStatus : 'product.status'
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
            closeOnConfirm: true
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/publicity')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    var msg = error.data.message?error.data.message:'no te puedo atender';
                    toastr.error('Huy Huy dice: ' + msg);
                    $scope.updateList = false;
                });
        };

        //
        $scope.listProduct = function() {
            petition.get('api/auxproduct/get/uniques')
                .then(function(data){
                    $scope.products = data.products;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.ListCodes = function(name) {
            if(name == undefined)return;
            petition.get('api/auxproduct/get/uniques/'+ name +'/codes')
                .then(function(data){
                    $scope.codes = data.codes;
                }, function(error){
                    $scope.codes = [];
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };
        //

        $scope.nextProcess = function(i) {
            alertConfig.title = '¿Desea seguir al siguiente proceso?';
            swal(alertConfig ,
                function() {
                    var id = $scope.tableData[i].id;
                    petition.put('api/publicity/' + id)
                        .then(function(data){
                            $scope.list();
                            toastr.success(data.message);
                        }, function(error){
                            console.log(error);
                            toastr.error('Huy Huy dice: ' + error.data.message);
                        });
                });
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    $scope.formSubmit=true;
                    petition.post('api/liquidation', $scope.liquidation ).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        util.modalClose();
                    }, function(error){
                        toastr.error('Huy Huy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });
        };

        $scope.new = function(){
            util.modal();
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
            $scope.listProduct();
        });
    });