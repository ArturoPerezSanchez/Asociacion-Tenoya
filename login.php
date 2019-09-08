<?php
	session_start();
  	include_once("gestionBD.php");
 	include_once("gestionarUsuarios.php");
	if(isset($_SESSION['login'])){
	Header("Location: index.php");
	}
	if (isset($_POST['submit'])){
		$usuario= $_POST['usuario'];
		$pass = $_POST['pass'];
		$conexion = crearConexionBD();
		$num_usuarios = consultarUsuario($conexion,$usuario,$pass);
		cerrarConexionBD($conexion);	
		if ($num_usuarios == 0)
			$login = "error";	
		else {
			$_SESSION['login'] = $usuario;
			Header("Location: index.php");
		}	
	}

?>
<?php
	include_once("cabecera.php");
?>
<title>Login</title>
<body>
<main>
	<?php if (isset($login)) {?>
		<div class=error>
			<img src='images/warning.png'>
			Usuario o contraseña incorrectos.
		</div>
	<?php
		}	
	?>

	
	<!-- The HTML login form -->
	<form id="login" action="login.php" method="post">
		<div class="login">
			<div class="inputform">
				<label for="usuario">Usuario: </label>
				<input type="text" name="usuario" id="usuario" />
			</div>
			<div class="inputform">
				<label for="pass">Contraseña: </label>
				<input type="password" name="pass" id="password" />
			</div>
			<div>
				<input type="submit" name="submit" value="Entrar" class="button loginbutton"/>
			</div>
		</div>
		<p>&nbsp;</p>
	</form>
</main>

<?php
	include_once("pie.php");
?>
</body>
</html>

