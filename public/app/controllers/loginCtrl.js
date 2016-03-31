angular.module('loginApp')
    .controller('LoginCtrl', function($scope, $location, $auth, toastr) {
        $scope.login = function() {
            $auth.login($scope.user)
                .then(function() {
                    toastr.success('Se a identificado correctamente!');
                    //$location.path('/dashboard');
                })
                .catch(function(error) {
                    toastr.error(error.data.message, error.status);
                });
        };
    });
