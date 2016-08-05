angular.module("loginApp")
	.factory("loginService", ["$location","$auth","$http","$q", function(location, auth, http, q){

		var showError = function (message){
			$(".msg-error").text(message);
			$(".error").fadeIn("fast");
			$(".success").hide();
		};

		var showSuccsess = function (message){
			$(".msg-success").text(message);
			$(".success").fadeIn("fast");
		};

		var hideError = function hideError(message){
			$(".error").hide();
			$(".success").hide();
		};

		function baseUrl( URL ) {
		    var prot = location.protocol();
		    var host = location.host();
		    return prot + '://' + host + '/' + URL;
		}

		var redirect = function (){
		    var token = auth.getToken();
		    if (token){
		        $('#token').val(token);
		        $('#frm').submit();
		    }
		};

		var validateKey = function(){
			var deferred = q.defer();
			var token  = 'Bearer' + ' ' + auth.getToken();
			http.get( baseUrl('api/validate-key'), { headers : {'Authorization' : token }})
			    .then(function(response){
			        deferred.resolve();
			    });

			return deferred.promise;
		};

		var isAuthenticated = function(){
			return auth.isAuthenticated();
		};

		var login = function(user){
			hideError();
			var deferred = q.defer();
			auth.login(user)
			    .then(function() {
			        deferred.resolve();
			    })
			    .catch(function(error) {
			        deferred.reject(error.data);
			    });
			return deferred.promise;
		};

		var signup = function(user){
			hideError();
			var deferred = q.defer();
			auth.signup(user)
			    .then(function(response) {
			        deferred.resolve(response.data);
			    })
			    .catch(function(error) {
			        deferred.reject(error.data);
			    });
			return deferred.promise;
		};

		var contact = function(user){
			hideError();
			var deferred = q.defer();
			http.post(baseUrl("api/associate"), user)
			    .then(function(response) {
			        deferred.resolve(response.data);
			    })
			    .catch(function(error) {
			        deferred.reject(error.data);
			    });
			return deferred.promise;
		};

		return {
			isAuthenticated: isAuthenticated,
			validateKey: validateKey,
			redirect: redirect,
			login: login,
			showError: showError,
			hideError: hideError,
			showSuccsess: showSuccsess,
			signup: signup,
			contact: contact
		};
	}]);