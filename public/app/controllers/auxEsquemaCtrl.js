angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Esquemas', {
                url: '/Adminitracion-de-esquemas',
                templateUrl: 'app/partials/auxEsquema.html',
                controller : 'auxEsquemasCtrl'
            });
    })
    .controller('auxEsquemasCtrl', function($scope, $compile, $state, $log, util, petition, toastr,$filter){

        util.liPage('esquemas');

        var s1 = {
            1: ['Proceso','btn-danger',false],
            2: ['Retoque','bgm-teal',false],
            3: ['Enviado','bgm-indigo',false]
        };
        var s2 = {
            0: ['En Proceso','btn-danger',false],
            1: ['Completado','bgm-green',false]
        };


        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "Proceso", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Tipo", "bSortable" : true},
                {"sTitle": "Hora Inicio", "bSortable" : true},
                {"sTitle": "Hora Fin", "bSortable" : true},
                {"sTitle": "Estatus" , "bSearchable": true},
                {"sTitle": "Duración (min)" , "bSearchable": true},
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'type', column: 'type', render : s1},
                            { name: 'status', column: 'status', render : s2}
                        ]
                    }
                ],
            data  	: 	['name','pu','type','date','date_finish','status','diff'],
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/publicity/relation/esquema/get')
                .then(function(data){
                    $scope.tableData = data.esquemas;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.filter = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data);
            $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd')
            // $scope.dateSave.date2 = $filter('date')($scope.data.date2, 'yyyy-MM-dd')
            petition.get('api/publicity/relation/esquemadate/get', { params : $scope.dateSave })
                .then(function(data){
                    $scope.tableData = data.esquemas;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.list();
        });
    });