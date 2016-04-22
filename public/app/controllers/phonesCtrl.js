angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Telefonos', {
                url: '/Adminitracion-de-telefonos/:id',
                templateUrl: 'app/partials/phones.html',
                controller : 'phonesCtrl'
            });
    })
    .controller('phonesCtrl', function($scope, $compile, $state,$stateParams, $log, util, petition, toastr){

        util.liPage('telefonos');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Operador", "bSortable" : true},
                {"sTitle": "Numero", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "270px"}
            ],
            actions	:  	[
                ['actions', [
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['operator_name','number','actions'],
            configStatus : 'status'
        };

        $scope.phoneClear = {
            id: null,
            number : null,
            operator_id: null,
        };

        $scope.list = function() {
            petition.get('api/customer/' + $scope.id)
                .then(function(data){
                    $scope.customerName = angular.copy(data.customer.name);
                    $scope.operatorsName(data.customer.phones, function( phones ){
                        $scope.tableData = phones;
                        $('#table').AJQtable('view', $scope, $compile);
                        $scope.updateList = false;
                    });
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listOperator = function() {
            petition.get('api/operador')
                .then(function(data){
                    $scope.operators = data.operadores;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.operatorsName = function( phones, callback ){
            for(var  i in phones){
                phones[i].operator_name = phones[i].operator.name;
            }
            callback(phones);
        };

        $scope.edit = function( ind ){
            $scope.phone = angular.copy($scope.tableData[ind]);
            util.muestraformulario();
        };

        $scope.submit = function () {
            var method = ( $scope.phone.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/customer/upd/phone'): util.baseUrl('api/customer/add/phone/' + $scope.id);
            var config = {
                method: method,
                url: url,
                params: $scope.phone
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
            $scope.phone = angular.copy($scope.phoneClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.phone = angular.copy($scope.phoneClear);
            util.muestraformulario();
        };

        $scope.address = function(){
            $state.go("Direcciones", { id: $scope.id });
        };

        $scope.social = function(){
            $state.go("Socials", { id: $scope.id });
        };

        angular.element(document).ready(function(){
            $scope.id = $stateParams.id;
            $scope.phone = angular.copy($scope.phoneClear);
            $scope.list();
            $scope.listOperator();
        });
    });