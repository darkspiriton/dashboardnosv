/**
 * Created by luizh on 13/06/2016.
 */
"use strict";
(function( $ ){
    var AJQactions2 = {};
    var view2 = function ($scope , $compile, tableData, tableConfig) {
        tableConfig || (tableConfig = {});

        var rowCompiler, oTable, acc = {};
        var App = {};

        // Datos de configuracion
        if(Object.keys(tableConfig).length > 1){
            App.columns = tableConfig.columns || [];
            App.buttons = tableConfig.buttons || [];
            App.data    = tableConfig.data || [];
            App.options = tableConfig.options || {};
        } else {
            App.columns = $scope.tableConfig.columns || [];
            App.buttons = $scope.tableConfig.buttons || [];
            App.data    = $scope.tableConfig.data || [];
            App.options = $scope.tableConfig.options || {};
        }

        App.rows    = tableData || $scope.tableData || [];


        // Conpilar en angular las filas
        rowCompiler = function(nRow, aData, iDataIndex) {
            var element, linker;
            linker = $compile(nRow);
            element = linker($scope);
            return element;
        };

        function searchObject(obj, prop) {
            if(typeof obj === 'undefined' || typeof prop === 'undefined') {
                return false;
            }

            var _index = prop.indexOf('.');
            if(_index > -1) {
                return searchObject(obj[prop.substring(0, _index)], prop.substr(_index + 1));
            }

            return obj[prop];
        }

        $.each(App.buttons, function(i , obj){
            if(obj.type == 'status'){
                $.each(obj.list, function(l , button){
                    AJQactions2[button.name] = {};
                    AJQactions2[button.name]['render'] = button.render;
                    AJQactions2[button.name]['event'] = button.name;
                    AJQactions2[button.name]['column'] = button.column;
                    if(typeof button.call_me == 'function') {
                        AJQactions2[button.name]['call_me'] = button.call_me;
                        AJQactions2[button.name]['run'] = function (i) {
                            var est = this.call_me(App.rows[i]);
                            if (!this.render.hasOwnProperty(est)) if (this.render.hasOwnProperty('fail')) est = 'fail'; else return '';
                            return `<a class="btn btn-xs ${this.render[est][1]}" ng-click="${this.event}(${i},$event)" style="min-width: 82px;" ${((this.render[est][2] === false) ? 'disabled="disabled"' : '')}>${this.render[est][0]}</a>`;
                        }
                    } else {
                        AJQactions2[button.name]['run'] = function (i) {
                            var est = searchObject(App.rows[i], this.column);
                            if (!this.render.hasOwnProperty(est)) if (this.render.hasOwnProperty('fail')) est = 'fail'; else return '';
                            return `<a class="btn btn-xs ${this.render[est][1]}" ng-click="${this.event}(${i},$event)" style="min-width: 82px;" ${((this.render[est][2] === false) ? 'disabled="disabled"' : '')}>${this.render[est][0]}</a>`;
                        }
                    }
                });
            }

            if(obj.type == 'actions'){
                $.each(obj.list, function(l , button){
                    var btns = '';
                    for (var x in button.render) {
                        btns = btns + `<a class="btn btn-xs ${button.render[x][2]}" ng-click="${button.render[x][1]}('{i}','$event')" style="min-width: 82px;">${button.render[x][0]}</a>`;
                    }
                    AJQactions2[button.name] = {};
                    AJQactions2[button.name]['render'] = btns;
                    AJQactions2[button.name]['run'] = function (i) {
                        return this.render.replace(new RegExp("{i}",'g'), i);
                    }
                });
            }

            if(obj.type == 'custom'){
                $.each(obj.list, function(l , button){
                    AJQactions2[button.name] = {};
                    AJQactions2[button.name]['render'] = button.template;
                    AJQactions2[button.name]['column'] = button.column;
                    if(typeof button.call_me == 'function') {
                        AJQactions2[button.name]['call_me'] = button.call_me;
                        AJQactions2[button.name]['run'] = function(i){
                            return this.call_me(App.rows[i], i, App.rows);
                        }
                    } else {
                    AJQactions2[button.name]['run'] = function (i) {
                        var template = this.render;
                        for(var y in this.column){
                                template = template.replace(`{${y}}`, searchObject(App.rows[i], this.column[y]));
                            }
                            return template;
                        }   
                    }
                });
            }

        });

        // Opciones de la tabla
        App.options = Object.assign(App.options, {
            fnCreatedRow: rowCompiler, 		// Al crear tabla conpilarla en angular
            aoColumns: App.columns                 // Columnas de tabla
        });

        // Inicializar la tabla
        if ( ! $.fn.DataTable.isDataTable( this[0] ) )
            oTable = $(this[0]).dataTable(App.options);
        else
            oTable = $( this[0] ).dataTable();

        // Limpiar registros
        oTable.fnClearTable();

        // Llenar de registros la tabla
        if (Object.keys(AJQactions2).length > 0){
            var data = [];
            $.each(App.rows, function(i , obj){
                var temp = [];
                $.each( App.data , function(x, val){
                    if (AJQactions2.hasOwnProperty(val)){
                        temp[x] = AJQactions2[val].run(i);
                    } else {
                        if ( val.constructor === Array)
                            temp[x] = (searchObject(obj,val[0])).substr(0, val[1]) + ' ...';
                        else
                            temp[x] = searchObject(obj,val);
                    }
                });
                data.push(temp);
            });
            if(data.length > 0)
                oTable.fnAddData(data);
        } else {
            var data = [];
            $.each(App.rows, function(i , obj){
                var temp = [];
                $.each( App.data , function(x, val){
                    if ( val.constructor === Array)
                        temp[x] = (searchObject(obj,val[0])).substr(0, val[1]) + ' ...';
                    else
                        temp[x] = searchObject(obj,val);
                });
                data.push(temp);
            });
            if(data.length > 0)
                oTable.fnAddData(data);
        }
    }

    function search2( busqueda ){
        $(this[0]).dataTable().fnFilter(busqueda);
    }

    function removeRow2 ( dom, callback ){
        var oTable = $(this[0]).dataTable();
        var element = $(dom).closest('tr');
        var row = $(element).get(0);
        var pos = oTable.fnGetPosition(row);
        element.fadeOut(750,function(){
            oTable.fnDeleteRow(pos);
        });
        callback();
    }

    var methods = {
        init : function() { console.log('indique accion') },
        view2 : view2,
        search2 : search2,
        removeRow2 : removeRow2
    };

    $.fn.AJQtable2 = function(methodOrOptions) {
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