angular.module('loginApp', ['ngResource', 'satellizer'])
    .config(["$authProvider", function($authProvider) {
        $authProvider.tokenName = "token";
        $authProvider.tokenPrefix = "DB_USC";
        $authProvider.signupUrl = "/auth/signup/usc";
    }]);