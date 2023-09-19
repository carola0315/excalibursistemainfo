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
					<p>CHECK LIST</p>
				</div>
				<?php
					}
				 ?>
			</td>
	</table>
	<table class = "datos_cliente">
		<tr>
		<td class="info_empresa">
			<div>
				<span class="h3">Datos de la solicitud</span><br>
				<span><label>Fecha:</label><p><?php echo $orden['fecha']; ?></p>
				<span><label>Numero de Orden:</label><p><?php echo $orden['id_Orden']; ?></p>
			</div>
		
		<td class="info_empresa">
			<div>
				<span class="h3">DATOS COORDINADOR</span><br>
				<span ><label>Nombre Coordinador: </label><p><?php echo $orden['coordinador'];?></p>
				<span ><label>Hora llegada Bodega: </label><p><?php echo $orden['Hora_llegada'];?></p>
				<span ><label>Hora salida Bodega: </label><p><?php echo $orden['Hora_salida'];?></p>
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
					<th width="50px">Cantidad.</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
			<?php
				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
			 ?>
				<tr>
					<td><?php echo $row['Nombre_producto']. $row['Descripcion']; ?></td>
					<td class="textcenter"><?php echo $row['Cantidad_producto']; ?></td>
				</tr>
			<?php
					}
				}
			?>
			</tbody>
			<thead>
				<tr>
					<th>Nombre Logistico</th>
                    <th>Telefono</th>
                    <th>Actividad</th>
					<th>Transporte</th>
				</tr>
			</thead>
			<tbody id="detalle_personal">
				<?php
					if($result_personal > 0){
						while($row2 = mysqli_fetch_assoc($query_personal)){

				?>
					<tr>
						<td class="textcenter"><?php echo $row2['Nombre_empleado']; ?></td>
						<td class="textcenter"><?php echo $row2['Telefono_empleado']; ?></td>
						<td class="textcenter"><?php echo $row2['actividad']; ?></td>
						<td class="textcenter"><?php echo $row2['Transporte']; ?></td>
					</tr>
				<?php
						}
					} 
				?>	
			</tbody>
			<thead>
				<tr>
					<th>Nombre Proveedor</th>
                    <th>Telefono</th>
                    <th>Actividad</th>
					<th>Transporte</th>
				</tr>
			</thead>
			<tbody id="detalle_proveedor">
			<?php
					if($result_personal > 0){
						while($row3 = mysqli_fetch_assoc($query_proveedor)){
				?>
				<tr>
					<td class="textcenter"><?php echo $row3['Nombre_proveedor']; ?></td>
					<td class="textcenter"><?php echo $row3['Telefono_proveedor']; ?></td>
					<td class="textcenter"><?php echo $row3['actividad']; ?></td>
					<td class="textcenter"><?php echo $row3['Transporte']; ?></td>
				</tr>
			<?php
					}
				} 
			?>	
			</tbody>
	</table>
	<table>
		<tr>
		<td class="info_empresa">
			<div>
				<span class="h3">Obligaciones Especificas de Excalibur</span>
				<textarea rows = "7" cols = "50" id ="Obligaciones_excalibur"><?php echo $orden['obligaciones_excalibur']; ?></textarea>
			</div>
		</td>
		
		<td class="info_empresa">
			<div>
				<span class="h3">Compromisos del Cliente</span>
				<textarea rows = "7" cols = "50" id ="Compromisos_cliente"><?php echo $orden['Compromiso_cliente']; ?></textarea>
			</div>
		</td>

		<td class="info_empresa">
			<div>
				<span class="h3">Observaciones generales</span>
				<textarea rows = "7" cols = "50" id ="Observaciones_generales"><?php echo $orden['observaciones_generales']; ?></textarea>
			</div>
		</td>
		</tr>
	</table>
	<table >
		<tr>
		<td class="info_empresa">
			<div>
				<span class="h3">Condiciones del lugar</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar"><?php echo $orden['Condiciones_lugar']; ?></textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">Accesos y permisos</span>
				<textarea rows = "7" cols = "50" id ="Accesos_permisos"><?php echo $orden['permisos']; ?></textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">Observaciones del Comercial</span>
				<textarea rows = "7" cols = "50" id ="Observaciones_comercial"><?php echo $orden['comercial']; ?></textarea>
			</div>
		</td>
		</tr>
	</table>
	<table>
		<tr>
		<td class="info_empresa">
			<div>
				<span class="h3">Observaciones HSE</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar"><?php echo $orden['hse']; ?></textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">V.B HSE</span>
				<textarea rows = "7" cols = "35" id ="vb_hse"></textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">V.B Asesor Comercial</span>
				<textarea rows = "7" cols = "35" id ="vb_comercial"></textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">V.B Jefe Operativo</span>
				<textarea rows = "7" cols = "35" id ="vb_operativo"></textarea>
			</div>
		</td>
		</tr>
	</table>
</div>
</body>
</html>