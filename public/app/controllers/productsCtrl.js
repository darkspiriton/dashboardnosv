angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Productos', {
                url: '/Adminitracion-de-productos',
                templateUrl: 'app/partials/products.html',
                controller : 'productsCtrl'
            });
    })
    .controller('productsCtrl', function($scope, $compile, $log, util, petition, toastr){

        util.liPage('products');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "codigo", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Precio", "bSortable" : true},
                {"sTitle": "Cant", "bSortable" : true, "sWidth": "1px"},
                {"sTitle": "Estado", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                            ['status',   {
                                            0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                                            1 : { txt : 'Activo' ,  cls : 'btn-success' } ,
                                        }
                            ],
                            ['actions',
                                [
                                    ['ver', 'view' ,'btn-info'],
                                    ['Editar', 'edit' ,'btn-primary']
                                ]
                            ]
                        ],
            data  	: 	['product_code','name','price','cant','status','actions'],
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
            closeOnConfirm: true
        };

        $scope.productClear = {
            name: '',
            price: '',
            attributes: {}
        };

        $scope.list = function() {
            petition.get('api/product')
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

        $scope.listAttr = function() {
            petition.get('api/attribute')
                .then(function(data){
                    $scope.attributos = data.types;
                    console.log($scope.attrs);
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.attr_select = function(){
            $scope.attr_values = $scope.attributos[$scope.attributo.id - 1].att;
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/product/' + id)
                .then(function(data){
                    $log.log(data);
                    $scope.productDetail = data.product;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        //$scope.edit = function( ind ){
        //    var id = $scope.tableData[ind].id;
        //    petition.get('api/user/' + id)
        //        .then(function(data){
        //            $log.log(data.user);
        //            $scope.empleado = data.user;
        //            var role_id = $scope.empleado.role_id;
        //            $scope.empleado.role_id = role_id.toString();
        //            $scope.empleado.password = '**********';
        //            util.muestraformulario();
        //        }, function(error){
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};
        //
        //$scope.submit = function () {
        //    var method = ( $scope.empleado.id )?'PUT':'POST';
        //    var url = ( method == 'PUT')? util.baseUrl('api/user/' + $scope.empleado.id): util.baseUrl('api/user');
        //    if ( $scope.empleado.password == '**********' )
        //        $scope.empleado.password = null;
        //    var config = {
        //        method: method,
        //        url: url,
        //        params: $scope.empleado
        //    };
        //    $scope.formSubmit=true;
        //    petition.custom(config).then(function(data){
        //        toastr.success(data.message);
        //        $scope.formSubmit=false;
        //        $scope.list();
        //        util.ocultaformulario();
        //    }, function(error){
        //        toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        $scope.formSubmit=false;
        //    });
        //};

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            util.muestraformulario();
        };

        $scope.showAttr = function (){
            util.modal('addAttr');
        }

        //$scope.getStatus = function( status ){
        //    if (status == 1)return 'Activo';
        //    else return 'Inactivo';
        //};

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
            $scope.listAttr();

            //Tabla de pruebas
            options = {
                aoColumns: [
                    {"sTitle": "Atributo", "bSortable" : true},
                    {"sTitle": "Valor", "bSortable" : true},
                    {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
                ]
            };
        });
    });