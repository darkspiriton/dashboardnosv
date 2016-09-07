angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('RequestApplication', {
                url: '/Pedidos-venta-empresas',
                templateUrl: 'app/partials/requestApplication.html',
                controller : 'RequestApplicationCtrl'
            });
    }])
    .controller('RequestApplicationCtrl', ["$scope", "$compile", "$state", "$log", "util", "petition", "toformData", "toastr",
        function(vm, $compile, $state, $log, util, petition, toformData, toastr){

        util.liPage('RequestApplication');

        var s1 = {
            0: ['no atendido','bgm-purple',false],
            1: ['atendido','bgm-green',false],
            2: ['rechazado','bgm-red',false],
            'fail': ['otro','bgm-black',false]
        };

        var a1 = [
            ['Atendido', 'bAtendido' ,'bgm-green'],
            ['Rechazado', 'bRechazado' ,'bgm-red'],
        ];

        vm.tableConfig  =   {
            columns :   [
                {"sTitle": "Fecha de solicitud", "bSortable" : true, "sWidth": "120px"},
                {"sTitle": "Nombre", "bSortable" : true },
                {"sTitle": "Correo electronico", "bSortable" : true },
                {"sTitle": "Telefono", "bSortable" : true },
                {"sTitle": "Estado", "bSortable" : true },
                {"sTitle": "Acción" , "bSortable" : false, "bSearchable": false , "sWidth": "90px"}
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
            data    :   ["created_at","name","email","phone","status","actions"],
        };

        vm.list = function(){
            vm.updateList = true;
            petition.get("api/associate")
                .then(function(data){
                    vm.tableData = data;
                    $("#table").AJQtable2("view2",vm, $compile);
                    vm.updateList = false;
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo listar contactos.");
                    vm.updateList = false;
                });
        };

        vm.filter = function(id){
            vm.updateList = true;
            petition.get("api/associate/" + id)
                .then(function(data){
                    vm.tableData = data;
                    $("#table").AJQtable2("view2",vm, $compile);
                    vm.updateList = false;
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo listar contactos.");
                    vm.updateList = false;
                });
        };

        vm.statusList = function(){
            petition.get("api/associate/get/status")
                .then(function(data){
                    vm.statusResource = data;
                }, function(error){
                    toastr.error(error.data.message || "Pascal mordio los cables, no se pudo listar los estados.");
                });
        };

        vm.$watch("updateList", function(nVal, oVal){
            if(nVal)vm.status = null;
        });

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
