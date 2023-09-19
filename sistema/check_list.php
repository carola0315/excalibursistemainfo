<?php
    session_start();
    include "..//conexion_BD.php";
    
    if(empty($_REQUEST['id_orden']))
	{
		echo "No es posible generar el check list.";

	}else{

		$idOrden = $_REQUEST['id_orden'];
		
		$query = mysqli_query($conection, "SELECT che.id_Orden, che.id_check_list, che.id_cliente, orden.Nombre_evento, orden.direccion_evento, orden.fecha_evento as fecha_evento,
                                            orden.estatus, cl.Nombre_cliente, orden.persona_evento, orden.contacto_evento, orden.indicaciones, orden.Hora_inicio, 
                                            orden.Hora_final, orden.asistentes, orden.cargo_persona_evento, orden.estatus
                                            FROM orden_servicio orden 
                                            INNER JOIN check_list che 
                                            ON che.id_Orden = orden.id_Orden 
                                            INNER JOIN clientes cl 
                                            ON che.id_cliente = cl.id_cliente 
                                            WHERE che.id_Orden = $idOrden AND orden.estatus != 1");

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
    <title>Check List</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id = "container">
        <div class = "title_page">
            <h1><i class="fal fa-list-ul"></i>Check List</h1>
        </div>
        <div class="datos_cliente">
                <div class="wd30">
                    <input type="hidden" name = "Check_list"  id= "Check_list" value="<?php echo $orden['id_Orden']; ?>">
                    <input type="hidden" name = "Estatus"  id= "Estatus" value="<?php echo $orden['estatus']; ?>">
                    <input type="hidden" name= "idChe" id = "idChe" value = "<?php echo $orden['id_check_list']; ?>">

                       <a href="#" id = "CheckList" Check = "<?php echo $orden['id_Orden']; ?>"
                            onclick="event.preventDefault();
                                    verDetalleCheck(<?php echo $orden['id_Orden']?>);"><i class="far fa-eye fa-2x"></i></a>

                        <a href="#" id = "imprimircheck" Check = "<?php echo $orden['id_Orden']; ?>" 
                            onclick="event.preventDefault();
                                    imprimirCheck(<?php echo $orden['id_Orden']?>);"><i class="fas fa-print fa-2x"></i></a>
                </div>
        </div>
        <div class = "datos_evento">
            <h4>Datos Evento</h4>
            <div class ="datos">
                <div class = "wd30">
                    <label>Nombre Marca</label>
                    <input type = "text" name = "Marca" id= "Marca" disabled required>
                    <label>Nombre de evento</label>
                    <input type = "text" name = "nombre_evento" id = "nombre_evento" disabled required>
                    <label>Direccion Evento</label>
                    <input type ="text" name = "direccion_evento" id="direccion_evento" disabled required>
                    <label>Indicaciones</label>
                    <input type ="text" name = "indicaciones" id="indicaciones" disabled required>
                </div>
                <div class =" wd50">
                    <label>Fecha evento</label>
                    <input type = "date" name = "fecha_evento" id = "fecha_evento" disabled required>
                    <label>Hora inicio </label>
                    <input type = "datetime" name = "hora_inicio" id = "hora_inicio" disabled required>
                    <label>Hora final</label>
                    <input type = "datetime" name = "hora_final" id = "hora_final" disabled required>
                    <label>Numero Asistentes</label>
                    <input type = "text" name = "asistentes" id = "asistentes" disabled required>
                </div>
                <div class = "wd50">
                    <label>Persona a cargo del evento</label>
                    <input type ="text" name = "persona_cargo" id="persona_cargo" disabled required>
                    <label>Telefono encargada</label>
                    <input type ="text" name = "contacto_evento" id="contacto_evento" disabled required>
                    <label>Cargo encargada</label>
                    <input type ="text" name = "cargo_encargada" id="cargo_encargada" disabled required>
                </div>
            </div>
        </div>
        <div class = "datos_evento">
            <div class ="datos">
                <div class = "Wd30">
                    <label>Obligaciones especificas de excalibur producciones</label>
                    <textarea rows = "5" cols = "50" id ="obligaciones_excalibur"></textarea>
                </div>
                <div class = "Wd30">
                    <label>Compromisos del cliente</label>
                    <textarea rows = "5" cols = "50" id = "compromisos_cliente"></textarea>
                </div>
            </div>
        </div>
        <div class = "datos_evento">
            <div class ="datos">
                <div class = "Wd30">
                    <label>Coordinador Evento</label>
                    <input type = "text" name = "coordinador" id = "coordinador">
                    <label>Hora llegada a Bodega</label>
                    <input type = "text" name = "hora_llegada" id = "hora_llegada">
                    <label>Hora Salida Bodega</label>
                    <input type = "text" name = "hora_salida" id = "hora_salida">
                </div>
                <div class = "Wd30">
                    <label>Condiciones del Lugar</label>
                    <textarea rows = "5" cols = "50" id ="condiciones_lugar"></textarea>
                </div>
                <div class = "Wd30">
                    <label>Acceso y Permisos</label>
                    <textarea rows = "5" cols = "50" id ="Accesos_permisos"></textarea>
                </div>
                <div>
                <label>Observaciones Generales</label>
                <textarea rows = "5" cols = "50" id ="Observaciones_generales"></textarea>
                </div>
            </div>
        </div>
        
        <table class = "tbl_venta">
            <thead> 
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody id = "detalle_producto">

            </tbody>
        </table>
        <table class = "tbl_venta">
            <thead>
                <tr>
                    <th width = "100px">Codigo</th>
                    <th>Nombre Colaborador</th>
                    <th>Telefono</th>
                    <th>Actividad</th>
                    <th width = "100px">Transporte</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><input type ="text" name = "txt_cod_empleado" id="txt_cod_empleado"></td>
                    <td id = "nombre_empleado">-</td>
                    <td id = "telefono_empleado">-</td>
                    <td><input type = "text" name ="actividad" id = "actividad"></td>
                    <td><input type = "text" name ="transporte" id = "transporte"></td>
                    <td><a href="#" id ="add_empleado" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
            </thead>
            <tbody id = "detalle_empleados">

            </tbody>
            <table class = "tbl_venta">
            <thead>
                <tr>
                    <th width = "100px">Codigo</th>
                    <th>Nombre Proveedor</th>
                    <th>Telefono</th>
                    <th>Actividad</th>
                    <th width = "100px">Transporte</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><input type ="text" name = "txt_cod_proveedor" id="txt_cod_proveedor"></td>
                    <td id = "nombre_proveedor">-</td>
                    <td id = "telefono_proveedor">-</td>
                    <td><input type = "text" name ="actividad_pro" id = "actividad_pro"></td>
                    <td><input type = "text" name ="transporte_pro" id = "transporte_pro"></td>
                    <td><a href="#" id ="add_proveedor" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
            </thead>
            <tbody id = "detalle_proveedor">

            </tbody>
        </table>
        <table>
        <div class = "datos_evento">
            <div class ="datos">
                <div class = "Wd30">
                    <label>Observaciones HSE</label>
                    <textarea rows = "7" cols = "50" id ="Observaciones_hse"></textarea>
                </div>
                <div>
                    <label>Observaciones Comercial</label>
                    <textarea rows = "7" cols = "50" id ="Observacion_comercial"></textarea>
                </div>
                    <div id = "acciones_cotizacion">
                        <label>Acciones</label>
                        <a href = "#" class = "btn_new textcenter" id = "editar_check"><i class="fas fa-edit"></i>Editar</a>
                        <a href = "#" class = "btn_new textcenter" id = "procesar_check"><i class="fas fa-check"></i>Procesar</a>
                    </div>
            </div>
        </div>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>
    <script type= "text/javascript">
    </script>
</body>
</html>