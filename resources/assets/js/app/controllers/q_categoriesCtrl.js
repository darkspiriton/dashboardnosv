angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('categories', {
                url: '/Gestion-de-categorias',
                templateUrl: 'app/partials/q_categories.html',
                controller : 'categoriesCtrl'
            });
    }])
    .controller('categoriesCtrl', ["$scope", "$compile", "$state", "util", "petition", "toastr",
        function($scope, $compile, $state, util, petition, toastr){

        util.liPage('categories');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Categoría", "bSortable" : true},
                {"sTitle": "Acción" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:   	[
                ['actions', [
                    ['editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['name','actions'],
            configStatus : 'status'
        };

        var alertConfig = {
            title: "¿Esta seguro?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonColor: "#212121",
            cancelButtonText: "CANCELAR",
            closeOnConfirm: true
        };

        $scope.categoryClear = {
            name: null
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/q_category')
                .then(function(data){
                    $scope.tableData = data.categories;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.edit = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/q_category/' + id )
                .then(function(data){
                    $scope.category = data.category;
                    util.muestraformulario();
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };


        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    var method = ( $scope.category.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/q_category/' + $scope.category.id) : util.baseUrl('api/q_category');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.category
                    };
                    $scope.formSubmit=true;
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        util.ocultaformulario();
                    }, function(error){
                        toastr.error('Huy Huy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });
        };

        $scope.cancel = function () {
            $scope.category = angular.copy($scope.categoryClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.category = angular.copy($scope.categoryClear);
            util.muestraformulario();
        };


        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.category = angular.copy($scope.categoryClear);
            $scope.list();
        });
    }]);
