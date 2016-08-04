angular.module('loginApp')
    .controller('loginCtrl', ["$scope", "loginService", function(vm, ls){

        var msg = "Unos gatos ninja robaron algunos cables, intentelo mas tarde por favor.";

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
        			ls.showError(error.message || msg);
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
        			ls.showError(error.message || msg);
        		});
        };

        vm.contact = function() {
        	vm.formSdg = true;
        	ls.contact(vm.contactUser)
        		.then(function(data){
        			ls.showSuccsess(data.message);
        			$("#contact").fadeOut("fast");
        			vm.loginBtn();
        			vm.formSdg = false;
        		}, function(error){
        			vm.formSdg = false;
        			ls.showError(error.message || msg);
        		});
        };

        vm.enterCase = function (e) {
            if(e.keyCode == 13 || e.charCode == 13)
                vm.login();
        };

        vm.loginBtn = function(){
        	$("#contact").fadeOut(100, function(){
                $("#register").fadeOut(100, function(){
        		    $("#login").fadeIn(100);
                });
        	});
        };

        vm.registerBtn = function(m){
        	if(m)vm.modalMaster = true;
        	vm.newUser = {};
        	vm.registerForm.$setPristine();
        	vm.registerForm.$setUntouched();
        	$("#contact").fadeOut(100, function(){
                $("#login").fadeOut(100, function(){
            		$("#register").fadeIn(100);
            		$(".error").hide();
            		$(".success").hide();
                });
        	});
        };

        vm.contactBtn = function(m){
        	if(m)vm.modalMaster = true;
        	vm.contactUser = {};
        	vm.contactForm.$setPristine();
        	vm.contactForm.$setUntouched();
        	$("#register").fadeOut(100, function(){
                $("#login").fadeOut(100, function(){
            		$("#contact").fadeIn(100);
            		$(".error").hide();
            		$(".success").hide();
                });
        	});
        };

    }]);
