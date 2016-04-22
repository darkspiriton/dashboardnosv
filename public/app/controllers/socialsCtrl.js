angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Socials', {
                url: '/Adminitracion-de-redes-social/:id',
                templateUrl: 'app/partials/socials.html',
                controller : 'socialsCtrl'
            });
    })
    .controller('socialsCtrl', function($scope, $compile, $state,$stateParams, $log, util, petition, toastr){

        util.liPage('telefonos');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Red Social", "bSortable" : true},
                {"sTitle": "URL", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "270px"}
            ],
            actions	:  	[
                ['actions', [
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['channel_name','channel_url','actions'],
            configStatus : 'status'
        };

        $scope.socialClear = {
            id: null,
            number : null,
            operator_id: null,
        };

        $scope.list = function() {
            petition.get('api/customer/' + $scope.id)
                .then(function(data){
                    $scope.customerName = angular.copy(data.customer.name);
                    $scope.socialsName(data.customer.socials, function( socials ){
                        $scope.tableData = socials;
                        $('#table').AJQtable('view', $scope, $compile);
                        $scope.updateList = false;
                    });
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listSocials = function() {
            petition.get('api/social')
                .then(function(data){
                    $scope.socials = data.socials;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.socialsName = function( socials, callback ){
            for(var i in socials){
                socials[i].channel_name = socials[i].channel.name;
            }
            callback(socials);
        };

        $scope.edit = function( ind ){
            $scope.social = angular.copy($scope.tableData[ind]);
            util.muestraformulario();
        };

        $scope.submit = function () {
            var method = ( $scope.social.id )?'PUT':'POST';
            var url = ( method == 'PUT')? util.baseUrl('api/customer/upd/social'): util.baseUrl('api/customer/add/social/' + $scope.id);
            var config = {
                method: method,
                url: url,
                params: $scope.social
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
            $scope.social = angular.copy($scope.socialClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.social = angular.copy($scope.socialClear);
            util.muestraformulario();
        };

        $scope.address = function(){
            $state.go("Direcciones", { id: $scope.id });
        };

        $scope.phone = function(){
            $state.go("Telefonos", { id: $scope.id });
        };

        angular.element(document).ready(function(){
            $scope.id = $stateParams.id;
            $scope.social = angular.copy($scope.socialClear);
            $scope.list();
            $scope.listSocials();
        });
    });