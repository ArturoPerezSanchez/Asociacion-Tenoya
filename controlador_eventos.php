<?php	
	session_start();
	
	if (isset($_REQUEST["NOMBRE"])){
		$evento["NOMBRE"] = $_REQUEST["NOMBRE"];
		$evento["FECHA_FIN"] = $_REQUEST["FECHA_FIN"];	
		$evento["FECHA_INICIO"] = $_REQUEST["FECHA_INICIO"];
		$evento["OID_EV"] = $_REQUEST["OID_EV"];			
		$_SESSION["evento"] = $evento;
			
		if (isset($_REQUEST["editar"])) Header("Location: consulta_eventos.php"); 
		else if (isset($_REQUEST["grabar"])) Header("Location: accion_modificar_evento.php");
		else if (isset($_REQUEST["borrar"])) Header("Location: accion_borrar_evento.php"); 
		else if (isset($_REQUEST["inscribir"])) Header("Location: accion_crear_inscripcion.php");
		else if (isset($_REQUEST["cancelar"])) Header("Location: accion_borrar_inscripcion.php");
		
	}
	else if (isset($_REQUEST["crear"])) Header("Location: accion_crear_eventwo.php");
	else
		Header("Location: consulta_eventos.php");

?>
