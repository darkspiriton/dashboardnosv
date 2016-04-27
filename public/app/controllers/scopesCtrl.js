angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Alcanses', {
                url: '/Adminitracion-de-registro-de-alcanse',
                templateUrl: 'app/partials/scopes.html',
                controller : 'scopesCtrl'
            });
    })
    .controller('scopesCtrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('alcanses');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "sWidth": "160px", "aaSorting": 'desc'},
                {"sTitle": "Canal", "bSortable" : true, "sWidth": "160px"},
                {"sTitle": "Tipo", "bSortable" : true, "sWidth": "160px"},
                {"sTitle": "Obervacion", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
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

        $scope.scopeClear = {
            channel_id: null,
            type_id: null,
            observation : null,
            products: []
        };

        $scope.list = function() {
            petition.get('api/scope')
                .then(function(data){
                    $scope.tableData = data.scopes;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listChannels = function() {
            petition.get('api/channel')
                .then(function(data){
                    $scope.socials = data.socials;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listTypes = function() {
            petition.get('api/scope/types')
                .then(function(data){
                    $scope.types = data.types;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listProductTypes = function() {
            petition.get('api/product/types')
                .then(function(data){
                    $scope.productTypes = data.types;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listProduct = function() {
            petition.get('api/product/type/' + $scope.type_product)
                .then(function(data){
                    $scope.products = data.type.products;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        //$scope.view = function( ind ){
        //    var id = $scope.tableData[ind].id;
        //    petition.get('api/product/group_attributes/' + id )
        //        .then(function(data){
        //            $scope.productGroupAttributes = data.grp_attributes;
        //            $scope.productDetail = angular.copy($scope.tableData[ind]);
        //            util.modal();
        //        }, function(error){
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};

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

        $scope.submit = function () {
            var method = ( $scope.product.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/product/' + $scope.product.id): util.baseUrl('api/product');
            var config = {
                method: method,
                url: url,
                data: toformData.dataFile($scope.product),
                headers: {'Content-Type': undefined}
            };
            $scope.formSubmit=true;
            $log.log($scope.product);
            petition.custom(config).then(function(data){
                toastr.success(data.message);
                $scope.formSubmit=false;
                $scope.list();
                util.ocultaformulario();
            }, function(error){
                toastr.error('Ups ocurrio un problema: ' + error.data.message);
                $scope.formSubmit=false;
            });
        };

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            util.ocultaformulario();
        };

        $scope.cancel2 = function () {
            $log.log($scope.scope);
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            util.muestraformulario();
        };

        // Adds Attributes

        // end Attributes

        changeButton = function (ind, dom){
            $scope.tableData[ind].status = ($scope.tableData[ind].status == 0)? 1 : 0;
            if ( $scope.tableData[ind].status == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
            } else {
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        };

        angular.element(document).ready(function(){
            $scope.scope = angular.copy($scope.scopeClear);
            $scope.list();
            $scope.listChannels();
            $scope.listTypes();
            $scope.listProductTypes();
        });
    });