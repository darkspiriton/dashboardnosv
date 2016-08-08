<!DOCTYPE html>
<html >
<head>
	<meta charset="UTF-8">
	<title>Ventas | NosVenden</title>
	<link rel="icon" type="image/jpeg" href="img/favicon.jpg" />

	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Exo:100,200,400">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300">
	<link rel="stylesheet" type="text/css" href="{{ elixir('css/loginV2.min.css') }}">
</head>

<body ng-app="loginApp" ng-controller="loginCtrl">
	<div class="body"></div>
	<div class="grad"></div>
	<div class="modal-container info" ng-click="modalMaster = true" ng-hide="modalMaster"></div>
	<div class="header">
		<img src="img/logo.jpg">
	</div>
	<br>
	<div ng-form="loginForm" class="login cero" id="login">
		<input type="email" placeholder="correo" ng-model="user.email" required><br>
		<input type="password" placeholder="contraseña" ng-keyup="enterCase($event)" ng-model="user.password" required><br>
		<input type="button" value="Login" ng-disabled="!loginForm.$valid || formSdg" ng-click="login()">
		<div class="register-box">
	  		<div class="line">
			  	<a class="float-l cursor" ng-click="registerBtn()">Registrarse</a>
			  	<a class="cursor" ng-click="contactBtn()">Contacto</a>
			    <a class="float-r cursor" href="/asociados/dashboard">Anónimo</a>
	  		</div>
			
		</div>
		<div class="error">
		    <p class="msg-error"></p>
		</div>
		<div class="success">
		    <p class="msg-success"></p>
		</div>
	</div>

	<div ng-form="registerForm" class="login css-form" id="register">
		<input type="text" placeholder="nombres" ng-model="newUser.name" required><br>
		<input type="email" placeholder="correo" ng-model="newUser.email" required><br>
		<input type="tel" placeholder="telefono" ng-model="newUser.phone"  ng-minlength="7" required><br>
		<input type="password" placeholder="contraseña" ng-model="newUser.password" ng-minlength="6" required><br>
		<input type="button" value="Registrarse" ng-disabled="!registerForm.$valid || formSdg" ng-click="signup()">
		<div class="register-box">
	  		<div class="line">
			  	<a class="float-l cursor" ng-click="loginBtn()">Login</a>
			  	<a class="cursor" ng-click="contactBtn()">Contacto</a>
			    <a class="float-r cursor" href="/asociados/dashboard">Anónimo</a>
	  		</div>
		</div>
		<div class="error">
		    <p class="msg-error"></p>
		</div>
	</div>

	<div ng-form="contactForm" class="login css-form" id="contact">
		<input type="text" placeholder="nombres" ng-model="contactUser.name" required><br>
		<input type="email" placeholder="correo" ng-model="contactUser.email" required><br>
		<input type="tel" placeholder="telefono" ng-model="contactUser.phone"  ng-minlength="7" required><br>
		<input type="button" value="Contactenme" ng-disabled="!contactForm.$valid || formSdg" ng-click="contact()">
			<div class="register-box">
		  		<div class="line">
				  	<a class="float-l cursor" ng-click="loginBtn()">Login</a>
				  	<a class="cursor" ng-click="registerBtn()">Registrarse</a>
				    <a class="float-r cursor" href="/asociados/dashboard">Anónimo</a>
		  		</div>
			</div>
		<div class="error">
		    <p class="msg-error"></p>
		</div>
		<div class="success">
		    <p class="msg-success"></p>
		</div>
	</div>

	<div class="modal info" ng-hide="modalInd || modalEmp || modalMaster">
			<div class="column-left">
				<div class="individual">
					<a class="cursor" ng-click="modalInd = true"><img src="img/individual-default.jpg"></a>
				</div>
			</div>
			<div class="column-right">
				<div class="empresas">
					<a class="cursor" ng-click="modalEmp = true"><img src="img/empresas-default.jpg"></a>
				</div>
			</div>
	</div>

	<div class="modal m-individual ng-hide" ng-show="modalInd && !modalMaster">
		<div class="m-body">
			<img class="float-l cursor" src="img/registrarse-default.jpg" ng-click="registerBtn(true)">
			<a href="asociados/dashboard"><img class="cursor" src="img/invitado-default.jpg"></a>
		</div>
	</div>

	<div class="modal m-empresas ng-hide" ng-show="modalEmp && !modalMaster">
		<div class="m-body">
			<div>
				<iframe width="650" height="340" src="https://www.youtube.com/embed/NtDG-Cnj-pw" frameborder="0" allowfullscreen=""></iframe>
			</div>
			<div>
				<img class="cursor img-continue" src="img/continuar-default.jpg" ng-click="contactBtn(true)">
			</div>
		</div>
	</div>

	<form id="frm" action="/asociados/dashboard" method="get" style="display: none;">
	</form>

	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

	<!-- Libraries -->
	<script src="/app/lib/angular/angular.js"></script>
	<script src="/app/lib/angular/angular-resource.js"></script>
	<script src="/app/lib/satellizer.min.js"></script>

	<!-- App Login -->
	<script type="text/javascript" src="{{ elixir('app-usc/compiled/loginApp.js') }}"></script>
</body>
</html>
