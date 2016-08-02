(function($){
	$(document).on("ready", function(){
		$("#register-btn").on("click",function(){
			$("#login").fadeOut("fast", function(){
				$("#register").fadeIn("fast");
			});
		});
		$("#login-btn").on("click",function(){
			$("#register").fadeOut("fast", function(){
				$("#login").fadeIn("fast");
			});
		});
	});
})(jQuery);