angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Productos', {
                url: '/Adminitracion-de-productos',
                templateUrl: 'app/partials/products.html',
                controller : 'productsCtrl'
            });
    })
    .controller('productsCtrl', function($scope, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('products');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "codigo", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Precio", "bSortable" : true},
                {"sTitle": "Cant", "bSortable" : true, "sWidth": "1px"},
                {"sTitle": "Estado", "bSortable" : true, "sWidth": "80px"},
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "270px"}
            ],
            actions	:  	[
                            ['status',   {
                                            0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                                            1 : { txt : 'Activo' ,  cls : 'btn-success' } ,
                                        }
                            ],
                            ['actions',
                                [
                                    ['ver', 'view' ,'btn-info'],
                                    ['Editar', 'edit' ,'btn-primary'],
                                    ['kardex', 'kardex' ,'bgm-teal']
                                ]
                            ]
                        ],
            data  	: 	['product_code','name','price','cant','status','actions'],
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

        $scope.productClear = {
            name: '',
            img: null,
            price: null,
            status : 0,
            type_product_id: null,
            groupAttr: []
        };

        $scope.newAttrClear = {
            id : null,
            val_id : null
        };

        $scope.list = function() {
            petition.get('api/product')
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listAttr = function() {
            petition.get('api/attribute')
                .then(function(data){
                    $scope.attributos = data.types;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.listTypeProduct = function() {
            petition.get('api/product/types')
                .then(function(data){
                    $scope.typeProduct = data.types;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/product/group_attributes/' + id )
                .then(function(data){
                    $scope.productGroupAttributes = data.grp_attributes;
                    $scope.productDetail = angular.copy($scope.tableData[ind]);
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.status = function( ind, dom ) {
            alertConfig.title = "¿Desea cambiar el estado del usuario?";
            var id = $scope.tableData[ind].id;
            swal(alertConfig ,
                function() {
                    petition.delete('api/product/' + id ).then(function(data){
                        toastr.success(data.message);
                        changeButton(ind , dom.target);
                    },function(error){
                        $log.log(error);
                        toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    });
                });
        };

        //$scope.edit = function( ind ){
        //    var id = $scope.tableData[ind].id;
        //    petition.get('api/user/' + id)
        //        .then(function(data){
        //            $log.log(data.user);
        //            $scope.empleado = data.user;
        //            var role_id = $scope.empleado.role_id;
        //            $scope.empleado.role_id = role_id.toString();
        //            $scope.empleado.password = '**********';
        //            util.muestraformulario();
        //        }, function(error){
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};

        $scope.submit = function () {
            var msg = productValidate();
            if ( msg  == ""){
                return $log.log('Good exelent !! ');
                var method = ( $scope.product.id )?'PUT':'POST';
                var url = ( method == 'PUT')? util.baseUrl('api/product/' + $scope.product.id): util.baseUrl('api/product');
                var config = {
                    method: method,
                    url: url,
                    data: toformData.dataFile($scope.product),
                    headers: {'Content-Type': undefined}
                };
                $scope.formSubmit=true;
                $log.log($scope.product);
                petition.custom(config).then(function(data){
                    toastr.success(data.message);
                    $scope.formSubmit=false;
                    $scope.list();
                    util.ocultaformulario();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.formSubmit=false;
                });
            } else {
                toastr.error(msg);
            }
        };

        function productValidate(){
            var grpAttTemp = removeQuantityValidate();

            for(var i in $scope.product.groupAttr){
                if ($scope.product.groupAttr[i].quantity == undefined){
                    return "Falta ingresar cantidad";
                }
            }

            for(var index in grpAttTemp){
                for(var index2 in  grpAttTemp){
                    if( index2 <= index )continue;
                    if (angular.equals(grpAttTemp[index],grpAttTemp[index2])){
                        return "Se encontro grupo de attributos iguales";
                    }
                }
            }

            return "";
        }

        function removeQuantityValidate(){
            var temp = angular.copy($scope.product.groupAttr);
            for(var i in temp) {
                temp[i].quantity = undefined;
                temp[i].id = undefined;
            }
            return temp;
        }

        $scope.cancel = function () {
            $scope.product = angular.copy($scope.productClear);
            util.ocultaformulario();
        };

        //$scope.cancel2 = function () {
        //    //$log.log($scope.product);
        //    var msg = productValidate();
        //    if ( msg == ''){
        //        return $log.log('Good exelent !! ');
        //    } else {
        //        $log.info(msg);
        //    }
        //};

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            util.muestraformulario();
        };

        $scope.getStatus = function( status ){
            if (status == 1)return 'Activo';
            else return 'Inactivo';
        };

        // Adds Attributes

        $scope.showAttr = function ( i ){
            $scope.index = i;
            util.modal('addAttr');
        };

        $scope.attr_select = function(){
            $scope.newAttr.id = $scope.attributos[$scope.attr_index].id;
            $scope.attr_values = $scope.attributos[$scope.attr_index].att;
        };

        $scope.cancelAddAttr = function(){
            util.modalClose('addAttr');
        };

        $scope.addAttrGrp = function(){
            var count =0;
            for(var i in  $scope.product.groupAttr[$scope.index].attributes){
                if($scope.newAttr.id == $scope.product.groupAttr[$scope.index].attributes[i].id){
                    count++;
                    $scope.product.groupAttr[$scope.index].attributes[i] = angular.copy($scope.newAttr);
                }
            }

            if (count == 0)$scope.product.groupAttr[$scope.index].attributes.push(angular.copy($scope.newAttr));
            util.modalClose('addAttr');

            $scope.attr_index = null;
            $scope.newAttr.val_id = null;
        };

        $scope.removeAttr = function( grpIndex, item ){
            $scope.product.groupAttr[grpIndex].attributes.splice(item, 1);
        };

        $scope.addGrpAttr = function(){
            $scope.count += 1;
            $scope.product.groupAttr.push({ id : $scope.count , attributes : [] });
        };

        $scope.removeGrpAttr = function( item ){
            $scope.product.groupAttr.splice(item, 1);
        };

        $scope.duplicateGrpAttr = function( item ){
            $scope.count++;
            item.id = $scope.count;
            $scope.product.groupAttr.push(angular.copy(item));
        };

        $scope.attrName = function( ind ){
            for(var i in  $scope.attributos){
                if(ind == $scope.attributos[i].id){
                    return $scope.attributos[i].name;
                }
            }
        };

        $scope.attrValueName = function( ind , ind2){
            for(var i in  $scope.attributos){
                if(ind == $scope.attributos[i].id){
                    for(var x in  $scope.attributos[i].att){
                        if(ind2 == $scope.attributos[i].att[x].id){
                            return $scope.attributos[i].att[x].valor;
                        }
                    }
                }
            }
        };

        $scope.showQuant = function ( grpIndex ){
            $scope.index = grpIndex;
            util.modal('addQuant');
        };

        $scope.cancelAddQuant = function(){
            util.modalClose('addAttr');
        };

        $scope.addQuantGrp = function(){
            $scope.product.groupAttr[$scope.index].quantity = angular.copy($scope.quantity);
            util.modalClose('addQuant');
            $scope.quantity = null;
        };

        // end Attributes

        changeButton = function (ind, dom){
            $scope.tableData[ind].status = ($scope.tableData[ind].status == 0)? 1 : 0;
            if ( $scope.tableData[ind].status == 1){
                $(dom).removeClass('btn-danger');
                $(dom).addClass('btn-success');
                $(dom).html('Activo');
            } else {
                $(dom).removeClass('btn-success');
                $(dom).addClass('btn-danger');
                $(dom).html('Inactivo');
            }
        };

        $scope.kardex = function( ind ){
            $state.go("Kardex", { id: $scope.tableData[ind].id });
        };

        $scope.$watch("productDetail.image", function( newVal, oldVal, scope ){
            if (!newVal) return;
            var route = 'img/products/' + newVal;
            route = util.baseUrl(route);
            scope.viewImage = route;
        });

        angular.element(document).ready(function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.newAttr = angular.copy($scope.newAttrClear);
            $scope.productDetail = {};
            $scope.count = 0;
            $scope.index = null;
            $scope.list();
            $scope.listAttr();
            $scope.listTypeProduct();
        });
    });