<?php

 function añadirPersona($conexion,$usuario) {

	try {
		$consulta = "CALL INSERTAR_PERSONA(:dni, :nombre, :apellidos, :sexo, :usuario, :contraseña)";
		$stmt=$conexion->prepare($consulta);
		$stmt->bindParam(':dni',$usuario["dni"]);
		$stmt->bindParam(':nombre',$usuario["nombre"]);
		$stmt->bindParam(':apellidos',$usuario["apellidos"]);
		$stmt->bindParam(':sexo',$usuario["sexo"]);
		$stmt->bindParam(':usuario',$usuario["usuario"]);
		$stmt->bindParam(':contraseña',$usuario["pass"]);
		$stmt->execute();
		$consulta2 = "SELECT OID_P from Persona where usuario=:usuario";
		$stmt2=$conexion->prepare($consulta2);
		$stmt2->bindParam(':usuario',$usuario["usuario"]);
		$stmt2->execute();
		$oid_p = $stmt2->fetchColumn();
 		$consulta3 = "CALL INSERTAR_MIEMBRO(:oid_p, :edad)";
		$stmt3 = $conexion->prepare($consulta3);
		$stmt3->bindParam(':oid_p',$oid_p);
		$edad= intval((strtotime("now")-strtotime($usuario["fechaNacimiento"]))/31536000);
		$stmt3->bindParam(':edad',$edad);
		$stmt3->execute();
		return true;
		
	} catch(PDOException $e) {
		$_SESSION["errorBD"] = "<p>ERROR en la validación: fallo en el acceso a la base de datos.</p><p></p>";
		return false;
    }
}
 
function consultarUsuario($conexion,$usuario,$pass) {
 	$consulta = "SELECT COUNT(*) AS TOTAL FROM PERSONA WHERE USUARIO=:usuario AND CONTRASEÑA=:pass";
	$stmt = $conexion->prepare($consulta);
	$stmt->bindParam(':usuario',$usuario);
	$stmt->bindParam(':pass',$pass);
	$stmt->execute();
	return $stmt->fetchColumn();
}


