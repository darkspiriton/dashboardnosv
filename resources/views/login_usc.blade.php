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
	<div class="header">
		<img src="img/logo.jpg">
	</div>
	<br>
	<div ng-form="loginForm" class="login cero" id="login">
		<input type="email" placeholder="correo" ng-model="user.email" required><br>
		<input type="password" placeholder="contraseña" ng-keyup="enterCase($event)" ng-model="user.password" required><br>
		<input type="button" value="Login" ng-disabled="!loginForm.$valid || formSdg" ng-click="login()">
		<div class="register-box">
		  	<a class="cursor" ng-click="registerBtn()">Registrarse</a>
		    <a class="float-l cursor">Anónimo</a>
		</div>
		<div class="error">
		    <p id="login-error"></p>
		</div>
		<div class="success">
		    <p id="register-success"></p>
		</div>
	</div>
	<div ng-form="registerForm" class="login css-form" id="register">
		<input type="text" placeholder="nombres" ng-model="newUser.name" required><br>
		<input type="email" placeholder="correo" ng-model="newUser.email" required><br>
		<input type="tel" placeholder="telefono" ng-model="newUser.phone"  ng-minlength="7" required><br>
		<input type="password" placeholder="contraseña" ng-model="newUser.password" ng-minlength="6" required><br>
		<input type="button" value="Registrarse" ng-disabled="!registerForm.$valid || formSdg" ng-click="signup()">
		<div class="register-box">
		  	<a class="cursor" ng-click="loginBtn()">Login</a>
		    <a class="float-l cursor">Anónimo</a>
		</div>
		<div class="error">
		    <p id="register-error"></p>
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
