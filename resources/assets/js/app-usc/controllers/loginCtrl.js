angular.module('loginApp')
    .controller('loginCtrl', ["$scope", "loginService", function(vm, ls){

    	if(ls.isAuthenticated())
    		ls.validateKey().then(function(){
    			ls.redirect();
    		});

        vm.login = function() {
        	vm.formSdg = true;
        	ls.login(vm.user)
        		.then(function(){
        			vm.formSdg = false;
        			ls.redirect();
        		}, function(error){
        			vm.formSdg = false;
        			ls.showError(error.message || "Usuario y/o contraseña incorrecta");
        		});
        };

        vm.signup = function() {
        	vm.formSdg = true;
        	ls.signup(vm.newUser)
        		.then(function(data){
        			ls.showSuccsess(data.message);
        			vm.loginBtn();
        			vm.formSdg = false;
        		}, function(error){
        			vm.formSdg = false;
        			ls.showError(error.message || "Unos gatos ninja robaron algunos cables, intentelo mas tarde por favor.");
        		});
        };

        vm.contact = function() {
        	vm.formSdg = true;
        	ls.signup(vm.contactUser)
        		.then(function(data){
        			ls.showSuccsess(data.message);
        			$("#contact").fadeOut("fast");
        			vm.loginBtn();
        			vm.formSdg = false;
        		}, function(error){
        			vm.formSdg = false;
        			ls.showError(error.message || "Unos gatos ninja robaron algunos cables, intentelo mas tarde por favor.");
        		});
        };

        vm.enterCase = function (e) {
            if(e.keyCode == 13 || e.charCode == 13)
                vm.login();
        };

        vm.loginBtn = function(){
        	$("#register").fadeOut("fast", function(){
        		$("#login").fadeIn("fast");
        	});
        };

        vm.registerBtn = function(m){
        	if(m)$("#modal-info").click();
        	vm.newUser = {};
        	vm.registerForm.$setPristine();
        	vm.registerForm.$setUntouched();
        	$("#login").fadeOut("fast", function(){
        		$("#register").fadeIn("fast");
        		$(".error").hide();
        		$(".success").hide();
        	});
        };

        vm.contactBtn = function(m){
        	if(m)$("#modal-info").click();
        	vm.newUser = {};
        	vm.contactForm.$setPristine();
        	vm.contactForm.$setUntouched();
        	$("#login").fadeOut("fast", function(){
        		$("#contact").fadeIn("fast");
        		$(".error").hide();
        		$(".success").hide();
        	});
        };

        angular.element(document).ready(function(){
        	$("#modal-info").on("click", function(){
        		$(".info").fadeOut("fast");
        	});
        });

    }]);
