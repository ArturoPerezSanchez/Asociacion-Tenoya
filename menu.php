<ul class="topnav" id="myTopnav">
	<?php
		if(isset($_SESSION['login'])){
	?>
		<li onclick="location.href='/iissi';">
			<a><img src="images/inicio.png" class="inicioimg" height="18px" width="auto" alt="Inicio"></a>
		</li>
		<li onclick="location.href='consulta_actividades.php';"><a>Actividades</a></li>
	  	<li onclick="location.href='consulta_eventos.php';"><a>Eventos</a></li>
	  	<li onclick="location.href='consulta_encuestas.php';"><a>Encuestas</a></li>
		<li onclick="location.href='logout.php';"><a>Salir</a></li>
		<li class="icon" onclick="myToggleMenu()"><a>&#9776;</a></li>
	<?php
		} else {
	?>
		<li onclick="location.href='login.php';"><a>Login</a></li>
		<li class="singup" onclick="location.href='form_alta_usuario.php';"><a>Registrarse</a></li>
	<?php
		}
	?>
</ul>
