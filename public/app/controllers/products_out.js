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

            $scope.list();
    });
