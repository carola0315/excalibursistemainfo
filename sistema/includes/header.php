<?php

if(empty($_SESSION['active']))
{
    header('location: ../');
}

?>
<header>
	<div class="header">
			
		<h1>LINEA DE ATENCION OPERATIVA LAO</h1>
		<div class="optionsBar">
            <p>Villavicencio, <?php echo fechaC(); ?></p>
			<span>|</span>
			<span class="user"> <?php echo $_SESSION['Usuario'].'-'.$_SESSION ['perfil']; ?> </span>
			<img class="photouser" src="img/user.png" alt="Usuario">
			<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
		</div>
	</div>
    <?php include "navegacion.php"; ?>
</header>

<div class="modal">
	<div class ="bodyModal">
		<form action="" method="post" name= "legalizacion" id = "legalizacion" onsubmit="event.preventDefault(); enviarDatosLegalizacion();">
			<h1><i class="fas fa-check-circle" style="font-size: 30pt;"></i><br>Legalizar Movimiento</h1><br>
			<h2 class = "datos_movimiento" id = "responsable"></h2>
			<h2 class = "fecha_movimiento" id = "fecha_movimiento"></h2>
			<h2 class = "precio_movimiento" id = "precio_movimiento"></h2>
			<input type = "hidden" name = "valor_movimiento" id = "valor_movimiento" required><br>
			<input type = "text" name = "valor_lega" id="valor_lega" placeholder = "Valor legalizado" required> <br>
			<input type = "text" name = "saldo_lega" id="saldo_lega" placeholder = "Saldo legalizacion" required> <br>
			<input type = "text" name = "observacion_lega" id="observacion_lega" placeholder = "observación" required> <br>
			<input type = "hidden" name = "id_responsable" id="id_responsable" required>
			<input type = "hidden" name = "id_movimiento" id="id_movimiento" required>
			<input type = "hidden" name = "action" value ="add_legalizacion" required>
			<div class="alert alertLegalizacion"></div>
			<button type="submit" class = "btn_aceptar"><i class="fas fa-save"></i>Legalizar</button>
			<a href = "#" class = "btn_cancel" onclick="closeModal();">Cerrar</a>
		</form>	
	</div>
</div>
