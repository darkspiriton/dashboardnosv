(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

angular.module('loginApp').controller('LoginCtrl', ["$scope", "$location", "$auth", "$window", "toastr", "$http", function ($scope, $location, $auth, $window, toastr, $http) {

    var redirect = function redirect() {
        var token = $auth.getToken();
        if (token) {
            $('#token').val(token);
            $('#frm').submit();
        }
    };

    var baseUrl = function baseUrl(URL) {
        var prot = $location.protocol();
        var host = $location.host();
        return prot + '://' + host + '/' + URL;
    };

    if ($auth.isAuthenticated()) {
        // authToken = 'Bearer' y authHeader = 'Authorization' son de SatellizerConfig
        var data = {};
        var token = 'Bearer' + ' ' + $auth.getToken();
        $http.get(baseUrl('api/validate-key'), { headers: { 'Authorization': token } }).then(function (response) {
            console.log(response.data.message);
            redirect();
        }, function (error) {
            console.log(error.data.message);
        });
    }

    $scope.login = function () {
        $auth.login($scope.user).then(function () {
            toastr.success('Se a identificado correctamente!');
            redirect();
        }).catch(function (error) {
            toastr.error(error.data.message);
        });
    };

    $scope.Myonkeyup = function (e) {
        if (e.keyCode == 13 || e.charCode == 13) $scope.login();
    };
}]);

},{}]},{},[1]);

//# sourceMappingURL=loginCtrl.js.map
