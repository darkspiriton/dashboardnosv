angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('outs', {
                url: '/Productos-en-stock-salida-y-ventas-del-dia',
                templateUrl: 'app/partials/products_out.html',
                controller : 'outsCtrl'
            });
    })
    .controller('outsCtrl', function($scope, petition, util, toastr) {

        util.liPage('outs');

            $scope.update = function () {
                $scope.total=0;
                $scope.list();
                $scope.list3();
                $scope.list4();
                $scope.list5();
            }
            
        $scope.total=0;
        
            $scope.list = function() {
                $scope.updateList = true;
                petition.get('api/auxmovement/get/movement/day')
                    .then(function(data){
                        $scope.infoData = data.data;                        
                        $scope.updateList = false;
                    }, function(error){
                        console.log(error);
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.updateList = false;
                    });
            };

            $scope.list3 = function() {
                $scope.updateList = true;
                petition.get('api/auxmovement/get/movementDay/consolidado')
                    .then(function(data){
                        $scope.infoData3 = data.data;
                        $scope.total=$scope.total+$scope.infoData3.uti;
                        $scope.updateList = false;
                    }, function(error){
                        console.log(error);
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.updateList = false;
                    });
            };

            $scope.list4 = function() {
                $scope.updateList = true;
                petition.get('api/auxmovement/get/movementDay/consolidadoOut')
                    .then(function(data){
                        $scope.infoData4 = data.data;
                        $scope.total=$scope.total+$scope.infoData4.uti;
                        // console.log( $scope.infoData3);
                        $scope.updateList = false;
                    }, function(error){
                        console.log(error);
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.updateList = false;
                    });
            };

            $scope.list5 = function() {
                $scope.updateList = true;
                petition.get('api/publicity/relation/indicator/get')
                    .then(function(data){
                        $scope.infoData5 = data.procesos;
                        // console.log( $scope.infoData3);
                        $scope.updateList = false;
                    }, function(error){
                        console.log(error);
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.updateList = false;
                    });
            };

            angular.element(document).ready(function(){
                $scope.list();
                $scope.list3();
                $scope.list4();
                $scope.list5();
            });

    });
