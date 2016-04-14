angular.module('App')
    .controller('usersCtrl', function($scope, $compile, util){

        util.liPage('users');

        $scope.tableConfig 	= 	{
                                    columns :	[
                                                    {"sTitle": "id"},
                                                    {"sTitle": "Nombre"},
                                                    {"sTitle": "Apellidos"},
                                                    {"sTitle": "correo"},
                                                    {"sTitle": "Telefono"},
                                                    {"sTitle": "Direccion"},
                                                    {"sTitle": "Estado" ,"bSearchable": false , "bSortable" : false},
                                                    {"sTitle": "Accion" , "bSearchable": false , "bSortable" : false}
                                                ],
                                    actions	:  	[
                                                    ['state',   {
                                                                    0 : { txt : 'Inactivo' , cls : 'btn-danger' },
                                                                    1 : { txt : 'Acitvo' ,  cls : 'btn-success'} ,
                                                                }
                                                    ],
                                                    ['actions', [
                                                                    ['Editar', 'edit' ,'btn-primary'],
                                                                    ['Detalle' , 'detail', 'btn-info']
                                                                ]
                                                    ]
                                                ],
                                    data  	: 	['id','nom',['ape', 50],'cor','tel','dir','state','actions'],
                                    configStatus : 'est'
                                }


        $scope.tableData = [
                            {id: 1 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 2 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 3 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 4 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 5 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 1 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 2 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 3 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 4 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 5 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 1 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 2 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 3 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 4 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 5 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 1 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 2 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 3 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1},
                            {id: 4 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 0},
                            {id: 5 , nom: "luis" , ape: "Game Master", cor : "luis@gmail.com", tel: "555-5555", dir: "Av. Arequipa 269 Lince", est : 1}
                            ];
        $('#tab_users').AJQtable('view', $scope, $compile);
    });