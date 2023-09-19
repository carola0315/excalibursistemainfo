<?php
    session_start();

    if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8){

        header("location: ./");
    }

    include "..//conexion_BD.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
    <title>Nueva Cotización</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id = "container">
        <div class = "title_page">
            <h1><i class="fas fa-cash-register"></i>Cotizaciones</h1>
        </div>
        <div class="datos_cliente">
            <div class ="action_cliente">
                <h4>Datos del cliente</h4>
                <a href="#" class="btn_new btn_new_cliente">Nuevo Cliente</a>
            </div>
            <form name ="form_new_cliente_cotizacion" id= "form_new_cliente_cotizacion" class="datos">
                <input type ="hidden" name = "action" value ="addCliente" >
                
                <div class="wd30">
                    <label>Nit o Cedula</label>
                    <input type = "text" name = "id_cliente" id= "id_cliente" >
                </div>
                <div class="wd60">
                    <label>Nombre</label>
                    <input type = "text" name = "Nombre_cliente" id= "Nombre_cliente" disabled required>
                </div>
                <div class="wd60">
                    <label>Dirección</label>
                    <input type = "text" name = "Direccion_cliente" id= "Direccion_cliente" disabled required>
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
                <div  id ="div_registro_cliente"  class="wd100">
                    <button type="submit" class ="btn_save"><i class="far fa-save fa-lg"></i>Crear cliente</button>
                </div>
            </form>
        </div>
        <div class = "datos_cotizacion">
            <h4>Datos Cotización</h4>
            <div class ="datos">
                <div class = "wd50">
                    <label>Comercial</label>
                    <p><?php echo $_SESSION ['Nombre_empleado']; ?></p>
                    <label>Fecha de evento</label>
                    <input type = "date" name = "fecha_evento" id = "fecha_evento">
                    
                </div>
                <div class =" wd50">
                    <label>Ciudad evento</label>
                    <input type = "text" name = "ciudad_evento" id = "ciudad_evento">
                    <label>Lugar evento</label>
                    <input type = "text" name = "lugar_evento" id = "lugar_evento">
                </div>
                <div class = "wd50">
                    <label>Tipo servicio</label>
                    <input type ="text" name = "tipo_servicio" id="tipo_servicio">
                    <label>Acciones</label>
                    <div id = "acciones_cotizacion">
                        <a href = "#" class = "btn_anular_cotizacion textcenter" id = "btn_anular_cotizacion"><i class="fas fa-trash"></i>Anular</a>
                        <a href = "#" class = "btn_new textcenter" id = "btn_procesar_cotizacion"><i class="fas fa-check"></i>Procesar</a>
                    </div>
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
                    <td> <a href="#" id ="add_producto_cotizacion" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
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
            <tbody id = "detalle_cotizacion">
                <!--Contenido ajax-->
            </tbody>
            <tfoot id = "detalles_totales">
                <!--Contenido ajax-->
            </tfoot>
        </table>
    </section>
    <?php include "includes/footer.php"; ?>

    <script type= "text/javascript">
        $(document).ready(function(){
            var usuario = '<?php echo $_SESSION['Usuario']; ?>';
            verDetalle(usuario);
        });
    </script>
</body>
</html>