<?php	
	session_start();	
	
	if (isset($_SESSION["evento"]) and isset($_SESSION["login"])) {
		$evento = $_SESSION["evento"];
		
		require_once("gestionBD.php");
		require_once("gestionarEventos.php");
		
		$conexion = crearConexionBD();		
		$excepcion = inscribir($conexion,$evento["OID_EV"], $_SESSION["login"]);
		cerrarConexionBD($conexion);
		unset($_SESSION["evento"]);
		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_eventos.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consulta_eventos.php");
			echo $excepcion;
	} 
	else Header("Location: consulta_eventos.php"); // Se ha tratado de acceder directamente a este PHP
?>