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
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "180px"}
            ],
            actions	:  	[
                            ['actions',
                                [
                                    ['ver', 'view' ,'btn-info'],
                                    ['Editar', 'edit' ,'btn-primary']
                                ]
                            ]
                        ],
            data  	: 	['product_code','name','price','actions'],
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

        $scope.empleadoClear = {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            address: '',
            birth_date: '',
            sex: '',
            photo:'',
            role_id: '',
            password: '',
            status: false
        };

        $scope.list = function() {
            petition.get('api/products')
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

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/products/' + id)
                .then(function(data){
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
            $scope.empleado = angular.copy($scope.empleadoClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.empleado = angular.copy($scope.empleadoClear);
            util.muestraformulario();
        };

        $scope.getStatus = function( status ){
            if (status == 1)return 'Activo';
            else return 'Inactivo';
        };

        angular.element(document).ready(function(){
            //$scope.empleado = angular.copy($scope.empleadoClear);
            $scope.list();
        });
    });