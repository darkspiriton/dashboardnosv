angular.module('App')
.config(["$stateProvider", function($stateProvider) {
    $stateProvider
    .state('auxClient', {
        url: '/Gestion-de-clientes',
        templateUrl: 'app/partials/auxClient.html',
        controller : 'auxClientCtrl as vm'
    });
}])
.controller('auxClientCtrl', ["$scope", "$compile", "$state", "util", "petition", "toastr","apiclient",
    function($scope, $compile, $state, util, petition, toastr, apiclient){

        util.liPage('auxClient');

        var actions = [           
            ['Eliminar', 'deleteClient' ,'bgm-red'],
            ['Editar', 'vm.editClient' ,'bgm-blue'],
        ];

        var s1 = {
            1: ['Potencial','bgm-green',false],
            2: ['Interesado','bgm-purple',false],
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Creación", "bSortable" : true},
                {"sTitle": "Nombre", "bSortable" : true},
                {"sTitle": "Email", "bSortable" : true},
                {"sTitle": "DNI", "bSortable" : true},
                {"sTitle": "Teléfono", "bSortable" : false},
                {"sTitle": "Dirección", "bSortable" : false},
                {"sTitle": "Referencia", "bSortable" : false},                              
                {"sTitle": "Estado", "bSortable" : true},                              
                {"sTitle": "Acción" , "bSearchable": true}
            ],
            buttons	:
            [
                {
                    type: 'status',
                    list: [
                        {name: 'status_id', column:'status_id', render: s1},
                    ]
                },
                {
                    type: 'actions',
                    list:  [
                    { name: 'actions', render: actions}
                    ]
                }
            ],

            data  	: 	['created_at','name','email','dni','phone','address','reference','status_id','actions'],
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

        //Variables
        var vm=this; 
        vm.months = [
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
        vm.years = [2016,2017];
        vm.status = [
            {id:'salida', name:'Salida'},
            {id:'Retornado', name:'Retorno'},
            {id:'Vendido', name:'Vendido'},
            {id:'Todo', name:'Todo'},
        ];

        //Funciones
        vm.cancelEditClient = cancelEditClient; 
        vm.list3 = list3;
        vm.editClient = editClient;
        vm.changeStateClient = changeStateClient;
        vm.showNewClientModal = showNewClientModal;
        vm.saveClient = saveClient;
        vm.download = download;

        function list() {
            vm.updateList = true;
            apiclient.index().then(successIndex).catch(rejectIndex);            
        }

        function successIndex(response){
            $scope.tableData = vm.listClients = response.clients;
            $('#table').AJQtable2('view2', $scope, $compile);
            vm.updateList = false;
        }

        function rejectIndex(response){
            toastr.error('Huy Huy dice: ' + response.data.message);
            vm.updateList = false;
        }               

        function cancelEditClient() {
            $scope.editImputClient=false;
            $scope.editImputClientI=false;
            $scope.client={};
            util.ocultaformulario();
        }

        function editClient(i){
            $scope.client={};
            if ($scope.tableData[i].status_id==1){
                $scope.potencial=true;
                $scope.editImputClient=true;
                $scope.client.status_id=$scope.tableData[i].status_id;
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
            } else if($scope.tableData[i].status_id==2){
                $scope.potencial=false;
                $scope.editImputClientI=true;
                $scope.client.status_id=$scope.tableData[i].status_id;
                $scope.client.id=$scope.tableData[i].id;
                $scope.client.name=$scope.tableData[i].name;
                $scope.client.phone=$scope.tableData[i].phone;
                $scope.client.status_id=$scope.tableData[i].status_id;
                $scope.close();
                util.muestraformulario();                
            }            
        }

        function changeStateClient(client) {
            $scope.potencial=true;
            $scope.editImputClient=true;
            $scope.editImputClientI=false;
            $scope.client.status_id=1;
        }

        function showNewClientModal(){
            $scope.newClient = {};
            $scope.newClientI = {};
            util.modal("newClientModal");
        }

        function saveClient(status,client){
            apiclient.saveClient(status,client).then(successSaveClient).catch(rejectSaveClient);
        }

        function successSaveClient(response){
            toastr.success(response.message);
            util.modalClose("newClientModal");
            $scope.all(); 
        }

        function rejectSaveClient(response){
            toastr.error("Huy Huy dice: " + response.data.message);
        }

        $scope.changeErrorPhone = function(phone){
            if(phone === undefined ){
                $scope.errorPhone=false;            
            }else if(phone.length == 9 ){
                $scope.errorPhone=true;
            }else{
                $scope.errorPhone=false;
            }
        };

       $scope.changeErrorName = function(name){              
            if(name === undefined ){
                $scope.errorName=false;
            }else if(name.length >= 1){
                $scope.errorName=true;
            }else{
                $scope.errorName=false;
            }
        };

        $scope.changeErrorAddress = function(address){              
            if(address === undefined ){
                $scope.errorAddress=false;
            }else if(address.length >= 1){
                $scope.errorAddress=true;
            }else{
                $scope.errorAddress=false;
            }
        };

        $scope.changeErrorReference = function(reference){              
            if(reference === undefined ){
                $scope.errorReference=false;
            }else if(reference.length >= 1){
                $scope.errorReference=true;
            }else{
                $scope.errorReference=false;
            }
        };

        function errorClear(){
            $scope.errorName=false;
            $scope.errorPhone=false;
            $scope.errorAddress=false;
            $scope.errorReference=false;
        }


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
        $scope.updateClientI = function(client){   
            petition.put("api/auxclient/update/clientI/"+client.id,client)
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
                list();
            }else if($scope.searchView == 2){
                list2();
            }
        };

        $scope.all = function(){
            cancelEditClient();
            errorClear();
            list();
            list2();
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
        
        function list2() {
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
        }

        /*******************************************************CONTROLADOR HISTORIAL*******************************************************/

        function list3(){
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
        }

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
            errorClear();
            $scope.showSearchLink=false;
            $scope.showLinkInfo=false;  
            $scope.test1=true;          
        };

        $scope.selectClient = function(i){
            errorClear();
            $scope.searchClient=i;
        };        

        function download(){
            vm.btnDownload  = true;
            apiclient.download().then(successDownload).catch(rejectDownload);
        }

        function successDownload(data){
            var date = new Date().getTime();
            var name = date + '-reporte-de-cliente.xls';
            var file = new Blob([data],{type : 'application/vnd.ms-excel; charset=UTF-8'});
            saveAs(file,name);
            vm.btnDownload = false;
        }

        function rejectDownload(error){
            toastr.error("El archivo es demasiado grande, no se puedo descargar")
            vm.btnDownload = false;
        }

        // $scope.download =  function (){
        //     $scope.btnDownload = true;
        //     petition.post('api/auxclient/filter/get/client/download',null,{responseType:'arraybuffer'})
        //     .then(function(data){
        //         var date = new Date().getTime();
        //         var name = date + '-reporte-de-cliente.xls';
        //         var file = new Blob([data],{type : 'application/vnd.ms-excel; charset=UTF-8'});
        //         saveAs(file,name);
        //         $scope.btnDownload = false;                
        //     },function(error){
        //         toastr.error("El archivo es demasiado grande, no se pudo descargar");
        //         $scope.btnDownload = false;
        //     });
        // };

        angular.element(document).ready(function(){
            $scope.productsView = [];
            $scope.searchView=1;
            list();
            $scope.btnDownload = false;
  
            /***********DELETE**********/            
            list2();
        });
    }]);
