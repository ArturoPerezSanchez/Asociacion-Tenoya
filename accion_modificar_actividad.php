<?php	
	session_start();	
	
	if (isset($_SESSION["actividad"])) {
		$actividad = $_SESSION["actividad"];
		unset($_SESSION["actividad"]);
		
		require_once("gestionBD.php");
		require_once("gestionarActividades.php");
		
		$conexion = crearConexionBD();
		$excepcion = modificar_actividad($conexion,$actividad["NOMBRE"],$actividad["DURACION"],$actividad["OID_A"],$actividad["DESCRIPCION"], $actividad["AULA"]);
		cerrarConexionBD($conexion);
			
		if ($excepcion<>"") {
			$_SESSION["excepcion"] = $excepcion;
			$_SESSION["destino"] = "consulta_actividades.php";
			Header("Location: excepcion.php");
		}
		else
			Header("Location: consulta_actividades.php");
	} 
	else Header("Location: consulta_actividades.php"); // Se ha tratado de acceder directamente a este PHP
?>
