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
                {"sTitle": "Cliente", "bSortable" : true},
                {"sTitle": "Obervacion", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['actions',
                    [
                        ['ver', 'view' ,'btn-info']
                    ]
                ]
            ],
            data  	: 	['date','channel.name','type.name','name','observation','actions'],
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
            id : null,
            name: null,
            channel_id: null,
            type_id: null,
            observation : null,
            products: []
        };

        $scope.viewProducts = [];

        $scope.productClear = {
            index : null,
            type : null
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

        $scope.listProduct = function( i ) {
            petition.get('api/product/type/' + $scope.productTypes[i].id)
                .then(function(data){
                    $scope.products = data.type.products;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/scope/' + id )
                .then(function(data){
                    $scope.scopeDetail = data.scope;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            alertConfig.title = "¿Todo es correcto? Una ves registrado no podra ser editado y/o eliminado";
            swal(alertConfig ,
                function() {
                    var method = ( $scope.scope.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/scope/' + $scope.product.id) : util.baseUrl('api/scope');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.scope
                    };
                    $scope.formSubmit = true;
                    $log.log($scope.scope);
                    petition.custom(config).then(function (data) {
                        toastr.success(data.message);
                        $scope.formSubmit = false;
                        $scope.list();
                        util.ocultaformulario();
                    }, function (error) {
                        toastr.error('Ups ocurrio un problema: ' + error.data.message);
                        $scope.formSubmit = false;
                    });
                });
        };

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            util.muestraformulario();
        };

        // Adds Products

        $scope.addPrd = function( prd ){
            if(!$scope.product.index)return false;
            var product = {id : $scope.products[prd.index].id};
            var productView = {
                    name : $scope.products[prd.index].name,
                    type_name : $scope.productTypes[prd.type].name
                };

            for(var i in $scope.scope.products){
                if ( product.id == $scope.scope.products[i].id)return false;
            }

            $scope.viewProducts.push(productView);
            $scope.scope.products.push(product);

            $scope.product = angular.copy($scope.productClear);
            $scope.products = [];
        };

        $scope.removePrd = function( i ){
            $scope.scope.products.splice(i, 1);
            $scope.viewProducts.splice(i, 1);
        };

        // end Attributes

        angular.element(document).ready(function(){
            $scope.scope = angular.copy($scope.scopeClear);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
            $scope.listChannels();
            $scope.listTypes();
            $scope.listProductTypes();
        });
    });