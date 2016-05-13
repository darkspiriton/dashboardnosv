angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('ReportePlanilla', {
                url: '/Reporte-de-planilla-entre-fechas',
                templateUrl: 'app/partials/indicatorPayRole.html',
                controller : 'reportPayRolCtrl'
            });
    })
    .controller('reportPayRolCtrl', function($scope, $compile, $log, util, petition, toastr, $filter, chart){

        util.liPage('reportePlanilla');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, "sWidth" : '80px'},
                {"sTitle": "H. Llegada", "bSortable" : false,"bSearchable": false},
                {"sTitle": "H. Inicio Break", "bSortable" : false,"bSearchable": false},
                {"sTitle": "H. Fin Break", "bSortable" : false,"bSearchable": false},
                {"sTitle": "H. Salida", "bSortable" : false,"bSearchable": false},
                {"sTitle": "Monto x día", "bSortable" : true,"bSearchable": false},
                {"sTitle": "Perdida x día", "bSortable" : true,"bSearchable": false}
            ],
            data  	: 	['fecha', 'codigo','product','color','talla','status']
        };

        $scope.data = {
            date1 : null,
            date2: null,
            employee: null,
            area: null
        };

        $scope.areas = [
            {id: 1, name: 'Administracion'},
            {id: 2, name: 'Sistemas'},
            {id: 3, name: 'Publicidad'},
            {id: 4, name: 'Ventas'},
        ];

        $scope.employees = [
            {id: 1, name: 'Juanito Alimaña con mucha maña'},
            {id: 2, name: 'Juan Pablo Neruda'},
            {id: 3, name: 'Andres iniesta barca'},
            {id: 4, name: 'Pedro Picapierda dino'},
            {id: 5, name: 'Dross Rotzank milibro'}
        ];

        $scope.list = function() {
            $scope.updateList = true;
            $scope.reportDownload = false;
            petition.get('api/auxmovement/get/movementDay')
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                    $scope.drawShow=false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        //$scope.listProviders = function() {
        //    petition.get('api/providers')
        //        .then(function(data){
        //            $scope.providers = data.providers;
        //        }, function(error){
        //            console.log(error);
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};

        //$scope.filter = function(){
        //    $scope.updateList = true;
        //    $scope.dateSave = angular.copy($scope.data);
        //    $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd')
        //    $scope.dateSave.date2 = $filter('date')($scope.data.date2, 'yyyy-MM-dd')
        //    petition.get('api/auxmovement/get/movementDays', { params : $scope.dateSave })
        //        .then(function(data){
        //            $scope.reportDownload = true;
        //            $scope.tableData = data.movements;
        //            $('#table').AJQtable('view', $scope, $compile);
        //            $scope.updateList = false;
        //            if ( data.movements.length > 0){
        //                chart.drawColummn(data.draw,data.days);
        //                $scope.drawShow=true;
        //            } else {
        //                $scope.drawShow=false;
        //            }
        //        }, function(error){
        //            console.log(error);
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //            $scope.updateList = false;
        //        });
        //};

        //$scope.download = function(){
        //    petition.post('api/auxmovement/get/movementDays/download', $scope.dateSave, {responseType:'arraybuffer'})
        //        .then(function(data){
        //            var date = new Date().getTime();
        //            var name = date + '-reporte-de-movimiento-'+ $scope.dateSave.date1+'-al-'+$scope.dateSave.date2+'.pdf';
        //            var file = new Blob([data],{ type : 'application/pdf'});
        //            saveAs(file, name);
        //        }, function(error){
        //            console.info(error);
        //        });
        //};

        angular.element(document).ready(function(){
            $scope.list();
        });
    });