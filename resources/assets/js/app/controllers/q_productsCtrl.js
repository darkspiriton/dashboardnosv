angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('q_products', {
                url: '/Gestion-de-productos',
                templateUrl: 'app/partials/q_products.html',
                controller : 'q_productsCtrl'
            });
    }])
    .controller('q_productsCtrl', ["$scope", "$compile", "$state", "util", "petition", "toastr",
        function($scope, $compile, $state, util, petition, toastr){

        util.liPage('q_products');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Categoria", "bSortable" : true, "sWidth": '1px'},
                {"sTitle": "Accion" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:   	[
                ['actions', [
                    ['detalle', 'detail' ,'btn-info']
                ]
                ]
            ],
            data  	: 	['name','category.name','actions'],
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

        $scope.productClear = {
            name: null,
            category_id: null,
            questionnaire_id: null,
            responses: []
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/answer/product')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.AddOption = function (i) {
            var count = 0;
            for(i in  $scope.product.responses){
                if($scope.questions[$scope.question_i].id == $scope.responsesView[i].question_id){
                    count++;
                    break;
                }
            }

            if (count == 0){
                toastr.success('se añadio');
                $scope.product.responses.push({id: $scope.options[i].id});
                $scope.responsesView.push({
                                            question: $scope.questions[$scope.question_i].question,
                                            question_id: $scope.questions[$scope.question_i].id,
                                            option:$scope.options[i].option
                                        });
                $scope.question_i = null;
                $scope.option_i = null;
                $scope.options = [];
            }
        };

        $scope.removeQuestion = function (i) {
            $scope.product.responses.splice(i,1);
            $scope.responsesView.splice(i,1);
        };

        $scope.listOptions = function(i) {
            petition.get('api/question/'+$scope.questions[i].id+'/options')
                .then(function(data){
                    $scope.options = data.options;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listQuestions = function(id) {
            $scope.product.responses = [];
            $scope.responsesView = [];
            petition.get('api/questionnaire/category/'+id)
                .then(function(data){
                    $scope.product.questionnaire_id = data.questionnaire.id;
                    $scope.questions = data.questionnaire.questions;
                    $scope.options = [];
                }, function(error){
                    $scope.questions = [];
                    $scope.options = [];
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listCategories = function() {
            petition.get('api/q_category')
                .then(function(data){
                    $scope.categories = data.categories;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.detail = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/answer/product/' + id )
                .then(function(data){
                    $scope.productDetail = data.product;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            if(!validateResponses())return toastr.error('Debe completar todas las preguntas');
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    var method = ( $scope.product.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/answer/product/' + $scope.product.id) : util.baseUrl('api/answer/product');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.product
                    };
                    $scope.formSubmit=true;
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.list();
                        util.ocultaformulario();
                    }, function(error){
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    })
                });
        };

        function validateResponses(){
            if($scope.questions.length > $scope.product.responses.length)
                return false;
            else
                return true;
        }

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            $scope.responsesView = [];
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.responsesView = [];
            util.muestraformulario();
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.responsesView = [];
            $scope.list();
            $scope.listCategories();
        });
    }]);