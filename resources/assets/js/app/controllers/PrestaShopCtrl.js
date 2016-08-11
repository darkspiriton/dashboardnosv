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
            ['Detalle', 'bAtendido' ,'bgm-green'],
            ['Atender', 'bRechazado' ,'bgm-red'],
            ['Eliminar', 'bRechazado' ,'bgm-red'],
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

        // vm.$watch("updateList", function(nVal, oVal){
        //     if(nVal)vm.status = null;
        // });

        vm.bAtendido = function(i){
            var id = vm.tableData[i].id;
            petition.put("api/associate/" + id, {status: 1})
                .then(function(data){
                    vm.list();
                    toastr.success(data.message);
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo confirmar cambio de estado.");
                });
        };

        vm.bRechazado = function(i){
            var id = vm.tableData[i].id;
            petition.put("api/associate/" + id, {status: 2})
                .then(function(data){
                    vm.list();
                    toastr.success(data.message);
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo confirmar cambio de estado.");
                });
        };

        angular.element(document).ready(function(){
            vm.list();
            vm.statusList();
        });
    }]);
