<?php	
	session_start();	
	
	if (isset($_SESSION["actividad"]) and isset($_SESSION["login"])) {
		$actividad = $_SESSION["actividad"];
		
		require_once("gestionBD.php");
		require_once("gestionarActividades.php");
		
		$conexion = crearConexionBD();		
		$excepcion = inscribir($conexion,$actividad["OID_A"], $_SESSION["login"]);
		cerrarConexionBD($conexion);
		unset($_SESSION["actividad"]);
		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_actividades.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consulta_actividades.php");
			echo $excepcion;
	} 
	else Header("Location: consulta_actividades.php"); // Se ha tratado de acceder directamente a este PHP
?>