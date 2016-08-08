angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('RequestProduct', {
                url: '/Pedidos-venta-productos',
                templateUrl: 'http://' + location.hostname + '/app/partials/requestProductAssociated.html',
                controller : 'RequestProductCtrl'
            });
    }])
    .controller('RequestProductCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr", "$auth",
        function($scope, $compile, $state, $log, util, petition, toformData, toastr, $auth){

        /**
            Declaracion de variables
        */

        $scope.product = {};

        /**
            END
        */

        util.liPage('RequestProduct');

        $scope.submit = function(product){
            isAuthenticated();
            petition.post("api/requestproduct", toformData.dataFile(product), {headers: {'Content-Type': undefined}})
                .then(function(data){
                    toastr.success(data.message || "No se pudo comprobar estado de publicación");
                    $scope.new();
                    util.ocultaformulario();
                }, function(error){
                    toastr.error(error.data.message || "Ocurrio un problema al intentar guardar su publicación");
                });
        };

        $scope.cancel = function(){
            $scope.product = {};
            $("#formproduct")[0].reset();
        };

        $scope.new = function(){
            $scope.product = {};
            $("#formproduct")[0].reset();
        };

        function isAuthenticated(){
            if (!$auth.isAuthenticated()) {
                $scope.GuestUser = true;
            } else {
                $scope.product.token = $auth.getToken();
            }
        }

        angular.element(document).ready(function(){
            $scope.list();
            $scope.estados();
            isAuthenticated();
        });
    }]);
