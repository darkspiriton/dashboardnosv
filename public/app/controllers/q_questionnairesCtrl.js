angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('questionnaires', {
                url: '/Adminitracion-de-cuestionarios',
                templateUrl: 'app/partials/q_questionnaires.html',
                controller : 'questionnairesCtrl'
            });
    })
    .controller('questionnairesCtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('questionnaires');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "descripciont", "bSortable" : true},
                {"sTitle": "Categoria", "bSortable" : true, "sWidth": '100px'},
                {"sTitle": "Status" , "bSearchable": true, "sWidth": '80px'},
                {"sTitle": "Accion" , "bSearchable": true, "sWidth": '190px'}
            ],
            actions	:   	[
                ['status',   {
                    0 : { txt : 'Inactivo' , cls : 'btn-danger', dis : false},
                    1 : { txt : 'Activo' ,  cls : 'btn-success', dis : false},
                }
                ],
                ['actions', [
                    ['eliminar', 'delete' ,'bgm-red'],
                    ['editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['date','description','category.name','status','actions'],
            configStatus : 'status'
        };

        // var alertConfig = {
        //     title: "¿Esta seguro?",
        //     text: "",
        //     type: "warning",
        //     showCancelButton: true,
        //     confirmButtonColor: "#DD6B55",
        //     confirmButtonText: "SI",
        //     cancelButtonColor: "#212121",
        //     cancelButtonText: "CANCELAR",
        //     closeOnConfirm: true
        // };

        // $scope.productClear = {
        //     name: null,
        //     cant: null,
        //     cod: null,
        //     provider_id: null,
        //     size_id : null,
        //     color_id: null,
        //     day: null,
        //     count: null,
        //     types: []
        // };

        $scope.list = function() {
            // $scope.updateList = true;
            // petition.get('api/auxproduct')
            //     .then(function(data){
            //         $scope.tableData = data.products;
            //         $('#table').AJQtable('view', $scope, $compile);
            //         $scope.updateList = false;
            //     }, function(error){
            //         console.log(error);
            //         toastr.error('Ups ocurrio un problema: ' + error.data.message);
            //         $scope.updateList = false;
            //     });
        };

        // $scope.edit = function (i) {
        //     petition.get('api/auxproduct/' + $scope.tableData[i].id)
        //         .then(function (data) {
        //             console.log(data);
        //             productEdit(data.product);
        //             $scope.productState = false;
        //             util.muestraformulario();
        //         }, function (error) {
        //             console.log(error);
        //             toastr.error('Uyuyuy dice: '+ error.data.message);
        //         });
        // };

        // $scope.delete = function (i) {
        //     alertConfig.title = "¿El producto se eliminara sin medio de retorno, esta seguro?";
        //     sweetAlert(alertConfig, function () {
        //         petition.delete('api/auxproduct/' + $scope.tableData[i].id)
        //             .then(function (data) {
        //                 $scope.list();
        //                 toastr.success(data.message);
        //             }, function (error) {
        //                 toastr.error('Uyuyuy dice: ' + error.data.message);
        //             });
        //     });
        // };

        // $scope.listSizes = function() {
        //     petition.get('api/sizes')
        //         .then(function(data){
        //             $scope.sizes = data.sizes;
        //         }, function(error){
        //             console.log(error);
        //             toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //         });
        // };

        // $scope.view = function( ind ){
        //     var id = $scope.tableData[ind].id;
        //     petition.get('api/product/group_attributes/' + id )
        //         .then(function(data){
        //             $scope.productGroupAttributes = data.grp_attributes;
        //             $scope.productDetail = angular.copy($scope.tableData[ind]);
        //             util.modal();
        //         }, function(error){
        //             toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //         });
        // };
        //
        // $scope.submit = function () {
        //     alertConfig.title = '¿Todo es correcto?'
        //     swal(alertConfig ,
        //         function() {
        //             var method = ( $scope.product.id ) ? 'PUT' : 'POST';
        //             var url = ( method == 'PUT') ? util.baseUrl('api/auxproduct/' + $scope.product.id) : util.baseUrl('api/auxproduct');
        //             var config = {
        //                 method: method,
        //                 url: url,
        //                 data: $scope.product
        //             };
        //             $scope.formSubmit=true;
        //             petition.custom(config).then(function(data){
        //                 toastr.success(data.message);
        //                 $scope.formSubmit=false;
        //                 $scope.list();
        //                 $scope.listCodes();
        //                 util.ocultaformulario();
        //             }, function(error){
        //                 toastr.error('Uyuyuy dice: ' + error.data.message);
        //                 $scope.formSubmit=false;
        //             })
        //         });
        //
        // };

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.muestraformulario();
        };


        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            // $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    });