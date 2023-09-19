<?php

    session_start();

    include "../conexion_BD.php";

    if(empty($_REQUEST['id_orden']))
	{
		echo "No es posible generar la Orden de Servicio.";

	}else{

		$idOrden = $_REQUEST['id_orden'];
		
		$query = mysqli_query($conection, "SELECT orden.id_Orden, orden.id_cliente, cli.Nombre_cliente, orden.fecha_evento, orden.Nombre_evento, orden.direccion_evento,
                                            orden.persona_evento, orden.contacto_evento, che.coordinador, orden.estatus
                                            FROM orden_servicio orden
                                            INNER JOIN clientes cli
                                            ON orden.id_cliente = cli.id_cliente
                                            INNER JOIN check_list che
                                            ON orden.id_Orden = che.id_Orden
                                            WHERE orden.id_Orden = $idOrden");

        $result = mysqli_num_rows($query);
                    
        if($result > 0){

            $orden = mysqli_fetch_assoc($query);
            $idOrden = $orden['id_Orden'];
            
        }
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>CIERRE ORDEN</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class = "forms_register">
            <i class="far fa-check-circle" style="color: #0cd600"></i>
            <h2>CIERRE DE ORDEN DE SERVICIO</h2>
            <br>
            <p>N° DE ORDEN: <span><?php echo $idOrden; ?></span></p>
            <p>Cliente: <span><?php echo $orden['Nombre_cliente']; ?></span></p>
            <p>Fecha de evento: <span><?php echo $orden['fecha_evento'] ?></span></p>
            <br>

            <div class = "datos_cotizacion">
            <h4>Datos Cotización</h4>
            <div class ="datos">
                <div class = "wd50">
                <input type="hidden" name = "finalizar"  id= "finalizar" value="<?php echo $orden['id_Orden']; ?>">
                <input type="hidden" name = "Estatus"  id= "Estatus" value="<?php echo $orden['estatus']; ?>">
                <input type="hidden" name = "Cliente"  id= "Cliente" value="<?php echo $orden['id_cliente']; ?>">

                <label>Socialización Evento Reunión LAO</label>
                <textarea rows= "5" cols = "50" id= "text_fin"></textarea>
                    
            </div>
            <div class = "wd50">    
                <label>Acciones</label>
                    <div id = "acciones_cotizacion">
                        <a href = "lista_ordenes.php" class = "btn_cancel"><i class="fas fa-trash"></i>Cancelar</a>
                        <a href = "#" class = "btn_aceptar" id = "Finalizar_orden"><i class="fas fa-check"></i>Aceptar</a>
                    </div>
                </div>
            </div>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>