<?php

function crearConexionBD() {
	$host="oci:dbname=localhost/XE;charset=UTF8";
	$usuario="iissi";
	$password="iissi";
	$host="oci:dbname=localhost/XE;charset=UTF8";
		/* Indicar que las sucesivas conexiones se puedan reutilizar */	
		$conexion=new PDO($host,$usuario,$password,array(PDO::ATTR_PERSISTENT => true));
	    /* Indicar que se disparen excepciones cuando ocurra un error*/
    	$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conexion;
}

function cerrarConexionBD($conexion){
	$conexion=null;
}

function esadmin($conexion,$login) {
	$consulta2 = "SELECT OID_P AS PERSONA FROM PERSONA WHERE usuario=:login";
	$stmt2 = $conexion->prepare($consulta2);
	$stmt2->bindParam(':login',$login);
	$stmt2->execute();
	$oid_p = $stmt2->fetchColumn();
 	$consulta = "SELECT COUNT(OID_AD) AS AD FROM Administrador WHERE oid_p=:oid_p";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':oid_p',$oid_p);
	$stmt->execute();
	$num = $stmt->fetchColumn();
	if ($num == 0) {
	return false;
	}else{
	return true;
	}
}

function esmiembro($conexion,$login) {
	$consulta2 = "SELECT OID_P AS PERSONA FROM PERSONA WHERE usuario=:login";
	$stmt2 = $conexion->prepare($consulta2);
	$stmt2->bindParam(':login',$login);
	$stmt2->execute();
	$oid_p = $stmt2->fetchColumn();
 	$consulta = "SELECT COUNT(OID_M) AS M FROM Miembro WHERE oid_p=:oid_p";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':oid_p',$oid_p);
	$stmt->execute();
	$num = $stmt->fetchColumn();
	if ($num == 0) {
	return false;
	}else{
	return true;
	}
}

function participa($conexion,$login,$oid_ev) {
	$consulta2 = "SELECT OID_P AS PERSONA FROM PERSONA WHERE usuario=:login";
	$stmt2 = $conexion->prepare($consulta2);
	$stmt2->bindParam(':login',$login);
	$stmt2->execute();
	$oid_p = $stmt2->fetchColumn();
 	$consulta = "SELECT OID_M AS Miembro FROM Miembro WHERE oid_p=:oid_p";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':oid_p',$oid_p);
	$stmt->execute();
	$oid_m = $stmt->fetchColumn();
	$consulta3 = "SELECT COUNT(OID_EVM) AS Miembro FROM EVENTOSMIEMBRO WHERE oid_ev=:oid_ev and oid_m=:oid_m";
	$stmt3 = $conexion->prepare($consulta3);
	$stmt3->bindParam(':oid_m',$oid_m);
	$stmt3->bindParam(':oid_ev',$oid_ev);
	$stmt3->execute();
	$participa = $stmt3->fetchColumn();
	if ($participa == 0) {
	return false;
	}else{
	return true;
	}
}

function participa2($conexion,$login,$oid_a) {
	$consulta2 = "SELECT OID_P AS PERSONA FROM PERSONA WHERE usuario=:login";
	$stmt2 = $conexion->prepare($consulta2);
	$stmt2->bindParam(':login',$login);
	$stmt2->execute();
	$oid_p = $stmt2->fetchColumn();
 	$consulta = "SELECT OID_M AS Miembro FROM Miembro WHERE oid_p=:oid_p";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':oid_p',$oid_p);
	$stmt->execute();
	$oid_m = $stmt->fetchColumn();
	$consulta3 = "SELECT COUNT(OID_A2) AS Actividad FROM ACTIVIDADESMIEMBRO WHERE oid_a=:oid_a and oid_m=:oid_m";
	$stmt3 = $conexion->prepare($consulta3);
	$stmt3->bindParam(':oid_m',$oid_m);
	$stmt3->bindParam(':oid_a',$oid_a);
	$stmt3->execute();
	$participa = $stmt3->fetchColumn();
	if ($participa == 0) {
		return false;
	}else{
		return true;
	}
}

?>
