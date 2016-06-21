angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Publicidad Ventas', {
                url: '/Gestion-de-publicidad-ventas',
                templateUrl: 'app/partials/publicityJVE.html',
                controller : 'publicityJVECtrl'
            });
    })
    .controller('publicityJVECtrl', function($scope, $compile, $state, util, petition, toastr, $fb){

        util.liPage('publicityJVE');

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
            1: ['R. sociales','btn-info'],
            'fail': ['R. sociales','btn-info',false]
        };

        var a2 = {
            null: ['Publicar Facebook','btn-primary'],
            'fail': ['Publicar Facebook','btn-primary',false]
        };

        var s4 = {
            0: ['Espera','bgm-purple',false],
            1: ['Aprobado','bgm-green',false],
            2: ['Rechazado','bgm-red',false]
        };

        call_me = function (row) {
            if(row.process.type_process_id == 3){
                if(row.process.status == 1){
                    if(row.status == 0){
                        return 2;
                    } else if(row.status == 1) {
                        return 1;
                    }
                } else {
                    return 0;
                }
            }
            return -1;
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"title": "Fecha", "bSortable" : true, 'width': '110px'},
                {"title": "Nombre", "bSortable" : true},
                {"title": "Proveedor", "bSortable" : true, 'width': '1px'},
                {"title": "Color" , "bSearchable": true, 'width': '1px'},
                {"title": "Foto" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Froceso" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Estado" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Aprobar" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Redes" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Agregar" , "bSearchable": true,'bSortable':false, "width": '80px'},
                {"title": "Facebook" , "bSearchable": true,'bSortable':false, "width": '80px'}
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'process', column: 'process.type_process_id', render: s1},
                            { name: 'approve', column: 'process.type_process_id', render: s2},
                            { name: 'addSocials', render: a1,  call_me: call_me},
                            { name: 'publish_fb', column: 'facebookID',  render: a2},
                            { name: 'status', render: s4 , call_me: call_me}
                        ]
                    },
                    {
                        type: 'custom',
                        list: [
                            { name: 'photo', template: '<img width="150px" src="{0}" alt="{1}">', column: ['photo','product.name']}
                        ]
                    }
                ],
            data  	: 	['date','product.name','product.provider.name','product.color.name','photo','process','status','approve','socials_list','addSocials','publish_fb']
        };

        $scope.facebook = {};

        $scope.list = function(d) {
            $scope.updateList = true;
            var obj = { params: {}};
            if(typeof d !== 'undefined')
                obj.params.date = d;
            petition.get('api/sales/publicity', obj)
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

        /*
         *  Facebook events
         */

        $scope.listAlbums_FB = function () {
            $fb.albums().then(function (response) {
                $scope.albums = response.data;
            }, function (error) {
                toastr.error('Facebook dice: '+ error.message);
            });
        };

        $scope.createAlbums_FB = function (name) {
            $scope.albumButton = true;
            $fb.createAlbum({name: name}).then(function (response) {
                toastr.success('Se agrego el album a Facebook');
                $scope.listAlbums_FB();
                albumClear();
                publishClear();
            },function (error) {
                toastr.error('Facebook dice: '+ error.message);
                $scope.albumButton = false;
            });
        };

        $scope.publish = function (i, album_id, desc) {
            $scope.publishButton = true;
            var data = {};
            data.name = desc;
            data.url = $scope.tableData[i].photo;

            $fb.publish(album_id, data).then(function (response) {
                console.log(response);
                util.modalClose('Facebook');
                toastr.success('Se publico la foto');
                publishClear();
                petition.put('api/socials/' + $scope.tableData[i].id, { fb_id: response.id});
                $scope.list();
            }, function (error) {
                console.log(error);
                toastr.error('Facebook dice: '+ error.message);
                $scope.publishButton = false;
            });
        };

        $scope.activeNewAlbum = function () {
            $scope.newAlbum = !$scope.newAlbum;
            $scope.AlbumName = null;
        };

        $scope.publish_fb = function (i) {
            publishClear();
            $scope.index = i;
            $scope.listAlbums_FB();
            util.modal('Facebook');
        };
        
        $scope.facebook.me = function () {
            $fb.me().then(function (data) {
                console.log(data);
            }, function (error) {
                console.log(error);
            })
        };

        /*
         * End events
         */

        function publishClear(){
            $scope.album = null;
            $scope.publishName = null;
            $scope.publishButton = false;
        }

        function albumClear(){
            $scope.newAlbum = true;
            $scope.AlbumName = null;
            $scope.albumButton = false;
        }

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.newAlbum = true;
            $scope.social = {};
            $scope.list();
            $scope.listSocials();
        });
    });