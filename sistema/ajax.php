<?php
   
    session_start();

    include "../conexion_BD.php";
     
    //buscar cliente para registro cotización

    if(!empty($_POST)){

        if($_POST['action'] == 'searchCliente'){

            if(!empty($_POST['id_cliente'])){

                $id_cliente = $_POST['id_cliente'];

                $query = mysqli_query($conection, "SELECT * FROM clientes WHERE id_cliente LIKE '$id_cliente' AND estatus= 1");
                mysqli_close($conection);
                $result = mysqli_num_rows($query);

                $data = '';
                if($result > 0){
                    $data = mysqli_fetch_assoc($query);
                }else{
                    $data = 0;
                }
                echo json_encode($data, JSON_UNESCAPED_UNICODE); 
            }   
            exit;
        }

        //registrar cliente cotizacion

        if($_POST['action'] == 'addCliente'){

            $id_cliente = $_POST['id_cliente'];
            $Nombre_cliente= $_POST['Nombre_cliente'];
            $Direccion_cliente = $_POST['Direccion_cliente'];
            $Telefono_cliente = $_POST['Telefono_cliente'];
            $Correo_cliente = $_POST['Correo_cliente'];
            $Contacto = $_POST['Contacto'];
            $Telefono_contacto = $_POST['Telefono_contacto'];

            $query_insert = mysqli_query($conection, "INSERT INTO clientes (id_cliente, Nombre_cliente, Direccion_cliente,
                                        Telefono_cliente, Correo_cliente, Contacto, Telefono_contacto) VALUES ('$id_cliente', '$Nombre_cliente','$Direccion_cliente',
                                        '$Telefono_cliente', '$Correo_cliente', '$Contacto', '$Telefono_contacto')");
            
            if($query_insert){
                $id_cliente = mysqli_insert_id($conection);
                $msg = $id_cliente;
            }else{
                $msg = 'error';
            }
            mysqli_close($conection);
            echo $msg;
            exit;
        }

        if($_POST['action'] == 'infoProducto'){
            
            $cod_producto = $_POST['txt_cod_producto'];

            $query = mysqli_query($conection, "SELECT Cod_producto, Nombre_producto, Descripcion, Precio, Iva
                                            FROM productos WHERE Cod_producto = $cod_producto AND estatus = 1");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);

            if($result > 0){

                $data= mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }

        //Agregar producto cot temporal
        
        if($_POST['action'] == 'add_producto_cotizacion'){

            if(empty($_POST['producto']) || empty($_POST['cantidadDias']) || empty($_POST['cantidadProducto']) || empty($_POST['precioUni']))
            {
                echo 'ha ocurrido un error';
            }else{
                $codProducto = $_POST['producto'];
                $cantidadDias = $_POST['cantidadDias'];
                $cantidadProducto = $_POST['cantidadProducto'];
                $precioUni= $_POST['precioUni'];
                $token = $_SESSION['Usuario'];

            

                $query_detalle_temp = mysqli_query($conection, "CALL agregar_detalle_cotizacion_temp($codProducto, $cantidadDias, $cantidadProducto,
                                                    $precioUni, '$token')");

                $result = mysqli_num_rows($query_detalle_temp);

                
                $detalleTabla ='';
                $sub_total = 0;
                $impuesto2 = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result > 0){
                    
                    while ($data = mysqli_fetch_assoc($query_detalle_temp)){

                        $iva = $data['Iva'];

                        $precio_total= round($data['Cantidad_dias'] * $data['Cantidad_producto'] * $data['Precio']);
                        $impuesto = round($precio_total * ($iva/100));
                        $impuesto2 = round($impuesto2 + $impuesto);
                        $sub_total = round($sub_total + $precio_total);
                        $total = round($total + $precio_total);

                        $detalleTabla .= '
                            <tr>
                                <td>'.$data['Cod_producto'].'</td>
                                <td colspan = "2">'.$data['Nombre_producto'].'</td>
                                <td colspan = "2">'.$data['Descripcion'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_dias'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_producto'].'</td>
                                <td class = "textcenter">'.$data['Precio'].'</td>
                                <td class = "textcenter">'.$precio_total.'</td>
                                <td class= "">
                                    <a class = "link_delete" href="#" onclick="event.preventDefault();
                                        del_product_detalle('.$data['cod_cotizacion_temp'].');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>';
                    }

                    //$impuesto2 = round($impuesto2 + $impuesto);
                    $total_sin_iva = round($sub_total);
                    $total = round($total_sin_iva + $impuesto2);

                    $detalles_totales = '<tr>
                                            <td colspan="5" class ="textright">Subtotal</td>
                                            <td class ="textright">'.$total_sin_iva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">IVA (19%)</td>
                                            <td class ="textright">'.$impuesto2.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">TOTAL</td>
                                            <td class ="textright">'.$total.'</td>
                                        </tr>';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalles_totales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
                mysqli_close($conection);
            }
            exit;
        } 

        //extrae datos del detalle temp cotizaciones

        if($_POST['action'] == 'verDetalles'){
            if(empty($_POST['user']))
            {
                echo 'ha ocurrido un error';
            }else{

                $token = $_SESSION['Usuario'];

                $query = mysqli_query($conection, "SELECT temp.cod_cotizacion_temp, temp.token_user, temp.Cantidad_dias,
                                                    temp.Cantidad_producto, temp.Precio, p.Cod_producto, p.Nombre_producto, p.Descripcion, p.Iva
                                                    FROM detalle_cotizaciones_temp  temp
                                                    INNER JOIN productos p
                                                    ON temp.Cod_producto = p.Cod_producto
                                                    WHERE token_user = '$token'");

                $result = mysqli_num_rows($query);
 
                $detalleTabla ='';
                $sub_total = 0;
                $impuesto = 0;
                $impuesto2 = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result > 0){

                    while ($data = mysqli_fetch_assoc($query)){

                        $iva = $data['Iva'];

                        $precio_total= round($data['Cantidad_dias'] * $data['Cantidad_producto'] * $data['Precio']);
                        $impuesto = round($precio_total * ($iva/100));
                        $impuesto2 = round($impuesto2 + $impuesto);
                        $sub_total = round($sub_total + $precio_total);
                        $total = round($total + $precio_total);

                        $detalleTabla .= '+
                            <tr>
                                <td>'.$data['Cod_producto'].'</td>
                                <td colspan = "2">'.$data['Nombre_producto'].'</td>
                                <td colspan = "2">'.$data['Descripcion'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_dias'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_producto'].'</td>
                                <td class = "textcenter">'.$data['Precio'].'</td>
                                <td class = "textcenter">'.$precio_total.'</td>
                                <td class= "">
                                    <a class = "link_delete" href="#" onclick="event.preventDefault();
                                        del_product_detalle('.$data['cod_cotizacion_temp'].');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>';
                    }

                    //$impuesto2 = round($impuesto2 + $impuesto);
                    $total_sin_iva = round($sub_total);
                    $total = round($total_sin_iva + $impuesto2);

                    $detalles_totales = '<tr>
                                            <td colspan="5" class ="textright">Subtotal</td>
                                            <td class ="textright">'.$total_sin_iva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">IVA (19%)</td>
                                            <td class ="textright">'.$impuesto2.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">TOTAL</td>
                                            <td class ="textright">'.$total.'</td>
                                        </tr>';
                                        
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalles_totales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
                mysqli_close($conection);
            }
            exit;
        } 

        if($_POST['action'] == 'del_product_detalle'){

            if(empty($_POST['id_detalle']))

            {
                echo 'ha ocurrido un error';
            }else{

                $id_detalle = $_POST['id_detalle'];
                $token = $_SESSION['Usuario'];


                $query_detalle_temp = mysqli_query($conection, "CALL Eliminar_detalle_temporal ( $id_detalle, '$token')");
                $result = mysqli_num_rows($query_detalle_temp);
 
                $detalleTabla ='';
                $sub_total = 0;
                $impuesto2 = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result > 0){
                    
                    while ($data = mysqli_fetch_assoc($query_detalle_temp)){

                        $iva = $data['Iva'];

                        $precio_total= round($data['Cantidad_dias'] * $data['Cantidad_producto'] * $data['Precio']);
                        $impuesto = round($precio_total * ($iva/100));
                        $impuesto2 = round($impuesto2 + $impuesto);
                        $sub_total = round($sub_total + $precio_total);
                        $total = round($total + $precio_total);

                        $detalleTabla .= '
                            <tr>
                                <td>'.$data['Cod_producto'].'</td>
                                <td colspan = "2">'.$data['Nombre_producto'].'</td>
                                <td colspan = "2">'.$data['Descripcion'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_dias'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_producto'].'</td>
                                <td class = "textcenter">'.$data['Precio'].'</td>
                                <td class = "textcenter">'.$precio_total.'</td>
                                <td class= "">
                                    <a class = "link_delete" href="#" onclick="event.preventDefault();
                                        del_product_detalle('.$data['cod_cotizacion_temp'].');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>';
                    }
                   
                    //$impuesto2 = round($impuesto2 + $impuesto);
                    $total_sin_iva = round($sub_total);
                    $total = round($total_sin_iva + $impuesto2);

                    $detalles_totales = '<tr>
                                            <td colspan="5" class ="textright">Subtotal</td>
                                            <td class ="textright">'.$total_sin_iva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">IVA (19%)</td>
                                            <td class ="textright">'.$impuesto2.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">TOTAL</td>
                                            <td class ="textright">'.$total.'</td>
                                        </tr>';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalles_totales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
                mysqli_close($conection);
            }
        }

        // Anular cotizacion
        if($_POST['action'] == 'anularCotizacion'){

            $token = $_SESSION['Usuario'];

            $query_eliminar = mysqli_query($conection, "DELETE FROM detalle_cotizaciones_temp WHERE token_user = '$token'");
            mysqli_close($conection);

            if($query_elimiar){
                echo 'Cotización eliminada';
            }else{
                echo 'error';
            }
            exit;
        }
        // Procesar cotizacion

        if($_POST['action'] == 'procesarCotizacion'){

            if(empty($_POST['id_cliente'])){

                echo "Ingrese datos del cliente";
            }else{
                $id_cliente = $_POST['id_cliente'];
            }

            $token = $_SESSION['Usuario'];
            $Usuario = $_SESSION['Usuario'];
            $fecha_evento = $_POST['fecha_evento'];
            $ciudad_evento = $_POST['ciudad_evento'];
            $lugar_evento = $_POST['lugar_evento'];
            $tipo_servicio = $_POST['tipo_servicio'];


            $query = mysqli_query($conection, "SELECT * FROM detalle_cotizaciones_temp WHERE token_user = '$token' ");
            $result = mysqli_num_rows($query);

            if($result > 0){

                $query_procesar = mysqli_query($conection, "CALL procesar_cotizacion ($id_cliente, '$Usuario', '$fecha_evento', '$ciudad_evento', '$lugar_evento', '$tipo_servicio', '$token')");
                $result_detalle = mysqli_num_rows($query_procesar);


                if($result_detalle > 0){
                   
                    $data = mysqli_fetch_assoc($query_procesar);
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                }else{
                    echo "error";
                }
            }else{
                echo "error";
            } 
        mysqli_close($conection);
        exit; 
        }

        if($_POST['action'] == 'addCliente'){

            $id_cliente = $_POST['id_cliente'];
            $Nombre_cliente= $_POST['Nombre_cliente'];
            $Direccion_cliente = $_POST['Direccion_cliente'];
            $Telefono_cliente = $_POST['Telefono_cliente'];
            $Correo_cliente = $_POST['Correo_cliente'];
            $Contacto = $_POST['Contacto'];
            $Telefono_contacto = $_POST['Telefono_contacto'];

            $query_insert = mysqli_query($conection, "INSERT INTO clientes (id_cliente, Nombre_cliente, Direccion_cliente,
                                        Telefono_cliente, Correo_cliente, Contacto, Telefono_contacto) VALUES ('$id_cliente', '$Nombre_cliente','$Direccion_cliente',
                                        '$Telefono_cliente', '$Correo_cliente', '$Contacto', '$Telefono_contacto')");
            
            if($query_insert){
                $id_cliente = mysqli_insert_id($conection);
                $msg = $id_cliente;
            }else{
                $msg = 'error';
            }
            mysqli_close($conection);
            echo $msg;
            exit;
        }

        if($_POST['action'] == 'verDetalles'){
            if(empty($_POST['user']))
            {
                echo 'ha ocurrido un error';
            }else{

                $token = $_SESSION['Usuario'];

                $query = mysqli_query($conection, "SELECT temp.cod_cotizacion_temp, temp.token_user, temp.Cantidad_dias,
                                                    temp.Cantidad_producto, temp.Precio, p.Cod_producto, p.Nombre_producto, p.Descripcion, p.iva
                                                    FROM detalle_cotizaciones_temp  temp
                                                    INNER JOIN productos p
                                                    ON temp.Cod_producto = p.Cod_producto
                                                    WHERE token_user = '$token'");

                $result = mysqli_num_rows($query);

 
                $detalleTabla ='';
                $sub_total = 0;
                $impuesto2 = 0;
                $iva = 0;
                $total = 0;
                $arrayData = array();

                if($result > 0){
                    
                    while ($data = mysqli_fetch_assoc($query)){

                        $iva = $data['Iva'];

                        $precio_total= round($data['Cantidad_dias'] * $data['Cantidad_producto'] * $data['Precio']);
                        $impuesto = round($precio_total * ($iva/100));
                        $impuesto2 = round($impuesto2 + $impuesto);
                        $sub_total = round($sub_total + $precio_total);
                        $total = round($total + $precio_total);

                        $detalleTabla .= '
                            <tr>
                                <td>'.$data['Cod_producto'].'</td>
                                <td colspan = "2">'.$data['Nombre_producto'].'</td>
                                <td colspan = "2">'.$data['Descripcion'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_dias'].'</td>
                                <td class = "textcenter">'.$data['Cantidad_producto'].'</td>
                                <td class = "textcenter">'.$data['Precio'].'</td>
                                <td class = "textcenter">'.$precio_total.'</td>
                                <td class= "">
                                    <a class = "link_delete" href="#" onclick="event.preventDefault();
                                        del_product_detalle('.$data['cod_cotizacion_temp'].');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>';
                    }

                    $impuesto2 = round($impuesto2 + $impuesto);
                    $total_sin_iva = round($sub_total);
                    $total = round($total_sin_iva + $impuesto2);

                    $detalles_totales = '<tr>
                                            <td colspan="5" class ="textright">Subtotal</td>
                                            <td class ="textright">'.$total_sin_iva.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">IVA ('.$iva.'%)</td>
                                            <td class ="textright">'.$impuesto2.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class ="textright">TOTAL</td>
                                            <td class ="textright">'.$total.'</td>
                                        </tr>';
                    $arrayData['detalle'] = $detalleTabla;
                    $arrayData['totales'] = $detalles_totales;

                    echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
                }else{
                    echo 'error';
                }
                mysqli_close($conection);
            }
            exit;

        }

        if($_POST['action'] == 'infoProductoEditar'){
            
            $cod_producto = $_POST['producto'];

            $query = mysqli_query($conection, "SELECT Cod_producto, Nombre_producto, Descripcion, Precio, Iva
                                            FROM productos WHERE Cod_producto = $cod_producto AND estatus = 1");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);

            if($result > 0){

                $data= mysqli_fetch_assoc($query);
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo 'error';
            exit;
        }

        if($_POST['action'] == 'add_producto_editar'){

            if(empty($_POST['producto']) || empty($_POST['cantidadDias']) || empty($_POST['cantidadProducto']) 
                || empty($_POST['precioUni'] || empty($_POST['id_cotizacion'])))
            {
                echo 'ha ocurrido un error';
            }else{
                $codProducto = $_POST['producto'];
                $cantidadDias = $_POST['cantidadDias'];
                $cantidadProducto = $_POST['cantidadProducto'];
                $precioUni= $_POST['precioUni'];
                $id_cotizacion= $_POST['id_cotizacion'];

                $query_detalle = mysqli_query($conection, "CALL agregar_detalle_editar ($codProducto, $cantidadDias, $cantidadProducto,
                                                    $precioUni, $id_cotizacion)");

                $result = mysqli_num_rows($query_detalle);

                mysqli_close($conection); 
            }
            exit;
        }

        if($_POST['action'] == 'eliminar_detalle_editar'){

            
            if(empty($_POST['id_detalle']))
            {
                echo 'Error';
            }else{

                $id_detalle = $_POST['id_detalle'];

                $query_detalle = mysqli_query($conection, "DELETE FROM detalle_cotizaciones WHERE id_coti_detalle = '$id_detalle'");


                mysqli_close($conection);

            }
            exit; 
        }

        if($_POST['action'] == 'procesar_editar'){
            
            if(empty($_POST['id_cliente'])){

                echo "Ingrese datos del cliente";
            }else{
                $id_cliente = $_POST['id_cliente'];
            }

            $id_cliente = $_POST['id_cliente'];
            $id_coti = $_POST['cotizacion'];
            $fecha_evento = $_POST['fecha_evento'];
            $ciudad_evento = $_POST['ciudad_evento'];
            $lugar_evento = $_POST['lugar_evento'];
            
            $query = mysqli_query($conection, "SELECT * FROM detalle_cotizaciones WHERE id_cotizacion = '$id_coti'");
            $result = mysqli_num_rows($query);

            if($result > 0){

                $query_procesar = mysqli_query($conection, "CALL procesar_editar_coti ('$id_coti', $id_cliente, '$fecha_evento',
                                                        '$ciudad_evento', '$lugar_evento')");
                $result_detalle = mysqli_num_rows($query_procesar);


                if($result_detalle > 0){
                   
                    $data = mysqli_fetch_assoc($query_procesar);
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                }else{
                    echo "error";
                }
            }else{
                echo "error";
            } 
        mysqli_close($conection);
        exit; 
            exit;
        }
    }
?>