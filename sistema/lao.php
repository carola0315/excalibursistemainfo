<?php

    session_start();

    include "../conexion_BD.php";

    if(!empty($_POST)){

        if(empty($_POST["id_orden"])){

            header("location: lista_ordenes.php");
            exit;
        }

        $id_Orden = $_POST['id_Orden'];
    }

	if(empty($_REQUEST['id']))
	{
		echo "No es posible generar la cotización.";

	}else{

		$id_Orden = $_REQUEST['id'];
		
	    $query = mysqli_query($conection,"SELECT o.id_orden, DATE_FORMAT(o.fecha_elaboracion, '%d/%m/%Y') as fecha_elaboracion, o.id_cliente, o.Nombre_evento, o.direccion_evento, 
                                            c.id_cotizacion, c.tipo_servicio, c.Usuario as comercial, DATE_FORMAT(c.fecha_evento, '%d/%m/%Y') as fecha_evento, o.estatus,  cl.Nombre_cliente, o.persona_evento, o.contacto_evento, o.estatus
                                            FROM orden_servicio o
                                            INNER JOIN cotizaciones c
                                            ON c.id_cotizacion = o.id_cotizacion
                                            INNER JOIN usuarios u 
                                            ON c.Usuario = u.Usuario 
                                            INNER JOIN clientes cl 
                                            ON c.id_cliente = cl.id_cliente 
                                            WHERE o.id_orden = $id_Orden");
        $result = mysqli_num_rows($query);
		
		if($result > 0){

			$orden = mysqli_fetch_assoc($query);
			$id_Orden = $orden['id_orden'];
            
		}	
	}  
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Proceso LAO</title>
    <link rel="stylesheet"  href="css/style_cotizacion.css">
</head>
<body>
	<?php include "includes/header.php"; ?>
    <section id="container">
        <div class = "data_delete">
            <i class="fas fa-project-diagram fa-5x"></i>
            <h1>LINEA DE ATENCION OPERATIVA LAO</h1>
            <br>
            <br>
            <br>
            <p>No. Orden:<span><?php echo $orden['id_orden']; ?></span></p>
            <p>Tipo de Servicio:<span><?php echo $orden['tipo_servicio']; ?></span></p>
            <p>Dirección evento:</label><?php echo $orden['direccion_evento']; ?></span></p>
            <p>Fecha Evento: </label><?php echo $orden['fecha_evento']; ?></span></p>
            <p>Asesor Comercial:</label><?php echo $orden ['comercial']; ?></span></p>
            <p>Nombre:</label> <?php echo $orden['Nombre_cliente']; ?></span></p>
            <p>Contacto:</label><?php echo $orden['persona_evento']; ?></span></p>
            <p>Telefono Contacto:</label><?php echo $orden['contacto_evento']; ?></span></p>
            <p>Estado Orden de Servicio: </label><?php 
                        if($orden["estatus"] == 0){
                            $estado = '<span class = "negada">Anulada</span>';
                        }else if($orden["estatus"]== 1){
                            $estado = '<span class = "pendiente">Orden Aprobada</span>';
                        }else if($orden["estatus"]== 2){
                            $estado = '<span class = "xejecutar">Pendiente ejecutar</span>';
                        }else if($orden["estatus"]==3){
                            $estado = '<span class = "ejecucion">Ejecución</span>';
                        }else{
                            $estado ='<span class = "aprobada">Ejecutada</span>';
                        }
                        echo $estado; ?></span></p>
        </div>
	</section>
	<section>
            <br>
            <form method = "post" class = "lao">
                <input type="hidden" name = "Orden_servicio"  value="<?php echo $id_Orden; ?>">
                <a href = "lista_ordenes.php" class = "btn_cancel">Cancelar</a>
                <a href = "orden_servicio.php?id=<?php echo $id_Orden; ?>" class = "btn_aceptar" id_orden = "<?php echo $id_Orden; ?>" id = "verOrden">Orden Servicio</a>
                <a href = "check_list.php?id=<?php echo $id_Orden; ?>" class = "btn_aceptar" id_orden = "<?php echo $id_Orden; ?>" id = "verCheckList">Check List</a>
                <a href = "costos.php?id=<?php echo $id_Orden; ?>" class = "btn_aceptar" id_orden = "<?php echo $id_Orden; ?>" id = "VerCostos">Costos</a>
                <a href = "Pos_venta.php?id=<?php echo $id_Orden; ?>" class = "btn_aceptar" id_orden = "<?php echo $id_Orden; ?>" id = "postventa">Post Venta</a>
                <a href = "Finalizar_orden.php?id=<?php echo $id_Orden; ?>" class = "btn_aceptar" id_orden = "<?php echo $id_Orden; ?>" id = "finalizar">Finalizar</a>
            </form>
        </div>
	</section>
</div>
<script type= "text/javascript">
    
</script>
</body>
</html>