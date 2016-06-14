angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Publicidad Ventas', {
                url: '/Gestion-de-publicidad-ventas',
                templateUrl: 'app/partials/publicityJVE.html',
                controller : 'publicityJVECtrl'
            });
    })
    .controller('publicityJVECtrl', function($scope, $compile, $state, util, petition, toastr){

        util.liPage('publicity');

        var s1 = {
            1: ['Proceso','btn-danger',false],
            2: ['Retoque','bgm-teal',false],
            3: ['Enviado','bgm-indigo',false],
            4: ['aprobado','bgm-green',false]
        };

        var s2 = {
            3: ['ver','btn-info'],
            4: ['ver - descargar','btn-info'],
            'fail': ['aprovar','bgm-green',false]
        };

        var a1 = {
            4: ['R. sociales','btn-info'],
            'fail': ['R. sociales','btn-info',false]
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"title": "Fecha", "bSortable" : true, 'width': '80px'},
                {"title": "Nombre", "bSortable" : true},
                {"title": "Proveedor", "bSortable" : true, 'width': '1px'},
                {"title": "Color" , "bSearchable": true, 'width': '1px'},
                {"title": "foto" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "proceso" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Aprobar" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Redes" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Acción" , "bSearchable": true,'bSortable':false, "width": '80px'}
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'process', column: 'process.type_process_id', render : s1},
                            { name: 'approve', column: 'process.type_process_id', render : s2},
                            { name: 'addSocials', column: 'process.type_process_id', render: a1}
                        ]
                    },
                    {
                        type: 'custom',
                        list: [
                            { name: 'photo', template: '<img width="150px" src="{0}" alt="{1}">', column: ['photo','product.name']}
                        ]
                    }
                ],
            data  	: 	[['product.created_at',10],'product.name','product.provider.name','product.color.name','photo','process','approve','socials_list','addSocials'],
            configStatus : 'process.type_process_id'
        };

        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/sales/publicity')
                .then(function(data){
                    $scope.tableData = data.publicities;
                    $('#table').AJQtable2('view2', $scope, $compile);
                    $scope.updateList = false;
                }, function(error){
                    var msg = error.data.message?error.data.message:'no te puedo atender';
                    toastr.error('Huy Huy dice: ' + msg);
                    $scope.updateList = false;
                });
        };

        $scope.approve = function (i) {
            $scope.publicity = angular.copy($scope.tableData[i]);
            if($scope.publicity.process.type_process_id != 4) {
                $scope.status = false;
            }else{
                $scope.status = true;
            }

            util.modal();
        };

        $scope._approve = function (opc, id) {
            petition.post('api/sales/publicity', { opc: opc, id: id})
                .then(function(data){
                    $scope.list();
                    toastr.success(data.message);
                    util.modalClose();
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };


        $scope.listSocials = function() {
            petition.get('api/socials/')
                .then(function(data){
                    $scope.socials = data.socials;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };
        
        $scope.addSocials = function (i) {
            $scope.social.publicity_id = angular.copy($scope.tableData[i].id);
            util.modal('Socials');
        };

        $scope.submit = function () {
            petition.post('api/socials', $scope.social)
                .then(function(data){
                    $scope.list();
                    toastr.success(data.message);
                    util.modalClose('Socials');
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.status = true;
            $scope.social = {};
            $scope.list();
            $scope.listSocials();
        });
    });