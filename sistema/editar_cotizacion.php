<?php

    session_start();

    
    if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8){

        header("location: ./");
    }

    include "../conexion_BD.php";

	if(empty($_REQUEST['cliente']) || empty($_REQUEST['coti']))
	{
		echo "No es posible generar la cotización.";

	}else{

		$id_cliente = $_REQUEST['cliente'];
		$id_cotizacion = $_REQUEST['coti'];
		$anulada = '';

		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}
	}

	$query = mysqli_query($conection,"SELECT c.id_cotizacion, DATE_FORMAT(c.fecha_elaboracion, '%d/%m/%Y') as fecha_elaboracion, c.id_cliente, c.tipo_servicio, c.ciudad_evento, c.lugar_evento, 
							DATE_FORMAT(c.fecha_evento, '%d/%m/%Y') as fecha_evento, c.estatus, c.Usuario as comercial, cl.Nombre_cliente, cl.Telefono_cliente, cl.Direccion_cliente, cl.Contacto, cl.Telefono_contacto 
										FROM cotizaciones c 
										INNER JOIN usuarios u 
										ON c.Usuario = u.Usuario 
										INNER JOIN clientes cl 
										ON c.id_cliente = cl.id_cliente
										WHERE c.id_cotizacion = $id_cotizacion AND c.id_cliente = $id_cliente AND c.estatus !=  2;");

		$result = mysqli_num_rows($query);
		
		if($result > 0){

			$cotizacion = mysqli_fetch_assoc($query);
			$id_cotizacion = $cotizacion['id_cotizacion'];

			if($cotizacion['estatus'] == 0){
				$anulada = '<img class="negada" src="img/negada.png" alt="negada">';
			}
		}	
	
	    $query_productos = mysqli_query($conection,"SELECT p.Cod_producto, p.Nombre_producto, p.Descripcion, p.Iva, dc.id_cotizacion, dc.id_coti_detalle, dc.Cantidad_dias, dc.Cantidad_producto, dc.Precio, ((dc.Cantidad_dias * dc.Cantidad_producto)* (dc.Precio)) as precio_total 
												FROM cotizaciones c 
												INNER JOIN detalle_cotizaciones dc 
												ON c.id_cotizacion = dc.id_cotizacion 
												INNER JOIN productos p 
												ON dc.Cod_producto = p.Cod_producto 
												WHERE c.id_cotizacion = $id_cotizacion");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
	<title>Editar Cotizacion</title>
    <link rel="stylesheet"  href="css/style_cotizacion.css">
</head>
<body>
<section id = "container">    
    <div id="editar_coti">
    <form action="" method ="post">
        <table id="editar_cotizacion">
            <tr>
                <td class="logo_cotizacion">
                    <div>
                        <img src="img/logo1.jpg">
                    </div>
                </td>
                <td class="info_empresa">
                    <div>
                        <span class="h2">COTIZACION SERVICIOS EXCALIBUR PRODUCCIONES SAS</span>
                        <p><?php echo $configuracion["Direccion"];?></p>
                        <p>Teléfono: <?php echo $configuracion["Telefono"];?></p>
                        <p>Email: <?php echo $configuracion["Correo"];?></p>
                    </div>
                </td>
            </tr>
        </table>
        <table id="cotizacion_cliente">
            <tr>   
                <div class="round">
                    <td class = "info_cliente">
                    <div class = "round">
                        <span class="h3">Cliente</span>
                        <p>Nit: <?php echo $cotizacion['id_cliente']; ?></p>
                        <p>Teléfono: <?php echo $cotizacion['Telefono_cliente']; ?></p>
                        <p>Nombre: <?php echo $cotizacion['Nombre_cliente']; ?></p>
                        <p>Dirección: <?php echo $cotizacion['Direccion_cliente']; ?></p>
                        <p>Contacto: <?php echo $cotizacion['Contacto']; ?></p>
                        <p>Telefono Contacto: <?php echo $cotizacion['Telefono_contacto']; ?></p>
                    </div>
                    <div id = "acciones_cotizacion">
                        <a href = "#" class = "btn_new textcenter" id = "procesar_editar" cotizacion = "<?php echo $cotizacion['id_cotizacion'];?>"
                        cliente = "<?php echo $cotizacion['id_cliente'];?>"><i class="fas fa-check"></i>Procesar</a>
                    </div>
                    </td>
                    <td class="info_cotizacion">
                    <div class="round">
                        <span class="h3">Cotización</span> 
                            <p>No. Cotización: <strong><?php echo $cotizacion['id_cotizacion']; ?></strong></p>
                            <p>Fecha:<?php echo $cotizacion['fecha_elaboracion']; ?></p>
                            <p>Tipo de Servicio:<?php echo $cotizacion['tipo_servicio']; ?></p>
                            <p>Ciudad Evento: <input type = "text" name = "ciudad_evento" id = "ciudad_evento" value = "<?php echo $cotizacion['ciudad_evento']; ?>"></p>
                            <p>Lugar Evento: <input type = "text" name = "lugar_evento" id = "lugar_evento" value = "<?php echo $cotizacion['lugar_evento']; ?>"></p>
                            <p>Fecha Evento: <input type = "date" name = "fecha_evento" id = "fecha_evento" value = "<?php echo $cotizacion['fecha_evento']; ?>"></p>
                            <p>Asesor Comercial: <?php echo $cotizacion ['comercial']; ?></p>
                    </div>
                    </td>
                    </table>
                </div> 
            </tr>
            
        </table>
        <table id="cotizacion_detalle">
            <thead>
                <tr>
                    <th width = "50px">Codigo</th>
                    <th class="textleft">Nombre</th>
                    <th class="textleft">Descripción</th>
                    <th width = "100px">Cantidad Dias</th>
                    <th width = "100px">Cantidad</th>
                    <th width = "textright">Valor Unitario</th>
                    <th width = "textright">Valor Total</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><input type ="text" name = "cod_producto_editar" id="cod_producto_editar"></td>
                    <td id ="Nombre">-</td>
                    <td id ="Descripcion">-</td>
                    <td><input type = "text" name ="cantidad_dias_editar" id = "cantidad_dias_editar" value = "0" min = "1" disabled></td>
                    <td><input type = "text" name ="cantidad_producto_editar" id = "cantidad_producto_editar" value = "0" min = "1" disabled></td>
                    <td><input type = "text" id = "txt_valor_unitario" class = "textright"></td>
                    <td id = "txt_valor_total" class = "textright">0</td>
                    <td> <a id ="add_producto_editar" class ="link_add" href="#" cotizacion = "<?php echo $cotizacion['id_cotizacion'];?>" cliente = ><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
                <tr>

                    <th class="textleft">Nombre</th>
                    <th class="textleft">Descripción</th>
                    <th width = "50px">Cantidad Dias</th>
                    <th width = "50px">Cantidad</th>
                    <th width = "textroght">Valor Unitario</th>
                    <th width = "textright">Valor Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id = "detalle_cotizacion_editar">
            <?php   

                $result_detalle = mysqli_num_rows($query_productos);

                $detalleTabla ='';
                $sub_total = 0;
                $impuesto2 = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result_detalle> 0){

                    while ($data = mysqli_fetch_assoc($query_productos)){
        
                        $iva = $data['Iva'];

                        $precio_total= round($data['Cantidad_dias'] * $data['Cantidad_producto'] * $data['Precio']);
                        $impuesto = round($precio_total * ($iva/100));
                        $impuesto2 = round($impuesto2 + $impuesto);
                        $sub_total = round($sub_total + $precio_total);
                        $total = round($total + $precio_total);
                        $total_sin_iva = round($sub_total);
                        $total = round($total_sin_iva + $impuesto2);

                   
                ?>  
                        <tr>
                            <td class="textleft"><?php echo $data['Nombre_producto']?></td>
                            <td class="textleft"><?php echo $data['Descripcion']?></td>
                            <td><?php echo $data['Cantidad_dias']?></td>
                            <td><?php echo $data['Cantidad_producto']?> </td>
                            <td><?php echo $data['Precio'] ?></td>
                            <td><?php echo $precio_total ?></td>
                            <td class= "">
                                    <a class = "link_delete" href="#" onclick="event.preventDefault();
                                                    eliminar_detalle_editar(<?php echo $data['id_coti_detalle']?>);"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>	
                <?php  } ?>
                    
            </tbody>
            <tfoot id = "detalles_totales_editar">
                <tr>
                    <td colspan="3" class="textright"><span>SUBTOTAL.</span></td>
                    <td class="textright"><span><?php echo $total_sin_iva; ?></span></td>
                </tr>
                <tr>
                    <td colspan="3" class="textright"><span>IVA (19%)</span></td>
                    <td class="textright"><span><?php echo $impuesto2; ?></span></td>
                </tr>
                <tr>
                    <td colspan="3" class="textright"><span>TOTAL.</span></td>
                    <td class="textright"><span><?php echo $total; ?></span></td>
                </tr>   
            <?php }  ?>
            </tfoot>
        </table>
        <div>
            <p class="nota">Cotización valida por 30 días, <br>pongase en contacto con tu asesor</p>
        </div>
    </form>
    </div>
</section>
</body>
</html>