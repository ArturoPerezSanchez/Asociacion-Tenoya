<?php

session_start();
if (!isset($_SESSION['login'])) {
	Header("Location: login.php");
}


require_once("gestionBD.php");



require_once("paginacion_consulta.php");

if (isset($_SESSION["actividad"])) {

	$actividad = $_SESSION["actividad"];

	unset($_SESSION["actividad"]);
}



// ¿Venimos simplemente de cambiar página o de haber seleccionado un registro ?

// ¿Hay una sesión activa?

if (isset($_SESSION["paginacion"])) $paginacion = $_SESSION["paginacion"];

$pagina_seleccionada = isset($_GET["PAG_NUM"]) ? (int) $_GET["PAG_NUM"] : (isset($paginacion) ? (int) $paginacion["PAG_NUM"] : 1);

$pag_tam = isset($_GET["PAG_TAM"]) ? (int) $_GET["PAG_TAM"] : (isset($paginacion) ? (int) $paginacion["PAG_TAM"] : 5);

if ($pagina_seleccionada < 1) $pagina_seleccionada = 1;

if ($pag_tam < 1) $pag_tam = 5;



// Antes de seguir, borramos las variables de sección para no confundirnos más adelante

unset($_SESSION["paginacion"]);



$conexion = crearConexionBD();



// La consulta que ha de paginarse

$query = "SELECT actividad.oid_a, actividad.Nombre As Nombre, actividad.Duracion, actividad.aula, actividad.FECHA_INICIO, actividad.Descripcion, Persona.Nombre As Nombre2, Persona.Apellidos  FROM actividad LEFT JOIN ACTIVIDADESMONITOR ON ACTIVIDADESMONITOR.OID_A = actividad.OID_A LEFT JOIN MONITOR ON MONITOR.OID_MO = ACTIVIDADESMONITOR.OID_MO LEFT JOIN PERSONA ON MONITOR.OID_P = PERSONA.OID_P";

$total_registros = total_consulta($conexion, $query);

$total_paginas = (int) ($total_registros / $pag_tam);

if ($total_registros % $pag_tam > 0) $total_paginas++;

if ($pagina_seleccionada > $total_paginas) $pagina_seleccionada = $total_paginas;



// Generamos los valores de sesión para página e intervalo para volver a ella después de una operación

$paginacion["PAG_NUM"] = $pagina_seleccionada;

$paginacion["PAG_TAM"] = $pag_tam;

$_SESSION["paginacion"] = $paginacion;

$filas = consulta_paginada($conexion, $query, $pagina_seleccionada, $pag_tam);

cerrarConexionBD($conexion);

?>
<title>Gestión de la Asociación: Lista de actividades</title>

<?php
include_once("cabecera.php");
?>

