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
					<p>ORDEN DE SERVICIO</p>
				</div>
			</td>
		</tr>
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
				<td><label>Fecha:</label><p>19-03-2023</p></td>
				<td><label>Numero de Orden:</label><p>03</p></td>
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
							<td><label>Razon Social:</label><p>NHSQ INGENIERIA</p></td>
							<td><label>Nit:</label> <p>901047361</p></td>
						</tr>
						<tr>
							<td><label>Dirección cliente:</label> <p>CL 143 N 9 55 AP 1202 ED TORRE CEDRO ROYAL ET 1</p></td>
							<td><label>Telefonos cliente:</label> <p>3134810020</p> <p>3134810020</p></td>
						</tr>
						<tr>
							<td><label>Nombre Contacto:</label> <p>Andres Cortes</p></td>
							<td><label>Correo cliente:</label> <p>acortes@cimaestudio.co</p></td>
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
					<th class="textleft">Descripción</th>
					<th width="50px">Cantidad dias.</th>
					<th width="50px">Cantidad.</th>
					<th class="textright" width="150px">Precio Unitario.</th>
					<th class="textright" width="150px"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
				<tr>
					<td>Plancha</td>
					<td class="textcenter">1</td>
					<td class="textcenter">1</td>
					<td class="textright">140.000</td>
					<td class="textright">140.000</td>
				</tr>
				<tr>
					<td>Plancha</td>
					<td class="textcenter">1</td>
					<td class="textcenter">1</td>
					<td class="textright">140.000</td>
					<td class="textright">140.000</td>
				</tr>
				<tr>
					<td>Plancha</td>
					<td class="textcenter">1</td>
					<td class="textcenter">1</td>
					<td class="textright">140.000</td>
					<td class="textright">140.000</td>
				</tr>
				<tr>
					<td>Plancha</td>
					<td class="textcenter">1</td>
					<td class="textcenter">1</td>
					<td class="textright">140.000</td>
					<td class="textright">140.000</td>
				</tr>
				<tr>
					<td>Plancha</td>
					<td class="textcenter">1</td>
					<td class="textcenter">1</td>
					<td class="textright">140.000</td>
					<td class="textright">140.000</td>
				</tr>
				<tr>
					<td>Plancha</td>
					<td class="textcenter">1</td>
					<td class="textcenter">1</td>
					<td class="textright">140.000</td>
					<td class="textright">140.000</td>
				</tr>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3" class="textright"><span>SUBTOTAL $</span></td>
					<td class="textright"><span>516.67</span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>IMPUESTOS $</span></td>
					<td class="textright"><span>516.67</span></td>
				</tr>
				<tr>
					<td colspan="3" class="textright"><span>TOTAL $</span></td>
					<td class="textright"><span>516.67</span></td>
				</tr>
		</tfoot>
	</table>
	<table>
		<div>
		<span class="h3">OBLIGACIONES ESPECIFICAS DE EXCALIBUR PRODUCCIONES</span>
			<tr>
				<td><label>CMJDUHEJBJBADJBDFJHJFBUU</label></td>
			</tr>
		</div>		
	</table>
	<table>
		<div>
		<span class="h3">COMPROMISOS DEL CLIENTE</span>
			<tr>
				<td><label>CMJDUHEJBJBADJBDFJHJFBUU</label></td>
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
		
	<table>
		<tr>
			<td><label><h3>Firma Asesor</h4></label><br>
			<p>__________________________</p></td>
		</tr>
		<tr>
			<td><label><h3>Firma cliente</h4></label><br>
			<p>_________________________</p></td>
		</tr>
	</table>  
	</div>
</div>
</body>
</html>