angular.module('App')
    .config(["$stateProvider", function($stateProvider) {
        $stateProvider
            .state('Employees', {
                url: '/Adminitracion-de-empleados',
                templateUrl: 'app/partials/employees.html',
                controller : 'employeesCtrl'
            });
    }])
    .controller('employeesCtrl', ["$scope", "$compile", "$log", "util", "petition", "toastr",
        function($scope, $compile, $log, util, petition, toastr){

        util.liPage('employees');

        $scope.tableConfig 	= 	{
            columns :	[
                {"sTitle": "Nombre de empleado", "bSortable" : true},
                {"sTitle": "Area", "bSortable" : true},
                {"sTitle": "Sexo" ,"bSearchable": false , "bSortable" : false },
                {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false , "sWidth": "190px"}
            ],
            actions	:  	[
                ['actions', [
                    ['ver', 'view' ,'btn-info'],
                    ['Editar', 'edit' ,'btn-primary']
                ]
                ]
            ],
            data  	: 	['name','area.name','sex','actions'],
            configStatus : 'status'
        };

        var alertConfig = {
            title: "¿Todo correcto?",
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
            area_id: null,
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

        $scope.months = [
            {id: '01', name: 'Enero'},
            {id: '02', name: 'Febrero'},
            {id: '03', name: 'Marzo'},
            {id: '04', name: 'Abril'},
            {id: '05', name: 'Mayo'},
            {id: '06', name: 'Junio'},
            {id: '07', name: 'Julio'},
            {id: '08', name: 'Agosto'},
            {id: '09', name: 'Septiembre'},
            {id: '10', name: 'Octubre'},
            {id: '11', name: 'Nomviembre'},
            {id: '12', name: 'Diciembre'}
        ];

        $scope.list = function() {
            petition.get('api/employee')
                .then(function(data){
                    $scope.tableData = data.employees;
                    $('#table').AJQtable('view', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listAreas = function() {
            $scope.areas = [];
            petition.get('api/employee/get/roles')
                .then(function(data){
                    $scope.areas = data.areas;
                }, function(error){
                    console.log(error);
                    toastr.error('Uyuyuy dice: ' + error.data.message);
                });
        };

        $scope.view = function( ind ){
            $scope.employeeDetail = {};
            var id = $scope.tableData[ind].id;
            petition.get('api/employee/' + id)
                .then(function(data){
                    $scope.employeeDetail = data.employee;
                    $scope.month = null;
                    $scope.CostsView();
                    util.modal();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.costFromMonth = function (month) {
            var date = new Date();
            var date2 = new Date(date.getFullYear() + '-'+month+'-15');
            $scope.CostsView(date2);
        };

        $scope.CostsView = function(date){
            date || (date = new Date());
            $scope.costoxmin = "";
            $scope.costoxhor = "";
            petition.get('api/employee/get/indicator', {params: {employee_id: $scope.employeeDetail.id, date:date}})
                .then(function(data){
                    $scope.costoxmin = data.costoMinuto;
                    $scope.costoxhor = data.costoHora;
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        $scope.edit = function( ind ){
            var id = $scope.tableData[ind].id;
            petition.get('api/employee/' + id)
                .then(function(data){
                    date_format(data.employee);
                    util.muestraformulario();
                }, function(error){
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                });
        };

        function date_format(employee){
            var days_pivot = [];
            resetDays();
            for(var i in employee.days){
                var obj = {};
                obj.day_id = employee.days[i].pivot.day_id;
                obj.start_time = new Date('1970-01-01 ' + employee.days[i].pivot.start_time);
                obj.end_time = new Date('1970-01-01 ' + employee.days[i].pivot.end_time);
                days_pivot.push(obj);
                $scope.days[$scope.getNameDay(obj.day_id)] = true;
            }
            employee.days = days_pivot;
            $scope.empleado = employee;
            $scope.typeH = "2";
        }

        function resetDays(){
            for(var i in $scope.days){
                $scope.days[i] = false;
            }
        }

        $scope.submit = function () {
            swal(alertConfig,
                function () {
                    var method = ( $scope.empleado.id ) ? 'PUT' : 'POST';
                    var url = ( method == 'PUT') ? util.baseUrl('api/employee/' + $scope.empleado.id) : util.baseUrl('api/employee');
                    var config = {
                        method: method,
                        url: url,
                        data: $scope.empleado
                    };
                    $scope.formSubmit = true;
                    petition.custom(config).then(function (data) {
                        toastr.success(data.message);
                        $scope.formSubmit = false;
                        $scope.list();
                        util.ocultaformulario();
                    }, function (error) {
                        toastr.error('Ups ocurrio un problema: ' + error.data.message);
                        $scope.formSubmit = false;
                    });
                });
        };

        $scope.cancel = function () {
            resetForm();
            util.ocultaformulario();
        };

        function resetForm(){
            $scope.typeH = null;
            $scope.start_time = null;
            $scope.end_time = null;
            $scope.empleado = angular.copy($scope.empleadoClear);
            $scope.days = angular.copy($scope.daysClear);
        }

        $scope.new = function(){
            resetForm();
            util.muestraformulario();
        };

        $scope.$watch('typeH', function(i){
            if (i == '1'){
                $scope.viewIrregular = false;
                $scope.viewRegular = true;
                $scope.globalDay();
            } else if(i == '2'){
                $scope.viewIrregular = true;
                $scope.viewRegular = false;
            } else {
                $scope.viewIrregular = false;
                $scope.viewRegular = false;
            }
        });

        $scope.globalDay = function(){
            for(var i in $scope.empleado.days){
                $scope.empleado.days[i].start_time = $scope.start_time;
                $scope.empleado.days[i].end_time = $scope.end_time;
            }
        };

        // Watch days

        $scope.$watch('days.lunes', function(new_val){
            if(new_val)
                addOrDelDay('add',1);
            else
                addOrDelDay('del',1);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.martes', function(new_val){
            if(new_val)
                addOrDelDay('add',2);
            else
                addOrDelDay('del',2);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.miercoles', function(new_val){
            if(new_val)
                addOrDelDay('add',3);
            else
                addOrDelDay('del',3);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.jueves', function(new_val){
            if(new_val)
                addOrDelDay('add',4);
            else
                addOrDelDay('del',4);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.viernes', function(new_val){
            if(new_val)
                addOrDelDay('add',5);
            else
                addOrDelDay('del',5);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.sabado', function(new_val){
            if(new_val)
                addOrDelDay('add',6);
            else
                addOrDelDay('del',6);

            if($scope.typeH == 1)$scope.globalDay();
        });

        $scope.$watch('days.domingo', function(new_val){
            if(new_val)
                addOrDelDay('add',7);
            else
                addOrDelDay('del',7);

            if($scope.typeH == 1)$scope.globalDay();
        });

        function addOrDelDay(opc, day){
            for(var i in $scope.empleado.days){
                if (day == $scope.empleado.days[i].day_id){
                    if(opc == 'del')
                        return $scope.empleado.days.splice(i,1);
                    else
                        return;
                }
            }
            if(opc == 'add'){
                $scope.empleado.days.splice(day-1,0, {day_id: day, start_time: null, end_time: null});
            }
        }

        $scope.getNameDay = function (i){
            var days = ['','lunes','martes','miercoles','jueves','viernes','sabado','domingo']
            return days[i];
        };

        // End days

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.empleado = angular.copy($scope.empleadoClear);
            $scope.days = angular.copy($scope.daysClear);
            $scope.list();
            $scope.listAreas();
        });
    }]);