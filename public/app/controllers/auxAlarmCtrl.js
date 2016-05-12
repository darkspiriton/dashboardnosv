angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Alarma', {
                url: '/stock-general-de-productos',
                template: '<div class="card" >'+
                '    <div class="card-header bgm-blue">'+
                '        <h2>Stock general de productos</h2>'+
                '        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"'+
                '                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"'+
                '                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>'+
                '    </div>'+
                '    <div class="card-body card-padding table-responsive">'+
                '        <div class="col-sm-12">'+
                '            <table id="table" class="table table-bordered table-striped w-100" style="text-align:center;"></table>'+
                '        </div><br>'+
                '    </div><br>'+
                '</div>',
                controller : 'auxAlarmCtrl'
            });
    })
    .controller('auxAlarmCtrl', function($scope, $compile, $log, util, petition, toastr, chart){

        util.liPage('alarma');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre de prenda", "bSortable" : true}
            ],
            data  	: 	['name']
        };


        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxproduct/get/alarm')
                .then(function(data){
                    $scope.tableData = data.alarms;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.success(error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            $scope.list();
        });
    });