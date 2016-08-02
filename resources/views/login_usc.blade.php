<!DOCTYPE html>
<html >
<head>
	<meta charset="UTF-8">
	<title>Random Login Form</title>

	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Exo:100,200,400">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:700,400,300">
	<link rel="stylesheet" type="text/css" href="css/loginV2.min.css">
</head>

<body>
	<div class="body"></div>
	<div class="grad"></div>
	<div class="header">
		<img src="img/logo.jpg">
	</div>
	<br>
	<div class="login cero" id="login">
		<input type="email" placeholder="correo" name="email"><br>
		<input type="password" placeholder="contraseña" name="password"><br>
		<input type="button" value="Login">
		<div class="register-box">
		  	<a class="cursor" id="register-btn">Registrarse</a>
		    <a class="float-l cursor">Anónimo</a>
		</div>
	</div>
	<div class="login" id="register">
		<input type="text" placeholder="nombres" name="name"><br>
		<input type="email" placeholder="correo" name="email"><br>
		<input type="tel" placeholder="telefono" name="phone"><br>
		<input type="password" placeholder="contraseña" name="password"><br>
		<input type="button" value="Registrarse">
		<div class="register-box">
		  	<a class="cursor" id="login-btn">Login</a>
		    <a class="float-l cursor">Anónimo</a>
		</div>
	</div>

	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script type="text/javascript" src="js/effects.min.js"></script>
</body>
</html>
