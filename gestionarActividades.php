	<?php
	  /*
	     * #===========================================================#
	     * #	Este fichero contiene las funciones de gestión     			 
	     * #	de libros de la capa de acceso a datos 		
	     * #==========================================================#
	     */
	     
	function consultarTodosEventos($conexion) {
		$consulta = "SELECT * FROM ACTIVIDAD ORDER BY NOMBRE";
	    return $conexion->query($consulta);
	}
	
	function quitar_actividad($conexion,$OID_A) {
		try {
			$stmt=$conexion->prepare('CALL BORRAR_ACTIVIDAD(:OID_A)');
			$stmt->bindParam(':OID_A',$OID_A);
			$stmt->execute();
			return "";
		} catch(PDOException $e) {
			return $e->getMessage();
	    }
	}
	
	function modificar_actividad($conexion,$nombre,$duracion,$oid_a,$descripcion,$aula) {
		try {
			$stmt=$conexion->prepare('UPDATE ACTIVIDAD SET NOMBRE=:nombre, DURACION=:duracion, AULA=:aula, DESCRIPCION=:descripcion WHERE OID_A=:oid_a');
			$stmt->bindParam(':duracion',$duracion);
			$stmt->bindParam(':aula',$aula);
			$stmt->bindParam(':descripcion',$descripcion);
			$stmt->bindParam(':nombre',$nombre);
			$stmt->bindParam(':oid_a',$oid_a);
			$stmt->execute();
			return "";
		} catch(PDOException $e) {
			return $e->getMessage();
	    }
	}

	function inscribir($conexion,$oid_a,$login) {
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
		$stmt3=$conexion->prepare("CALL INSERTAR_ACTIVIDADESMIEMBRO(:oid_a, :oid_m)");
		$stmt3->bindParam(':oid_a',$oid_a);
		$stmt3->bindParam(':oid_m',$oid_m);
		$stmt3->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
    function borrarsuscripcion($conexion,$oid_a,$login) {
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

		$stmt3=$conexion->prepare("SELECT OID_A2 AS OID_A2 FROM ACTIVIDADESMIEMBRO WHERE oid_m=:oid_m and oid_a=:oid_a");
		$stmt3->bindParam(':oid_a',$oid_a);
		$stmt3->bindParam(':oid_m',$oid_m);
		$stmt3->execute();
		$oid_a2 = $stmt3->fetchColumn();

		$stmt4=$conexion->prepare("CALL BORRAR_ACTIVIDADESMIEMBRO(:oid_a2)");
		$stmt4->bindParam(':oid_a2',$oid_a2);
		$stmt4->execute();
		return "";
	} catch(PDOException $e) {
		return $e->getMessage();
    }
}
		
	?>