<?php	
	session_start();
	
	if (isset($_REQUEST["OID_A"])){
		$actividad["NOMBRE"] = $_REQUEST["NOMBRE"];
		$actividad["AULA"] = $_REQUEST["AULA"];
		$actividad["DURACION"] = $_REQUEST["DURACION"];
		$actividad["OID_A"] = $_REQUEST["OID_A"];		
		$actividad["DESCRIPCION"] = $_REQUEST["DESCRIPCION"];		
		$_SESSION["actividad"] = $actividad;
			
		if (isset($_REQUEST["grabar"])) Header("Location: accion_modificar_actividad.php");
		else if (isset($_REQUEST["editar"])) Header("Location: consulta_actividades.php");
		else if (isset($_REQUEST["borrar"])) Header("Location: accion_borrar_actividad.php");
		else if (isset($_REQUEST["inscribir"])) Header("Location: accion_crear_inscripcion_actividad.php"); 
		else if (isset($_REQUEST["cancelar"])) Header("Location: accion_borrar_inscripcion_actividad.php");
	}
	else 
		Header("Location: consulta_actividades.php");

?>
