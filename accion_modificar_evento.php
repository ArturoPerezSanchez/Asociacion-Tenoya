<?php	
	session_start();	
	
	if (isset($_SESSION["evento"])) {
		$evento = $_SESSION["evento"];
		unset($_SESSION["evento"]);
		
		require_once("gestionBD.php");
		require_once("gestionarEventos.php");
		
		$conexion = crearConexionBD();		
		$excepcion = modificar_evento($conexion,$evento["OID_EV"], $evento["FECHA_INICIO"], $evento["FECHA_FIN"]);
			
		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_eventos.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consulta_eventos.php");
	} 
	else Header("Location: consulta_eventos.php"); // Se ha tratado de acceder directamente a este PHP
?>