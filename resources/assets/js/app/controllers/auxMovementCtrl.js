angular.module('App')
	.config(["$stateProvider", function($stateProvider) {
		$stateProvider
			.state('Movimientos', {
				url: '/Generar-movimientos',
				templateUrl: 'app/partials/auxMovement.html',
				controller : 'auxMovementCtrl'
			});
	}])

	.controller('auxMovementCtrl', ["$scope", "$compile", "$state", "$log", "$filter", "util", "petition", "toformData", "toastr",
		function($scope, $compile, $state, $log, $filter, util, petition, toformData, toastr){

		/**
		 * Initial var
		 */

		$scope.data = {};

		util.liPage('movimientos');

		$scope.tableConfig  =   {
			columns :   [
				{"sTitle": "Codigo", "bSortable" : true},
				{"sTitle": "Nombre", "bSortable" : true},
				{"sTitle": "Color", "bSortable" : true},
				{"sTitle": "Talla", "bSortable" : true},
				{"sTitle": "Precio Venta (S/.)", "bSortable" : true},
				{"sTitle": "Precio" , "bSearchable": false , "sWidth": "80px"},
				{"sTitle": "Acción" , "bSearchable": false , "sWidth": "190px"}
			],
			actions :   [
				['status',   {
					0 : { txt : 'regular' , cls : 'bgm-green', dis : false },
					1 : { txt : 'liquidacion' ,  cls : 'btn-info',dis: false}
				}
				],
				['actions', [
					['Agregar', 'addProduct' ,'bgm-teal'],
					['x codigo', 'otherProduct' ,'bgm-purple']
				]
				]
			],
			data    :   ['cod','name','color','size','price','status','actions'],
			configStatus : 'status'
		};

		var alertConfig = {
			title: "¿Esta seguro?",
			text: "",
			type: "warning",
			showCancelButton: true,
			customClass: "product-out",
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "SI",
			cancelButtonColor: "#212121",
			cancelButtonText: "CANCELAR",
			closeOnConfirm: true,
			html: true
		};

		var alertConfirmation = {
			title: "Se genero salida de los productos",
			text: "",
			customClass: "product-out",
			type: "success",
			html: true
		};

		$scope.productsClear = [];

		$scope.list = function() {
			$scope.updateList = true;
			petition.get('api/auxmovement')
				.then(function(data){
					$scope.tableData = data.products;
					$('#table').AJQtable('view', $scope, $compile);
					$scope.updateList = false;
				}, function(error){
					toastr.error('Huy huy dice: ' + error.data.message);
					util.resetTable($scope,$compile);
					$scope.updateList = false;
				});
		};

		$scope.addProduct = function(ind){
			addProductHelper($scope.tableData[ind]);
		};

		$scope.addProduct2 = function(ind){
			$scope.prdTemp.id = $scope.codes[ind].id;
			$scope.prdTemp.cod =  $scope.codes[ind].cod;

			addProductHelper($scope.prdTemp);

			$scope.otherCod = null;
			util.modalClose('codes');
		};

		$scope.otherProduct = function (i){
			if ($scope.anadir){
				$scope.codes = [];
				petition.get('api/auxmovement/get/codes' , {params: {id: $scope.tableData[i].id}})
					.then(function(data){
						$scope.codes = data.codes;
						productTemp(i);
						util.modal('codes');
					}, function(error){
						toastr.error('Huy huy dice: ' + error.data.message);
						$scope.updateList = false;
					});
			}
		};

		/*
		 |
		 |  Helpers for validate products    
		 |
		 */

		function productTemp(i){
			$scope.prdTemp = null;
			$scope.prdTemp = angular.copy($scope.tableData[i]);
			$scope.prdTemp.id = null;
			$scope.prdTemp.cod =  null;
		}

		function addProductHelper(product){
			var product = angular.copy(product);
			var count = 0;
			for(var i in  $scope.dataProducts){
				if($scope.dataProducts[i].id == product.id){
					count++;
					break;
				}
			}

			if (count == 0 && $scope.anadir){

				$scope.dataProducts.push({id: product.id, discount:0, priceOut:product.price});

				product.discount = 0;
				product.preciofinal = product.price - product.discount;
                product.backupPrice = product.price;
				$scope.products.push(angular.copy(product));

				toastr.success('se añadio');
			}
		}

		/*
		 |  END
		 */
		$scope.data.codOrder=null;
		$scope.data.requestDate=null;
		$scope.data.shipmentDate=null;

		function setUpper(string){
			var upper = ' '+string.toString().toUpperCase();
			return upper;
		}

		function confirmationTable(){
			var template = `<table class="table table-bordered w-100 table-attr text-center">
								<thead>
								<tr>
								    <h5><label> Vendedor(a) : </label>${(function(){
								    	for(var i in $scope.sellers){
								    		if($scope.sellers[i].id == $scope.data.seller_id)
								    			return $scope.sellers[i].name; 
								    	}
								    	return '';
								    })()}</h5>
								    <h5><label> Cliente : </label>${$scope.client.name + ' - DNI: ' + $scope.client.dni}</h5>
									<h5><label> Código : </label>${setUpper($scope.data.codOrder)}</h5>                                            
									<h5><label> F. Pedido : </label>${util.setDate($scope.data.requestDate)}</h5>                                           
									<h5> <label> F. Salida : </label>${util.setDate($scope.data.shipmentDate)}</h5>                                                                      
								<tr>
								<tr>
									<th>Cod</th>
									<th>Nombre</th>
									<th>Talla</th>
									<th>Color</th>
									<th>P. Venta</th>
									<th>Desc.</th>
									<th>P. Final</th>
								</tr>
								</thead>
								<tbody>
									${(function(){
										var rowList = "";
										for(var i in $scope.products){
											var row = "<tr>";
											row += `<td>${$scope.products[i].cod}</td>`;
											row += `<td>${$scope.products[i].name}</td>`;
											row += `<td>${$scope.products[i].size}</td>`;
											row += `<td>${$scope.products[i].color}</td>`;
											row += `<td>${$scope.products[i].price}</td>`;
											row += `<td>${$scope.products[i].discount}</td>`;
											row += `<td>${$scope.products[i].preciofinal}</td>`;
											row += `</tr>`;
											rowList += row;
										}
										return rowList;
									})()}
								</tbody>
							</table>`;
			return template;
		}

		$scope.submit = function(data) {    
			alertConfig.title = '¿Todo es correcto?';
			alertConfig.text = confirmationTable();
				swal(alertConfig,
					function () {
						data.products =  $scope.dataProducts;
						petition.post('api/auxmovement/out', data)
							.then(function (data) {
								toastr.success(data.message);
								confirmnationModal(data);
								$scope.list();
								util.ocultaformulario();
								$scope.anadir = false;
								$scope.formSubmit = false;
							}, function (error) {
								toastr.error('Huy huy dice: ' + error.data.message);
								$scope.formSubmit = false;
							});
					});
		};

		function confirmnationModal(data){
			alertConfirmation.text = confirmationOuts(data.products, data.movements);
			swal(alertConfirmation);
		}

		/**
		 *  Template para confirmnacion de productos en salida
		 */
		 var confirmationOuts = function(products, movements){
			var tdList = "";

			for(var i in movements)
			{
				for(var j in products)
				{
					if(movements[i].product_id == products[j].id)
					{
						movements[i].product_cod = products[j].cod;
						movements[i].product_name = products[j].name;
						movements[i].product_size = products[j].size.name;
						movements[i].product_color = products[j].color.name;
						if(products[j].settlement){
							movements[i].price = Number(products[j].settlement.price).toFixed(2);
						} else {
							movements[i].price = Number(products[j].cost_provider + products[j].utility).toFixed(2);
						}
						movements[i].price_final = Number(movements[i].price - movements[i].discount).toFixed(2);
						break;
					}
				}
				tdList +=`<tr>
							<td>${movements[i].product_cod}</td>
							<td>${movements[i].product_name}</td>
							<td>${movements[i].product_size}</td>
							<td>${movements[i].product_color}</td>
							<td>${movements[i].price}</td>
							<td>${movements[i].discount}</td>
							<td>${movements[i].price_final}</td>
						</tr>`;
			}

			var template = `<table class="table table-attr table-bordered text-center w-100">
										<thead>
										<tr>
											<h5><label> Vendedor(a) : </label>${(function(){
												for(var i in $scope.sellers){
													if($scope.sellers[i].id == $scope.data.seller_id)
														return $scope.sellers[i].name; 
												}
												return '';
											})()}</h5>
											<h5><label> Cliente : </label>${$scope.client.name + ' - DNI: ' + $scope.client.dni}</h5>
											<h5><label> Código : </label>${setUpper($scope.data.codOrder)}</h5>
											<h5><label> F. Pedido : </label>${util.setDate($scope.data.requestDate)}</h5>
											<h5> <label> F. Salida : </label>${util.setDate($scope.data.shipmentDate)}</h5>
										<tr>
										<tr>
											<th>Cod</th>
											<th>Nombre</th>
											<th>Talla</th>
											<th>Color</th>
											<th>Precio</th>
											<th>Desc.</th>
											<th>Total</th>
										</tr>
										</thead>
										<tbody>
										<tr>                                            
											${tdList}
										</tr>
										</tbody>
									</table>
								</div>`;

			return template;
		 };



		$scope.preciofinal=function (i){
			if($scope.products[i].discount < 0){
				$scope.products[i].discount = 0;
                if($scope.products[i].price < 0){
                    $scope.products[i].price=0;
                }	
                $scope.products[i].preciofinal=$scope.products[i].price;			
				return ;
			} else if($scope.products[i].discount === undefined){
                $scope.products[i].discount=0;
				$scope.products[i].preciofinal = $scope.products[i].price;
				return ;
			} else if($scope.products[i].price<0){
                $scope.products[i].price=0;
                return ;
            }else if($scope.products[i].price === undefined){
                $scope.products[i].price=0;
                return ;
            }

			$scope.products[i].preciofinal = Math.round(($scope.products[i].price - $scope.products[i].discount)*100)/100;
            if($scope.products[i].preciofinal<0){
                $scope.products[i].price=$scope.products[i].backupPrice;
                $scope.products[i].preciofinal=$scope.products[i].price;
                $scope.products[i].discount=0;
                toastr.error('El valor minimo del Precio de Final deberia ser: '+'S/.'+0);                
            }

			$scope.dataProducts[i].discount = angular.copy($scope.products[i].discount);
            $scope.dataProducts[i].priceOut = angular.copy($scope.products[i].price);
			
		};

		function removeDetail(){
			$scope.data = {};
			$scope.clientSearchText = null;
			$scope.client = null;
		}



		$scope.removeProduct = function(i){
			$scope.dataProducts.splice(i,1);
			$scope.products.splice(i,1);
		};

        $scope.restoreProduct = function(i){
            console.log($scope.products[i].backupPrice);
            $scope.products[i].price=$scope.products[i].backupPrice;
            $scope.products[i].discount=0; 
            $scope.preciofinal(i);           
        };

		$scope.cancel = function () {
			$scope.anadir = false;            
			removeDetail();
			resetProduct();
			util.ocultaformulario();
		};

		$scope.new = function(){
			$scope.anadir = true;
			removeDetail();
			resetProduct();
			util.muestraformulario();
			$scope.listPositiontion();
		};

		function resetProduct(){
			$scope.dataProducts = angular.copy($scope.productsClear);
			$scope.products = angular.copy($scope.productsClear);
		}


		/**
		 *	Update for add seller in order
		 */

		 $scope.listSeller = function(){
		 	petition.get('api/user/get/sellers')
		 		.then(function(data){
		 			$scope.sellers = data.sellers;
		 		}, function(error){
		 			toastr.error('Huy huy dice: ' + error.data.message);
		 		});
		 };

		/**
		 * END
		 */


		/**
		 *	Client search
		 */

		$scope.clientSearch = function(text){
		  	$scope.listView = true;
		  	$scope.data.client_id = null;
		  	$scope.client = null;
		  	petition.get('api/auxclient/' + text)
		  		.then(function(data){
		  			$scope.clients = data;
		  		}, function(error){
		  			toastr.error('Huy huy dice: ' + error.data.message);
		  		});
		};

		$scope.selectClient = function(i) {
		  	$scope.client = $scope.clients[i];
		  	if($scope.client.dni == "No tiene")$scope.client.dni = null;
		  	if($scope.client.email == "No tiene")$scope.client.email = null;
		  	$scope.data.client_id = $scope.clients[i].id;
		  	$scope.listView = false;
		  };

		$scope.listPositiontion = function(){
		    var pos = $('#clientSearchText').offset();
		    $("#list").css({top: pos.top - 35, left: pos.left});
		};

		$scope.showNewClientModal = function(){
			$scope.newClient = {};
		  	util.modal("newClientModal");
		};

		$scope.saveClient = function(client){
            client.status_id=1;
			petition.post("api/auxclient", client)
				.then(function(data){
					toastr.success(data.message);
					util.modalClose("newClientModal");
				}, function(error){
					toastr.error("Huy Huy dice: " + error.data.message);
				});
		};

		var clientOriginal;

		$scope.btnEditClient = function(){
			$scope.editImputClient = true;
			clientOriginal = angular.copy($scope.client);
		};

		$scope.cancelEditClient = function(){
			$scope.editImputClient = false;
			$scope.client = clientOriginal;
		};

		$scope.editClient = function(client){

            // client.status_id=1;
            var oldClient = clientOriginal;
            // console.log([client,oldClient]);
            // console.log(client.address == oldClient.address);
            // console.log(client.reference == oldClient.reference);
            // console.log(client.dni == oldClient.dni);
            // console.log(client.email == oldClient.email);
            if(client.address !== oldClient.address || client.reference !== oldClient.reference ||
               client.dni !== oldClient.dni || client.email !== oldClient.email){
                client.status_id=1;
            }

			petition.put("api/auxclient/" + client.id, client)
				.then(function(data){
					toastr.success(data.message);
					$scope.editImputClient = false;
				}, function(error){
					toastr.error("Huy Huy dice: " + error.data.message);
				});
		};

		/**
		 * END
		 */
		angular.element(document).ready(function(){
			resetProduct();
			$scope.list();

			$scope.listSeller();
			$scope.data = {};
		});
	}]);
