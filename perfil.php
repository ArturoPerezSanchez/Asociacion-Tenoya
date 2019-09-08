<?php

	session_start();


if(!isset($_SESSION['login'])){
Header("Location: login.php");
}

	require_once("gestionBD.php");

	require_once("gestionareventos.php");

	require_once("paginacion_consulta.php");



	if (isset($_SESSION["persona"])){

		$persona = $_SESSION["persona"];

		unset($_SESSION["persona"]);

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

	$query = "SELECT * from PERSONA where USUARIO ='" . $_SESSION['login'] ."'";

	// Se comprueba que el tamaño de página, página seleccionada y total de registros son conformes.

	// En caso de que no, se asume el tamaño de página propuesto, pero desde la página 1

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


  <title>Mi Perfil</title>
<?php

	include_once("cabecera.php");
?>



<main>

	 <nav>

		<div id="enlaces">

			<?php

				for( $pagina = 1; $pagina <= $total_paginas; $pagina++ )

					if ( $pagina == $pagina_seleccionada) { 	?>

						<span class="current"><?php echo $pagina; ?></span>

			<?php }	else { ?>

						<a href="perfil.php?PAG_NUM=<?php echo $pagina; ?>&PAG_TAM=<?php echo $pag_tam; ?>"><?php echo $pagina; ?></a>

			<?php } ?>

		</div>



		<form method="get" action="perfil.php">

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



	<article class="persona">

		<form method="post" action="controlador_eventos.php">

			<div class="fila_persona">

				<div class="datos_a_paginar">

					<input id="DNI" name="DNI"

						type="hidden" value="<?php echo $fila["DNI"]; ?>"/>

					<input id="NOMBRE" name="NOMBRE"

						type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>

					<input id="APELLIDOS" name="APELLIDOS"

						type="hidden" value="<?php echo $fila["APELLIDOS"]; ?>"/>

					<input id="EDAD" name="EDAD"

						type="hidden" value="<?php echo $fila["EDAD"]; ?>"/>
						
					<input id="SEXO" name="SEXO"

						type="hidden" value="<?php echo $fila["SEXO"]; ?>"/>
						
					<input id="USUARIO" name="USUARIO"

						type="hidden" value="<?php echo $fila["USUARIO"]; ?>"/>
						
						<input id="CONTRASEÑA" name="CONTRASEÑA"

						type="hidden" value="<?php echo $fila["CONTRASEÑA"]; ?>"/>



				<?php

					if (isset($persona) ) { ?>

						<!-- Editando título -->
						<h4>Fecha de inicio: </h4>
						
						<h3><input id="FECHA_DE_INICIO" name="FECHA_DE_INICIO" type="date" value="<?php echo $fila["FECHA_DE_INICIO"]; ?>"/>	</h3>
						
						<h4>Fecha de finalización: </h4>
						
						<h3><input id="FECHA_DE_FIN" name="FECHA_DE_FIN" type="date" value="<?php echo $fila["FECHA_DE_FIN"]; ?>"/>	</h3>

						<h4>Precio por persona(€):</h4>
						
						<h3><input id="PRECIO" name="PRECIO" type="number" min="0" value="<?php echo $fila["PRECIO"]; ?>"/>	</h3>

						<h4>Plazas máximas: </h4>
						
						<h3><input id="PLAZAS_MAX" name="PLAZAS_MAX" type="number" value="<?php echo $fila["PLAZAS_MAX"]; ?>"/>	</h3>

						<h4>Plazas ocupadas: </h4>
						
						<h3><input id="PLAZAS_OCUPADAS" name="PLAZAS_OCUPADAS" min="10" max ="<?php $fila["PLAZAS_MAX"]?>" type="number" value="<?php echo $fila["PLAZAS_OCUPADAS"]; ?>"/>	</h3>

						
						
						

				<?php }	else { ?>

						<!-- mostrando título -->

						<input id="NOMBRE" name="NOMBRE" type="hidden" value="<?php echo $fila["NOMBRE"]; ?>"/>

						<div class="DNI"><b><?php echo $fila["DNI"]; ?></b></div>
						<div class="NOMBRE">Fecha de inicio: <em><?php echo $fila["NOMBRE"]; ?></em></div>
						<div class="APELLIDOS">Fecha de finalización: <em><?php echo $fila["APELLIDOS"]; ?></em></div>
						
						<div class="SEXO">Plazas máximas: <em><?php echo $fila["SEXO"]; ?></em></div>
						<div class="USUARIO">Plazas ocupadas: <em><?php echo $fila["USUARIO"]; ?></em></div>
			</div>
				<?php } ?>

				</div>



				<div id="botones_fila">

				<?php if (isset($persona) and ($persona["NOMBRE"] == $fila["NOMBRE"])) { ?>

						<button id="grabar" name="grabar" type="submit" class="editar_fila">

							<img src="images/bag_menuito.bmp" class="editar_fila" alt="Guardar modificación">

						</button>

				<?php } else {?>

						<button id="editar" name="editar" type="submit" class="editar_fila">

							<img src="images/pencil_menuito.bmp" class="editar_fila" alt="Editar persona">

						</button>

				<?php } ?>

					<button id="borrar" name="borrar" type="submit" class="editar_fila">

						<img src="images/remove_menuito.bmp" class="editar_fila" alt="Borrar persona">

					</button>

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