<body>
	<main>
		<nav>
			<div id="enlaces">
				<?php
				for ($pagina = 1; $pagina <= $total_paginas; $pagina++)
					if ($pagina == $pagina_seleccionada) { 	?>
					<span class="current"><?php echo $pagina; ?></span>
				<?php } else { ?>
					<a href="consulta_actividades.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>
				<?php } ?>
			</div>

			<form method="get" action="consulta_actividades.php">
				<input id="PAG_NUM" name="PAG_NUM" type="hidden" value="<?php echo $pagina_seleccionada ?>" />
				Mostrando
				<input id="PAG_TAM" name="PAG_TAM" type="number" min="1" max="<?php echo $total_registros; ?>" value="<?php echo $pag_tam ?>" autofocus="autofocus" />

				entradas de <?php echo $total_registros ?>

				<input type="submit" value="Cambiar">

			</form>

		</nav>



		<?php

		foreach ($filas as $fila) {

			?>



			<article class="actividad">

				<form method="post" action="controlador_actividades.php">

					<div class="fila_actividad">

						<div class="datos_a_paginar">
							<input id="OID_A" name="OID_A" type="hidden" value="<?php echo $fila["OID_A"]; ?>" />

							<input id="NOMBRE" name="NOMBRE" type="hidden" value="<?php echo $fila["NOMBRE"]; ?>" />

							<input id="DURACION" name="DURACION" type="hidden" value="<?php echo $fila["DURACION"]; ?>" />

							<input id="AULA" name="AULA" type="hidden" value="<?php echo $fila["AULA"]; ?>" />

							<input id="FECHA_INICIO" name="FECHA_INICIO" type="hidden" value="<?php echo $fila["FECHA_INICIO"]; ?>" />

							<input id="DESCRIPCION" name="DESCRIPCION" type="hidden" value="<?php echo $fila["DESCRIPCION"]; ?>" />


							<input id="NOMBRE2" name="NOMBRE2" type="hidden" value="<?php echo $fila["NOMBRE2"]; ?>" />

							<input id="APELLIDOS" name="APELLIDOS" type="hidden" value="<?php echo $fila["APELLIDOS"]; ?>" />



							<?php
								$fecha = substr($fila['FECHA_INICIO'], 0, -10);


								if (isset($actividad) and ($actividad["OID_A"] == $fila["OID_A"])) { ?>

								<!-- Editando título -->
								<div class=editar_input>
									<div>
										<h4>Duración (en minutos):</h4>
										<h3><input id="DURACION" name="DURACION" type="text" value="<?php echo $fila["DURACION"]; ?>" /> </h3>
										<h4>Nombre:</h4>
										<h3><input id="NOMBRE" name="NOMBRE" type="text" value="<?php echo $fila["NOMBRE"]; ?>" /></h3>
										<h4>Descripcion:</h4>
										<h3><input id="DESCRIPCION" name="DESCRIPCION" type="text" value="<?php echo $fila["DESCRIPCION"]; ?>" /></h3>
										<h4>Aula:</h4>
										<h3><input id="AULA" name="AULA" type="text" value="<?php echo $fila["AULA"]; ?>" /> </h3>
									</div>
								</div>


							<?php } else { ?>

								<!-- mostrando título -->
								<div class="NOMBRE"><b><?php echo $fila["NOMBRE"]; ?></b></div>
								<div class="AULA">Aula: <em><?php echo $fila["AULA"]; ?></em></div>
								<div class="DURACION">Duración (En minutos): <em><?php echo $fila["DURACION"]; ?></em></div>
								<div class="FECHA_INICIO">Fecha: <em><?php echo $fecha; ?></em></div>
								<div class="DESCRIPCION">Descripcion: <em><?php echo $fila["DESCRIPCION"]; ?></em></div>
								<?php if (isset($fila["NOMBRE2"])) { ?>
									<div class="nombre2"><b>Monitor:</b> <em><?php echo $fila["NOMBRE2"], ' ', $fila["APELLIDOS"]; ?></em></div>
							<?php }
								} ?>

						</div>



						<div id="botones_fila">

							<?php


								if (isset($actividad) and ($actividad["OID_A"] == $fila["OID_A"]) and esadmin($conexion, $_SESSION['login'])) { ?>


								<button id="grabar" name="grabar" type="submit" class="editar_fila">

									<img src="images/bag_menuito.bmp" class="editar_fila" alt="Guardar modificación">

								</button>

							<?php } else if (esadmin($conexion, $_SESSION['login'])) { ?>

								<button id="editar" name="editar" type="submit" class="editar_fila">

									<img src="images/pencil_menuito.bmp" class="editar_fila" alt="Editar actividad">

								</button>

							<?php }
								if (esadmin($conexion, $_SESSION['login'])) { ?>

								<button id="borrar" name="borrar" type="submit" class="editar_fila">

									<img src="images/remove_menuito.bmp" class="editar_fila" alt="Borrar actividad">

								</button>
								<?php }
									if (esmiembro($conexion, $_SESSION['login'])) {

										if (!participa2($conexion, $_SESSION['login'], $fila["OID_A"])) { ?>


									<button id="inscribir" name="inscribir" type="submit" class="editar_fila">

										<img src="images/apply.png" class="editar_fila" alt="Inscribirse">

									</button>
								<?php } else {
											?>

									<button id="cancelar" name="cancelar" type="submit" class="editar_fila">

										<img src="images/cancel.png" class="editar_fila" alt="Cancelar subscripción">

									</button>
							<?php }
								}
								?>
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