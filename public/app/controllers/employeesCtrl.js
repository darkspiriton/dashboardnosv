angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Employees', {
                url: '/Adminitracion-de-empleados',
                templateUrl: 'app/partials/employees.html',
                controller : 'employeesCtrl'
            });
    })
    .controller('employeesCtrl', function($scope, $compile, $log, util, petition, toastr){

        util.liPage('employees');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre de empleado", "bSortable" : true},
                {"sTitle": "sexo", "bSortable" : true},
                {"sTitle": "Rol" ,"bSearchable": false , "bSortable" : false },
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['actions', [
                    ['ver', 'view' ,'btn-info'],
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['name','sexo','area','actions'],
            configStatus : 'status'
        };

        //nombre
        //sexo
        //area
        //dias - reg -no reg - HORA     ******
        //tiempo almuerzo
        //sueldo

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

        $scope.empleadoClear = {
            id: null,
            name: null,
            sex: null,
            area: null,
            days: [],
            break: null,
            salary: null
        };

        $scope.daysClear = {
            lunes: false,
            martes: false,
            miercoles: false,
            jueves: false,
            viernes: false,
            sabado: false,
            domingo: false
        };

        $scope.list = function() {
            petition.get('api/user')
                .then(function(data){
                    $scope.tableData = data.users;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.view = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/user/' + id)
                .then(function(data){
                    $scope.userDetail = data.user;
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        //$scope.edit = function( ind ){
        //    var id = $scope.tableData[ind].id;
        //    petition.get('api/user/' + id)
        //        .then(function(data){
        //            $scope.empleado = data.user;
        //            $scope.empleado.password = '**********';
        //            util.muestraformulario();
        //        }, function(error){
        //            toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        });
        //};

        //$scope.submit = function () {
        //    var method = ( $scope.empleado.id )?'PUT':'POST';
        //    var url = ( method == 'PUT')? util.baseUrl('api/user/' + $scope.empleado.id): util.baseUrl('api/user');
        //    if ( $scope.empleado.password == '**********' )
        //        $scope.empleado.password = null;
        //    var config = {
        //        method: method,
        //        url: url,
        //        params: $scope.empleado
        //    };
        //    $scope.formSubmit=true;
        //    petition.custom(config).then(function(data){
        //        toastr.success(data.message);
        //        $scope.formSubmit=false;
        //        $scope.list();
        //        util.ocultaformulario();
        //    }, function(error){
        //        toastr.error('Ups ocurrio un problema: ' + error.data.message);
        //        $scope.formSubmit=false;
        //    });
        //};

        $scope.cancel = function () {
            $scope.empleado = angular.copy($scope.empleadoClear);
            $scope.days = angular.copy($scope.daysClear);
            util.ocultaformulario();
        };

        $scope.cancel2 = function () {
            console.log($scope.empleado);
        };

        $scope.new = function(){
            $scope.empleado = angular.copy($scope.empleadoClear);
            util.muestraformulario();
        };

        $scope.typeHorary = function(i){
            if (i == '1'){
                $scope.viewIrregular = false;
                $scope.viewRegular = true;
                $scope.globalDay();
            } else if(i == '2'){
                $scope.viewIrregular = true;
                $scope.viewRegular = false;
            }
        };

        $scope.globalDay = function(){
            for(var i in $scope.empleado.days){
                $scope.empleado.days[i].timeIni = $scope.timeIni;
                $scope.empleado.days[i].timeFin = $scope.timeFin;
            }
        };

        // Watch days

        $scope.$watch('days.lunes', function(new_val){
            if(new_val)
                addOrDelDay('add',1,'lunes');
            else
                addOrDelDay('del',1);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.martes', function(new_val){
            if(new_val)
                addOrDelDay('add',2,'martes');
            else
                addOrDelDay('del',2);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.miercoles', function(new_val){
            if(new_val)
                addOrDelDay('add',3,'miercoles');
            else
                addOrDelDay('del',3);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.jueves', function(new_val){
            if(new_val)
                addOrDelDay('add',4,'jueves');
            else
                addOrDelDay('del',4);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.viernes', function(new_val){
            if(new_val)
                addOrDelDay('add',5,'viernes');
            else
                addOrDelDay('del',5);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.sabado', function(new_val){
            if(new_val)
                addOrDelDay('add',6,'sabado');
            else
                addOrDelDay('del',6);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.domingo', function(new_val){
            if(new_val)
                addOrDelDay('add',7,'domingo');
            else
                addOrDelDay('del',7);

            if($scope.typeH == 1)$scope.globalDay();
        });

        function addOrDelDay(opc, day, name){
            for(var i in $scope.empleado.days){
                if (day == $scope.empleado.days[i].id){
                    if(opc == 'del')
                        return $scope.empleado.days.splice(i,1);
                }
            }
            if(opc == 'add'){
                $scope.empleado.days.splice(day-1,0, {id: day, name:name, timeIni: null, timeFin: null});
            }
        }

        // End days

        angular.element(document).ready(function(){
            $scope.empleado = angular.copy($scope.empleadoClear);
            $scope.days = angular.copy($scope.daysClear);
            $scope.areas = [
                {id : 1 , name : 'Administración'},
                {id : 2 , name : 'Sistemas'},
                {id : 3 , name : 'Publicidad'},
                {id : 4 , name : 'Ventas'}
            ];

            $scope.list();
        });
    });