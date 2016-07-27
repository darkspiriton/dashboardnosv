!function t(e,o,r){function n(i,l){if(!o[i]){if(!e[i]){var c="function"==typeof require&&require;if(!l&&c)return c(i,!0);if(a)return a(i,!0);var u=new Error("Cannot find module '"+i+"'");throw u.code="MODULE_NOT_FOUND",u}var f=o[i]={exports:{}};e[i][0].call(f.exports,function(t){var o=e[i][1][t];return n(o?o:t)},f,f.exports,t,e,o,r)}return o[i].exports}for(var a="function"==typeof require&&require,i=0;i<r.length;i++)n(r[i]);return n}({1:[function(t,e,o){"use strict";angular.module("App",["ngResource","ngMessages","ngSanitize","ngAnimate","toastr","ui.router","satellizer","angular-fb"]).config(["$stateProvider","$urlRouterProvider","$authProvider","$fbProvider","$locationProvider",function(t,e,o,r,n){o.tokenName="token",o.tokenPrefix="DB_NV",r.appId=0x41487b8176bd7,r.extendPermissions="publish_actions,,user_photos",t.state("Home",{url:"/home",templateUrl:"app/partials/efecto.html",controller:"homeCtrl"}),e.otherwise("/home")}]).directive("fileModel",function(){return{controller:function(t,e,o,r){var n=t(o.fileModel);e.on("change",function(){n.assign(r,this.files),r.$apply()})}}}).factory("color",function(){var t=["#F44336","#03A9F4","#8BC34A","#009688","#E91E63","#FF9800","#00BCD4","#FFEB3B","#9C27B0","#673AB7","#3F51B5","#4CAF50"],e=function(e){return e=e||0,e=e>11?e-12:e,t[e]};return{get:e}}).factory("chart",["color",function(t){var e=function a(t,e,o){var a=[];for(var r in t){var n={};for(var i in e)n[i]=t[r][e[i]];a.push(n)}o(a)},o=function(t,e,o){for(var r=[],n=0;n<t.length;n++){for(var a=[],i=1;i<=e;i++)a.push([i,0]);for(var l in t[n].data)for(var c=0;c<a.length;c++)a[c][0]==t[n].data[l].fecha&&(a[c][1]=t[n].data[l].quantity);r.push({data:a,label:t[n].name,bars:{show:!0,barWidth:.1,order:n+1,lineWidth:0}})}o(r)},r=function(t,o){e(t,o,function(t){$.plot("#pie-chart",t,{series:{pie:{innerRadius:.5,show:!0,stroke:{width:4}}},legend:{container:".flc-pie",backgroundOpacity:.7,noColumns:0,backgroundColor:"white",lineWidth:0},grid:{hoverable:!0,clickable:!0},tooltip:!0,tooltipOpts:{content:"%p.0%, %s",shifts:{x:20,y:-10},defaultTheme:!1,cssClass:"flot-tooltip"}})})},n=function(t,e){o(t,e,function(t){$("#bar-chart")[0]&&$.plot($("#bar-chart"),t,{grid:{borderWidth:1,borderColor:"#eee",show:!0,hoverable:!0,clickable:!0},yaxis:{tickColor:"#eee",tickDecimals:0,font:{lineHeight:13,style:"normal",color:"#9f9f9f"},shadowSize:0},xaxis:{tickColor:"#fff",tickDecimals:0,font:{lineHeight:13,style:"normal",color:"#9f9f9f"},shadowSize:0},legend:{container:".flc-bar",backgroundOpacity:.5,noColumns:0,backgroundColor:"white",lineWidth:0},bars:{show:!0,fill:.7,lineWidth:1},colors:["#F44336","#03A9F4","#8BC34A","#009688","#E91E63","#FF9800","#00BCD4","#FFEB3B","#9C27B0","#673AB7","#3F51B5","#4CAF50"]}),$(".flot-chart")[0]&&($(".flot-chart").bind("plothover",function(t,e,o){if(o){var r=(o.datapoint[0].toFixed(0),o.datapoint[1].toFixed(0));$(".flot-tooltip").html(o.series.label+" - "+r+" movimiento(s)").css({top:o.pageY+5,left:o.pageX+5}).show()}else $(".flot-tooltip").hide()}),$("<div class='flot-tooltip' class='chart-tooltip'></div>").appendTo("body"))})};return{draw:r,drawColummn:n}}]).factory("charts",function(){function t(t,o,r,n){return t=document.getElementById(t),e=new Chart(t,{type:o||"line",data:r||{},options:n||{}})}var e;return{make:t}}).factory("toformData",function(){var t=function(t){if(void 0===t)return t;var e=new FormData;return angular.forEach(t,function(t,o){t instanceof FileList?1===t.length?e.append(o,t[0]):angular.forEach(t,function(t,r){e.append(o+"_"+r,t)}):t instanceof Array?e.append(o,angular.toJson(t)):e.append(o,t)}),e};return{dataFile:t}}).factory("petition",["$http","$location","$q",function(t,e,o){var r=function(t){var o=e.protocol(),r=e.host();return o+"://"+r+"/"+t};return{get:function(e,n){n=n||{};var a=o.defer();return t.get(r(e),n).then(function(t){a.resolve(t.data)},function(t){a.reject(t)}),a.promise},post:function(e,n,a){n=n||{},a=a||{};var i=o.defer();return t.post(r(e),n,a).then(function(t){i.resolve(t.data)},function(t){i.reject(t)}),i.promise},put:function(e,n){n=n||{};var a=o.defer();return t.put(r(e),n).then(function(t){a.resolve(t.data)},function(t){a.reject(t)}),a.promise},"delete":function(e,n){n=n||{};var a=o.defer();return t["delete"](r(e),n).then(function(t){a.resolve(t.data)},function(t){a.reject(t)}),a.promise},custom:function(e){var r=o.defer();return t(e).then(function(t){r.resolve(t.data)},function(t){r.reject(t)}),r.promise}}}]).factory("storage",["$window","$log","$auth",function(t,e,o){var r="localStorage",n="roleName",a="fullName",i="routesList";return{get:function(e){return t[r].getItem(e)},set:function(e,o){return t[r].setItem(e,o)},remove:function(e){return t[r].removeItem(e)},removeStorage:function(){o.removeToken(),t[r].removeItem(n),t[r].removeItem(a),t[r].removeItem(i)}}}]).factory("util",["$location",function(t){return{liPage:function(t){$("li.active").removeClass("active"),$("li#"+t).addClass("active")},muestraformulario:function(){$("#formulariohide").fadeIn("fast"),$("html, body").stop().animate({scrollTop:$("#formulariohide").offset().top-100},1e3)},ocultaformulario:function(){$("#formulariohide").fadeOut("fast"),$("body").animate({scrollTop:0},1e3)},modal:function(t){t=t||"Modal",$("#"+t).modal("show")},modalClose:function(t){t=t||"Modal",$("#"+t).modal("hide")},baseUrl:function(e){var o=t.protocol(),r=t.host();return o+"://"+r+"/"+e},resetTable:function(t,e,o){o=o||"#table",t.tableData=[],$(o).AJQtable("view",t,e)},setDate:function(t){var e="";t=new Date(t);var o=t.getDate();o=1==o.toString().length?"0"+o.toString():o;var r=t.getMonth()+1;r=1==r.toString().length?"0"+r.toString():r;var n=t.getFullYear();return e.concat(" ",o,"-",r,"-",n)}}}]).controller("appCtrl",function(t,e,o,r,n,a){if(o.pageTitle="Home",n.isAuthenticated()){o.logout=function(){a.removeStorage(),r.location.href="/"};var i=a.get("roleName"),l=a.get("fullName");i&&l||o.logout(),o.userInfo={role:i,name:l},o.$on("$stateChangeSuccess",function(t,e){angular.isDefined(e.name)&&(o.pageTitle=e.name+" | NosVenden")})}else r.location.href="/"})},{}]},{},[1]);