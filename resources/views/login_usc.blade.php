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
	<div id="modal-info" class="modal-container info"></div>
	<div class="header">
		<img src="img/logo.jpg">
	</div>
	<br>
	<div ng-form="loginForm" class="login cero" id="login">
		<input type="email" placeholder="correo" ng-model="user.email" required><br>
		<input type="password" placeholder="contraseña" ng-keyup="enterCase($event)" ng-model="user.password" required><br>
		<input type="button" value="Login" ng-disabled="!loginForm.$valid || formSdg" ng-click="login()">
		<div class="register-box">
		  	<a class="float-l cursor" ng-click="registerBtn()">Registrarse</a>
		    <a class="float-r cursor">Anónimo</a>
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
		  	<a class="float-l cursor" ng-click="loginBtn()">Login</a>
		    <a class="float-r cursor">Anónimo</a>
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
		<div class="register-box"></div>
		<div class="error">
		    <p class="msg-error"></p>
		</div>
		<div class="success">
		    <p class="msg-success"></p>
		</div>
	</div>

	<div class="modal info">
		<div class="column-left">
			<div class="m-title">
				Individual
			</div>
			<div class="m-body">
				<div>
					<a href="#"><img src="img/logo.jpg"></a>
				</div>
				<div>
					<a class="cursor" ng-click="registerBtn(true)"><img src="img/logo.jpg"></a>
				</div>
			</div>
		</div>
		<div class="column-right">
			<div class="m-title">
				Empresas
			</div>
			<div class="m-body">
				<div>
					<iframe width="450" height="277" src="https://www.youtube.com/embed/NtDG-Cnj-pw" frameborder="0" allowfullscreen></iframe>
				</div>
				<div>
					<a class="cursor" ng-click="contactBtn(true)"><img src="img/logo.jpg"></a>
				</div>
			</div>
		</div>
	</div>

	<form id="frm" action="/dashboard" method="post" style="display: none;">
	    <input type="text" name="Authorization" id="token">
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
