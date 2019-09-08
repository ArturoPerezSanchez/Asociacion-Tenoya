<?php

	session_start();
	if(!isset($_SESSION['login'])){
	Header("Location: login.php");
	}


	require_once("gestionBD.php");


	require_once("paginacion_consulta.php");



	if (isset($_SESSION["encuesta"])){

		$encuesta = $_SESSION["encuesta"];

		unset($_SESSION["encuesta"]);

	}



	// ¿Venimos simplemente de cambiar página o de haber seleccionado un registro ?

	// ¿Hay una sesión activa?

	if (isset($_SESSION["paginacion"])) $paginacion = $_SESSION["paginacion"];

	$pagina_seleccionada = isset($_GET["PAG_NUM"])? (int)$_GET["PAG_NUM"]:

												(isset($paginacion)? (int)$paginacion["PAG_NUM"]: 1);

	$pag_tam = isset($_GET["PAG_TAM"])? (int)$_GET["PAG_TAM"]:

										(isset($paginacion)? (int)$paginacion["PAG_TAM"]: 5);

	if ($pagina_seleccionada < 1) $pagina_seleccionada = 1;

	if ($pag_tam < 1) $pag_tam = 5;



	// Antes de seguir, borramos las variables de sección para no confundirnos más adelante

	unset($_SESSION["paginacion"]);



	$conexion = crearConexionBD();



	// La consulta que ha de paginarse

	$query = "SELECT encuesta.oid_en, encuesta.PREGUNTA FROM encuesta";

	$total_registros = total_consulta($conexion,$query);

	$total_paginas = (int) ($total_registros / $pag_tam);

	if ($total_registros % $pag_tam > 0) $total_paginas++;

	if ($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;



	// Generamos los valores de sesión para página e intervalo para volver a ella después de una operación

	$paginacion["PAG_NUM"] = $pagina_seleccionada;

	$paginacion["PAG_TAM"] = $pag_tam;

	$_SESSION["paginacion"] = $paginacion;

	$filas = consulta_paginada($conexion,$query,$pagina_seleccionada,$pag_tam);

	cerrarConexionBD($conexion);

?>



  <title>Gestión de la Asociación: Lista de encuestas</title>



<?php

	include_once("cabecera.php");

	include_once("menu.php");



?>

<body>

<main>

	 <nav>

		<div id="enlaces">

			<?php

				for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )

					if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="consulta_encuestas.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>



		<form method="get" action="consulta_encuestas.php">

			<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada?>"/>

			Mostrando

			<input id="PAG_TAM" name="PAG_TAM" type="number"

				min="1" max="<?php echo $total_registros;?>"

				value="<?php echo $pag_tam?>" autofocus="autofocus" />

			entradas de <?php echo $total_registros?>

			<input type="submit" value="Cambiar">

		</form>

	</nav>



	<?php

		foreach($filas as $fila) {

	?>



	<article class="encuesta">

		<form method="post" action="controlador_encuestas.php">

			<div class="fila_encuesta">

				<div class="datos_a_paginar">
						<input id="OID_EN" name="OID_EN"

						type="hidden" value="<?php echo $fila["OID_EN"]; ?>"/>

					<input id="PREGUNTA" name="PREGUNTA"

						type="hidden" value="<?php echo $fila["PREGUNTA"]; ?>"/>



				<?php
					if (isset($encuesta) and ($encuesta["DURACION"] == $fila["DURACION"])) { ?>

						<!-- Editando título -->
						<div class = editar_input> 
						<h4>Duración (en minutos):</h4>
						<h3><input id="PREGUNTA" name="PREGUNTA" type="text" value="<?php echo $fila["PREGUNTA"]; ?>"/>	</h3>
						</div>
			
				<?php }	else { ?>

						<!-- mostrando título -->
						
						<div class="PREGUNTA"><b><?php echo $fila["PREGUNTA"]; ?></b></div>
						<fieldset>
   							<legend>Respuestas</legend>
						<?php 
								$consulta = "SELECT RESPUESTA.comentario, Persona.Nombre, Persona.Apellidos from RESPUESTA LEFT JOIN PERSONA ON RESPUESTA.OID_P = PERSONA.OID_P where RESPUESTA.OID_EN=:OID_EN";
								$stmt2 = $conexion->prepare($consulta);
								$stmt2->bindParam( ':OID_EN', $fila["OID_EN"]);
								$stmt2->execute();
								$filas2 = $stmt2;
								foreach($filas2 as $row) {
						?>
									<input id="COMENTARIO" name="COMENTARIO"
									type="hidden" value="<?php echo $row["COMENTARIO"]; ?>"/>
									<input id="NOMBRE" name="NOMBRE"
									type="hidden" value="<?php echo $row["NOMBRE"]; ?>"/>
									<input id="APELLIDOS" name="APELLIDOS"
									type="hidden" value="<?php echo $row["APELLIDOS"]; ?>"/>
									<div class="COMENTARIO"><b><?php echo $row["NOMBRE"], ' ', $row["APELLIDOS"], ': '?></b><?php echo $row["COMENTARIO"]; ?></div>
						
				<?php }?>
								</fieldset>
		<?php	} ?>

				</div>



			</div>

		</form>

	</article>



	<?php } ?>

</main>



<?php

	include_once("pie.php");

?>

</body>

</html>