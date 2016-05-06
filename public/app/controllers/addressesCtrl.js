angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Direcciones', {
                url: '/Adminitracion-de-direcciones/:id',
                templateUrl: 'app/partials/addresses.html',
                controller : 'addressesCtrl'
            });
    })
    .controller('addressesCtrl', function($scope, $compile, $state,$stateParams, $log, util, petition, toastr){

        util.liPage('direcciones');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Ubicacion", "bSortable" : true},
                {"sTitle": "Direccion", "bSortable" : true},
                {"sTitle": "Referencia", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "100px"}
            ],
            actions	:  	[
                ['actions', [
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['ubi','description','reference','actions'],
            configStatus : 'status'
        };

        $scope.addressClear = {
            id: null,
            description : null,
            reference: null,
            ubigeo_id: null
        };

        $scope.list = function() {
            petition.get('api/customer/' + $scope.id)
                .then(function(data){
                    $scope.customerName = angular.copy(data.customer.name);
                    $scope.ubgeoNames(data.customer.addresses , function( addresses ){
                        $scope.tableData = addresses;
                        $('#table').AJQtable('view', $scope, $compile);
                        $scope.updateList = false;
                    });
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.ubgeoNames = function( addresses, callback ){
            for(var  i in addresses){
                addresses[i].ubi = addresses[i].ubigeo.UBIDEN + ' - ' +
                                    addresses[i].ubigeo.UBIPRN + ' - ' +
                                    addresses[i].ubigeo.UBIDSN;
            }
            callback(addresses);
        };

        $scope.listDepa = function() {
            console.log('1');
            petition.get('api/ubigeo/departamento')
                .then(function(data){
                    $scope.ubigeo.dep = data.departamentos;
                    $scope.ubigeo.pro = [];
                    $scope.ubigeo.dis = [];
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listProv = function() {
            console.log('2');
            petition.get('api/ubigeo/provincia/' + $scope.ubigeo.dep_id)
                .then(function(data){
                    $scope.ubigeo.pro = data.provincias;
                    $scope.ubigeo.dis = [];
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listDist = function() {
            console.log('3');
            petition.get('api/ubigeo/distrito/' + $scope.ubigeo.pro_id)
                .then(function(data){
                    $scope.ubigeo.dis = data.distritos;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.$watch('ubigeo.dep_id', function() {
            $scope.listProv();
        });

        $scope.$watch('ubigeo.pro_id', function() {
            $scope.listDist();
        });


        $scope.edit = function( ind ){
            $scope.address = angular.copy($scope.tableData[ind]);
            $scope.ubigeo.dep_id = $scope.tableData[ind].ubigeo.UBIDEP;
            $scope.ubigeo.pro_id = $scope.tableData[ind].ubigeo.UBIPRV;
            util.muestraformulario();
        };

        $scope.submit = function () {
            var method = ( $scope.address.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/customer/upd/address'): util.baseUrl('api/customer/add/address/' + $scope.id);
            var config = {
                method: method,
                url: url,
                params: $scope.address
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
            $scope.address = angular.copy($scope.addressClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.ubigeo.dep_id = null;
            $scope.ubigeo.pro_id = null;
            $scope.address = angular.copy($scope.addressClear);
            util.muestraformulario();
        };

        $scope.phone = function(){
            $state.go("Telefonos", { id: $scope.id });
        };

        $scope.social = function(){
            $state.go("Socials", { id: $scope.id });
        };

        angular.element(document).ready(function(){
            $scope.id = $stateParams.id;
            $scope.address = angular.copy($scope.addressClear);
            $scope.ubigeo = {};
            $scope.list();
            $scope.listDepa();
        });
    });