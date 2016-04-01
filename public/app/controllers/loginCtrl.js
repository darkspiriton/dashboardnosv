angular.module('loginApp')
    .controller('LoginCtrl', function($scope, $location, $auth, $window, toastr) {
        if ($auth.isAuthenticated()) {
            $window.location.href = '/dashboard';
        }
        $scope.login = function() {
            $auth.login($scope.user)
                .then(function() {
                    toastr.success('Se a identificado correctamente!');
                    $window.location.href = '/dashboard';
                })
                .catch(function(error) {
                    toastr.error(error.data.message, error.status);
                });
        };
    });
