<?php
	session_start();
	
	require_once("gestionBD.php");
	include_once("controlador_eventos.php");

	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formulario'])) {
		$formulario['nombre'] = "";
		$formulario['descripcion'] = "";
		$formulario['fechaInicio'] = "";
		$formulario['fechaFin'] = "";
		$_SESSION['formulario'] = $formulario;
	}
	// Si ya existían valores, los cogemos para inicializar el formulario
	else
		$formulario = $_SESSION['formulario'];
	if (isset($_SESSION['errores'])) {
		$errores = $_SESSION['errores'];
	}
		
	// Creamos una conexión con la BD
	$conexion = crearConexionBD();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/biblio.css" />
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>
  <script src="js/validacion_cliente_alta_usuario.js" type="text/javascript"></script>
  <title>Alta de Usuario</title>
</head>

<body>
	<script>

	$(document).ready(function(){
		$("#dni").on("input", function(){
			$("#usuario").val($(this).val());
		});
		
		$("#pass").on("keyup",function(){
		//Calculo del color
		passwordColor();
		});
	});
	</script>
	
	<?php
		include_once("cabecera.php");
		if (isset($errores)) { 
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores en el formulario:</h4>";
    		echo $errores; 
    		echo "</div>";
  		}
	?>
	

	
	<form validaid="crearEvento" method="get" action="accion_crear_evento.php">
		<p><i>Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Evento</legend>

			<div><label for="nombre">Nombre:<em>*</em></label>
			<input id="nombre" name="nombre" type="text" size="40" value="<?php echo $formulario['nombre'];?>" required/>
			</div><br/>
			
			<div><label for="descripcion">Descripcion:<em>*</em></label>
			<input id="descripcion" name="descripcion" type="text" size="40" value="<?php echo $formulario['descripcion'];?>" required/>
			</div><br/>

			<div><label for="fechaNacimiento">Fecha Inicio:</label>
			<input type="date" id="fechaInicio" name="fechaInicio" value="<?php echo $formulario['fechaInicio'];?>"/>
			</div><br/>
			<div><label for="fechaNacimiento">Fecha Fin:</label>
			<input type="date" id="fechaFin" name="fechaFin" value="<?php echo $formulario['fechaFin'];?>"/>
			</div><br/>
		</fieldset>

		<div><input type="submit" value="crear" id="crear" />

		<input type="button" value="Volver" onclick="location='consulta_eventos.php'" /> </div>
	</form>



	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>
	
	</body>
</html>
