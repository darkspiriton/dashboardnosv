angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Productos', {
                url: '/Adminitracion-de-productos',
                templateUrl: 'app/partials/auxProduct.html',
                controller : 'productsCtrl'
            });
    }])
    .controller('productsCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toastr", "$injector",
        function($scope, $compile, $state, $log, util, petition, toastr, $injector){

        /*
         |
         |  Init
         |
         */

        $scope.provider = {};
        $scope.product = {};
        $scope.data = {};
        $scope.productAux = [];

        $scope.stsCase = false;

        var messages = {
            reserve: {title: "¿Esta seguro de cambiar estado a reservado?", response: "Se cambio el estado a reservado."},
            transition: {title: "¡Cuidado este proceso no se puede revertir!", response: "El producto esta en transición."},
            observe: {title: "¿Esta seguro de cambiar el estado a observado?", response: "Se cambio el estado a observado"},
            inProvider: {title: "¿Esta seguro de cambiar el estado?", response: "Se cambio el estado a en proveedor"},
        };

        var reasonsMsg = {
            3: "Reservado",
            4: "Observado",
            5: "Transición",
            6: "Devolucion Proveedor"
        };

        var msg;

        $scope.situations = [
            {id: 1, name:'Producto dañado' },
            {id: 2, name:'Producto no esta en el almacen' }
        ];

        $scope.transitions = [
            {id: 1, name:'Producto esta en transición' },
            {id: 2, name:'Otros' }
        ];

        /*
         |  END
         */
        util.liPage('productsADM');

        var actions = [
                        ['eliminar', 'delete' ,'bgm-red'],
                        ['editar', 'edit' ,'btn-primary'],
                        ['movimientos','movements','bgm-teal'],
                        ['reservar','reserve','bgm-purple'],
                        ['observar','observe','bgm-lime'],
                        ['transición','transition','bgm-orange'],
                        ['en proveedor','inProvider','bgm-red']
                    ];

        var status = {
                    0 : ['salida','btn-danger', false],
                    1 : ['disponible', 'btn-success', false],
                    2 : ['vendido', 'bgm-teal', false],
                    3 : ['reservado', 'bgm-purple'],
                    4 : ['observado', 'bgm-lime'],
                    5 : ['transición', 'bgm-orange'],
                    6 : ['en proveedor', 'bgm-red']
                };

        var statusForSale = {
                    0 : ['normal', 'btn-success', false],
                    1 : ['liquidacion', 'btn-info', false]
                };

        util.liPage('products');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Nombre", "bSortable" : true, 'sWidth': '250px'},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Tipos", "bSortable" : true, "bSearchable": true},
                {"sTitle": "P. Prov" , "bSearchable": false},
                {"sTitle": "Utilidad" , "bSearchable": false},
                {"sTitle": "Estado V." , "bSearchable": false},
                {"sTitle": "P. Real" , "bSearchable": false},
                {"sTitle": "Precio" , "bSearchable": false},
                {"sTitle": "Status", "bSortable" : false, "bSearchable": true},
                {"sTitle": "Accion", "bSortable" : false, "bSearchable": false, "sWidth" : "90px"}
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'productStatusDetail', column: 'status', render: status},                            
                            { name: 'statusForSale', column: 'liquidation', render: statusForSale},
                        ]
                    },
                    {
                        type: 'actions',
                        list: [
                            { name: 'actions', render: actions}
                        ]
                    }
                ],
            data  	: 	['cod','name','provider','size','color','types','cost_provider','utility','statusForSale','price_real','precio','productStatusDetail','actions'],
            configStatus : 'status'
        };

        var configList =    {
            columns :   [
                {"sTitle": "Codigo", "bSortable" : true, 'sWidth': '1px'},
                {"sTitle": "Nombre", "bSortable" : true, 'sWidth': '250px'},
                {"sTitle": "Proveedor", "bSortable" : true},
                {"sTitle": "Talla", "bSortable" : true},
                {"sTitle": "Color", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Tipos", "bSortable" : true, "bSearchable": true},
                {"sTitle": "Status", "bSortable" : false, "bSearchable": true},
            ],
            data    :   ['cod','name','provider','size','color','types','statusSale'],
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
            closeOnConfirm: true,
            html: true
        };

        $scope.productClear = {
            name: null,
            cant: null,
            cod: null,
            provider_id: null,
            size_id : null,
            color_id: null,
            day: null,
            count: null,
            cost:null,
            uti:null,
            types: []
        };

        var newProvider =  {
            id: 0,
            name: '>>---> (Nuevo Proveedor) <---<<'
        };

        var newColor =  {
            id: 0,
            name: '>>---> (Nuevo Color) <---<<'
        };

        var newType =  {
            id: 0,
            name: '>>---> (Nuevo Tipo) <---<<'
        };

        function otherView(data){
            for(var i in data){
                if (data[i].status === 0){
                    data[i].statusSale = "salida";
                } else if (data[i].status == 1){
                    data[i].statusSale = "disponible";
                } else if (data[i].status == 2){
                    data[i].statusSale = "vendido";
                } else if (data[i].status == 3){
                    data[i].statusSale = "reservado";
                } else if (data[i].status == 4){
                    data[i].statusSale = "observado";
                } else {
                    data[i].statusSale = "Otro";
                }
            }
            $('#tableList').AJQtable2('view2', $scope, $compile, data, configList);
        }

        $scope.edit = function (i) {
            petition.get('api/auxproduct/' + $scope.tableData[i].id)
                .then(function (data) {
                    productEdit(data.product);
                    $scope.productState = false;
                    util.muestraformulario();
                }, function (error) {
                    toastr.error('Uyuyuy dice: '+ error.data.message);
                });
        };

        function productEdit(data){
            for(var i in data.types){
                delete data.types[i].pivot;
            }
            data.cod = parseInt(data.cod);
            data.provider_id = parseInt(data.provider_id);
            data.color_id = parseInt(data.color_id);
            data.size_id = parseInt(data.size_id);
            data.cost = parseFloat(data.cost);
            data.uti = parseFloat(data.uti);
            data.alarm.day=parseInt(data.alarm.day);
            data.alarm.count=parseInt(data.alarm.count);
            $scope.product = data;
        }

        $scope.delete = function (i) {
            alertConfig.title = "¿Desea eliminar?";
            alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
                                        <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>${$scope.tableData[i].cod}</td>
                                            <td>${$scope.tableData[i].name}</td>
                                            <td>${$scope.tableData[i].size}</td>
                                            <td>${$scope.tableData[i].color}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`;
            sweetAlert(alertConfig, function () {
                petition.delete('api/auxproduct/' + $scope.tableData[i].id)
                    .then(function (data) {
                        $scope.searchList($scope.data);
                        toastr.success(data.message);
                    }, function (error) {
                        toastr.error('Uyuyuy dice: ' + error.data.message);
                    });
            });
        };

        $scope.listProviders = function() {
            $scope.providers = [];
            petition.get('api/auxproviders')
                .then(function(data){
                    $scope.providers = data.providers;
                    $scope.providersFilter = angular.copy(data.providers);
                    $scope.providers.push(newProvider);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listSizes = function() {
            petition.get('api/sizes')
                .then(function(data){
                    $scope.sizes = data.sizes;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listColors = function() {
            petition.get('api/colors')
                .then(function(data){
                    $scope.colors = data.colors;
                    $scope.colorsFilter = angular.copy(data.colors);
                    $scope.colors.push(newColor);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listTypes = function() {
            petition.get('api/auxproduct/get/type')
                .then(function(data){
                    $scope.types = data.types;
                    $scope.typesFilter = angular.copy(data.types);
                    $scope.types.push(newType);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.listCodes = function() {
            $scope.btnCods = true;
            $scope.codes = [];
            petition.get('api/auxproduct/get/code')
                .then(function(data){
                    $scope.codes = data.codes;
                     $scope.btnCods = false;
                }, function(error){
                     $scope.btnCods = false;
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.view = function( ind ){
            $scope.productGroupAttributes = [];
            $scope.productDetail = {};
            var id = $scope.tableData[ind].id;
            petition.get('api/product/group_attributes/' + id )
                .then(function(data){
                    $scope.productGroupAttributes = data.grp_attributes;
                    $scope.productDetail = angular.copy($scope.tableData[ind]);
                    util.modal();
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    var method = ( $scope.product.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/auxproduct/' + $scope.product.id) : util.baseUrl('api/auxproduct');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.product
                    };
                    $scope.formSubmit=true;
                    petition.custom(config).then(function(data){
                        toastr.success(data.message);
                        $scope.formSubmit=false;
                        $scope.searchList($scope.data);
                        $scope.listCodes();
                        util.ocultaformulario();
                    }, function(error){
                        toastr.error('Huy Huy dice: ' + error.data.message);
                        $scope.formSubmit=false;
                    });
                });

        };

        $scope.cancelForm = function (){
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.product = angular.copy($scope.productClear);
            $scope.productState = true;
            util.muestraformulario();
        };


        // Events

        $scope.eventProvider = function( v ){
            if ( v !== 0)return true;
            $scope.product.provider_id = null;
            $scope.newFeature.tittle = 'Nuevo Proveedor';
            $scope.newFeature.label = 'Ingrese nombre de proveedor';
            $scope.newFeature.url = 'api/auxproduct/set/provider';
            $scope.newFeature.name = null;
            util.modal('feature');
        };

        $scope.eventColor = function( v ){
            if ( v !== 0)return true;
            $scope.product.color_id = null;
            $scope.newFeature.tittle = 'Nuevo Color';
            $scope.newFeature.label = 'Ingrese nombre de color';
            $scope.newFeature.url = 'api/auxproduct/set/color';
            $scope.newFeature.name = null;
            util.modal('feature');
        };

        $scope.addType = function( i ){
            if ( $scope.types[i].id === 0) {
                $scope.typeSelect = null;
                $scope.newFeature.tittle = 'Nuevo Tipo de Producto';
                $scope.newFeature.label = 'Ingrese el tipo';
                $scope.newFeature.url = 'api/auxproduct/get/type';
                $scope.newFeature.name = null;
                util.modal('feature');
            }else{
                var count = 0;
                for(var ind in  $scope.product.types){
                    if(angular.equals($scope.types[i],$scope.product.types[ind])){
                        count++;
                    }
                }

                if (count === 0){
                    $scope.product.types.push(angular.copy($scope.types[i]));
                }
                $scope.typeSelect = null;
            }

        };

        $scope.removeType = function(i){
            $scope.product.types.splice(i,1);
        };

        $scope.addFeature = function () {
            petition.post($scope.newFeature.url,
                {name: $scope.newFeature.name})
                .then(function(data){
                    toastr.success(data.message);
                    util.modalClose('feature');
                    $scope.listProviders();
                    $scope.listColors();
                    $scope.listTypes();
                }, function(error){
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                });
        };

        // End events

// 
        /**
         *  Nueva vista de movimientos por producto
         *
         *  @params int         id
         *  @return Collection  movements
         */

        $scope.movements = function(i){
            var id = $scope.tableData[i].id;
            petition.get('api/auxproduct/get/movements/'+id)
                .then(function(data){
                    $scope.productMovements = data.movements;
                    util.modal('productMovements');
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params String
         *  @return tag:a-button
         */

        $scope.movementStatus = function(movement){
            var info = {
                'Retornado': ['Retornado','bgm-red'],
                'Vendido': ['Vendido','bgm-teal'],
                'salida': ['Salida','bgm-deeppurple'],
                'reprogramado': ['reprogramado','bgm-purple']
            };
            if(movement.status == 'salida'){
                if(movement.situation == 'reprogramado')
                    return `<a class="btn btn-xs disabled ${info.reprogramado[1]}">${info.reprogramado[0]}</a>`;
            }
            return `<a class="btn btn-xs disabled ${info[movement.status][1]}">${info[movement.status][0]}</a>`;
        };

        $scope.movementClass = function(movement){
            var classname = '';
            switch(movement.status) {
                case 'salida':
                    classname = 'arrow-purple';
                    break;
                case 'Vendido':
                    classname = 'arrow-teal';
                    break;
                case 'Retornado':
                    classname = 'arrow-red';
                    break;
            }

            return classname;
        };

        /*
         * Helper para vista de detalle de movimientos
         *
         *  @params Int
         *  @return tag:a-button
         */

        $scope.payStatus = function(status){
            var info = {
                0: ['Normal','bgm-green',false],
                1: ['Liquidacion','btn-info',false],
            };

            return `<a class="btn btn-xs disabled ${info[status][1]}">${info[status][0]}</a>`;
        };

        $scope.reserve = function(i){
            msg = messages.reserve;
            listReasons(3, i);
            util.modal();
        };

        /*
         *
         * Helper para filtro
         *
         */

        $scope.typesList = function(){
            petition.get('api/auxproduct/filter/get/types')
                .then(function(data){
                    $scope.types = data.types;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.providerList = function(){
            petition.get('api/auxproduct/filter/get/providers')
                .then(function(data){
                    $scope.providers = data.providers;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.productList = function(){
            petition.get('api/auxproduct/filter/get/products')
                .then(function(data){
                    $scope.products = data.products;
                    $scope.colors = data.colors;
                    $scope.sizes = data.sizes;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.searchList = function(dataSearch){
            $scope.btnDisable = true;

            petition.get('api/auxproduct/filter/get/search', {params: dataSearch})
                .then(function(data){
                    $scope.tableData = data.products;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.btnDisable = false;
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.btnDisable = false;
                });
        };

        $scope.download = function(dataSearch){
            $scope.btnDownload = true;
            if(dataSearch.status_sale === "")dataSearch.status_sale = null;
            petition.post('api/auxproduct/filter/get/search/download', dataSearch, {responseType:'arraybuffer'})
                .then(function(data){
                    var date = new Date().getTime();
                    var name = date + '-reporte-de-kardex.xls';
                    var file = new Blob([data],{ type : 'application/vnd.ms-excel; charset=UTF-8'});
                    saveAs(file, name);
                    $scope.btnDownload = false;
                }, function(error){
                    toastr.error("El archivo es demasiado grande, no se pudo descargar");
                    $scope.btnDownload = false;
                });
        };


        $scope.clear = function(dataSearch){
            dataSearch.order=null;
            dataSearch.type=null;
            dataSearch.provider_id=null;
            dataSearch.status_sale=null;
            dataSearch.status=null;           
            // dataSearch.product=null;
            dataSearch.size=null;
            // dataSearch.color=null;
            // var result = document.getElementsByClassName("btn dropdown-toggle btn-default");  
            // console.log(result);          
            // result[0]["outerHTML"]='<button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="Ninguno" aria-expanded="false"><span class="filter-option pull-left">Ninguno</span>&nbsp;<span class="caret"></span></button>';
             
            toastr.success("Se limpiaron todos los filtros de busqueda");
        };

        /**
         *    Helper para observar un producto en el kardex
         *
         *    @param  Int  id
         *    @return  Object:String    Confirmation
         */
         $scope.observe = function (i){
            $scope.productId = $scope.tableData[i].id;
            $scope.productAux.cod = $scope.tableData[i].cod;
            $scope.productAux.name = $scope.tableData[i].name;
            $scope.productAux.size = $scope.tableData[i].size;
            $scope.productAux.color = $scope.tableData[i].color;
            $scope.productAux.situation = null;
            petition.get('api/auxproduct/observe/' +  $scope.productId)
                .then(function(data){
                    //Validar si el producto esta observado o no
                    $scope.status=data.status;
                    if($scope.status === false){
                        util.modal('Modal');                        
                        //Mostrar modal y poder elegir el motivo de la observacion
                        //Luego mostrar detalle de modificacion
                        //Luego mostrar detalle de confirmacion
                        //changeObserve(productAux,id);
                    }else if ($scope.status === true){
                        //Mostrar detalle de modificacion
                        //Luego mostrar detalle de confirmacion
                        $scope.changeObserve();
                    } else if ($scope.status === null){
                        toastr.error('Huy Huy dice: ' + data.message);
                    }
                },function(error){
                    $scope.searchList($scope.data);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });

         };

         $scope.inProvider = function (i){
            $scope.productId = $scope.tableData[i].id;
            $scope.productAux.cod = $scope.tableData[i].cod;
            $scope.productAux.name = $scope.tableData[i].name;
            $scope.productAux.size = $scope.tableData[i].size;
            $scope.productAux.color = $scope.tableData[i].color; 
            $scope.productAux.situation = $scope.tableData[i].status;          
            $scope.inProviderChange();
         };

        $scope.observe = function (i){
            msg = messages.observe;
            listReasons(4, i);
            util.modal();
        };


        $scope.inProvider = function (i) {
            msg = messages.inProvider;
            listReasons(6, i);
            util.modal();
        };

        $scope.transition = function (i){
            msg = messages.transition;
            listReasons(5, i);
            util.modal();
        };

        $scope.cancel = function(){
            util.modalClose();
        };

        function resetProduct(){
            $scope.products = [];
            $scope.colors = [];
            $scope.sizes = [];

            $scope.product = {};
            $scope.data.product = null;
            $scope.data.color = null;
            $scope.data.size = null;
        }

        function resetColorSize(){
            $scope.colors = [];
            $scope.sizes = [];

            $scope.data.color = null;
            $scope.data.size = null;
        }

        /*
         *  END
         */ 

         $scope.resetFilter = function(){
             return void($scope.data = {});
         };

         $scope.$watch("data", function(nValue){
            if(Object.keys(nValue).lenght){
                return void($scope.btnDisable = true);
            }

            for (var i in nValue) {
                if(nValue[i]){
                    return void($scope.btnDisable = false);
                }
            }
            return void($scope.btnDisable = true);
         }, true);

        function listReasons(id, i){
            var product = $scope.tableData[i];
            if(product.status != 1 && product.status != 2){
                $scope.stsCase = true;
            } else {
                $scope.stsCase = false;
            }

            $scope.product_i = i;
            $scope.reasons = [];
            petition.get('api/auxproduct/get/reasons/' + parseInt(id))
                .then(function(response){
                    $scope.reasons = response;
                },function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                });
        }

        $scope.confirmChangeStatus = function(reason_i, product_i){
            var data = {};
            var product = $scope.tableData[product_i];
            var reason = $scope.reasons[reason_i];

            data.reason_id = reason.id;
            data.response = msg.response;

            alertConfig.title = msg.title;
            alertConfig.text = confirmTemplate(reason.description, product);

            sweetAlert(alertConfig, function () {
                petition.post('api/auxproduct/' + product.id + '/status/change', data)
                    .then(function (response) {
                       util.modalClose('Modal');
                       toastr.success(response.message);
                       $scope.searchList($scope.data);
                    }, function (error) {
                        toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                    });
            });
        };

        $scope.confirmRestoreStatus = function (reason_i, product_i){
            var product = $scope.tableData[product_i];
            var reason = reasonsMsg[product.status];

            alertConfig.title = "¿Esta seguro de restaurar el estado del producto, estado actual sera resuelto?";
            alertConfig.text = confirmTemplate(reason, product);

            sweetAlert(alertConfig, function () {
                petition.post('api/auxproduct/' + product.id + '/status/change')
                    .then(function (response) {
                       util.modalClose('Modal');
                       toastr.success(response.message);
                       $scope.searchList($scope.data);
                    }, function (error) {
                        toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                    });
            });
        };

        function confirmTemplate(reason, product) {
            var template = `<table class="table table-bordered w-100 table-attr text-center">
                                <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Talla</th>
                                    <th>Color</th>
                                    <th>Motivo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>${product.cod}</td>
                                    <td>${product.name}</td>
                                    <td>${product.size}</td>
                                    <td>${product.color}</td>
                                    <td>${reason}</td>
                                </tr>
                                </tbody>
                            </table>
                            </div>`;

            return template;
        }

        $scope.productStatusDetail = function(i){
            var product = $scope.tableData[i];
            petition.post('api/auxproduct/' + product.id + '/status/detail')
                .then(function (response) {
                    $scope.statusDetail = response;
                    util.modal('ModalDetail');
                }, function (error) {
                    toastr.error('Huy Huy dice: ' + error.data.message || "Error inesperado =(");
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope, $compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.newFeature = {};

            $scope.productList();
            $scope.listProviders();
            $scope.listSizes();
            $scope.listColors();
            $scope.listTypes();
        });
    }]);
