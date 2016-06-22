angular.module('App')
    .config(function($stateProvider) {
        $stateProvider
            .state('Facebook', {
                url: '/Estadistica-de-publico-de-fotos',
                template: '<div class="card" >'+
                '    <div class="card-header bgm-blue">'+
                '        <h2>Stock general de productos por proveedor</h2>'+
                '        <button ng-disabled="updateList" class="btn bgm-green btn-float waves-effect btnLista" ng-click="list()"'+
                '                data-trigger="hover" data-toggle="popover" data-placement="top" data-content="Pulse para actualizar los registros"'+
                '                title="" data-original-title="Actualizar"><i class="md md-sync"></i></button>'+
                '    </div>'+
                '    <div class="card-body card-padding table-responsive">'+
                '        <div class="col-sm-12">'+
                '            <table id="table" class="table table-bordered table-striped w-100"></table>'+
                '        </div><br>'+
                '    </div><br>'+
                '</div>',
                controller : 'facebookPublicityCtrl'
            });
    })
    .controller('facebookPublicityCtrl', function($scope, $compile, $log, util, petition, toastr, $fb){

        util.liPage('Facebook');

        $scope.tableConfig 	= 	{
            columns :	[
                {"title": "Fecha", "bSortable" : true, 'width': '110px'},
                {"title": "Nombre", "bSortable" : true},
                {"title": "Proveedor", "bSortable" : true, 'width': '1px'},
                {"title": "Color" , "bSearchable": true, 'width': '1px'},
                {"title": "Foto" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "likes" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "comentarios" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "copartido" , "bSearchable": true,'bSortable':false, 'width': '1px'},
                {"title": "Link" , "bSearchable": true,'bSortable':false, 'width': '1px'},
            ],
            buttons	:
                [
                    {
                        type: 'custom',
                        list: [
                            { name: 'photo', template: '<img width="150px" src="{0}" alt="{1}">', column: ['picture','product.name']},
                            { name: 'link', template: '<a href="{0}" target="_blank" class="btn btn-xs btn-primary">ir a facebook</a>', column: ['link']}
                        ]
                    }
                ],
            data  	: 	['date','product.name','product.provider.name','product.color.name','photo','likes','comments','shares','link']
        };



        $scope.list = function() {
            $scope.updateList = true;
            petition.get('api/publicity/get/facebook')
                .then(function(data){
                    console.log(data);
                    $scope.publicities = data.publicities;
                    $scope.listFacebook(data.ids);
                }, function(error){
                    console.log(error);
                    toastr.error('Ups ocurrio un problema: ' + error.data.message);
                    $scope.updateList = false;
                });
        };

        $scope.listFacebook = function(ids){
            var data = {};
            data.ids = ids;
            data.fields = "link,picture,source,shares,likes.summary(true){id},comments.summary(true){id}";
            $fb.collection('/', data).then(function (response) {
                $scope.facebookData = response;
                mergeInfo($scope.publicities, $scope.facebookData);
            }, function (message, error) {
                console.log(arguments);
                toastr.error('Facebook dice: ' + message);
            });
        };
        
        function mergeInfo(server, facebook) {
            var rows = [];
            console.log(facebook);
            $.each(server, function (i, data) {
                var obj = data;
                var face = facebook[data.facebookID];
                obj.likes = face.likes?face.likes.summary.total_count:0;
                obj.comments = face.comments?face.comments.summary.total_count:0;
                obj.shares = face.shares?face.shares.count:0;
                obj.link = face.link;
                obj.picture = face.picture;
                rows.push(obj);
            });
            $scope.tableData = rows;
            $('#table').AJQtable2('view2', $scope, $compile);
            $scope.updateList = false;
        }


        angular.element(document).ready(function(){
            util.resetTable($scope,$compile);
            $scope.product = angular.copy($scope.productClear);
            $scope.list();
        });
    });