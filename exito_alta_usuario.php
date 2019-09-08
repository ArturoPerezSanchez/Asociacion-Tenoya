<?php
	session_start();

	require_once("gestionBD.php");
	require_once("gestionarUsuarios.php");
		
	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		$nuevoUsuario = $_SESSION["formulario"];
	}
	else 
		Header("Location: form_alta_usuario.php");	
		$conexion = crearConexionBD();
?>

<!DOCTYPE html>
<html lang="es">
  <title>Usuario Registrado correctamente</title>


	<?php
		include_once("cabecera.php");
	?>
	<body>
	<main>	
		<?php if (añadirPersona($conexion, $nuevoUsuario)) { 
				$_SESSION['login'] = $nuevoUsuario['usuario'];

				?>
				
				<h1>Hola <?php echo $nuevoUsuario["nombre"]; ?>, gracias por registrarte</h1>
				<div >	
			   		Pulsa <a href="inicio.php">aquí</a> para acceder a la página principal.
				</div>

				<?php } else if(isset($_SESSION["errorBD"])){
					echo "AAAAAAAAAAAAAAAAH";
					echo $_SESSION["errorBD"];
					}else{
						$_SESSION['formulario'] = $nuevoUsuario;	
					 ?>
				<h1>El usuario ya existe en la base de datos.</h1>
				<div >	
					Pulsa <a href="form_alta_usuario.php">aquí</a> para volver al formulario.
				</div>
		<?php } ?>
	</main>

	<?php
		include_once("pie.php");
	?>
</body>
</html>
<?php
	cerrarConexionBD($conexion);
?>

