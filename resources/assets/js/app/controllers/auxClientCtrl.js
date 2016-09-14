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

        var status3={
            'salida' : ['Salida','btn-danger', false],
            'Retornado' : ['Retorno','bgm-lime', false],
            'Vendido' : ['Vendido','bgm-Green', false],
        };

        var tableConfig3 =   {
            columns :   [
            {"sTitle": "Fecha de envio", "bSortable" : true},               
            {"sTitle": "Codigo Producto", "bSortable" : true},
            {"sTitle": "Producto", "bSortable" : true},
            {"sTitle": "Talla", "bSortable" : false},
            {"sTitle": "Color", "bSortable" : false},
            {"sTitle": "Estado", "bSortable" : false},                              
            {"sTitle": "Precio de Compra", "bSortable" : false},                                              
            ],
            buttons :
            [                    
            {
                type: 'status',
                list: [
                { name: 'status', column:'status',render: status3}
                ]
            }
            ],
            data    :   ['date_shipment','product.cod','product.name','product.size.name','product.color.name','status','total_price'],
        };      

        $scope.months = [
        {id:1, name:'Enero'},
        {id:2, name:'Febrero'},
        {id:3, name:'Marzo'},
        {id:4, name:'Abril'},
        {id:5, name:'Mayo'},
        {id:6, name:'Junio'},
        {id:7, name:'Julio'},
        {id:8, name:'Agosto'},
        {id:9, name:'Setiembre'},
        {id:10, name:'Octubre'},
        {id:11, name:'Noviembre'},
        {id:12, name:'Diciembre'},
        ];

        $scope.years = [2016,2017];

        $scope.status = [
        {id:'salida', name:'Salida'},
        {id:'Retornado', name:'Retorno'},
        {id:'Vendido', name:'Vendido'},
        {id:'Todo', name:'Todo'},
        ];

        var alertConfig = {
            title: "¿Esta seguro?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "SI",
            cancelButtonColor: "#212121",
            cancelButtonText: "CANCELAR",
            closeOnConfirm: false,
            closeOnCancel: false,
            html:true
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/auxclient')
            .then(function(data){
                $scope.tableData = data.clients;
                $scope.listClients=$scope.tableData;
                console.log($scope.listClients);
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
            
            if ($scope.tableData[i].dni != "No tiene"){
                $scope.client.dni=$scope.tableData[i].dni;
            } else {
                $scope.client.dni=null;
            }

            if ($scope.tableData[i].email != "No tiene"){
                $scope.client.email=$scope.tableData[i].email;
            } else {
                $scope.client.email=null;
            }    

            $scope.client.address=$scope.tableData[i].address;
            $scope.client.reference=$scope.tableData[i].reference;   
            $scope.close();                     
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
            alertConfig.title = '¿Esta seguro de eliminar este cliente?';
            alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
            <thead>
            <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>DNI</th>
            <th>Teléfono</th>                                            
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>${$scope.tableData[i].name}</td>
            <td>${$scope.tableData[i].email}</td>
            <td>${$scope.tableData[i].dni}</td>
            <td>${$scope.tableData[i].phone}</td>
            </tr>
            </tbody>
            </table>`;
            swal(alertConfig,function(isConfirm){
                if (isConfirm) {  
                    var id = $scope.tableData[i].id;
                    petition.delete("api/auxclient/"+id)
                    .then(function(data){
                        toastr.success(data.message);                    
                        $scope.all();
                        swal("Eliminado!", "Se elimino el cliente correctamente", "success");                     
                    }, function(error){
                        toastr.error("Huy Huy dice: " + error.data.message);
                    });                  
                } else {  
                  swal("Cancelado", "No se modifico nada :)", "error"); 
              }

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
            alertConfig.title = '¿Esta seguro desea restaurar este cliente?';
            alertConfig.text = `<table class="table table-bordered w-100 table-attr text-center">
            <thead>
            <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>DNI</th>
            <th>Teléfono</th>                                            
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>${$scope.tableData2[i].name}</td>
            <td>${$scope.tableData2[i].email}</td>
            <td>${$scope.tableData2[i].dni}</td>
            <td>${$scope.tableData2[i].phone}</td>
            </tr>
            </tbody>
            </table>`;
            swal(alertConfig,function(isConfirm){
                if(isConfirm){
                    var id = $scope.tableData2[i].id;
                    petition.post("/api/auxclient/delete/restore/"+id)
                    .then(function(data){                            
                        toastr.success(data.message);                                                
                        $scope.all();
                        swal("Restaurado!", "Se restauro el cliente correctamente", "success");
                    }, function(error){
                        toastr.error("Huy Huy dice: " + error.data.message);
                    });
                }else{
                    swal("Cancelado", "No se restauro nada :)", "error"); 
                }
                
            });

        };
        $scope.vacio=null;

        /*******************************************************CONTROLADOR DELETE*******************************************************/
        
        $scope.list2 = function() {
            $scope.updateList = true;
            petition.get('api/auxclient/get/delete/')
            .then(function(data){
                $scope.tableData2 = data.clients;  
                var count = Object.keys($scope.tableData2).length;                  
                if(count === 0){
                    $scope.vacio=true;
                }else{
                    $scope.vacio=false;
                }
                $('#table2').AJQtable2('view2', $scope, $compile,$scope.tableData2,tableConfig2);
                $scope.updateList = false;
            }, function(error){
                console.log(error);
                toastr.error('Huy Huy dice: ' + error.data.message);
                $scope.updateList = false;
            });
        };

        /*******************************************************CONTROLADOR HISTORIAL*******************************************************/

        $scope.list3=function(){
            $scope.updateList3 = true;
            petition.get('api/auxclient/get/movement/'+$scope.client_id , {params: {status: $scope.status_id, month: $scope.month, year: $scope.year }})
            .then(function(data){
                $scope.tableData3 = data.movements;
                var count = Object.keys($scope.tableData3).length;                  
                if(count === 0){
                    $scope.vacio3=true;
                }else{
                    $scope.vacio3=false;
                }
                $('#table3').AJQtable2('view2', $scope, $compile,$scope.tableData3,tableConfig3);
                $scope.updateList3=false;
            },function(error){
                toastr.error('Huy Huy dice: ' + error.data.message);
                $scope.updateList3 = false;
            });
        };

        $scope.filter=function(i){
            if(i==4){
                $scope.searchView2=4;
            }else if(i == 5){
                $scope.searchView2=5;
            }
            $scope.status_id=null;
            $scope.month=null;
            $scope.year=null;
        };

        $scope.updateLinkFacebook = function(){
            util.modal("newLinkFacebook");
        };

        $scope.linkChange = function(){
            $scope.showSearchLink=true;            
        };

        $scope.linkSearch = function(){
            $scope.showLinkInfo=true;
        };

        $scope.close = function(){
            $scope.showSearchLink=false;
            $scope.showLinkInfo=false;  
            $scope.test1=true;          
        };

        $scope.download =  function (){
            $scope.btnDownload = true;
            petition.post('api/auxclient/filter/get/client/download',null,{responseType:'arraybuffer'})
            .then(function(data){
                var date = new Date().getTime();
                var name = date + '-reporte-de-cliente.xls';
                var file = new Blob([data],{type : 'application/vnd.ms-excel; charset=UTF-8'});
                saveAs(file,name);
                $scope.btnDownload = false;                
            },function(error){
                toastr.error("El archivo es demasiado grande, no se pudo descargar");
                $scope.btnDownload = false;
            });
        };

        angular.element(document).ready(function(){
            $scope.productsView = [];
            $scope.searchView=1;
            $scope.list();
            $scope.btnDownload = false;

            /***********DELETE**********/            
            $scope.list2();
        });
    }]);
