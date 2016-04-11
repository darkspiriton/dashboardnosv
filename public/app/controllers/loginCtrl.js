angular.module('loginApp')
    .controller('LoginCtrl', function($scope, $location, $auth, $window, toastr){
        var redirect = function(){
            var token = $window['localStorage'].getItem('DB_NV_token');
            if (token)$('#token').val(token);
            $('#frm').submit();
        }

        if ($auth.isAuthenticated()) {
            redirect();
        }

        $scope.login = function() {
            $auth.login($scope.user)
                .then(function() {
                    toastr.success('Se a identificado correctamente!');
                    redirect();
                })
                .catch(function(error) {
                    toastr.error(error.data.message);
                });
        };
    });
