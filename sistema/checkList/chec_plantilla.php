<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!--<img class="anulada" src="img/anulado.png" alt="Anulada"> -->
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="img/logo.png">
				</div>
			</td>
			<td class="info_empresa">
				<div>
					<span class="h2">EXCALIBUR PRODUCCIONES SAS</span>
					<p>NIT: 900612569-1</p>
					<p>GESTION ADMINISTRATIVA</p>
					<p>CHECK LIST</p>
				</div>
			</td>
		</tr>
	</table>
	<table class = "datos_cliente">
		<td class="info_empresa">
			<div>
				<span class="h3">Datos de la solicitud</span>
				<span><label>Fecha:</label><p>19-03-2023</p>
				<span><label>Numero de Orden:</label><p>03</p>
			</div>
		<td class="info_empresa">
			<div>
				<span class="h3">DATOS COORDINADOR</span>
				<span ><label>Nombre Coordinador: </label><p>Daniel Maldonado</p>
				<span ><label>Hora llegada Bodega: </label><p>8:00 am</p>
				<span ><label>Hora salida Bodega: </label><p>8:30 am</p>
			</div>
		</td>
	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Datos del Evento</span>
					<table class="datos_cliente">
						<tr>
							<td><label>Nombre Marca:</label><p>NHSQ INGENIERIA</p></td>
							<td><label>Nombre del evento</label><p>mercado campesino</p></td>
							<td><label>Fecha evento:</label><p>30 - 03 - 2023</p></td>
						</tr>
						<tr>
							<td><label>Numero de Asistentes:</label><p>40</p></td>
							<td><label>Hora inicio:</label><p>8:00 am</p></td>
							<td><label>Hora final:</label><p>5:00 pm</p></td>
						</tr>
						<tr>
							<td><label>Direcion evento:</label><p>plaza principal acacias</p></td>
							<td><label>Indicaciones:</label><p>plaza principa acacias local 45 frente a la alcaldia</p></td>
						</tr>
						<tr>
							<td><label>Persona a cargo evento:</label><p>Andres Cortes</p></td>
							<td><label>Contacto:</label><p>3209875642</p></td>
							<td><label>Cargo:</label><p>Coordinador</p></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<table id="factura_detalle">
			<thead>
				<tr>
					<th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
				<tr>
					<td class="textcenter">Plancha</td>
					<td class="textcenter">a vapor color azul</td>
					<td class="textcenter">1</td>
				</tr>
				<tr>
					<td class="textcenter">Plancha</td>
					<td class="textcenter">a vapor color azul</td>
					<td class="textcenter">1</td>
				</tr>
				<tr>
					<td class="textcenter">Plancha</td>
					<td class="textcenter">a vapor color azul</td>
					<td class="textcenter">1</td>
				</tr>
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
				<tr>
					<td class="textcenter">Roberto Reptil</td>
					<td class="textcenter">3245678902</td>
					<td class="textcenter">Logistico carpas</td>
					<td class="textcenter">Propio</td>
				</tr>
				<tr>
					<td class="textcenter">Roberto Reptil</td>
					<td class="textcenter">3245678902</td>
					<td class="textcenter">Logistico carpas</td>
					<td class="textcenter">Propio</td>
				</tr>
				<tr>
					<td class="textcenter">Roberto Reptil</td>
					<td class="textcenter">3245678902</td>
					<td class="textcenter">Logistico carpas</td>
					<td class="textcenter">Propio</td>
				</tr>
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
				<tr>
					<td class="textcenter">Erika Celis</td>
					<td class="textcenter">3245678902</td>
					<td class="textcenter">Manteles</td>
					<td class="textcenter">Recoger</td>
				</tr>
			</tbody>
	</table>
	<table>
		<td class="info_empresa">
			<div>
				<span class="h3">Obligaciones Especificas de Excalibur</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">Compromisos del Cliente</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">Observaciones generales</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
	</table>
	<table >
		<td class="info_empresa">
			<div>
				<span class="h3">Condiciones del lugar</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">Accesos y permisos</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">Observaciones del Comercial</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
	</table>
	<table>
		<td class="info_empresa">
			<div>
				<span class="h3">Observaciones HSE</span>
				<textarea rows = "7" cols = "50" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">V.B HSE</span>
				<textarea rows = "7" cols = "35" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">V.B Asesor Comercial</span>
				<textarea rows = "7" cols = "35" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
		<td class="info_empresa">
			<div>
				<span class="h3">V.B Jefe Operativo</span>
				<textarea rows = "7" cols = "35" id ="Condiciones_lugar">Daniel Maldonado</textarea>
			</div>
		</td>
	</table>
</div>
</body>
</html>