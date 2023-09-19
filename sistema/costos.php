<?php
    session_start();

    if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8){

        header("location: ./");
    }
    
    include "..//conexion_BD.php";
    
        if(empty($_REQUEST['id_orden']))
            {
                echo "No existen costos para esta Orden";

            }else{

		        $idOrden = $_REQUEST['id_orden'];
		
                $query = mysqli_query($conection, "SELECT orden.id_Orden, orden.id_cliente, cli.Nombre_cliente, orden.fecha_evento, orden.Nombre_evento, orden.Precio_total, che.coordinador, orden.estatus
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
    <title>COSTOS</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id = "container">
        <div class = "title_page">
            <h1><i class="fas fa-cash-register"></i>COSTOS/INGRESOS POR EVENTO</h1>
        </div>
        <div class="datos_cliente">
    
                    <input type="hidden" name = "costos"  id= "costos" value="<?php echo $orden['id_Orden']; ?>">
                    <input type="hidden" name = "Estatus"  id= "Estatus" value="<?php echo $orden['estatus']; ?>">

                       <a href="#" id = "Costos" Costo = "<?php echo $orden['id_Orden']; ?>"
                            onclick="event.preventDefault();
                                    verDetalleCosto(<?php echo $orden['id_Orden']?>);"><i class="far fa-eye fa-2x"></i></a>
                </div>
        </div>
        <div class = "datos_evento">
            <h4>Datos Evento</h4>
            <div class ="datos">
                <div class = "wd30">
                    <label>Nit o Cedula de cliente</label>
                    <input type = "text" name = "identificacion" id= "identificacion" disabled required>
                    <label>Nombre Marca</label>
                    <input type = "text" name = "Marca" id= "Marca" disabled required>
                </div>
                <div class = "wd30">
                    <label>Nombre de evento</label>
                    <input type = "text" name = "nombre_evento" id = "nombre_evento" disabled required>
                    <label>Fecha evento</label>
                    <input type = "date" name = "fecha_evento" id = "fecha_evento" disabled required>
                    
                </div>
                <div class = "wd50">
                    <label>Coordinador del evento</label>
                    <input type = "text" name = "coordinador_evento" id= "coordinador_evento" disabled required>
                    <label>Precio Neto Evento</label>
                    <input type = "text" name = "Precio_total" id= "Precio_total" disabled required>
                </div>
            </div>
        </div>
        <h4>Información Costos</h4>
        <table class = "tbl_venta">
            <thead>
                <tr>
                    <th>Fecha Compra/pago</th>
                    <th>Numero Factura/Soporte</th>
                    <th>Tipo de Costo</th>
                    <th>Descripción</th>
                    <th width = "100px">Valor Costo</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><input type = "date" name = "fecha_costo" id = "fecha_costo"></td>
                    <td><input type = "text" name = "n_soporte" id = "n_soporte"></td>
                    <td>
                        <select name = "tipo_costo" id = "tipo_costo">
                            <option value = "freelance">Personal Freelance</option>
                            <option value = "Combustible">Gasto Combustible</option>
                            <option value = "Horas_extras">Horas Extras</option>
                            <option value = "Rodamiento">Rodamiento</option>
                            <option value = "Depreciacion">Depreciación</option>
                            <option value = "Alquiler_tercero">Alquiler Tercero</option>
                            <option value = "Viaticos">Viaticos</option>
                            <option value = "Consumible">Consumibles</option>
                        </select></td>
                    <td><input type ="text" name ="Descripcion_costo" id = "Descripcion_costo" placeholder= "Descripcion costo"></td>
                    <td><input type ="text" name ="Precio_costo" id = "Precio_costo" placeholder= "Precio costo"></td>
                    <td> <a href="#" id ="add_costo" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
                <tr>
                    <th>Fecha Compra/pago</th>
                    <th>Numero Factura/Soporte</th>
                    <th>Tipo de Costo</th>
                    <th>Descripción</th>
                    <th width = "100px">Valor Costo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id = "detalleCostos">
                <!--Contenido ajax-->
            </tbody>
            <tfoot id = "totalesCostos">
                <!--Contenido ajax-->
            </tfoot>
        </table>
        <h4>Información ingresos</h4>
        <table class = "tbl_venta">
            <thead>
                <tr>
                    <th>Fecha pago Factura</th>
                    <th>Tipo de Pago</th>
                    <th>Descripción Soporte</th>
                    <th>N. factura</th>
                    <th width = "100px">Valor Pago Realizado</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><input type = "date" name = "fecha_pago" id = "fecha_pago"></td>
                    <td>
                        <select name = "tipo_pago" id = "tipo_pago">
                            <option value = "Efectivo">Efectivo</option>
                            <option value = "Transferencia">Transferencia</option>
                            <option value = "Cheque">Cheque</option>
                            <option value = "Contraprestacion">Contraprestación</option>
                        </select></td>
                    <td><input type ="text" name ="Descripcion_soporte" id = "Descripcion_soporte"></td>
                    <td><input type ="text" name ="num_factura" id = "num_factura"></td>
                    <td><input type ="text" name ="Precio_pago" id = "Precio_pago"></td>
                    <td> <a href="#" id ="add_abono" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
                </tr>
                <tr>
                    <th>Fecha pago Factura</th>
                    <th>Tipo de Pago</th>
                    <th>Descripción Soporte</th>
                    <th>N. factura</th>
                    <th width = "100px">Valor Pago Realizado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id = "detalle_pago">
                <!--Contenido ajax-->
            </tbody>
            <tfoot id = "totales_pago">
                <!--Contenido ajax-->
            </tfoot>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>
    <script type= "text/javascript">
    </script>
</body>
</html>