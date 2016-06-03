angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('q_customers', {
                url: '/Gestion-de-clientes-de-cuestionarios',
                templateUrl: 'app/partials/q_customers.html',
                controller : 'q_customersCtrl'
            });
    })
    .controller('q_customersCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('q_customers');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Cuestionario", "bSortable" : true},
                {"sTitle": "Cliente", "bSortable" : true},
                {"sTitle": "Telefono", "bSortable" : true, "sWidth": '1px'},
                {"sTitle": "Accion" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:   	[
                ['actions', [
                    ['detalle', 'detail' ,'btn-info']
                ]
                ]
            ],
            data  	: 	['questionnaire.description', 'customer.name', 'customer.phone','actions']
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

        $scope.responseClear = {
            customer_id:null,
            url: null,
            name: null,
            phone: null,
            category_id: null,
            questionnaire_id: null,
            responses: []
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/answer/customer')
                .then(function(data){
                    $scope.tableData = data.responses;
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
            for(i in  $scope.response.responses){
                if($scope.questions[$scope.question_i].id == $scope.responsesView[i].question_id){
                    count++;
                    break;
                }
            }

            if (count == 0){
                toastr.success('se añadio');
                $scope.response.responses.push({id: $scope.options[i].id});
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
            $scope.response.responses.splice(i,1);
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
            $scope.response.responses = [];
            $scope.responsesView = [];
            petition.get('api/questionnaire/category/'+id)
                .then(function(data){
                    $scope.response.questionnaire_id = data.questionnaire.id;
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
            var id = $scope.tableData[ind].customer.id;
            var qq = $scope.tableData[ind].questionnaire.id;
            petition.get('api/answer/customer/' + id + '/' + qq)
                .then(function(data){
                    $scope.customerDetail = data.customer;
                    console.log( $scope.customerDetail);
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
                    var method = ( $scope.response.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/answer/customer/' + $scope.response.id) : util.baseUrl('api/answer/customer');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.response
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
            if($scope.questions.length > $scope.response.responses.length)
                return false;
            else
                return true;
        }

        $scope.cancel = function () {
            $scope.response = angular.copy($scope.responseClear);
            $scope.responsesView = [];
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.response = angular.copy($scope.responseClear);
            $scope.responsesView = [];
            util.muestraformulario();
        };

        //  Event

        $scope.eventCustomer = function (caseCustomer) {
            if (caseCustomer == 'new') {
                $scope.response.customer_id = null;
                $scope.search = null;
                $scope.newCustomer = true;
                $scope.existCustomer = false;
                clearCustomer();
            }else if (caseCustomer == 'exist'){
                $scope.newCustomer = false;
                $scope.existCustomer = true;
                clearCustomer();
            }
        };
        
        function clearCustomer() {
            $scope.response.user_id = null;
            $scope.response.user = null;
            $scope.response.name = null;
            $scope.response.sex = null;
            $scope.response.age = null;
        }

        //

        $scope.listSearch = function() {
            $scope.listPositiontion();
            $scope.listView = true;
            $scope.response.customer_id = null;
            petition.get('api/answer/customer/search/tag/' + $scope.search)
                .then(function(data){
                    $scope.customers = data.customers;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.selectCustomer = function(i){
            $scope.response.customer_id = $scope.customers[i].id;
            $scope.search = $scope.customers[i].name;
        };

        $scope.listPositiontion = function(){
            var pos = $('#searchCustomer').offset();
            $("#list").css({top: pos.top - 35, left: pos.left});
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.response = angular.copy($scope.responseClear);
            $scope.responsesView = [];
            $scope.newCustomer = false;
            $scope.existCustomer = false;
            $scope.list();
            $scope.listCategories();
        });
    });