angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Publicidad', {
                url: '/Gestion-de-publicidad',
                templateUrl: 'app/partials/publicity.html',
                controller : 'publicityCtrl'
            });
    })
    .controller('publicityCtrl', function($scope, $compile, $state, util, petition,toformData, toastr){

        util.liPage('publicity');

        var s1 = {
            1: ['Proceso','btn-danger',false],
            2: ['Retoque','bgm-teal',false],
            3: ['Enviado','bgm-indigo',false],
            4: ['Aprobado','bgm-green',false]
        };

        var s2 = {
            2: ['Ver / Subir Imagen','btn-info'],
            3: ['Ver / Subir Imagen','btn-info'],
            4: ['Ver','btn-info'],
            'fail': ['Ver / Subir Imagen','btn-info',false]
        };

        var s3 = {
            1: ['Siguiente','btn-primary'],
            2: ['Siguiente','btn-primary'],
            3: ['Siguiente','btn-primary',false],
            'fail': ['Siguiente','btn-primary',false]
        };

        var s4 = {
            0: ['Espera','bgm-purple',false],
            1: ['Aprobado','bgm-green',false],
            2: ['Rechazado','bgm-red',false]
        };

        $scope.tableConfig 	= 	{
            columns :	[
                {"title": "Fecha", "bSortable" : true, 'width': '110px'},
                {"title": "Nombre", "bSortable" : true},
                {"title": "Proveedor", "bSortable" : true, 'width': '1px'},
                {"title": "Color" , "bSearchable": true, 'width': '1px'},
                {"title": "Foto" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Proceso" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Estado" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Vista" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Redes" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Detalle" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Acción" , "bSearchable": true,'bSortable':false, "width": '1px'}
            ],
            buttons	:
                [
                    {
                        type: 'status',
                        list:  [
                            { name: 'process', column: 'process.type_process_id', render : s1},
                            { name: 'view', column: 'process.type_process_id', render : s2},
                            { name: 'nextProcess', column: 'process.type_process_id', render: s3},
                            { name: 'status', render: s4 , call_me: function (row) {
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
                            }}
                        ]
                    },
                    {
                        type: 'actions',
                        list: [
                            { name: 'detail', render: [['Detalle','detail','btn-info']]}
                        ]
                    },
                    {
                        type: 'custom',
                        list: [
                            { name: 'photo', template: '<img width="150px" src="{0}" alt="{1}">', column: ['photo','product.name']}
                        ]
                    }
                ],
            data  	: 	['date','product.name','product.provider.name','product.color.name','photo','process','status','view','socials_list','detail','nextProcess'],
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
            closeOnConfirm: true
        };

        $scope.list = function(d) {
            $scope.updateList = true;
            var obj = { params: {}};
            if(typeof d !== 'undefined')
                obj.params.date = d;
            petition.get('api/publicity', obj)
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

        $scope.view = function (i) {
            $scope.index = i;
            petition.get('api/publicity/' + $scope.tableData[i].id)
                .then(function(data){
                    $scope.publicity = data.publicity;
                    util.modal('Photo');
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.detail = function (i) {
            petition.get('api/publicity/relation/' + $scope.tableData[i].product_id)
                .then(function(data){
                    $scope.detailProduct.outfits = data.outfits;
                    $scope.detailProduct.liquidation = data.liquidation;
                    util.modal('product');
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.upload = function (id) {
            if($scope.image == null) return toastr.error('Ninguna imagen seleccionada');
                var config = {
                    method: 'POST',
                    url: 'api/publicity/' + id +'/upload',
                    data: toformData.dataFile({ img: $scope.image}),
                    headers: {'Content-Type': undefined}
                };
                $scope.uploading = true;
                petition.custom(config).then(function(data){
                    $scope.image = null;
                    $('#formImage').trigger("reset");
                    toastr.success(data.message);
                    $scope.uploading = false;
                    $scope.list();
                    $scope.view($scope.index);
                }, function(error){
                    toastr.error('Huy Huy dice: ' + error.data.message);
                    $scope.uploading = false;
                });

        };

        //
        $scope.listProduct = function() {
            petition.get('api/auxproduct/get/uniques')
                .then(function(data){
                    $scope.products = data.products;
                }, function(error){
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };

        $scope.ListCodes = function(name) {
            if(name == undefined)return;
            petition.get('api/auxproduct/get/uniques/'+ name +'/codes')
                .then(function(data){
                    $scope.codes = data.codes;
                }, function(error){
                    $scope.codes = [];
                    console.log(error);
                    toastr.error('Huy Huy dice: ' + error.data.message);
                });
        };
        //

        $scope.nextProcess = function(i) {
            alertConfig.title = '¿Desea seguir al siguiente proceso?';
            swal(alertConfig ,
                function() {
                    var id = $scope.tableData[i].id;
                    petition.put('api/publicity/' + id)
                        .then(function(data){
                            $scope.list();
                            toastr.success(data.message);
                        }, function(error){
                            console.log(error);
                            toastr.error('Huy Huy dice: ' + error.data.message);
                        });
                });
        };

        $scope.submit = function () {
            alertConfig.title = '¿Todo es correcto?';
            swal(alertConfig ,
                function() {
                    $scope.formSubmit=true;
                    petition.post('api/publicity', { product_id: $scope.product_id})
                        .then(function(data){
                            toastr.success(data.message);
                            $scope.formSubmit=false;
                            $scope.list();
                            util.modalClose();
                        }, function(error){
                            toastr.error('Huy Huy dice: ' + error.data.message);
                            $scope.formSubmit=false;
                        })
                });
        };

        $scope.new = function(){
            util.modal();
        };

        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.detailProduct = {};
            $scope.list();
            $scope.listProduct();
        });
    });