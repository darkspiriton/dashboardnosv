angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('PrestaShop', {
                url: '/Pedidos-venta-prestashop',
                templateUrl: 'app/partials/prestaShop.html',
                controller : 'PrestaShopCtrl'
            });
    }])
    .controller('PrestaShopCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr",
        function(vm, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('PrestaShop');

        var s1 = {
            0: ['no atendido','bgm-purple',false],
            1: ['atendido','bgm-green',false],
        };

        var a1 = [
            ['Detalle', 'bDetail' ,'bgm-green'],
            ['Atender', 'bUpdate' ,'bgm-blue'],
            ['Eliminar', 'bDelete' ,'bgm-red'],
        ];

        vm.tableConfig  =   {
            columns :   [
                {"sTitle": "Fecha de solicitud", "bSortable" : true, "sWidth": "120px"},
                {"sTitle": "Código", "bSortable" : true },
                {"sTitle": "Cantidad de Productos", "bSortable" : true },
                {"sTitle": "Monto Total", "bSortable" : true },
                {"sTitle": "Nombre Usuario", "bSortable" : true },
                {"sTitle": "Correo" , "bSortable" : true, "bSearchable": true },
                {"sTitle": "Teléfono" , "bSortable" : true, "bSearchable": true },
                {"sTitle": "Estado" , "bSortable" : true, "bSearchable": true },
                {"sTitle": "Acciones" , "bSortable" : false, "bSearchable": false , "sWidth": "190px"},
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status', column: 'status', render: s1},
                        ]
                    },
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render: a1},
                        ]
                    }
                ],
            data    :   ["created_at","id","cantProduct","total_price","user.name","user.email","user.phone","status","actions"],
        };

        vm.tableConfig2  =   {
            columns :   [
                {"sTitle": "Nombre", "bSortable" : true, "sWidth": "120px"},
                {"sTitle": "Cantidad", "bSortable" : true },
                {"sTitle": "Precio Unitario", "bSortable" : true },
                {"sTitle": "Sub Total", "bSortable" : true },
                {"sTitle": "Stock", "bSortable" : true },
                {"sTitle": "Imagen" , "bSortable" : true, "bSearchable": true },
                // {"sTitle": "Link" , "bSortable" : true, "bSearchable": true }               
            ],
            buttons :
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'status', column: 'status', render: s1},
                        ]
                    },
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render: a1},
                        ]
                    },
                    {
                        type: 'custom',
                        list: [
                            { name: 'url_image', template: '<a href="{2}" target="_blank"><img width="150px" src="{0}" alt="{1}"></a>', column: ['url_image','name','url_product']}
                        ]
                    }
                ],
            data    :   ["name","cant","price","total_price","stock","url_image","url_product"],
        };

        vm.list = function(){
            vm.updateList = true;
            petition.get("api/prestashop")
                .then(function(data){
                    vm.tableData = data.pedidos;
                    $("#table").AJQtable2("view2",vm, $compile);
                    vm.updateList = false;
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo listar contactos.");
                    vm.updateList = false;
                });
        };

        vm.filter = function(id){
            vm.updateList = true;
            petition.get("api/prestashop/",{params: {status: vm.status}})
                .then(function(data){
                    vm.tableData = data.pedidos;
                    $("#table").AJQtable2("view2",vm, $compile);
                    vm.updateList = false;
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo listar contactos.");
                    vm.updateList = false;
                });
        };

        vm.statusList = function(){
            petition.get("api/prestashop/get/status")
                .then(function(data){
                    vm.estados = data.estados;
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo listar los estados.");
                });
        };

        vm.bDetail = function(i){
            var id = vm.tableData[i].id;
            petition.get("api/prestashop/products/" + id)
                .then(function(data){
                    vm.products=data.products;
                    console.log(vm.products);
                    vm.open();
                    $("#table2").AJQtable2("view2",vm, $compile,vm.products,vm.tableConfig2);
                    toastr.success(data.message);
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo confirmar cambio de estado.");
                });
        };

        vm.bDelete = function(i){
            var id = vm.tableData[i].id;
            petition.delete("api/prestashop/" + id)
                .then(function(data){
                    vm.list();
                    toastr.success(data.message);
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo confirmar cambio de estado.");
                });
        };

        vm.bUpdate = function(i){
            var id = vm.tableData[i].id;
            petition.put("api/prestashop/" + id)
                .then(function(data){
                    vm.list();
                    toastr.success(data.message);
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo confirmar cambio de estado.");
                });
        };

        vm.open = function(){                        
            util.muestraformulario();
        };

        vm.close = function(){
            util.ocultaformulario();
            vm.products = {};
        };

        angular.element(document).ready(function(){
            vm.list();
            vm.statusList();
        });
    }]);
