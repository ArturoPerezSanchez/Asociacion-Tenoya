<?php
	session_start();
	if(!isset($_SESSION['login'])){
	Header("Location: login.php");
	}

	require_once("gestionBD.php");
?>


<?php

	include_once("cabecera.php");

	include_once("menu.php");
?>

<main>
	<div class="inicio">
<h1>La Asociación Tenoya Le da la Bienvenida</h1>
<p><strong>La Asociación de mayores</strong> , ubicada en la calle el de Molino Tenoya, 79, 35018 Las Palmas de Gran Canaria 
 <img src="images/inicio1.png" class="inicio1" alt="Foto1"> Ofrecemos un catálogo de <strong>actividades diarias</strong> para las personas de más avanzada edad tales como dominó, gimnasia, bingo, desayunos, etc.
<p>Además de las actividades, tambien realizamos diferentes <strong>eventos</strong> como excursiones, visitas guiadas o viajes.
<p>En espacio de la asociación es compartido, por lo que también. se dispone de otras instalaciones para todas las edades. Como son el <strong>bar </strong>que está justo al entrar al centro de mayores. o la recien remodelada <strong>peluquería</strong>. 
<p>También ponemos a disponibilidad de nuestros clientes el <strong>gimnasio</strong>, que dispone de una ámplia variedad de máquinas para el ejercicio físico y con frecuencia se realizan sesiones de gimnasia guiadas por un monitor en las que se realizan ejercicios en grupo fomentando la actividad física y el deporte en grupo.</p>
<p>Para ser <strong>miembro</strong>, sólo se necesita ser mayor de 55 años. y pagar una cuota de 15€ al año, con esto tendrás acceso a todas las actividades, eventos y podrás votar en las<strong> encuestas</strong>.</p>
Actualmente, con el motivo de la finalización del año 2018 se está organizando un viaje a maspalomas para ver la decoración navideña y celebrar juntos el comienzo del nuevo año.</p>

</main>
</div>


<?php

	include_once("pie.php");
?>

</body>

</html>
