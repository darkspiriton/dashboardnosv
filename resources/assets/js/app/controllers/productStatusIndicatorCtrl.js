angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('IndicadorProductosEstados', {
                url: '/Indicador-de-historial-de-stados-de-productos',
                templateUrl: 'app/partials/productSatatusIndicator.html',
                controller : 'productStatusIndicatorCtrl'
            });
    }])
    .controller('productStatusIndicatorCtrl', ["$scope", "$compile", "util", "petition", "toastr",
        function($scope, $compile, util, petition, toastr) {

        util.liPage("ProductStatusIndicator");

        /**
         * Data
         */
        

        
        /**
         * Methods
         */
       


        /**
         * Ready
         */
        angular.element(document).ready(function(){

        });
    }]);
