angular.module('App')
    .controller('usersCtrl', function($scope, $compile, util, petition, toastr){

        util.liPage('usersa');

        $scope.empleadoClear = {
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            address: '',
            birth_date: '',
            sex: '',
            photo:'',
            role_id: '',
            password: '',
            status: true,
        };
        
        $scope.submit = function () {
            $scope.createUser=true;
            petition.post('api/user', $scope.empleado).then(function(data){
                console.log(data);
                toastr.success(data.message);
                $scope.createUser=false;
            }, function(error){
                console.log(error);
                toastr.error(error.data.message);
                $scope.createUser=false;
            });
        }

        $scope.cancelar = function () {
            $scope.empleado = angular.copy($scope.empleadoClear);
        }
        

    });