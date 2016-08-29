angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('auxClient', {
                url: '/Gestion-de-clientes',
                templateUrl: 'app/partials/auxClient.html',
                controller : 'auxClientCtrl'
            });
    }])
    .controller('auxClientCtrl', ["$scope", "$compile", "$state", "util", "petition", "toastr",
        function($scope, $compile, $state, util, petition, toastr){

        util.liPage('auxClient');

        var actions = [           
            ['Eliminar', 'deleteClient' ,'bgm-red'],
            ['Editar', 'editClient' ,'bgm-blue'],
        ];

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Creación", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Email", "bSortable" : true},
                {"sTitle": "DNI", "bSortable" : true},
                {"sTitle": "Teléfono", "bSortable" : false},
                {"sTitle": "Dirección", "bSortable" : false},
                {"sTitle": "Referencia", "bSortable" : false},                              
                {"sTitle": "Acción" , "bSearchable": true}
            ],
            buttons	:
                [
                    {
                        type: 'actions',
                        list:  [
                            { name: 'actions', render: actions}
                        ]
                    }
                ],

            data  	: 	['created_at','name','email','dni','phone','address','reference','actions'],
            configStatus: 'status'
        };

        var actions2 = [           
            ['Recuperar', 'restoreClient' ,'bgm-red']
        ];

        var tableConfig2  =   {
            columns :   [
                {"sTitle": "Fecha Eliminación", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Email", "bSortable" : true},
                {"sTitle": "DNI", "bSortable" : true},
                {"sTitle": "Teléfono", "bSortable" : false},
                {"sTitle": "Dirección", "bSortable" : false},
                {"sTitle": "Referencia", "bSortable" : false},                              
                {"sTitle": "Acción" , "bSearchable": true}
            ],
            buttons :
                [                    
                    {
                        type: 'actions',
                        list: [
                            { name: 'actions', render: actions2}
                        ]
                    }
                ],
            data    :   ['deleted_at','name','email','dni','phone','address','reference','actions'],
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
            html:true
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxclient')
                .then(function(data){
                    $scope.tableData = data.clients;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };                

        $scope.cancelEditClient = function () {
            $scope.editImputClient=false;
            $scope.client=[];
            util.ocultaformulario();
        };

        $scope.new = function(){
            $scope.outfit = angular.copy($scope.outfitClear);
            $scope.productsView = [];
            util.muestraformulario();

        };

        $scope.client={};
        $scope.editClient = function(i){
            $scope.editImputClient=true;
            $scope.client.id=$scope.tableData[i].id;
            $scope.client.name=$scope.tableData[i].name;
            $scope.client.phone=$scope.tableData[i].phone;
            $scope.client.dni=$scope.tableData[i].dni;
            $scope.client.email=$scope.tableData[i].email;
            $scope.client.address=$scope.tableData[i].address;
            $scope.client.reference=$scope.tableData[i].reference;                        
            util.muestraformulario();
            
        };

        $scope.showNewClientModal = function(){
            $scope.newClient = {};
            util.modal("newClientModal");
        };

        $scope.saveClient = function(client){
            petition.post("api/auxclient", client)
                .then(function(data){
                    toastr.success(data.message);
                    util.modalClose("newClientModal");
                    $scope.all();                    
                }, function(error){
                    toastr.error("Huy Huy dice: " + error.data.message);
                });
        };

        $scope.updateClient = function(client){   
            petition.put("api/auxclient/"+client.id,client)
                .then(function(data){
                    toastr.success(data.message);
                    $scope.all();
                    util.ocultaformulario();
                }, function(error){
                    toastr.error("Huy Huy dice: " + error.data.message);
                });
        };

        $scope.deleteClient = function(i){
            var id = $scope.tableData[i].id;
            petition.delete("api/auxclient/"+id)
                .then(function(data){
                    toastr.success(data.message);                    
                    $scope.all();                    
                }, function(error){
                    toastr.error("Huy Huy dice: " + error.data.message);
                });
        };

        $scope.update = function(){
            if($scope.searchView == 1){
                $scope.list();
            }else if($scope.searchView == 2){
                $scope.list2();
            }
        };

        $scope.all = function(){
            $scope.list();
            $scope.list2();
        };

        $scope.restoreClient = function(i){
            var id = $scope.tableData2[i].id;
            petition.post("/api/auxclient/delete/restore/"+id)
                .then(function(data){
                    toastr.success(data.message);                    
                    $scope.all();
                }, function(error){
                    toastr.error("Huy Huy dice: " + error.data.message);
                });
        };

        /*******************************************************CONTROLADOR DELETE*******************************************************/
        $scope.list2 = function() {
            $scope.updateList = true;
            petition.get('api/auxclient/get/delete/')
                .then(function(data){
                    $scope.tableData2 = data.clients;
                    $('#table2').AJQtable2('view2', $scope, $compile,$scope.tableData2,tableConfig2);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        angular.element(document).ready(function(){
            $scope.outfit = angular.copy($scope.outfitClear);
            $scope.productsView = [];
            $scope.searchView=1;
            $scope.list();

            /***********DELETE**********/
            $scope.list2();
        });
    }]);
