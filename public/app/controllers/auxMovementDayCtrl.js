angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('MovementDay', {
                url: '/Movimientos-diarios',
                templateUrl: 'app/partials/auxMovementDay.html',
                controller : 'auxMovementDayCtrl'
            });
    })
    .controller('auxMovementDayCtrl', function($scope, $compile, $state, $log, util, petition, toastr,$filter){

        util.liPage('movementday');

        var s1 = {
            "Retornado": ['Retorno','bgm-teal',false],
            "Vendido": ['Vendido','bgm-green',false]
        };



        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Fecha", "bSortable" : true, 'sWidth': '80px'},
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Producto", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Precio S/.", "bSortable" : true},
                {"sTitle": "Descuento S/.", "bSortable" : true},
                {"sTitle": "P. Final S/.", "bSortable" : true},
                {"sTitle": "Estatus" , "bSearchable": true},
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status', column: 'status', render : s1}
                        ]
                    }
                ],
            data  	: 	['fecha','codigo','product','color','talla','price','discount','pricefinal','status'],
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxmovement/get/movementDay')
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.filter = function(){
            $scope.updateList = true;
            $scope.dateSave = angular.copy($scope.data);
            $scope.dateSave.date1 = $filter('date')($scope.data.date1, 'yyyy-MM-dd')
            // $scope.dateSave.date2 = $filter('date')($scope.data.date2, 'yyyy-MM-dd')
            petition.get('api/auxmovement/get/movementDay/get', { params : $scope.dateSave })
                .then(function(data){
                    $scope.tableData = data.movements;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                    // if ( data.movements.length > 0){
                    //     chart.drawColummn(data.draw,data.days);
                    //     $scope.drawShow=true;
                    // } else {
                    //     $scope.drawShow=false;
                    // }
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
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

        // function productEdit(data){
        //     for(var i in data.types){
        //         delete data.types[i].pivot;
        //     }
        //     data.cod = parseInt(data.cod);
        //     data.provider_id = parseInt(data.provider_id);
        //     data.color_id = parseInt(data.color_id);
        //     data.size_id = parseInt(data.size_id);
        //     data.cost = parseFloat(data.cost);
        //     data.uti = parseFloat(data.uti);
        //     data.alarm.day=parseInt(data.alarm.day);
        //     data.alarm.count=parseInt(data.alarm.count);
        //     $scope.product = data;
        // }

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

        // $scope.listProviders = function() {
        //     petition.get('api/auxproviders')
        //         .then(function(data){
        //             $scope.providers = data.providers;
        //             $scope.providers.push(newProvider);
        //         }, function(error){
        //             console.log(error);
        //             toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //         });
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

        // $scope.listColors = function() {
        //     petition.get('api/colors')
        //         .then(function(data){
        //             $scope.colors = data.colors;
        //             $scope.colors.push(newColor);
        //         }, function(error){
        //             console.log(error);
        //             toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //         });
        // };

        // $scope.listTypes = function() {
        //     petition.get('api/auxproduct/get/type')
        //         .then(function(data){
        //             $scope.types = data.types;
        //             $scope.types.push(newType);
        //         }, function(error){
        //             console.log(error);
        //             toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //         });
        // };

        // $scope.listCodes = function() {
        //     petition.get('api/auxproduct/get/code')
        //         .then(function(data){
        //             $scope.codes = data.codes;
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

        // $scope.cancel = function () {
        //     $scope.product = angular.copy($scope.productClear);
        //     $scope.productState = true;
        //     util.ocultaformulario();
        // };
        //
        // $scope.new = function(){
        //     $scope.product = angular.copy($scope.productClear);
        //     $scope.productState = true;
        //     util.muestraformulario();
        // };


        // Events

        // $scope.eventProvider = function( v ){
        //     if ( v != 0)return true;
        //     $scope.product.provider_id = null;
        //     $scope.newFeature.tittle = 'Nuevo Proveedor';
        //     $scope.newFeature.label = 'Ingrese nombre de proveedor';
        //     $scope.newFeature.url = 'api/auxproduct/set/provider';
        //     $scope.newFeature.name = null;
        //     util.modal('feature');
        // };
        //
        // $scope.eventColor = function( v ){
        //     if ( v != 0)return true;
        //     $scope.product.color_id = null;
        //     $scope.newFeature.tittle = 'Nuevo Color';
        //     $scope.newFeature.label = 'Ingrese nombre de color';
        //     $scope.newFeature.url = 'api/auxproduct/set/color';
        //     $scope.newFeature.name = null;
        //     util.modal('feature');
        // };
        //
        // $scope.addType = function( i ){
        //     if ( $scope.types[i].id == 0) {
        //         $scope.typeSelect = null;
        //         $scope.newFeature.tittle = 'Nuevo Tipo de Producto';
        //         $scope.newFeature.label = 'Ingrese el tipo';
        //         $scope.newFeature.url = 'api/auxproduct/get/type';
        //         $scope.newFeature.name = null;
        //         util.modal('feature');
        //     }else{
        //         var count = 0;
        //         for(ind in  $scope.product.types){
        //             if(angular.equals($scope.types[i],$scope.product.types[ind])){
        //                 count++;
        //             }
        //         }
        //
        //         if (count == 0){
        //             $scope.product.types.push(angular.copy($scope.types[i]));
        //         }
        //         $scope.typeSelect = null;
        //     }
        //
        // };
        //
        // $scope.removeType = function(i){
        //     $scope.product.types.splice(i,1);
        // };
        //
        // $scope.addFeature = function () {
        //     petition.post($scope.newFeature.url,
        //         {name: $scope.newFeature.name})
        //         .then(function(data){
        //             toastr.success(data.message);
        //             util.modalClose('feature');
        //             $scope.listProviders();
        //             $scope.listColors();
        //             $scope.listTypes();
        //         }, function(error){
        //             console.log(error);
        //             toastr.error('Uyuyuy dice: ' + error.data.message);
        //         });
        // };

        // End events

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            // $scope.product = angular.copy($scope.productClear);
            // $scope.newFeature = {};
            $scope.list();
            // $scope.listProviders();
            // $scope.listSizes();
            // $scope.listColors();
            // $scope.listTypes();
            // $scope.listCodes();
        });
    });