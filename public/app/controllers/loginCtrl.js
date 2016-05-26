angular.module('loginApp')
    .controller('LoginCtrl', function($scope, $location, $auth, $window, toastr, $http){

        var redirect = function(){
            var token = $auth.getToken();
            if (token){
                $('#token').val(token);
                $('#frm').submit();
            }
        };

        var baseUrl =  function ( URL ) {
            var prot = $location.protocol();
            var host = $location.host();
            return prot + '://' + host + '/' + URL;
        };

        if ($auth.isAuthenticated()) {
            // authToken = 'Bearer' y authHeader = 'Authorization' son de SatellizerConfig
            data = {};
            var token  = 'Bearer' + ' ' + $auth.getToken();
            $http.get( baseUrl('api/validate-key'), { headers : {'Authorization' : token }})
                .then(function(response){
                    console.log(response.data.message);
                    redirect();
                }, function(error){
                    console.log(error.data.message);
                });
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

        $scope.Myonkeyup = function (e) {
            if(e.keyCode == 13 || e.charCode == 13)
                $scope.login();
        };
    });
