angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Clientes', {
                url: '/Adminitracion-de-clientes',
                templateUrl: 'app/partials/customers.html',
                controller : 'customersCtrl'
            });
    })
    .controller('customersCtrl', function($scope, $compile, $log, util, petition, toastr){

        util.liPage('customers');


    });