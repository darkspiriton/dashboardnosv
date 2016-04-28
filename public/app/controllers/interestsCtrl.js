angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Intereses', {
                url: '/Adminitracion-de-registro-de-interes',
                templateUrl: 'app/partials/interests.html',
                controller : 'interestsCtrl'
            });
    })
    .controller('interestsCtrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('intereses');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "sWidth": "160px", "aaSorting": 'desc'},
                {"sTitle": "Canal", "bSortable" : true, "sWidth": "160px"},
                {"sTitle": "Cliente", "bSortable" : true},
                {"sTitle": "Obervacion", "bSortable" : true},
                {"sTitle": "Estado", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['status',   {
                    1 : { txt : 'Sin atender' , cls : 'btn-danger'},
                    2 : { txt : 'Atendido' ,  cls : 'btn-success', dis : false } ,
                }
                ],
                ['actions',
                    [
                        ['ver', 'view' ,'btn-info']
                    ]
                ]
            ],
            data  	: 	['created_at','channel.name','customer.name','observation','status','actions'],
            configStatus : 'status_id'
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

        $scope.interestClear = {
            id : null,
            customer_id: null,
            channel_id: null,
            observation : null,
            products: []
        };

        $scope.viewProducts = [];

        $scope.productClear = {
            index : null,
            type : null
        };

        $scope.list = function() {
            petition.get('api/interest')
                .then(function(data){
                    $scope.tableData = data.interests;
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

        $scope.listSearch = function() {
            $scope.listView = true;
            $scope.interest.customer_id = null;
            petition.get('api/customer/search/' + $scope.search)
                .then(function(data){
                    $scope.customers = data.customers;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.selectCustomer = function(i){
            $scope.interest.customer_id = $scope.customers[i].id;
            $scope.search = $scope.customers[i].name;
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
            petition.get('api/interest/' + id )
                .then(function(data){
                    $scope.interestDetail = data.interest;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            alertConfig.title = "¿Todo es correcto? Una ves registrado no podra ser editado y/o eliminado";
            swal(alertConfig ,
                function() {
                    var method = ( $scope.interest.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/interest/' + $scope.product.id) : util.baseUrl('api/interest');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.interest
                    };
                    $scope.formSubmit = true;
                    $log.log($scope.interest);
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
            $scope.listPositiontion();
        };

        // Adds Products

        $scope.addPrd = function( prd ){
            if(!$scope.product.index)return false;
            var product = {id : $scope.products[prd.index].id};
            var productView = {
                name : $scope.products[prd.index].name,
                type_name : $scope.productTypes[prd.type].name
            };

            for(var i in $scope.interest.products){
                if ( product.id == $scope.interest.products[i].id)return false;
            }

            $scope.viewProducts.push(productView);
            $scope.interest.products.push(product);

            $scope.product = angular.copy($scope.productClear);
            $scope.products = [];
        };

        $scope.removePrd = function( i ){
            $scope.interest.products.splice(i, 1);
            $scope.viewProducts.splice(i, 1);
        };

        // end Attributes

        $scope.getStatus = function( status ){
            if (status == 2)return 'Atendido';
            else return 'Sin atender';
        };

        $scope.listPositiontion = function(){
            var pos = $('#searchCustomer').offset();
            $("#list").css({top: pos.top - 35, left: pos.left});
        };

        angular.element(document).ready(function(){
            $scope.interest = angular.copy($scope.interestClear);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
            $scope.listChannels();
            $scope.listProductTypes();
        });
    });