<?php
	$sub_total = 0;
	$impuesto2 = 0;
	$iva = 0;
	$total = 0;
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="img/logo.png">
				</div>
			</td>
			<td class="info_empresa">
				<?php
					if($result_config > 0){
				 ?>
				<div>
					<span class="h2"><?php echo strtoupper($configuracion['Nombre']); ?></span>
					<p>NIT: <?php echo $configuracion['Nit']; ?></p>
					<p>GESTION ADMINISTRATIVA</p>
					<p>ORDEN DE SERVICIO</p>
				</div>
				<?php
					}
				 ?>
			</td>
	</table>
	<table>
		<tr>
			<td class="info_empresa">
				<div>
					<span>EXCALIBUR PRODUCCIONES - Productor de eventos corporativos, sociales e infantiles - Agencia BTL - Alquiler de medios audiovisuales, 
						tarimas, carpas y demas, con operación nacional  www.excaliburproducciones.com</span>
				</div>
			</td>
		</tr>
	</table>
	<table class = "datos_cliente">
		<div>
		<span class="h3">Datos de la solicitud</span>
			<tr>
				<td><label>Fecha:</label><p><?php echo $orden['fecha']; ?></p></td>
				<td><label>Numero de Orden:</label><p><?php echo $orden['id_Orden']; ?></p></td>
			</tr>
		</div>	
	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Datos Cliente</span>
					<table class="datos_cliente">
						<tr>
							<td><label>Razon Social:</label><p><?php echo $orden['Nombre_cliente']; ?></p></td>
							<td><label>Nit:</label> <p><?php echo $orden['id_cliente']; ?></p></td>
						</tr>
						<tr>
							<td><label>Dirección cliente:</label> <p><?php echo $orden['Direccion_cliente']; ?></p></td>
							<td><label>Telefonos cliente:</label> <p><?php echo $orden['Telefono_cliente']; ?></p> <p><?php echo $orden['Telefono_contacto']; ?></p></td>
						</tr>
						<tr>
							<td><label>Nombre Contacto:</label> <p><?php echo $orden['Contacto']; ?></p></td>
							<td><label>Correo cliente:</label> <p><?php echo $orden['Correo_cliente']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Datos del Evento</span>
					<table class="datos_cliente">
						<tr>
							<td><label>Nombre Marca:</label><p><?php echo $orden['Nombre_cliente']; ?></p></td>
							<td><label>Nombre del evento</label><p><?php echo $orden['Nombre_evento']; ?></p></td>
							<td><label>Fecha evento:</label><p><?php echo $orden['fecha_evento']; ?></p></td>
						</tr>
						<tr>
							<td><label>Numero de Asistentes:</label><p><?php echo $orden['asistentes']; ?></p></td>
							<td><label>Hora inicio:</label><p><?php echo $orden['Hora_inicio']; ?></p></td>
							<td><label>Hora final:</label><p><?php echo $orden['Hora_final']; ?></p></td>
						</tr>
						<tr>
							<td><label>Direcion evento:</label><p><?php echo $orden['direccion_evento']; ?></p></td>
							<td><label>Indicaciones:</label><p><?php echo $orden['indicaciones']; ?></p></td>
						</tr>
						<tr>
							<td><label>Persona a cargo evento:</label><p><?php echo $orden['persona_evento']; ?></p></td>
							<td><label>Contacto:</label><p><?php echo $orden['contacto_evento']; ?></p></td>
							<td><label>Cargo:</label><p><?php echo $orden['cargo_persona_evento']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>

	<table id="factura_detalle">
			<thead>
				<tr>
					<th class="textleft">Descripción</th>
					<th width="50px">Cantidad dias.</th>
					<th width="50px">Cantidad.</th>
					<th class="textright" width="150px">Precio Unitario.</th>
					<th class="textright" width="150px"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
			<?php
				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){

						$iva = $data['Iva'];
			 ?>
				<tr>
					<td><?php echo $row['Nombre_producto']. $row['Descripcion']; ?></td>
					<td class="textcenter"><?php echo $row['Cantidad_dias']; ?></td>
					<td class="textcenter"><?php echo $row['Cantidad_producto']; ?></td>
					<td class="textright"><?php echo $row['Precio']; ?></td>
					<td class="textright"><?php echo $row['precio_total']; ?></td>
				</tr>
			<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total);
					}
				}

				$impuesto = round($precio_total * ($iva/100));
                $impuesto2 = round($impuesto2 + $impuesto);
				$total_sin_iva = round($sub_total);
                $total = round($total_sin_iva + $impuesto2);
			?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3" class="textright"><span>SUBTOTAL $.</span></td>
					<td class="textright"><span><?php echo $total_sin_iva; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IMPUESTOS $</span></td>
					<td class="textright"><span><?php echo $impuesto2; ?></span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL $.</span></td>
					<td class="textright"><span><?php echo $total; ?></span></td>
				</tr>
		</tfoot>
	</table>
	<table>
		<div>
		<span class="h3">OBLIGACIONES ESPECIFICAS DE EXCALIBUR PRODUCCIONES</span>
			<tr>
				<td><label><?php echo $orden['observaciones1']; ?></label></td>
			</tr>
		</div>		
	</table>
	<table>
		<div>
		<span class="h3">COMPROMISOS DEL CLIENTE</span>
			<tr>
				<td><label><?php echo $orden['Compromisos_cliente']; ?></label></td>
			</tr>
		</div>
	</table>
	<div>
			<p class="nota">Autorizo en forma previa, expresa e informada, como Titular de los datos personales comunicados a Excalibur Producciones S.A.S. (la “Compañía”), 
						con NIT. 900.612.569-1, el tratamiento de mis datos personales para: (i) cumplir y hacer cumplir las obligaciones entre la Compañía y el titular del dato; 
						(ii) comunicar información publicitaria y de mercadeo sobre los productos y servicios que ofrece, intermedia o comercializa la Compañía, 
						a través de medios físicos, digitales y de nuevas tecnologías de la información, tales como redes sociales, 
						mensajería instantánea y/o plataformas virtuales asociadas a los datos personales que comunico (como correo electrónico y número celular); 
						(iii) evaluar preferencias, experiencias sobre productos y hábitos de consumo; (iv) fidelizar clientes.</p>
	</div>
	<table>
		<tr>
			<td><label><h3>Firma Asesor</h4></label><br>
			<p><?php echo $orden['Usuario']; ?></p></td>
		</tr>
		<tr>
			<td><label><h3>Firma cliente</h4></label><br>
			<p>_________________________</p></td>
		</tr>
	</table>
</div>
</body>
</html>