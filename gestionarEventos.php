<?php
     
function quitar_evento($conexion,$oid_ev) {
	try {
		$stmt=$conexion->prepare('CALL BORRAR_EVENTO(:oid_ev)');
		$stmt->bindParam(':oid_ev',$oid_ev);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function modificar_evento($conexion,$oid_ev,$fechaInicio, $fechaFin) {
	try {
		$stmt=$conexion->prepare('UPDATE EVENTO SET FECHA_INICIO =:fechaInicio, FECHA_FIN=:fechaFin WHERE OID_EV=:oid_ev');
		$stmt->bindParam(':oid_ev',$oid_ev);
		$originalDate = $fechaInicio;
		$newDate = date("d-m-Y", strtotime($originalDate));
		$stmt->bindParam(':fechaInicio',$newDate);
		$originalDate2 = $fechaFin;
		$newDate2 = date("d-m-Y", strtotime($originalDate2));
		$stmt->bindParam(':fechaFin',$newDate2);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function inscribir($conexion,$oid_ev,$login) {
	try {
		$consulta2 = "SELECT OID_P AS PERSONA FROM PERSONA WHERE usuario=:login";
		$stmt2 = $conexion->prepare($consulta2);
		$stmt2->bindParam(':login',$login);
		$stmt2->execute();
		$oid_p = $stmt2->fetchColumn();
 		$consulta = "SELECT OID_M AS M FROM Miembro WHERE oid_p=:oid_p";
		$stmt = $conexion->prepare($consulta);
		$stmt->bindParam(':oid_p',$oid_p);
		$stmt->execute();
		$oid_m = $stmt->fetchColumn();
		$stmt3=$conexion->prepare("CALL INSERTAR_EVENTOSMIEMBRO(:oid_m, :oid_ev)");
		$stmt3->bindParam(':oid_ev',$oid_ev);
		$stmt3->bindParam(':oid_m',$oid_m);
		$stmt3->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function borrarsuscripcion($conexion,$oid_ev,$login) {
	try {
		$consulta2 = "SELECT OID_P AS PERSONA FROM PERSONA WHERE usuario=:login";
		$stmt2 = $conexion->prepare($consulta2);
		$stmt2->bindParam(':login',$login);
		$stmt2->execute();
		$oid_p = $stmt2->fetchColumn();

 		$consulta = "SELECT OID_M AS M FROM Miembro WHERE oid_p=:oid_p";
		$stmt = $conexion->prepare($consulta);
		$stmt->bindParam(':oid_p',$oid_p);
		$stmt->execute();
		$oid_m = $stmt->fetchColumn();

		$stmt3=$conexion->prepare("SELECT OID_EVM AS OID_EVM FROM EVENTOSMIEMBRO WHERE oid_m=:oid_m and oid_ev=:oid_ev");
		$stmt3->bindParam(':oid_ev',$oid_ev);
		$stmt3->bindParam(':oid_m',$oid_m);
		$stmt3->execute();
		$oid_evm = $stmt3->fetchColumn();

		$stmt4=$conexion->prepare("CALL BORRAR_EVENTOSMIEMBRO(:oid_evm)");
		$stmt4->bindParam(':oid_evm',$oid_evm);
		$stmt4->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

function crear_evento($conexion,$oid_ev,$nombre, $fechaInicio, $fechaFin, $descripcion) {
	try {
		$stmt=$conexion->prepare('INSERT EVENTO SET FECHA_INICIO =:fechaInicio, FECHA_FIN=:fechaFin WHERE OID_EV=:oid_ev');
		$stmt->bindParam(':oid_ev',$oid_ev);
		$originalDate = $fechaInicio;
		$newDate = date("d-m-Y", strtotime($originalDate));
		$stmt->bindParam(':fechaInicio',$newDate);
		$originalDate2 = $fechaFin;
		$newDate2 = date("d-m-Y", strtotime($originalDate2));
		$stmt->bindParam(':fechaFin',$newDate2);
		$stmt->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}

 function crear_evento($conexion,$nombre, $fechaInicio, $fechaFin, $descripcion) {

	try {
		$consulta = "CALL INSERTAR_EVENTO(:nombre, :fechaInicio, :fechaFin, :descripcion)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':nombre',$usuario["nombre"]);
		$stmt->bindParam(':fechaInicio',$usuario["fechaInicio"]);
		$stmt->bindParam(':fechaFin',$usuario["fechaFin"]);
		$stmt->bindParam(':descripcion',$usuario["descripcion"]);
		$stmt->execute();
		return true;
		
	} catch(PDOException $e) {
		$_SESSION["errorBD"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p></p>";
		return false;
    }

	
?>