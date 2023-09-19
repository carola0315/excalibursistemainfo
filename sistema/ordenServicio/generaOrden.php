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

    if(empty($_REQUEST['Orden']))
    {
        echo "No es posible generar la factura.";
    }else{

        $idOrden= $_REQUEST['Orden'];
        $anulada = '';

        $query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
        $result_config  = mysqli_num_rows($query_config);
        if($result_config > 0){
            $configuracion = mysqli_fetch_assoc($query_config);
        }

        $query = mysqli_query($conection,"SELECT orden.id_Orden, DATE_FORMAT(orden.fecha_elaboracion, '%d/%m/%Y') AS fecha, orden.id_cliente, cliente.Nombre_cliente, cliente.Direccion_cliente, cliente.Telefono_cliente, 
                                            cliente.Telefono_contacto, cliente.Contacto, cliente.Correo_cliente, orden.Nombre_evento, DATE_FORMAT(orden.fecha_evento, '%d/%m/%Y') AS fecha_evento, orden.asistentes, orden.Hora_inicio,
                                            orden.Hora_final, orden.direccion_evento, orden.indicaciones, orden.persona_evento, orden.contacto_evento, orden.cargo_persona_evento, 
                                            orden.observaciones1, orden.Compromisos_cliente, orden.estatus, orden.id_cotizacion, coti.Usuario
                                            FROM orden_servicio orden
                                            INNER JOIN clientes cliente
                                            ON orden.id_cliente = cliente.id_cliente
                                            INNER JOIN cotizaciones coti
                                            ON orden.id_cotizacion = coti.id_cotizacion
                                            WHERE orden.id_Orden = $idOrden");

        $result = mysqli_num_rows($query);
        if($result > 0){

            $orden = mysqli_fetch_assoc($query);
            $no_Orden = $orden['id_Orden'];

            if($orden['estatus'] == 0){
                $anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
            }

            $query_productos = mysqli_query($conection,"SELECT detalle.id_Orden, producto.Cod_producto, producto.Nombre_producto, producto.Descripcion, detalle.Cantidad_dias, 
                                                        detalle.Cantidad_producto, detalle.Precio, (detalle.Cantidad_dias * detalle.Cantidad_producto * detalle.Precio) as precio_total,
                                                        producto.Iva
                                                        FROM detalle_orden detalle
                                                        INNER JOIN productos producto
                                                        ON detalle.Cod_producto = producto.Cod_producto
                                                        WHERE detalle.id_Orden = $no_Orden");

            $result_detalle = mysqli_num_rows($query_productos);

            ob_start();

            include(dirname('__FILE__').'/orden.php');

            $html = ob_get_clean();

            // instantiate and use the dompdf class
			
            //$dompdf->loadHtml($html);
			
            $mpdf = new \Mpdf\MPDF(); 

			$mpdf->writeHTML($html);

			$mpdf->output("Orden_Servicio", "D");

			
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
