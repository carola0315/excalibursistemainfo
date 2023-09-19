<?php

    //print_r($_REQUEST);
    //exit;
    //echo base64_encode('2');
    //exit;
    session_start();

    if(empty($_SESSION['active']))
    {
        header('location: ../');
    }

    include "../../conexion_BD.php";
    require_once '../vendor/autoload.php';
    //use Dompdf\Dompdf;

    if(empty($_REQUEST['Check']))
    {
        echo "No es posible generar la factura.";
    }else{

        $idOrden= $_REQUEST['Check'];
        $anulada = '';

        $query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
        $result_config  = mysqli_num_rows($query_config);
        if($result_config > 0){
            $configuracion = mysqli_fetch_assoc($query_config);
        }

        $query = mysqli_query($conection,"SELECT chec.id_Orden, DATE_FORMAT(chec.fecha_elaboracion, '%d%m%Y') AS fecha, chec.coordinador, chec.Hora_llegada, chec.Hora_salida, orden.id_cliente, 
                                        cliente.Nombre_cliente, orden.Nombre_evento, DATE_FORMAT(orden.fecha_evento, '%d%m%Y') AS fecha_evento, orden.asistentes, orden.Hora_inicio, orden.Hora_final, 
                                        orden.direccion_evento, orden.indicaciones, orden.persona_evento, orden.contacto_evento, orden.cargo_persona_evento, chec.obligaciones_excalibur, 
                                        chec.Compromiso_cliente, chec.observaciones_generales, chec.Condiciones_lugar, chec.permisos, chec.comercial, chec.hse, orden.estatus
                                        FROM check_list chec
                                        INNER JOIN orden_servicio orden
                                        ON chec.id_Orden = orden.id_Orden
                                        INNER JOIN clientes cliente
                                        ON orden.id_cliente = cliente.id_cliente
                                        WHERE chec.id_Orden = $idOrden");

        $result = mysqli_num_rows($query);
        if($result > 0){

            $orden = mysqli_fetch_assoc($query);
            $no_Orden = $orden['id_Orden'];

            if($orden['estatus'] == 0){
                $anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
            }

            $query_productos = mysqli_query($conection,"SELECT detalle.id_Orden, producto.Cod_producto, producto.Nombre_producto, producto.Descripcion, 
                                                        detalle.Cantidad_producto
                                                        FROM detalle_orden detalle
                                                        INNER JOIN productos producto
                                                        ON detalle.Cod_producto = producto.Cod_producto
                                                        WHERE detalle.id_Orden = $no_Orden");

            $result_detalle = mysqli_num_rows($query_productos);

            $query_personal = mysqli_query($conection, "SELECT detalle.id_Orden, detalle.Ced_empleado, emple.Nombre_empleado, emple.Telefono_empleado, 
                                                        detalle.actividad, detalle.Transporte
                                                        FROM check_detalle detalle
                                                        INNER JOIN empleados emple
                                                        ON detalle.Ced_empleado = emple.Ced_empleado
                                                        WHERE detalle.id_Orden = $no_Orden");

            $result_personal = mysqli_num_rows($query_personal);

            $query_proveedor = mysqli_query($conection, "SELECT pro.id_Orden, pro.cod_proveedor, provee.Nombre_proveedor, provee.Telefono_proveedor, 
                                                        pro.actividad, pro.Transporte
                                                        FROM check_detalle_pro pro
                                                        INNER JOIN proveedores provee
                                                        ON pro.cod_proveedor = provee.cod_proveedor
                                                        WHERE pro.id_Orden = $no_Orden");

            $result_proveedor = mysqli_num_rows($query_proveedor);

            ob_start();

            include(dirname('__FILE__').'/chec.php');

            $html = ob_get_clean();

            // instantiate and use the dompdf class
			
            //$dompdf->loadHtml($html);
			
            $mpdf = new \Mpdf\MPDF(); 

			$mpdf->writeHTML($html);

			$mpdf->output("Check_List", "D");

			
            // (Optional) Setup the paper size and orientation
            //$dompdf->setPaper('letter', 'portrait');
            // Render the HTML as PDF
            //$dompdf->render();
            // Output the generated PDF to Browser
            //$dompdf->stream('Orden_Servicio'.$idOrden.'.pdf',array('Attachment'=>0));
            exit;
        }
    }

?>
