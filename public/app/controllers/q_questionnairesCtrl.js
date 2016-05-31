angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('questionnaires', {
                url: '/Gestion-de-cuestionarios',
                templateUrl: 'app/partials/q_questionnaires.html',
                controller : 'questionnairesCtrl'
            });
    })
    .controller('questionnairesCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('questionnaires');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "description", "bSortable" : true},
                {"sTitle": "Categoria", "bSortable" : true, "sWidth": '1px'},
                {"sTitle": "Accion" , "bSearchable": true, "sWidth": '190px'}
            ],
            actions	:   	[
                ['actions', [
                    ['detalle', 'detail' ,'btn-info'],
                    ['editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['description','category.name','actions'],
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

        $scope.questionnaireClear = {
            description: null,
            category_id: null,
            questions: []
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/questionnaire')
                .then(function(data){
                    $scope.tableData = data.questionnaires;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.edit = function (i) {
            petition.get('api/questionnaire/' + $scope.tableData[i].id + '/edit')
                .then(function (data) {
                    productEdit(data.questionnaire, data.questions);
                    $scope.productState = false;
                    util.muestraformulario();
                }, function (error) {
                    console.log(error);
                    toastr.error('Uyuyuy dice: '+ error.data.message);
                });
        };
        
        function productEdit(questionnaire,questions) {
            $scope.questionnaire = questionnaire;
            $scope.questionnaire.questions = [];
            for(i in questions){
                $scope.questionnaire.questions.push({id: questions[i].id});
            }
            $scope.questionsView = questions;
        }
        
        $scope.addQuestion = function (ind) {
            var count = 0;
            for(i in  $scope.questionnaire.questions){
                if($scope.questions[ind].id == $scope.questionnaire.questions[i].id){
                    count++;
                    $scope.question = null;
                    break;
                }
            }

            if (count == 0){
                toastr.success('se añadio');
                $scope.questionnaire.questions.push({id: $scope.questions[ind].id});
                $scope.questionsView.push($scope.questions[ind]);
                $scope.question = null;
            }
        };

        $scope.removeQuestion = function (i) {
            $scope.questionnaire.questions.splice(i,1);
            $scope.questionsView.splice(i,1);
        };

        $scope.listQuestions = function() {
            petition.get('api/question')
                .then(function(data){
                    $scope.questions = data.questions;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listCategories = function() {
            petition.get('api/q_category')
                .then(function(data){
                    $scope.categories = data.categories;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.detail = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/questionnaire/' + id )
                .then(function(data){
                    $scope.questionnaireDetail = data.questionnaire;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            if($scope.questionnaire.questions.length == 0)return toastr.error('El cuestionario debe tener al menos una pregunta');
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    var method = ( $scope.questionnaire.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/questionnaire/' + $scope.questionnaire.id) : util.baseUrl('api/questionnaire');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.questionnaire
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

        $scope.cancel = function () {
            $scope.questionnaire = angular.copy($scope.questionnaireClear);
            $scope.questionsView = [];
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.questionnaire = angular.copy($scope.questionnaireClear);
            $scope.questionsView = [];
            util.muestraformulario();
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.questionnaire = angular.copy($scope.questionnaireClear);
            $scope.questionsView = [];
            $scope.list();
            $scope.listQuestions();
            $scope.listCategories();
        });
    });