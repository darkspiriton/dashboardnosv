angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Clientes', {
                url: '/Adminitracion-de-clientes',
                templateUrl: 'app/partials/customers.html',
                controller : 'customersCtrl'
            });
    })
    .controller('customersCtrl', function($scope, $compile, $state, $log, util, petition, toastr){

        util.liPage('clientes');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Telefono", "bSortable" : true},
                {"sTitle": "Edad", "bSortable" : true},
                {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "470px"}
            ],
            actions	:  	[
                ['status',   {
                    0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                    1 : { txt : 'Activo' ,  cls : 'btn-success' } ,
                }
                ],
                ['actions', [
                    ['ver', 'view' ,'btn-info'],
                    ['Editar', 'edit' ,'bgm-teal'],
                    ['Dirreccion', 'address' ,'bgm-indigo'],
                    ['Telefono', 'phone' ,'bgm-blue'],
                    ['Redes', 'social' ,'bgm-lightblue']
                ]
                ]
            ],
            data  	: 	['name','phone','age','status','actions'],
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

        $scope.customerClear = {
            name: '',
            age: '',
            status: 0
        };

        $scope.list = function() {
            petition.get('api/customer')
                .then(function(data){
                    $scope.tableData = data.customers;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.status = function( ind, dom ) {
            alertConfig.title = "¿Desea cambiar el estado del cliente?";
            var id = $scope.tableData[ind].id;
            swal(alertConfig ,
                function() {
                    petition.delete('api/customer/' + id ).then(function(data){
                        toastr.success(data.message);
                        changeButton(ind , dom.target);
                    },function(error){
                        $log.log(error);
                        toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    });
                });
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/customer/' + id)
                .then(function(data){
                    $scope.customerDetail = data.customer;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.edit = function( ind ){
            $scope.customer = angular.copy($scope.tableData[ind]);
            util.muestraformulario();
        };

        $scope.submit = function () {
            var method = ( $scope.customer.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/customer/' + $scope.customer.id): util.baseUrl('api/customer');
            var config = {
                method: method,
                url: url,
                params: $scope.customer
            };
            $scope.formSubmit=true;
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
            $scope.customer = angular.copy($scope.customerClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.customer = angular.copy($scope.customerClear);
            util.muestraformulario();
        };

        $scope.getStatus = function( status ){
            if (status == 1)return 'Activo';
            else return 'Inactivo';
        };

        changeButton = function (ind, dom){
            $scope.tableData[ind].status = ($scope.tableData[ind].status == 0)? 1 : 0;
            if ( $scope.tableData[ind].status == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
            }else{
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        };

        // Addresses
        $scope.address = function( ind ){
            $state.go("Direcciones", { id: $scope.tableData[ind].id });
        };

        angular.element(document).ready(function(){
            $scope.customer = angular.copy($scope.customerClear);
            $scope.list();
        });
    });