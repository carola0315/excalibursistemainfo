<?php
    session_start();

    if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8){

        header("location: ./");
    }


    include "..//conexion_BD.php";
    
    if(empty($_REQUEST['id_orden']))
	{
		echo "No es posible generar la Orden de Servicio.";

	}else{

		$idOrden = $_REQUEST['id_orden'];
		
		$query = mysqli_query($conection, "SELECT o.id_Orden, DATE_FORMAT(o.fecha_elaboracion, '%d/%m/%Y') as fecha_elaboracion, o.id_cliente, o.Nombre_evento, o.direccion_evento, 
                                            c.id_cotizacion, c.tipo_servicio, c.Usuario as comercial, DATE_FORMAT(c.fecha_evento, '%d/%m/%Y') as fecha_evento, o.estatus,  cl.Nombre_cliente, 
                                            o.persona_evento, o.contacto_evento, o.estatus
                                            FROM orden_servicio o
                                            INNER JOIN cotizaciones c
                                            ON c.id_cotizacion = o.id_cotizacion
                                            INNER JOIN usuarios u 
                                            ON c.Usuario = u.Usuario 
                                            INNER JOIN clientes cl 
                                            ON c.id_cliente = cl.id_cliente 
                                            WHERE o.id_Orden = $idOrden AND c.estatus != 0");

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
    <title>Orden de Servicio</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id = "container">
        <div class = "title_page">
            <h1><i class="fal fa-list-ul"></i>Orden de Servicio</h1>
        </div>
        <div class="datos_cliente">
            <div class ="action_cliente">
                <h4>Datos del cliente</h4>
            </div>
                <div class="wd30">
                    <input type="hidden" name = "Orden"  id= "Orden_servicio" value="<?php echo $orden['id_Orden']; ?>">
                    <input type="hidden" name = "Estatus"  id= "Estatus" value="<?php echo $orden['estatus']; ?>">

                       <a href="#" id = "OrdenServicio" Orden = "<?php echo $orden['id_Orden']; ?>"
                            onclick="event.preventDefault();
                                    verDetalleOrden(<?php echo $orden['id_Orden']?>);"><i class="far fa-eye fa-2x"></i></a>

                        <a href="#" id = "imprimir" Orden = "<?php echo $orden['id_Orden']; ?>" 
                            onclick="event.preventDefault();
                                    imprimirOrden(<?php echo $orden['id_Orden']?>);"><i class="fas fa-print fa-2x"></i></a>
                </div>
            <form name ="nueva_orden" id= "nueva_orden" class="datos">
                <div class="wd30">
                    <label>Nit o Cedula</label>
                    <input type = "text" name = "id_cliente" id= "id_cliente_orden" disabled requerid >
                </div>
                <div class="wd60">
                    <label>Nombre</label>
                    <input type = "text" name = "Nombre_cliente" id= "Nombre_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Telefono</label>
                    <input type = "text" name = "Telefono_cliente" id= "Telefono_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Correo Electronico</label>
                    <input type = "text" name = "Correo_cliente" id= "Correo_cliente" disabled required>
                </div>
                <div class="wd30">
                    <label>Contacto</label>
                    <input type = "text" name = "Contacto" id= "Contacto" disabled required>
                </div>
                <div class="wd30">
                    <label>Telefono Contacto</label>
                    <input type = "text" name = "Telefono_contacto" id= "Telefono_contacto" disabled required>
                </div>
            </form>
        </div>
        <div class = "datos_evento">
            <h4>Datos Evento</h4>
            <div class ="datos">
                <div class = "wd30">
                    <label>Nombre Marca</label>
                    <input type = "text" name = "Marca" id= "Marca" disabled required>
                    <label>Nombre de evento</label>
                    <input type = "text" name = "nombre_evento" id = "nombre_evento">
                    <label>Direccion Evento</label>
                    <input type ="text" name = "direccion_evento" id="direccion_evento">
                    <label>Indicaciones</label>
                    <input type ="text" name = "indicaciones" id="indicaciones">
                </div>
                <div class =" wd50">
                    <label>Fecha evento</label>
                    <input type = "date" name = "fecha_evento" id = "fecha_evento">
                    <label>Hora inicio </label>
                    <input type = "datetime" name = "hora_inicio" id = "hora_inicio">
                    <label>Hora final</label>
                    <input type = "datetime" name = "hora_final" id = "hora_final">
                    <label>Numero Asistentes</label>
                    <input type = "text" name = "asistentes" id = "asistentes">
                </div>
                <div class = "wd50">
                    <label>Persona a cargo del evento</label>
                    <input type ="text" name = "persona_cargo" id="persona_cargo">
                    <label>Telefono encargada</label>
                    <input type ="text" name = "contacto_evento" id="contacto_evento">
                    <label>Cargo encargada</label>
                    <input type ="text" name = "cargo_encargada" id="cargo_encargada">
                    <label>Acciones</label>
                    <div id = "acciones_cotizacion">
                        <a href = "#" class = "btn_new textcenter" id = "editar_orden"><i class="fas fa-edit"></i>Editar</a>
                        <a href = "#" class = "btn_new textcenter" id = "procesar_orden"><i class="fas fa-check"></i>Procesar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class = "datos_evento">
            <div class = "datos">
                <div class ="wd50">
                    <label>Obligaciones especificas de excalibur producciones</label>
                    <textarea rows = "10" cols = "50" id ="obligaciones_excalibur"></textarea>
                </div>
                <div class ="wd50">
                    <label>Compromisos del cliente</label>
                    <textarea rows = "10" cols = "50" id = "compromisos_cliente"></textarea>
                </div>
            </div>
        </div>
        <table class = "tbl_venta">
            <thead>
                <tr>
                    <th width = "100px">Codigo</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th width = "100px">Cantidad Dias</th>
                    <th width = "100px">Cantidad</th>
                    <th width = "textright">Valor Unitario</th>
                    <th width = "textright">Valor Total</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><input type ="text" name = "txt_cod_producto" id="txt_cod_producto"></td>
                    <td id = "txt_Nombre">-</td>
                    <td id = "txt_Descripcion">-</td>
                    <td><input type = "text" name ="txt_cantidad_dias" id = "txt_cantidad_dias" value = "0" min = "1" disabled></td>
                    <td><input type = "text" name ="txt_cantidad" id = "txt_cantidad" value = "0" min = "1" disabled></td>
                    <td><input type = "text" id = "txt_valor_unitario" class = "textright"></td>
                    <td id = "txt_valor_total" class = "textright">0</td>
                    <td><a href="#" id ="add_producto_orden" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
                <tr>
                    <th>Código</th>
                    <th colspan= "2">Nombre</th>
                    <th colspan = "2">Descripción</th>
                    <th>Cantidad Dias</th>
                    <th>Cantidad</th>
                    <th class = "textright">Valor Unitario</th>
                    <th class = "textright">Valor Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id = "detalle_orden">

            </tbody>
            <tfoot id = "detalles_totales_orden">
               
            </tfoot>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>

    <script type= "text/javascript">
       
    </script>
    
</body>
</html>