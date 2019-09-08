<?php
	session_start();

	// Importar librerías necesarias
	require_once("gestionBD.php");


	// Comprobar que hemos llegado a esta página porque se ha rellenado el formulario
	if (isset($_SESSION["formulario"])) {
		// Recogemos los datos del formulario
		$nuevoUsuario["dni"] = $_REQUEST["dni"];
		$nuevoUsuario["nombre"] = $_REQUEST["nombre"];
		$nuevoUsuario["apellidos"] = $_REQUEST["apellidos"];
		$nuevoUsuario["fechaNacimiento"] = $_REQUEST["fechaNacimiento"];
		$nuevoUsuario["usuario"] =  $_REQUEST["usuario"];
		$nuevoUsuario["pass"] = $_REQUEST["pass"];
		$nuevoUsuario["confirmpass"] = $_REQUEST["confirmpass"];
		$nuevoUsuario["sexo"] = $_REQUEST["sexo"];
		
		
	}
	else{ // En caso contrario, vamos al formulario
		Header("Location: form_alta_usuario.php");
	}
	
	// Guardar la variable local con los datos del formulario en la sesión.
	$_SESSION["formulario"] = $nuevoUsuario;


	// Validamos el formulario en servidor
	// Si se produce alguna excepción PDO en la validación, volvemos al formulario informando al usuario
		$conexion = crearConexionBD();
		$errores = validarDatosUsuario($conexion, $_SESSION["formulario"]);
		$errores = existeUsuario($conexion,$nuevoUsuario["usuario"],$nuevoUsuario["dni"]);
		if(isset($errores)){
			$_SESSION["errores"] = $errores;
			Header('Location: form_alta_usuario.php');
		} else {
			Header('Location: exito_alta_usuario.php');
		}
	
	
///////////////////////////////////////////////////////////
// Validación en servidor del formulario de alta de usuario
///////////////////////////////////////////////////////////
function validarDatosUsuario($conexion, $nuevoUsuario){
	session_start();  
	unset($_SESSION['errores']);  
	// Validación del NIF
	if($nuevoUsuario["dni"]=="") 
		$errores = "<p>El DNI no puede estar vacío</p>";
	else if(!preg_match("/^[0-9]{8}[A-Z]$/", $nuevoUsuario["dni"])){
		$errores = "<p>El DNI debe contener 8 números y una letra mayúscula: " . $nuevoUsuario["dni"]. "</p>";
	}

	// Validación del Nombre		
	if($nuevoUsuario["nombre"]=="") 
		$errores = "<p>El nombre no puede estar vacío</p>";
	// Validación del Apellido			
	if($nuevoUsuario["apellidos"]=="") 
		$errores = "<p>Los apellidos no pueden estar vacíos</p>";
		
	// Validación del perfil
	if($nuevoUsuario["sexo"] != "Hombre" &&
		$nuevoUsuario["sexo"] != "Mujer" && 
		$nuevoUsuario["sexo"] != "Otro") {
		$errores = "<p>El sexo es erroneo.</p>";
	}
		
	// Validación de la contraseña
	if(!isset($nuevoUsuario["pass"]) || strlen($nuevoUsuario["pass"])<8){
		$errores = "<p>Contraseña no válida: debe tener al menos 8 caracteres</p>";
	}else if(!preg_match("/[a-z]+/", $nuevoUsuario["pass"]) || 
		!preg_match("/[A-Z]+/", $nuevoUsuario["pass"]) || !preg_match("/[0-9]+/", $nuevoUsuario["pass"])){
		$errores = "<p>Contraseña no válida: debe contener letras mayúsculas y minúsculas y dígitos</p>";
	}else if($nuevoUsuario["pass"] != $nuevoUsuario["confirmpass"]){
		$errores = "<p>Las contraseñas no coinciden</p>";
	}
	return $errores;
}
	
function existeUsuario($conexion,$usuario,$dni) {
 	$consulta = "SELECT COUNT(*) AS TOTAL FROM Persona WHERE USUARIO=:usuario OR DNI=:dni";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':usuario',$usuario);
	$stmt->bindParam(':dni',$dni);
	$stmt->execute();
	$num = $stmt->fetchColumn();
	if ($num != 0) {
		$errores = "<p>El usuario ya existe.</p>";
	}
	return $errores;
}
?>

