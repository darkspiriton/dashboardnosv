angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('questions', {
                url: '/Gestion-de-preguntas',
                templateUrl: 'app/partials/q_questions.html',
                controller : 'questionsCtrl'
            });
    })
    .controller('questionsCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('questions');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Pregunta", "bSortable" : true},
                {"sTitle": "Accion" , "bSearchable": true, "sWidth": '80px'}
            ],
            actions	:   	[
                ['actions', [
                    ['detalle', 'view' ,'btn-info']
                ]
                ]
            ],
            data  	: 	['question','actions'],
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

        $scope.questionClear = {
            question: null,
            options: []
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/question')
                .then(function(data){
                    $scope.tableData = data.questions;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.addOption = function(option){
            var count = 0;
            for(i in  $scope.question.options){
                if(angular.equals(option,$scope.question.options[i])){
                    count++;
                    break;
                }
            }

            if (count == 0){
                toastr.success('se añadio');
                $scope.question.options.push(option);
                $scope.option = null;
            }

        };
        
        $scope.removeOption = function (i) {
            $scope.question.options.splice(i,1);
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/question/' + id )
                .then(function(data){
                    $scope.questionDetail = data.question;
                    util.modal();
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            if($scope.question.options.length == 0)return toastr.error('La pregunta debe tener al menos una opción');
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    var method = ( $scope.question.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/question/' + $scope.question.id) : util.baseUrl('api/question');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.question
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
            $scope.question = angular.copy($scope.questionClear);
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.question = angular.copy($scope.questionClear);
            util.muestraformulario();
        };


        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.question = angular.copy($scope.questionClear);
            $scope.list();
        });
    });