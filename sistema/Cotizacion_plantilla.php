<?php

    session_start();

    include "../conexion_BD.php";
	require_once 'archivos_excel/vendor/autoload.php';
	
	use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};


	if(empty($_REQUEST['cl']) || empty($_REQUEST['c']))
	{
		echo "No es posible generar la cotización.";

	}else{
		$id_cliente = $_REQUEST['cl'];
		$id_cotizacion = $_REQUEST['c'];
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
										WHERE c.id_cotizacion = $id_cotizacion AND c.id_cliente = $id_cliente AND c.estatus != 10;");

		$result = mysqli_num_rows($query);
		
		if($result > 0){

			$cotizacion = mysqli_fetch_assoc($query);
			$id_cotizacion = $cotizacion['id_cotizacion'];

			if($cotizacion['estatus'] == 0){
				$anulada = '<img class="negada" src="img/negada.png" alt="negada">';
			}
		}	
	
	$query_productos = mysqli_query($conection,"SELECT p.Cod_producto, p.Nombre_producto, p.Descripcion, dc.Cantidad_dias, dc.Cantidad_producto, dc.Precio, ((dc.Cantidad_dias * dc.Cantidad_producto)* (dc.Precio)) as precio_total, p.Iva 
												FROM cotizaciones c 
												INNER JOIN detalle_cotizaciones dc 
												ON c.id_cotizacion = dc.id_cotizacion 
												INNER JOIN productos p 
												ON dc.Cod_producto = p.Cod_producto 
												WHERE c.id_cotizacion = $id_cotizacion");

		$result_detalle = mysqli_num_rows($query_productos);

		$detalleTabla ='';
        $sub_total = 0;
        $impuesto = 0;
        $impuesto2 = 0;
        $iva = 0;
        $total = 0;
        $arrayData = array();


		$excel = new Spreadsheet();
		$hojaActiva = $excel -> getActiveSheet();
		$hojaActiva -> setTitle("Cotizacion".$cotizacion['id_cotizacion']);

		$hojaActiva-> setCellValue('C2', $cotizacion['Nombre_cliente']);
		$hojaActiva-> setCellValue('C3', "Fecha evento: ".$cotizacion['fecha_evento']);
		$hojaActiva-> setCellValue('C4', "Asesor Comercial: ". $cotizacion['comercial']);
		$hojaActiva->getColumnDimension('A')->setWidth(8);
		$hojaActiva-> setCellValue('A6', 'Dias');
		$hojaActiva->getColumnDimension('B')->setWidth(8);
		$hojaActiva-> setCellValue('B6', 'Cantidad');
		$hojaActiva->getColumnDimension('C')->setWidth(50);
		$hojaActiva-> setCellValue('C6', 'Descripción');
		$hojaActiva->getColumnDimension('D')->setWidth(15);
		$hojaActiva-> setCellValue('D6', 'Valor Unitario');
		$hojaActiva->getColumnDimension('E')->setWidth(20);
		$hojaActiva-> setCellValue('E6', 'Valor Total');

		$fila = 7;

		if($result_detalle > 0){
			
			while ($data = mysqli_fetch_assoc($query_productos)){

				$iva = $data['Iva'];

				$precio_total= round($data['Cantidad_dias'] * $data['Cantidad_producto'] * $data['Precio']);
				$impuesto = round($precio_total * ($iva/100));
				$impuesto2 = round($impuesto2 + $impuesto);
				$sub_total = round($sub_total + $precio_total);
				$total = round($total + $precio_total);

			$hojaActiva -> setCellValue('C'.$fila, $data['Nombre_producto'].$data['Descripcion']);
			$hojaActiva -> setCellValue('A'.$fila, $data['Cantidad_dias']);
			$hojaActiva -> setCellValue('B'.$fila, $data['Cantidad_producto']);
			$hojaActiva -> setCellValue('D'.$fila, $data['Precio']);
			$hojaActiva -> setCellValue('E'.$fila, $precio_total);
			$fila++;

		}
	
		$total_sin_iva = round($sub_total);
        $total = round($total_sin_iva + $impuesto2);

			$hojaActiva -> setCellValue('D'.$fila, 'SUBTOTAL');
			$hojaActiva -> setCellValue('E'.$fila++, $total_sin_iva);
			$hojaActiva -> setCellValue('D'.$fila, 'Iva');
			$hojaActiva -> setCellValue('E'.$fila++, $impuesto2);
			$hojaActiva -> setCellValue('D'.$fila, 'TOTAL');
			$hojaActiva -> setCellValue('E'.$fila++, $total);
		}

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Cotizacion.xls"');
		header('Cache-Control: max-age=0');

	$writer = IOFactory::createWriter($excel, 'Xls');
	ob_get_clean();
	$writer->save('php://output'); 	

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cotizacion</title>
    <link rel="stylesheet"  href="css/style_cotizacion.css">
</head>
<body>
<!-- <img class="Negada" src="img/negada.png" alt="Negada"> -->
<div id="page_pdf">
	<table id="cotizacion_head">
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
			<td class="info_cotizacion">
				<div class="round">
					<span class="h3">Cotización</span>
					<p>No. Cotización: <strong><?php echo $cotizacion['id_cotizacion']; ?></strong></p>
					<p>Fecha: <?php echo $cotizacion['fecha_elaboracion']; ?></p>
					<p>Tipo de Servicio:<?php echo $cotizacion['tipo_servicio']; ?></p>
					<p>Ciudad Evento: <?php echo $cotizacion['ciudad_evento']; ?></p>
					<p>Lugar Evento:<?php echo $cotizacion['lugar_evento']; ?></p>
					<p>Fecha Evento Evento: <?php echo $cotizacion['fecha_evento']; ?></p>
					<p>Asesor Comercial: <?php echo $cotizacion ['comercial']; ?></p>
				</div>
			</td>
		</tr>
	</table>
	<table id="cotizacion_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Cliente</span>
					<table class="datos_cliente">
						<tr>
							<td><label>Nit:</label><p><?php echo $cotizacion['id_cliente']; ?></p></td>
							<td><label>Teléfono:</label> <p><?php echo $cotizacion['Telefono_cliente']; ?></p></td>
						</tr>
						<tr>
							<td><label>Nombre:</label> <p><?php echo $cotizacion['Nombre_cliente']; ?></p></td>
							<td><label>Dirección:</label> <p><?php echo $cotizacion['Direccion_cliente']; ?></p></td>
						</tr>
						<tr>
							<td><label>Contacto:</label> <p><?php echo $cotizacion['Contacto']; ?></p></td>
							<td><label>Telefono Contacto:</label> <p><?php echo $cotizacion['Telefono_contacto']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>


	<table id="cotizacion_detalle">
		<thead>
			<tr>
                <th width = "100px">Cantidad Dias</th>
                <th width = "100px">Cantidad</th>
                <th class="textleft">Descripción</th>
                <th width = "textright">Valor Unitario</th>
                <th width = "textright">Valor Total</th>
			</tr>
		</thead>
		<tbody id="detalle_cotizacion">
		 <?php

		if($result_detalle> 0){

			while ($data = mysqli_fetch_assoc($query_productos)){
				
		?>
		<tr>
            <td><?php echo $data['Cantidad_dias']?></td>
            <td><?php echo $data['Cantidad_producto']?> </td>
			<td class="textleft"><?php echo $data['Nombre_producto'].$data['Descripcion']?></td>
            <td><?php echo $data['Precio'] ?></td>
            <td><?php echo $precio_total ?></td>
		</tr>	
		<?php  
			
			}
		?>
		</tbody>
		<tfoot id="detalle_totales">
			<tr>
				<td colspan="3" class="textright"><span>SUBTOTAL.</span></td>
				<td class="textright"><span><?php echo $total_sin_iva; ?></span></td>
			</tr>
			<tr>
				<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
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
</div>

</body>
</html>