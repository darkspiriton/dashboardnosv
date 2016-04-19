(function( $ ){

	var view = function ($scope , $compile) {

		var data , options, rowCompiler, cols, actions, acc = new Object();

		// Datos de configuracion
		var config = {};
		var table;
		cols 	= $scope.tableConfig.columns || [];
		actions 	= $scope.tableConfig.actions || [];
		data 	= $scope.tableConfig.data || [];
		config.configStatus  = ($scope.tableConfig.configStatus)?$scope.tableConfig.configStatus: 'sta';
		$scope.tableData || ($scope.tableData = [])

		// Conpilar en angular las filas 
		rowCompiler = function(nRow, aData, iDataIndex) {
			var element, linker;
			linker = $compile(nRow);
			element = linker($scope);
			return nRow = element;
		};

		for (i in actions) {
			if ( actions[i][0]  === 'actions' ) {
	            acc['dataA'] =  actions[i][1];
				acc['actions'] = function(ind){
					var btns = ''
					for (x in acc.dataA){
						btns = btns + '<button class="btn btn-xs '+ acc.dataA[x][2] +'" ng-click="'+ acc.dataA[x][1] +'(' + ind + "," +"$event" + ')" style="width: 82px;">'+ acc.dataA[x][0] +'</button>';
					}
					return btns;
				}
	        } else if (! acc.hasOwnProperty('actions'))
	        	acc['actions'] = function(){ return '' }
	        
	        if (actions[i][0]  === 'status' ) {
	            acc['dataS'] =  actions[i][1];
				acc['status'] = function(ind, est){
					return "<button class='btn btn-xs " + acc.dataS[est].cls + "' ng-click='status("+ ind + ','+ '$event' + ")' style='width: 82px;' "+ ((acc.dataS[est].dis === false)?'disabled="disabled"':'') + " >" + acc.dataS[est].txt + "</button>";
				}
	        } else if (! acc.hasOwnProperty('status'))
	        	acc['status'] = function(){ return '' }
		};

		// Opciones de la tabla
		options = {
			fnCreatedRow: rowCompiler, 		// Al crear tabla conpilarla en angular
			aoColumns: cols                 // Columnas de tabla
		};

		// Inicializar la tabla
		if ( ! $.fn.DataTable.isDataTable( this[0] ) ) 
		  	oTable = $(this[0]).dataTable(options);
		else
			oTable = $( this[0] ).dataTable();

		// Limpiar registros
		oTable.fnClearTable();

		// Llenar de registros la tabla
		if (actions.length > 0){
				$.each($scope.tableData, function(i , obj){
					var temp = [];
					var est = obj[config.configStatus];
					$.each( data , function(x, val){
						if (val == 'actions'){
							temp[x] = acc.actions(i);
						} else if (val == 'status') {
							temp[x] = acc.status(i, est);
						} else {
							if ( val.constructor === Array)
								temp[x] = (obj[val[0]]).substr(0, val[1]) + ' ...';
							else
								temp[x] = obj[val];
						}
					});
					oTable.fnAddData(temp);
				});
		} else {
			$.each($scope.tableData, function(i , obj){
				var temp = []
				$.each( data , function(x, val){
					if ( val.constructor === Array)
						temp[x] = (obj[val[0]]).substr(0, val[1]);
					else
						temp[x] = obj[val];
				});
				oTable.fnAddData(temp);
			});
		}
	}

	function search( busqueda ){
		$(this[0]).dataTable().fnFilter(busqueda);
	}

	function removeRow ( dom, callback ){
		var oTable = $(this[0]).dataTable();
		var element = $(dom).closest('tr');
		var row = $(element).get(0);
		var pos = oTable.fnGetPosition(row);
		element.fadeOut(750,function(){
			oTable.fnDeleteRow(pos);
		});
		callback();
	};

    var methods = {
        init : function() { console.log('indique accion') },
        view : view,
        search : search,
		removeRow : removeRow
    };

    $.fn.AJQtable = function(methodOrOptions) {
        if ( methods[methodOrOptions] ) {
            return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {
            // Default to "init"
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  methodOrOptions + ' no existe en AngularJQtable' );
        }
    };

})( jQuery );