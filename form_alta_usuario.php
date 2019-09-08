<?php
	session_start();
	
	require_once("gestionBD.php");

	// Si no existen datos del formulario en la sesión, se crea una entrada con valores por defecto
	if (!isset($_SESSION['formulario'])) {
		$formulario['dni'] = "";
		$formulario['nombre'] = "";
		$formulario['apellidos'] = "";
		$formulario['fechaNacimiento'] = "";
		$formulario['pass'] = "";
		$formulario['confirmpass'] = "";
		$formulario['sexo'] = "";
		$formulario['usuario'] = "";
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
  <title>Alta de Usuario</title>

<body>
	<?php
		include_once("cabecera.php");
		if (isset($errores)) { 
	    	echo "<div id=\"div_errores\" class=\"error\">";
			echo "<h4> Errores en el formulario:</h4>";
    		echo $errores; 
    		echo "</div>";
  		}
	?>
	

	
	<form validate="true" method="get" action="accion_alta_usuario.php">
		<p><i>Los campos obligatorios están marcados con </i><em>*</em></p>
		<fieldset><legend>Datos personales</legend>
			<div class="singupform">
				<div class="inputform">
					<label for="dni">DNI<em>*</em></label>
					<input id="dni" name="dni" type="text" placeholder="12345678X" pattern="^[0-9]{8}[A-Z]" title="Ocho dígitos seguidos de una letra mayúscula" value="<?php echo $formulario['dni'];?>" required>
				</div>

				<div class="inputform">
					<label for="nombre">Nombre:<em>*</em></label>
					<input id="nombre" name="nombre" type="text" size="40" value="<?php echo $formulario['nombre'];?>" required/>
				</div>
				
				<div class="inputform">
					<label for="apellidos">Apellidos:<em>*</em></label>
					<input id="apellidos" name="apellidos" type="text" size="40" value="<?php echo $formulario['apellidos'];?>" required/>
				</div>
				<div class="inputform">
					<label for="fechaNacimiento">Fecha de nacimiento:</label>
					<input type="date" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $formulario['fechaNacimiento'];?>"/>
				</div>

				<div class="inputform">
					<br>
					<table class="sexo">
						<tr>
							<td>
							Hombre
							</td>
							<td>
							Mujer	
							</td>
							<td>
							Otro
							</td>
						</tr>
						<tr>
							<td>
								<input class="sexOption" name="sexo" type="radio" value="Hombre" <?php if($formulario['sexo']=='Hombre') echo ' checked ';?>/>
							</td>
							<td>
								<input class="sexOption" name="sexo" type="radio" value="Mujer" <?php if($formulario['sexo']=='Mujer') echo ' checked ';?>/>
							</td>
							<td>
								<input class="sexOption" name="sexo" type="radio" value="Otro" <?php if($formulario['sexo']=='Otro') echo ' checked ';?>/>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</fieldset>
		<fieldset><legend>Datos de cuenta</legend>
			<div class="singupform">
				<div class="inputform">
					<label for="usuario">Usuario:<em>*</em></label>
					<input id="usuario" name="usuario" type="text" placeholder="usuario" value="<?php echo $formulario['usuario'];?>" required/><br>
				</div>
		
				<div class="inputform">
					<label for="pass">Conraseña:<em>*</em></label>
	                <input type="password" name="pass" id="pass" placeholder="Mínimo 8 caracteres entre letras y dígitos"
	                oninput = "passwordConfirmation()" required/>
				</div>
			<div class="inputform">
				<label for="confirmpass">Confirmar Contraseña:<em>*</em></label>
				<input type="password" name="confirmpass" id="confirmpass" placeholder="Confirmación de contraseña" 
				oninput = "passwordConfirmation()" required/>
			</div>
		</fieldset>
		<br>
		<div>
			<input type="button" class="button" value="Volver" onclick="location='login.php'" /> 
			<input type="submit" class="button" value="Enviar" /> 
		</div>
	</form>



	<?php
		include_once("pie.php");
		cerrarConexionBD($conexion);
	?>
	
	</body>
</html>
