angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Direcciones', {
                url: '/Adminitracion-de-direcciones/:id',
                templateUrl: 'app/partials/addresses.html',
                controller : 'addressesCtrl'
            });
            //.state('Telefonos', {
            //    url: '/Adminitracion-de-telfonos',
            //    templateUrl: 'app/partials/phones.html',
            //    controller : 'phonesCtrl'
            //})
            //.state('Socials', {
            //    url: '/Adminitracion-de-redes-social',
            //    templateUrl: 'app/partials/socials.html',
            //    controller : 'socialsCtrl'
            //});
    })
    .controller('addressesCtrl', function($scope, $compile,$routeParams, $log, util, petition, toastr){

        util.liPage('direcciones');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Edad", "bSortable" : true},
                {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false , "sWidth": "80px"},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['status',   {
                    0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                    1 : { txt : 'Activo' ,  cls : 'btn-success' } ,
                }
                ],
                ['actions', [
                    ['ver', 'view' ,'btn-info'],
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['name','age','status','actions'],
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

        $scope.addressClear = {
            name: '',
            age: '',
            status: 0
        };

        $scope.list = function() {
            petition.get('api/address/' + $scope.id)
                .then(function(data){
                    $scope.tableData = data.addresses;
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

        $scope.edit = function( ind ){
            $scope.address = angular.copy($scope.tableData[ind]);
            util.muestraformulario();
        };

        $scope.submit = function () {
            var method = ( $scope.address.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/customer/' + $scope.address.id): util.baseUrl('api/customer');
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
            $scope.address = angular.copy($scope.addressClear);
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

        angular.element(document).ready(function(){
            $scope.id = $stateParams.id;
            $scope.address = angular.copy($scope.addressClear);
            $scope.list();
        });
    });