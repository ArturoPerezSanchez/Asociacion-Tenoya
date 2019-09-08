<?php

	session_start();
	if(!isset($_SESSION['login'])){
	Header("Location: login.php");
	}


	require_once("gestionBD.php");


	require_once("paginacion_consulta.php");



	if (isset($_SESSION["evento"])){

		$evento = $_SESSION["evento"];

		unset($_SESSION["evento"]);

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

	$query = "SELECT evento.oid_ev, evento.Nombre, evento.fecha_inicio, evento.fecha_fin, VIAJE.Destino, VIAJE.Precio, Viaje.Plazas_Max  FROM evento LEFT JOIN VIAJE ON VIAJE.OID_EV = EVENTO.OID_EV";

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
  <title>Gestión de la Asociación: Lista de eventos</title>



<?php
	include_once("cabecera.php");
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

						<a href="consulta_eventos.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>



		<form method="get" action="consulta_eventos.php">

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



	<article class="evento">

		<form method="post" action="controlador_eventos.php">

			<div class="fila_evento">

				<div class="datos_a_paginar">
					<input id="OID_EV" name="OID_EV"

						type="hidden" value="<?php echo $fila["OID_EV"]; ?>"/>

					<input id="NOMBRE" name="NOMBRE"

						type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>

					<input id="FECHA_INICIO" name="FECHA_INICIO"

						type="hidden" value="<?php echo $fila["FECHA_INICIO"]; ?>"/>


					<input id="FECHA_FIN" name="FECHA_FIN"

						type="hidden" value="<?php echo $fila["FECHA_FIN"]; ?>"/>

					<input id="DESTINO" name="DESTINO"

						type="hidden" value="<?php echo $fila["DESTINO"]; ?>"/>

					<input id="PRECIO" name="PRECIO"

						type="hidden" value="<?php echo $fila["PRECIO"]; ?>"/>

					<input id="PLAZAS_MAX" name="PLAZAS_MAX"

						type="hidden" value="<?php echo $fila["PLAZAS_MAX"]; ?>"/>



				<?php
					if (isset($evento) and ($evento["OID_EV"] == $fila["OID_EV"])) {
					 ?>
						<!-- Editando título -->

						<div class = editar_input> 
						<h4>Fecha Inicio:</h4>
						<h3><input id="FECHA_INICIO" name="FECHA_INICIO" type="date"/>	</h3>
						<h4>Fecha Fin:</h4>
						<h3><input id="FECHA_FIN" name="FECHA_FIN" type="date"/>	</h3>
						</div>
			
				<?php }	else {
						 ?>
						<!-- mostrando título -->

						<div class="NOMBRE"><b><?php echo $fila["NOMBRE"]; ?></b></div><em>
						<div class="FECHA_INICIO">Fecha inicio : <?php echo $fila["FECHA_INICIO"]; ?></div>
						<div class="FECHA_FIN">Fecha fin (mm/dd/yy): <?php echo $fila["FECHA_FIN"]; ?></div></em>
						<?php if (isset($fila["Destino"]) || isset($fila["PLAZAS_MAX"]) || isset( $fila["Precio"])) { ?>
	 						<fieldset>
   							<legend>Viaje</legend>
    					<div class="Destino">Destino: <?php echo $fila["DESTINO"]; ?></div></em>
    					<div class="PLAZAS_MAX">Plazas Máximas: <?php echo $fila["PLAZAS_MAX"]; ?></div></em>
    					<div class="Precio">Precio: <?php echo $fila["PRECIO"]; ?></div></em>
  							</fieldset>

				<?php }} ?>

				</div>



				<div id="botones_fila">

				<?php if (isset($evento) and ($evento["OID_EV"] == $fila["OID_EV"]) and esadmin($conexion, $_SESSION['login'])) { ?>

						<button id="grabar" name="grabar" type="submit" class="editar_fila">

							<img src="images/bag_menuito.bmp" class="editar_fila" alt="Guardar modificación">

						</button>

				<?php } else if(esadmin($conexion, $_SESSION['login'])){ ?>

						<button id="editar" name="editar" type="submit" class="editar_fila">

							<img src="images/pencil_menuito.bmp" class="editar_fila" alt="Editar evento">
						</button>

					<?php } 

					if(esmiembro($conexion, $_SESSION['login'])){ 
						if(!participa($conexion, $_SESSION['login'], $fila["OID_EV"])){ ?>

					<button id="inscribir" name="inscribir" type="submit" class="editar_fila">

						<img src="images/apply.png" class="editar_fila" alt="Inscribirse">

					</button>


						<?php } else{
							?>

					<button id="cancelar" name="cancelar" type="submit" class="editar_fila">

						<img src="images/cancel.png" class="editar_fila" alt="Cancelar subscripción">

					</button>
		
					<?php }} if(esadmin($conexion, $_SESSION['login'])){ ?>
			

					<button id="borrar" name="borrar" type="submit" class="editar_fila">

						<img src="images/remove_menuito.bmp" class="editar_fila" alt="Borrar evento">

					</button>
<?php } ?>
				</div>

			</div>

		</form>

	</article>



	<?php } 

if(esadmin($conexion, $_SESSION['login'])){ ?>
		
	<button  id="crear" value="crear"  name="crear" class="editar_fila" onclick="location.href = 'formulario_crear_evento.php';">
	<img src="images/add.png" class="editar_fila" style="width: 50px;"alt="Nuevo evento">
	</button>


					<?php } 
	?>

</main>



<?php

	include_once("pie.php");

?>

</body>

</html>