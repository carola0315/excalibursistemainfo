<?php
   
    session_start();

    include "../conexion_BD.php";
     
    //buscar cotizacion aprobada

    if(!empty($_POST)){
        
        if($_POST['action'] == 'OrdenServicio'){

            if(!empty($_POST['idOrden'])){

                $idOrden = $_POST['idOrden'];

                $query = mysqli_query($conection, "SELECT o.id_Orden, o.fecha_elaboracion as fecha_elaboracion, o.id_cliente, o.Nombre_evento, o.direccion_evento, 
                                                    c.id_cotizacion, c.tipo_servicio, c.Usuario as comercial, c.fecha_evento as fecha_evento, o.estatus,  cl.Nombre_cliente,
                                                    cl.Telefono_cliente, cl.Correo_cliente, cl.Contacto, cl.Telefono_contacto, o.persona_evento, o.contacto_evento, o.indicaciones, o.Hora_inicio, o.Hora_final,
                                                    o.asistentes, o.asistentes, o.cargo_persona_evento, o.Compromisos_cliente, o.observaciones1
                                                    FROM orden_servicio o
                                                    INNER JOIN cotizaciones c
                                                    ON c.id_cotizacion = o.id_cotizacion
                                                    INNER JOIN usuarios u 
                                                    ON c.Usuario = u.Usuario 
                                                    INNER JOIN clientes cl 
                                                    ON c.id_cliente = cl.id_cliente 
                                                    WHERE o.id_Orden = $idOrden");
                $result = mysqli_num_rows($query);
                mysqli_close($conection);
                
                $data='';

                if($result > 0){
                    $data = mysqli_fetch_assoc($query);
                }else{
                    $data = 0;
                }
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
            }   
            exit; 
        } 
    }
    
    if($_POST['action'] == 'detalleOrden'){
        
        if(empty($_POST['id_orden']))
       
        {
            echo 'ha ocurrido un error';

        }else{

            $idOrden  = $_POST['id_orden'];

            $query_detalle = mysqli_query($conection, "SELECT detalle.id_Orden, detalle.Cod_orden_detalle, detalle.Cod_producto, producto.Nombre_producto, producto.Descripcion, 
                                                       detalle.Cantidad_dias, detalle.Cantidad_producto, detalle.Precio, producto.Iva
                                                        FROM detalle_orden detalle
                                                        INNER JOIN productos producto
                                                        ON detalle.Cod_producto = producto.Cod_producto
                                                        INNER JOIN orden_servicio orden
                                                        ON orden.id_Orden = detalle.id_Orden
                                                        WHERE orden.id_Orden = $idOrden;");

            $result_detalle = mysqli_num_rows($query_detalle);

            $detalleTabla ='';
            $sub_total = 0;
            $impuesto2 = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if($result_detalle > 0) {
                
                while ($data = mysqli_fetch_assoc($query_detalle)){

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
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                    eliminar_product_orden('.$data['Cod_orden_detalle'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }
                
                $total_sin_iva = round($sub_total);
                $total = round($total_sin_iva + $impuesto2);

                $detalles_totales = '<tr>
                                        <td colspan="5" class ="textright">Subtotal</td>
                                        <td class ="textright">'.$total_sin_iva.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class ="textright">Impuestos</td>
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
    
    if($_POST['action'] == 'eliminar_product_orden'){

        if(empty($_POST['id_detalle']))

        {
            echo 'ha ocurrido un error';
        }else{
            
            $idOrden = $_POST['Orden_servicio'];
            $estatus = $_POST['estatus'];
            $id_detalle = $_POST['id_detalle'];

            if ($estatus == 1){

                $query = mysqli_query($conection, "DELETE FROM detalle_orden WHERE Cod_orden_detalle = $id_detalle");

                $query_detalle = mysqli_query($conection, "SELECT detalle.id_Orden, detalle.Cod_orden_detalle, detalle.Cod_producto, producto.Nombre_producto, producto.Descripcion, 
                                        detalle.Cantidad_dias, detalle.Cantidad_producto, detalle.Precio, producto.Iva, producto.estatus
                                        FROM detalle_orden detalle
                                        INNER JOIN productos producto
                                        ON detalle.Cod_producto = producto.Cod_producto
                                        INNER JOIN orden_servicio orden
                                        ON orden.id_Orden = detalle.id_Orden
                                        WHERE orden.id_Orden = $idOrden AND orden.estatus = 1;"); 

            $result_detalle = mysqli_num_rows($query_detalle);

            $detalleTabla ='';
            $sub_total = 0;
            $impuesto2 = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if($result_detalle > 0){
                
                while ($data = mysqli_fetch_assoc($query_detalle)){

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
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                    eliminar_product_orden('.$data['Cod_orden_detalle'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }
               
                $total_sin_iva = round($sub_total);
                $total = round($total_sin_iva + $impuesto2);

                $detalles_totales = '<tr>
                                        <td colspan="5" class ="textright">Subtotal</td>
                                        <td class ="textright">'.$total_sin_iva.'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class ="textright">Impuestos</td>
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
        }else{
            echo 'error no se permite eliminar producto';
        }
            mysqli_close($conection);
        }
        exit;
    }  
    
    if($_POST['action'] == 'add_producto_orden'){

       if(empty($_POST['producto']) || empty($_POST['cantidadDias']) || empty($_POST['cantidadProducto']) || empty($_POST['precioUni']))
        
       {
            echo 'ha ocurrido un error';
        }else{
            $codProducto = $_POST['producto'];
            $cantidadDias = $_POST['cantidadDias'];
            $cantidadProducto = $_POST['cantidadProducto'];
            $precioUni= $_POST['precioUni'];
            $idOrden = $_POST['Orden_servicio'];
            $estatus = $_POST['estatus'];

            if($estatus == 1){

            $query_detalle_orden = mysqli_query($conection, "CALL agregar_detalle_orden ($codProducto, $cantidadDias, $cantidadProducto,
                                                $precioUni, $idOrden)");

            $result = mysqli_num_rows($query_detalle_orden);

            
            $detalleTabla ='';
            $sub_total = 0;
            $impuesto2 = 0;
            $iva = 0;
            $total = 0;
            $arrayData = array();

            if($result > 0){
                
                while ($data = mysqli_fetch_assoc($query_detalle_orden)){

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
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_product_orden('.$data['Cod_orden_detalle'].');"><i class="fas fa-trash"></i></a>
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
            }else{
                echo ("Estado de la Orden no permite el adicionar producto");
            }
        } 
        exit;
    }

    if($_POST['action'] == 'editar_orden'){

        if(empty($_POST['id_Orden'])){

            echo "Error al consultar numero de Orden";

        }else{
            $idOrden = $_POST['id_Orden'];
        }
        
        $idOrden = $_POST['id_Orden'];
        $nombre_evento = $_POST['nombre_evento'];
        $direccion_evento = $_POST['direccion_evento'];
        $indicaciones = $_POST['indicaciones'];
        $fecha_evento = $_POST['fecha_evento'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_final = $_POST['hora_final'];
        $asistentes = $_POST['numero_asistentes'];
        $persona_cargo = $_POST['persona_cargo'];
        $telefono_cargo = $_POST['telefono_encargada'];
        $cargo_encargada = $_POST['cargo_encargada'];
        $obligaciones = $_POST['obligaciones_excalibur'];
        $compromisos = $_POST['compromisos_cliente'];
        $estatus = $_POST['estatus'];

        if($estatus == 1){

            $query = mysqli_query($conection, "SELECT * FROM detalle_orden WHERE id_Orden = $idOrden");

            $result = mysqli_num_rows($query);

            if($result > 0){

                $query_procesar = mysqli_query($conection, "CALL editar_orden ($idOrden, '$nombre_evento', '$direccion_evento', '$indicaciones',
                '$fecha_evento', '$hora_inicio', '$hora_final', $asistentes, '$persona_cargo', '$telefono_cargo', '$cargo_encargada', '$obligaciones',
                '$compromisos')");

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
        }else{
            echo "error";
        }
        mysqli_close($conection);
        exit;
    }

    if($_POST['action'] == 'procesarOrden'){

        if(empty($_POST['id_cliente'])){

            echo "Ingrese datos del cliente";
        }else{
            $idOrden = $_POST['id_orden'];
        }

        $idOrden = $_POST['id_orden'];

        $query_orden = mysqli_query($conection, "SELECT * FROM orden_servicio WHERE id_Orden = $idOrden AND estatus = 1");
        $result_orden = mysqli_num_rows($query_orden);

        if($result_orden < 0){

            echo "error";

        }else{
        
            $query = mysqli_query($conection, "SELECT * FROM detalle_orden WHERE id_Orden = $idOrden ");
            $result = mysqli_num_rows($query);

            if($result > 0){

                $query_crear_check=mysqli_query($conection, "CALL ejecutar_orden ($idOrden)");

                $query_procesar = mysqli_query($conection, "SELECT * FROM check_list WHERE id_Orden = $idOrden");

                $result_procesar= mysqli_num_rows($query_procesar);

                if($result_procesar > 0){
                
                    $data = mysqli_fetch_assoc($query_procesar);

                    echo json_encode($data, JSON_UNESCAPED_UNICODE);

                }else{
                    echo "error";
                }
            }else{
                echo "error";
            }
        }
    mysqli_close($conection);
    exit; 
    }


    if($_POST['action'] == 'procesarCheck'){
        
        if(empty($_POST['id_orden'])){

            echo "Ingrese datos del cliente";
        }else{
            $id_chec = $_POST['id_chec'];
        }

        $idOrden = $_POST['id_orden'];

        $query_check = mysqli_query($conection, "SELECT * FROM check_list chec 
                                                 JOIN check_detalle detalle 
                                                 ON chec.id_Orden = detalle.id_Orden
                                                 WHERE chec.id_Orden =  $idOrden");
        $result_check = mysqli_num_rows($query_check);

        if($result_check <= 0 ){

            echo "error";

        }else{
        
            $query = mysqli_query($conection, "SELECT * FROM detalle_orden WHERE id_Orden = $idOrden ");
            $result = mysqli_num_rows($query);

            if($result > 0){

                $query_actualizar = mysqli_query($conection, "UPDATE orden_servicio SET estatus = 3 WHERE id_Orden = $idOrden");
                $query_procesar = mysqli_query($conection, "SELECT * FROM check_list WHERE id_Orden = $idOrden");
                $result_detalle = mysqli_num_rows($query_procesar);

                if($result_detalle > 0){
                
                    $data = mysqli_fetch_assoc($query_procesar);
                    echo json_encode($data, JSON_UNESCAPED_UNICODE);
                }else{
                    echo "error update";
                }
            }else{
                echo "error";
            }
        } 
    mysqli_close($conection);
    exit; 
    }

    if($_POST['action'] == 'CheckList'){

        if(!empty($_POST['idOrden'])){

            $idOrden = $_POST['idOrden'];

            $query = mysqli_query($conection, "SELECT che.id_Orden, che.id_cliente, orden.Nombre_evento, orden.direccion_evento, orden.fecha_evento as fecha_evento,
                                                orden.estatus, cl.Nombre_cliente, orden.persona_evento, orden.contacto_evento, orden.indicaciones, orden.Hora_inicio, 
                                                orden.Hora_final, orden.asistentes, orden.cargo_persona_evento, che.obligaciones_excalibur, che.Compromiso_cliente,
                                                che.coordinador, che.Hora_llegada, che.Hora_salida, che.Condiciones_lugar, che.permisos, che.observaciones_generales,
                                                che.hse, che.comercial
                                                FROM orden_servicio orden 
                                                INNER JOIN check_list che 
                                                ON che.id_Orden = orden.id_Orden 
                                                INNER JOIN clientes cl 
                                                ON che.id_cliente = cl.id_cliente 
                                                WHERE che.id_Orden = $idOrden AND orden.estatus != 1;");
            $result = mysqli_num_rows($query);
            mysqli_close($conection);
            
            $data='';

            if($result > 0){
                $data = mysqli_fetch_assoc($query);
            }else{
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }   
        exit; 
    }

    if($_POST['action'] == 'detalleCheck'){

        if(empty($_POST['id_orden']))
       
        {
            echo 'ha ocurrido un error';

        }else{

            $idOrden  = $_POST['id_orden'];

            $query_detalle = mysqli_query($conection, "SELECT detalle.id_Orden, detalle.Cod_orden_detalle, detalle.Cod_producto, producto.Nombre_producto, producto.Descripcion, 
                                                        detalle.Cantidad_producto
                                                        FROM detalle_orden detalle
                                                        INNER JOIN productos producto
                                                        ON detalle.Cod_producto = producto.Cod_producto
                                                        INNER JOIN orden_servicio orden
                                                        ON orden.id_Orden = detalle.id_Orden
                                                        WHERE orden.id_Orden = $idOrden; ");

            $result_detalle = mysqli_num_rows($query_detalle);

            $query_personal = mysqli_query($conection, "SELECT che.id_Orden, perso.Cod_check, perso.Ced_empleado, perso.actividad, perso.Transporte, emple.Nombre_empleado, emple.Telefono_empleado
                                                        FROM check_detalle perso
                                                        INNER JOIN check_list che
                                                        ON perso.id_Orden = che.id_Orden
                                                        INNER JOIN empleados emple
                                                        ON perso.Ced_empleado = emple.Ced_empleado
                                                        WHERE che.id_Orden = $idOrden;");

            $result_personal = mysqli_num_rows($query_personal);

            $query_proveedor = mysqli_query($conection, "SELECT che.id_Orden, pro.Cod_check_pro, pro.cod_proveedor, pro.actividad, pro.Transporte, emple.Nombre_proveedor, emple.Telefono_proveedor
                                                        FROM check_detalle_pro pro
                                                        INNER JOIN check_list che
                                                        ON pro.id_Orden = che.id_Orden
                                                        INNER JOIN proveedores emple
                                                        ON pro.cod_proveedor = emple.cod_proveedor
                                                        WHERE che.id_Orden = $idOrden;");

            $result_proveedor = mysqli_num_rows($query_proveedor);

            $detalleTabla ='';
            $detallePersonal='';
            $detalleProveedor='';
            $arrayData = array();

            if($result_detalle > 0) {
                
                while ($data = mysqli_fetch_assoc($query_detalle)){

                    $detalleTabla .= '
                        <tr>
                            <td>'.$data['Nombre_producto'].'</td>
                            <td>'.$data['Descripcion'].'</td>
                            <td>'.$data['Cantidad_producto'].'</td>
                        </tr>';
                }

                while ($data = mysqli_fetch_assoc($query_personal)){

                    $detallePersonal .= '
                        <tr>
                            <td>'.$data['Ced_empleado'].'</td>
                            <td>'.$data['Nombre_empleado'].'</td>
                            <td>'.$data['Telefono_empleado'].'</td>
                            <td>'.$data['actividad'].'</td>
                            <td>'.$data['Transporte'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_personal('.$data['Cod_check'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                while ($data = mysqli_fetch_assoc($query_proveedor)){

                    $detalleProveedor .= '
                        <tr>
                            <td>'.$data['cod_proveedor'].'</td>
                            <td>'.$data['Nombre_proveedor'].'</td>
                            <td>'.$data['Telefono_proveedor'].'</td>
                            <td>'.$data['actividad'].'</td>
                            <td>'.$data['Transporte'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_proveedor('.$data['Cod_check_pro'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $arrayData['detalle'] = $detalleTabla;
                $arrayData['personal'] = $detallePersonal;
                $arrayData['proveedor'] = $detalleProveedor;
                
                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            }else{
                echo 'error';
            }
            mysqli_close($conection); 
        } 
        exit;
    }

    if($_POST['action'] == 'infoEmpleado'){
        
        $Ced_empleado = $_POST['cedEmpleado'];

        $query = mysqli_query($conection, "SELECT Ced_empleado, Nombre_empleado, Telefono_empleado
                                        FROM empleados WHERE Ced_empleado = $Ced_empleado AND Estado = 'activo'");

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

    if($_POST['action'] == 'add_empleado'){

        if(empty($_POST['cedEmpleado']) || empty($_POST['actividad']) || empty($_POST['transporte']))
         
        {
             echo 'ha ocurrido un error';
             
         }else{

             $id_check = $_POST['id_che'];
             $idOrden = $_POST['Orden_servicio'];
             $ced_empleado = $_POST['cedEmpleado'];
             $actvidad = $_POST['actividad'];
             $estatus = $_POST['estatus'];
             $transporte = $_POST['transporte'];
             
             if($estatus == 2){
 
             $query_detalle_orden = mysqli_query($conection, "CALL 	agregar_empleado ($idOrden, $id_check, $ced_empleado,
                                                '$actvidad', '$transporte')");
 
             $result = mysqli_num_rows($query_detalle_orden);
 
             $detalleTabla ='';
             $detallePersonal='';
             $arrayData = array();
 
             if($result > 0) {
                 
                 while ($data = mysqli_fetch_assoc($query_detalle_orden)){
 
                    $detallePersonal .= '
                         <tr>
                             <td>'.$data['Ced_empleado'].'</td>
                             <td>'.$data['Nombre_empleado'].'</td>
                             <td>'.$data['Telefono_empleado'].'</td>
                             <td>'.$data['actividad'].'</td>
                             <td>'.$data['Transporte'].'</td>
                             <td class= "">
                                 <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                         eliminar_personal('.$data['Cod_check'].');"><i class="fas fa-trash"></i></a>
                             </td>
                         </tr>';
                 }
 
                 $arrayData['personal'] = $detallePersonal;
                 
                 echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
             }else{
                 echo 'error';
             }
             mysqli_close($conection); 
         } 
         exit;
        }
    }

    if($_POST['action'] == 'infoProveedor'){
        
        $Ced_proveedor = $_POST['cedProveedor'];

        $query = mysqli_query($conection, "SELECT cod_proveedor, Nombre_proveedor, Telefono_proveedor
                                        FROM proveedores WHERE  cod_proveedor = $Ced_proveedor AND estatus = 1");

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

    if($_POST['action'] == 'add_proveedor'){

        if(empty($_POST['idProveedor']) || empty($_POST['actividad']) || empty($_POST['transporte']))
         
        {
             echo 'ha ocurrido un error';
             
         }else{

             $id_check = $_POST['id_che'];
             $idOrden = $_POST['Orden_servicio'];
             $id_proveedor = $_POST['idProveedor'];
             $actvidad = $_POST['actividad'];
             $estatus = $_POST['estatus'];
             $transporte = $_POST['transporte'];
             
             if($estatus == 2){
 
             $query_detalle_orden = mysqli_query($conection, "CALL 	agregar_proveedor ($idOrden, $id_check, $id_proveedor,
                                                '$actvidad', '$transporte')");
 
             $result = mysqli_num_rows($query_detalle_orden);
 
             $detalleTabla ='';
             $detallePersonal='';
             $detalleProveedor='';

             $arrayData = array();
 
             if($result > 0) {
                 
                 while ($data = mysqli_fetch_assoc($query_detalle_orden)){
 
                    $detalleProveedor .= '
                         <tr>
                             <td>'.$data['cod_proveedor'].'</td>
                             <td>'.$data['Nombre_proveedor'].'</td>
                             <td>'.$data['Telefono_proveedor'].'</td>
                             <td>'.$data['actividad'].'</td>
                             <td>'.$data['Transporte'].'</td>
                             <td class= "">
                                 <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                         eliminar_proveedor('.$data['Cod_check_pro'].');"><i class="fas fa-trash"></i></a>
                             </td>
                         </tr>';
                 }
 
                 $arrayData['proveedor'] = $detalleProveedor;
                 
                 echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
             }else{
                 echo 'error';
             }
             mysqli_close($conection); 
         } 
         exit;
        } 
    }


    if($_POST['action'] == 'eliminar_personal'){

        if(empty($_POST['id_personal']))

        {
            echo 'ha ocurrido un error';
        }else{
            
            $idOrden = $_POST['Orden_servicio'];
            $estatus = $_POST['estatus'];
            $id_detalle = $_POST['id_personal'];

            if ($estatus == 2){

                $query = mysqli_query($conection, "DELETE FROM check_detalle WHERE Cod_check = $id_detalle");

                $query_detalle = mysqli_query($conection, "SELECT che.id_Orden, perso.Cod_check, perso.Ced_empleado, perso.actividad, perso.Transporte, 
                                                        emple.Nombre_empleado, emple.Telefono_empleado
                                                        FROM check_detalle perso
                                                        INNER JOIN check_list che
                                                        ON perso.id_Orden = che.id_Orden
                                                        INNER JOIN empleados emple
                                                        ON perso.Ced_empleado = emple.Ced_empleado
                                                        WHERE che.id_Orden = $idOrden;"); 

            $result_detalle = mysqli_num_rows($query_detalle);

            $detalleTabla ='';
            $detallePersonal='';
            $arrayData = array();
 
             if($result_detalle> 0) {
                 
                 while ($data = mysqli_fetch_assoc($query_detalle)){
 
                    $detallePersonal .= '
                         <tr>
                             <td>'.$data['Ced_empleado'].'</td>
                             <td>'.$data['Nombre_empleado'].'</td>
                             <td>'.$data['Telefono_empleado'].'</td>
                             <td>'.$data['actividad'].'</td>
                             <td>'.$data['Transporte'].'</td>
                             <td class= "">
                                 <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                         eliminar_personal('.$data['Cod_check'].');"><i class="fas fa-trash"></i></a>
                             </td>
                         </tr>';
                 }
 
                 $arrayData['personal'] = $detallePersonal;
                 
                 echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
             }else{
                 echo 'error';
             }
             mysqli_close($conection); 
         } 
         exit;
        }
    }

    if($_POST['action'] == 'eliminar_proveedor'){

        if(empty($_POST['id_proveedor']))

        {
            echo 'ha ocurrido un error';
        }else{
            
            $idOrden = $_POST['Orden_servicio'];
            $estatus = $_POST['estatus'];
            $id_detalle = $_POST['id_proveedor'];

            if ($estatus == 2){

                $query = mysqli_query($conection, "DELETE FROM check_detalle_pro WHERE Cod_check_pro = $id_detalle");

                $query_detalle = mysqli_query($conection, "SELECT che.id_Orden, pro.Cod_check_pro, pro.cod_proveedor, pro.actividad, pro.Transporte, emple.Nombre_proveedor, emple.Telefono_proveedor
                                                FROM check_detalle_pro pro
                                                INNER JOIN check_list che
                                                ON pro.id_Orden = che.id_Orden
                                                INNER JOIN proveedores emple
                                                ON pro.cod_proveedor = emple.cod_proveedor
                                                WHERE che.id_Orden = $idOrden;"); 

                $result = mysqli_num_rows($query_detalle);
                
                $detalleTabla ='';
                $detallePersonal='';
                $detalleProveedor='';

                $arrayData = array();

                if($result > 0) {
                    
                    while ($data = mysqli_fetch_assoc($query_detalle)){

                    $detalleProveedor .= '
                            <tr>
                                <td>'.$data['cod_proveedor'].'</td>
                                <td>'.$data['Nombre_proveedor'].'</td>
                                <td>'.$data['Telefono_proveedor'].'</td>
                                <td>'.$data['actividad'].'</td>
                                <td>'.$data['Transporte'].'</td>
                                <td class= "">
                                    <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                            eliminar_proveedor('.$data['Cod_check_pro'].');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>';
                    }

                    $arrayData['proveedor'] = $detalleProveedor;
                 
                 echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
             }else{
                 echo 'error';
             }
             mysqli_close($conection); 
         } 
         exit;
        } 
    }


    if($_POST['action'] == 'editar_check'){

        if(empty($_POST['id_Orden'])){

            echo "Error al consultar numero de Orden";

        }else{
            $idOrden = $_POST['id_Orden'];
        }
        
        $idOrden = $_POST['id_Orden'];
        $id_che = $_POST['che'];
        $obligaciones = $_POST['obligaciones'];
        $compromisos = $_POST['compromiso'];
        $coordinador = $_POST['coordinador'];
        $llegada = $_POST['llegada'];
        $salida = $_POST['salida'];
        $condiciones = $_POST['condiciones'];
        $permisos = $_POST['permisos'];
        $generales = $_POST['generales'];
        $hse = $_POST['hse'];
        $comercial = $_POST['comercial'];
        $estatus = $_POST['estatus'];

        if($estatus == 2){

            $query = mysqli_query($conection, "SELECT * FROM detalle_orden WHERE id_Orden = $idOrden");

            $result = mysqli_num_rows($query);

            $query_personal = mysqli_query($conection, "SELECT * FROM check_detalle WHERE id_Orden = $idOrden AND id_check_list = $id_che ");

            $result_personal = mysqli_num_rows($query_personal);

            if($result > 0 || $result_personal > 0){

                $query_procesar = mysqli_query($conection, "CALL editar_check($idOrden, $id_che, '$obligaciones', '$compromisos', '$coordinador', '$llegada',
                                                                                '$salida', '$condiciones', '$permisos', '$generales', '$hse', '$comercial')");

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
        }else{
            echo "error";
        }
        mysqli_close($conection);
        exit;
    }

    if($_POST['action'] == 'Costos'){

        if(!empty($_POST['idOrden'])){

            $idOrden = $_POST['idOrden'];

            $query = mysqli_query($conection, "SELECT orden.id_Orden, orden.id_cliente, cli.Nombre_cliente, orden.fecha_evento, orden.Nombre_evento, orden.Precio_total, che.coordinador, orden.estatus
                                                FROM orden_servicio orden
                                                INNER JOIN clientes cli
                                                ON orden.id_cliente = cli.id_cliente
                                                INNER JOIN check_list che
                                                ON orden.id_Orden = che.id_Orden
                                                WHERE orden.id_Orden = $idOrden");
            $result = mysqli_num_rows($query);
            mysqli_close($conection);
            
            $data='';

            if($result > 0){
                $data = mysqli_fetch_assoc($query);
            }else{
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }  
        exit; 
    }

    if($_POST['action'] == 'add_costo'){

        if(empty($_POST['fecompra']) || empty($_POST['tipo_costo']) || empty($_POST['descripcion']) || empty($_POST['precio']))
         
        {
             echo 'ha ocurrido un error';
             
         }else{

             $idOrden = $_POST['Orden_servicio'];
             $fecompra = $_POST['fecompra'];
             $numsoporte = $_POST['numsoporte'];
             $tipocosto = $_POST['tipo_costo'];
             $descripcion = $_POST['descripcion'];
             $precioneto = $_POST['precio'];
             $estatus = $_POST['estatus'];
             
             if($estatus != 1){
 
             $query_detalle_costos = mysqli_query($conection, "CALL agregar_costo ($idOrden, '$fecompra', '$numsoporte', '$tipocosto',
                                                '$descripcion', '$precioneto')");
 
             $result = mysqli_num_rows($query_detalle_costos);
 
            $detalleTabla ='';
            $sub_total = 0;
            $total = 0;
            $arrayData = array();

            if($result > 0){
                
                while ($data = mysqli_fetch_assoc($query_detalle_costos)){

                    $precio_total= round($data['Precio_costo']);
                    $sub_total = round($sub_total + $precio_total);
                    $total = round($total + $precio_total);

                    $detalleTabla .= '
                        <tr>
                            <td>'.$data['Fecha_costo'].'</td>
                            <td>'.$data['numero_soporte'].'</td>
                            <td>'.$data['Tipo_costo'].'</td>
                            <td>'.$data['Descripcion_costo'].'</td>
                            <td class = "textcenter">'.$data['Precio_costo'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_costo('.$data['id_costo'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $detalles_totales = '
                                    <tr>
                                        <td colspan="5" class ="textright">TOTAL</td>
                                        <td class ="textright">'.$total.'</td>
                                    </tr>';
                $arrayData['detalle_costos'] = $detalleTabla;
                $arrayData['totales_costos'] = $detalles_totales;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            }else{
                echo 'error';
            } 
            mysqli_close($conection);
            }else{
                echo ("Estado de la Orden no permite el adicionar costos");
            }
        } 
        exit;
    }

    if($_POST['action'] == 'add_abono'){

        if(empty($_POST['feabono']) || empty($_POST['tipo_pago']) || empty($_POST['descripcion']) || empty($_POST['pago']))

        {
             echo 'ha ocurrido un error';
             
         }else{

             $idOrden = $_POST['OrdenServicio'];
             $feabono = $_POST['feabono'];
             $tipoPago = $_POST['tipo_pago'];
             $descripcion = $_POST['descripcion'];
             $factura = $_POST['factura'];
             $pago = $_POST['pago'];
            
             $query_detalle_abonos = mysqli_query($conection, "CALL agregar_abono ($idOrden, '$feabono', '$tipoPago', '$descripcion',
                                                '$factura', '$pago')");
 
             $result = mysqli_num_rows($query_detalle_abonos);
 
            $detallePagos ='';
            $sub_total2 = 0;
            $total2 = 0;
            $arrayData = array();

            if($result > 0){
                
                while ($data = mysqli_fetch_assoc($query_detalle_abonos)){

                    $precio_total2= round($data['precio_abono']);
                    $sub_total2 = round($sub_total2 + $precio_total2);
                    $total2 = round($total2 + $precio_total2);

                    $detallePagos .= '
                        <tr>
                            <td>'.$data['Fecha_abono'].'</td>
                            <td>'.$data['tipo_abono'].'</td>
                            <td>'.$data['soporte'].'</td>
                            <td>'.$data['factura'].'</td>
                            <td class = "textcenter">'.$data['precio_abono'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_abono('.$data['id_abono'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $totales_pago = '
                                    <tr>
                                        <td colspan="5" class ="textright">TOTAL</td>
                                        <td class ="textright">'.$total2.'</td>
                                    </tr>';
                $arrayData['detalle_pago'] = $detallePagos;
                $arrayData['totales_pago'] = $totales_pago;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            }else{
                echo 'error';
            } 
            mysqli_close($conection);
        } 
        exit;
    }
    
    if($_POST['action'] == 'detalleCostos'){


        if(empty($_POST['id_orden']))
       
        {
            echo 'ha ocurrido un error';

        }else{

            $idOrden  = $_POST['id_orden'];

            $query_costos = mysqli_query($conection, "SELECT * FROM costos WHERE id_Orden = $idOrden");
                                                        
            $result_costos = mysqli_num_rows($query_costos);

            $query_abonos = mysqli_query($conection, "SELECT * FROM abonos WHERE id_Orden = $idOrden");

            $result_abonos = mysqli_num_rows($query_abonos);

            $detalleTabla ='';
            $sub_total = 0;
            $total = 0;
            $arrayData = array();
            $detalleIngreso='';
            $sub_total2 = 0;
            $total2 = 0;
            
            if($result_costos > 0) {
                
                while ($data = mysqli_fetch_assoc($query_costos)){

                    $precio_total= round($data['Precio_costo']);
                    $sub_total = round($sub_total + $precio_total);
                    $total = round($total + $precio_total);

                    $detalleTabla .= '
                        <tr>
                            <td>'.$data['Fecha_costo'].'</td>
                            <td>'.$data['numero_soporte'].'</td>
                            <td>'.$data['Tipo_costo'].'</td>
                            <td>'.$data['Descripcion_costo'].'</td>
                            <td class = "textcenter">'.$data['Precio_costo'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_costo('.$data['id_costo'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $detalles_totales = '
                                    <tr>
                                        <td colspan="5" class ="textright">TOTAL</td>
                                        <td class ="textright">'.$total.'</td>
                                    </tr>';

                while ($data = mysqli_fetch_assoc($query_abonos)){

                    $precio_total2= round($data['precio_abono']);
                    $sub_total2 = round($sub_total2 + $precio_total2);
                    $total2 = round($total2 + $precio_total2);

                    $detalleIngreso .= '
                        <tr>
                            <td>'.$data['Fecha_abono'].'</td>
                            <td>'.$data['tipo_abono'].'</td>
                            <td>'.$data['soporte'].'</td>
                            <td>'.$data['factura'].'</td>
                            <td class = "textcenter">'.$data['precio_abono'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_abono('.$data['id_abono'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $totales_pago = '
                                    <tr>
                                        <td colspan="5" class ="textright">TOTAL</td>
                                        <td class ="textright">'.$total2.'</td>
                                    </tr>';
                                    
                $arrayData['detalle_costos'] = $detalleTabla;
                $arrayData['totales_costos'] = $detalles_totales;
                $arrayData['detalle_pago'] = $detalleIngreso;
                $arrayData['totales_pago'] = $totales_pago;
                 
                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            }else{
                echo 'error';  
            }
            mysqli_close($conection); 
        } 
        exit;
    } 

    if($_POST['action'] == 'eliminar_costo'){

        if(empty($_POST['id_costo']))

        {
            echo 'ha ocurrido un error';
        }else{
            
            $idOrden = $_POST['Orden_servicio'];
            $idCosto = $_POST['id_costo'];

            $query = mysqli_query($conection, "DELETE FROM costos WHERE id_costo = $idCosto");

            $query_detalle_costos = mysqli_query($conection, "SELECT * FROM costos WHERE id_Orden = $idOrden"); 

            $result = mysqli_num_rows($query_detalle_costos);
                
            $detalleTabla ='';
            $sub_total = 0;
            $total = 0;
            $arrayData = array();

            if($result > 0){
                
                while ($data = mysqli_fetch_assoc($query_detalle_costos)){

                    $precio_total= round($data['Precio_costo']);
                    $sub_total = round($sub_total + $precio_total);
                    $total = round($total + $precio_total);

                    $detalleTabla .= '
                        <tr>
                            <td>'.$data['Fecha_costo'].'</td>
                            <td>'.$data['numero_soporte'].'</td>
                            <td>'.$data['Tipo_costo'].'</td>
                            <td>'.$data['Descripcion_costo'].'</td>
                            <td class = "textcenter">'.$data['Precio_costo'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_costo('.$data['id_costo'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $detalles_totales = '
                                    <tr>
                                        <td colspan="5" class ="textright">TOTAL</td>
                                        <td class ="textright">'.$total.'</td>
                                    </tr>';
                $arrayData['detalle_costos'] = $detalleTabla;
                $arrayData['totales_costos'] = $detalles_totales;
                 
                 echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
             }else{
                 echo 'error';
             }
             mysqli_close($conection); 
         exit;
        } 
    }

    if($_POST['action'] == 'eliminar_abono'){

        if(empty($_POST['id_abono']))

        {
            echo 'ha ocurrido un error';
        }else{
            
            $idOrden = $_POST['Orden_servicio'];
            $idAbono = $_POST['id_abono'];

            $query = mysqli_query($conection, "DELETE FROM abonos WHERE id_abono = $idAbono");

            $query_detalle_abonos = mysqli_query($conection, "SELECT * FROM abonos WHERE id_Orden = $idOrden"); 

            $result = mysqli_num_rows($query_detalle_abonos);
                
            $detallePagos ='';
            $sub_total2 = 0;
            $total2 = 0;
            $arrayData = array();

            if($result > 0){
                
                while ($data = mysqli_fetch_assoc($query_detalle_abonos)){

                    $precio_total2= round($data['precio_abono']);
                    $sub_total2 = round($sub_total2 + $precio_total2);
                    $total2 = round($total2 + $precio_total2);

                    $detallePagos .= '
                        <tr>
                            <td>'.$data['Fecha_abono'].'</td>
                            <td>'.$data['tipo_abono'].'</td>
                            <td>'.$data['soporte'].'</td>
                            <td>'.$data['factura'].'</td>
                            <td class = "textcenter">'.$data['precio_abono'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" id=" '.$data['id_Orden'].' " onclick="event.preventDefault();
                                        eliminar_abono('.$data['id_abono'].');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>';
                }

                $totales_pago = '
                                    <tr>
                                        <td colspan="5" class ="textright">TOTAL</td>
                                        <td class ="textright">'.$total2.'</td>
                                    </tr>';
                $arrayData['detalle_pago'] = $detallePagos;
                $arrayData['totales_pago'] = $totales_pago;
                 
                 echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
             }else{
                 echo 'error';
             }
             mysqli_close($conection); 
         exit;
        } 
    }

    if($_POST['action'] == 'add_movimiento'){

        if(empty($_POST['tipo_movi']) || empty($_POST['descripcion_movi']) || empty($_POST['encargado']) || empty($_POST['valor_movi']))

        {
             echo 'ha ocurrido un error';
             
         }else{

             $Tipo = $_POST['tipo_movi'];
             $descripcion = $_POST['descripcion_movi'];
             $encargado = $_POST['encargado'];
             $valor = $_POST['valor_movi'];
             $fecha_limite = $_POST['fecha_limite'];
             $estado = $_POST['estado'];

            $query_saldo = mysqli_query($conection, "SELECT saldo FROM caja_menor ORDER BY id_movimiento DESC LIMIT 1");

            $saldo = mysqli_fetch_assoc($query_saldo);

            $saldoActual = $saldo['saldo'];

            if ($Tipo == 'Ingreso'){

                $saldo1 = $saldoActual + $valor;

                $query_caja = mysqli_query($conection, "CALL caja_menor ('$Tipo', '$descripcion', '$encargado', '$valor',
                                                '$fecha_limite', '$estado', '$saldo1')");

            }else{
                
                $saldo1 = $saldoActual - $valor;

                $query_caja = mysqli_query($conection, "CALL caja_menor ('$Tipo', '$descripcion', '$encargado', '$valor',
                                                '$fecha_limite', '$estado', '$saldo1')");
            }

            $result = mysqli_num_rows($query_caja);
 
            $detalleCaja ='';
            $arrayData = array();

            if($result > 0){
                
                while ($data = mysqli_fetch_assoc($query_caja)){

                    $detalleCaja .= '
                        <tr>
                            <td>'.$data['fecha_movimiento'].'</td>
                            <td>'.$data['tipo_movimiento'].'</td>
                            <td>'.$data['Descripcion'].'</td>
                            <td>'.$data['Nombre_empleado'].'</td>
                            <td class = "textcenter">'.$data['Valor_movimiento'].'</td>
                            <td>'.$data['fecha_limite'].'</td>
                            <td>'.$data['Estado'].'</td>
                            <td>'.$data['Legalizacion'].'</td>
                            <td class = "textcenter">'.$data['Saldo'].'</td>
                            <td class= "">
                                <a class = "link_delete" href="#" onclick="event.preventDefault();
                                    legalizar('.$data['id_movimiento'].');"><i class="fal fa-check"></i></a>
                            </td>
                        </tr>';
                }

                $arrayData['detalleCaja'] = $detalleCaja;

                echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
            }else{
                echo 'error';
            } 
            mysqli_close($conection);
        } 
        exit;
    }

    if($_POST['action'] == 'legalizar'){

        $movimiento = $_POST['movimiento'];

        $query = mysqli_query($conection, "SELECT caja.id_movimiento, caja.fecha_movimiento, caja.tipo_movimiento, caja.Descripcion, caja.Responsable, emple.Nombre_empleado,
                                caja.Valor_movimiento, caja.fecha_limite, caja.Estado, caja.Saldo, caja.Legalizacion
                                FROM caja_menor caja
                                INNER JOIN empleados emple
                                ON caja.Responsable = emple.Ced_empleado
                                WHERE caja.id_movimiento = $movimiento");

        $result = mysqli_num_rows($query);

        if($result > 0){

            $data = mysqli_fetch_assoc($query);
            
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit; 
        
    }

    if($_POST['action'] == 'add_legalizacion'){

        if(!empty ($_POST['valor_lega']) || !empty($_POST['saldo_lega']) || !empty($_POST['id_movimiento'])){
                    
        $valor_legalizado = $_POST['valor_lega'];
        $saldo_legalizacion = $_POST['saldo_lega'];
        $id_movimiento = $_POST['id_movimiento'];
        $responsable = $_POST['id_responsable'];
        $observacion = $_POST['observacion_lega'];
        $valor_movimiento = $_POST['valor_movimiento'];


        $query = mysqli_query ($conection, "INSERT INTO legalizaciones(id_movimiento, responsable, Valor_movimiento, Valor_legalizado, 
                                saldo_legalizacion, observacion_lega)VALUES($id_movimiento, '$responsable', $valor_movimiento, $valor_legalizado, $saldo_legalizacion, '$observacion')");
            
            if($query == false){

                $alert ='<p class="msg_error">Ha ocurrido un error</p>';

            }else{

                $query_consulta = mysqli_query($conection, "SELECT lega.id_legalizacion, caja.id_movimiento
                                                            FROM legalizaciones lega
                                                            INNER JOIN caja_menor caja
                                                            ON lega.id_movimiento = caja.id_movimiento
                                                            WHERE lega.id_movimiento = $id_movimiento");

                $result_consulta = mysqli_num_rows($query_consulta);

                if($result_consulta > 0){

                    $legalizaciones = mysqli_fetch_assoc($query_consulta);
                    $legal = $legalizaciones['id_legalizacion'];

                    $query_up = mysqli_query($conection, "UPDATE caja_menor SET Estado = 'Legalizado', Legalizacion = $legal
                                                       WHERE id_movimiento = '$id_movimiento' ");

                    
                }

            } 
        
        $query_caja = mysqli_query($conection, "SELECT caja.id_movimiento, caja.fecha_movimiento, caja.tipo_movimiento, caja.Descripcion, caja.Responsable,
                                                emple.Nombre_empleado, caja.Valor_movimiento, caja.fecha_limite, caja.Estado, caja.Saldo, caja.Legalizacion
                                                FROM caja_menor caja
                                                INNER JOIN empleados emple
                                                ON caja.Responsable = emple.Ced_empleado
                                                ORDER BY id_movimiento DESC"); 

        $result_caja = mysqli_num_rows($query_caja);

        $detalleCaja ='';
        $arrayData = array();

        if($result_caja > 0){

            while ($data = mysqli_fetch_assoc($query_caja)){

                $detalleCaja .= '
                    <tr>
                        <td>'.$data['fecha_movimiento'].'</td>
                        <td>'.$data['tipo_movimiento'].'</td>
                        <td>'.$data['Descripcion'].'</td>
                        <td>'.$data['Nombre_empleado'].'</td>
                        <td class = "textcenter">'.$data['Valor_movimiento'].'</td>
                        <td>'.$data['fecha_limite'].'</td>
                        <td>'.$data['Estado'].'</td>
                        <td>'.$data['Legalizacion'].'</td>
                        <td class = "textcenter">'.$data['Saldo'].'</td>
                        <td class= "">
                            <a class = "link_delete" href="#" onclick="event.preventDefault();
                                legalizar('.$data['id_movimiento'].');"><i class="fal fa-check"></i></a>
                        </td>
                    </tr>';
            }

            $arrayData['detalleCaja'] = $detalleCaja;
            echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
        }else{
            echo 'error';
        } 
        mysqli_close($conection);
        } 
        exit;
    }

    if($_POST['action'] == 'pago_legalizaciones'){
        
        $legalizacion = $_POST['legalizacion'];

        $queryUp = mysqli_query($conection, "UPDATE legalizaciones SET Estatus = 2 WHERE id_legalizacion = $legalizacion");

        $query = mysqli_query($conection, "SELECT legal.id_legalizacion, legal.fecha_legalizacion, legal.observacion_lega, legal.responsable,
                                            emple.Nombre_empleado, legal.saldo_legalizacion, legal.Estatus
                                            FROM legalizaciones legal
                                            INNER JOIN empleados emple
                                            ON legal.responsable = emple.Ced_empleado
                                            ORDER BY id_legalizacion DESC");

        $result = mysqli_num_rows($query);

        if($result > 0){

            $data = mysqli_fetch_assoc($query);
            
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo 'error';
        exit;
        
    }

    if($_POST['action'] == 'Postventa'){

        if(!empty($_POST['idOrden'])){

            $idOrden = $_POST['idOrden'];

            $query = mysqli_query($conection, "SELECT orden.id_Orden, orden.id_cliente, cli.Nombre_cliente, orden.fecha_evento, orden.Nombre_evento, orden.Precio_total, che.coordinador, orden.estatus,
                                                orden.persona_evento, orden.contacto_evento
                                                FROM orden_servicio orden
                                                INNER JOIN clientes cli
                                                ON orden.id_cliente = cli.id_cliente
                                                INNER JOIN check_list che
                                                ON orden.id_Orden = che.id_Orden
                                                WHERE orden.id_Orden = $idOrden AND orden.estatus = 3");

            $result = mysqli_num_rows($query);
            
            $data='';

            if($result > 0){

                $data = mysqli_fetch_assoc($query);

                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }else{

                $data = 0;
            }
            echo 'error';
            exit; 
        }
    }

    if($_POST['action'] == 'addPosventa'){

        if(!empty ($_POST['Orden']) || !empty($_POST['idCliente'])){
                    
            $Orden = $_POST['Orden'];
            $Cliente = $_POST['idCliente'];
            $usuario = $_POST['usuario'];
            $meContacto= $_POST['meContacto'];
            $contacto= $_POST['contacto'];
            $calHorarios = $_POST['calHorarios'];
            $desHorarios = $_POST['desHorarios'];
            $calPersonal = $_POST['calPersonal'];
            $desPersonal = $_POST['desPersonal'];
            $calElementos = $_POST['calElementos'];
            $desElementos = $_POST['desElementos'];
            $calDinamismo = $_POST['calDinamismo'];
            $usoEpp = $_POST['usoEpp'];
            $Ambiente = $_POST['Ambiente'];
            $desAmbiente = $_POST['desAmbiente'];
            $calGeneral = $_POST['calGeneral'];
            $desGeneral = $_POST['desGeneral'];
            $utiServicios = $_POST['utiServicios'];
            $conocioEx = $_POST['conocioEx'];
            $evento = $_POST['nombre_evento'];

            $query_post = mysqli_query($conection, "SELECT * FROM postventa WHERE id_Orden = $Orden");

            $result_post = mysqli_num_rows($query_post);
            
            if($result_post > 0){

                echo 'ha ocurrido un error';

            }else{
               
            $query_insert = mysqli_query($conection, "INSERT INTO postventa(id_Orden, id_cliente, Usuario, Metodo_llamada, Persona_encuesta, cumple_horarios, observacion_horarios, 
            personal, observaciones_personal, elementos, observaciones_elementos, dinamismo_actividades, personal_dotacion, medio_ambiente, observaciones_medio, servicio_general,
            observaciones_generales, excalibur_nuevamente, como_conocio, nombre_evento)VALUES($Orden, $Cliente, '$usuario', '$meContacto', '$contacto', $calHorarios, '$desHorarios',
            $calPersonal, '$desPersonal', $calElementos, '$desElementos', $calDinamismo, '$usoEpp', '$Ambiente', '$desAmbiente', $calGeneral, '$desGeneral', '$utiServicios', '$conocioEx',
            '$evento')");

            }

            $query_final = mysqli_query($conection, "SELECT * FROM postventa WHERE id_Orden = $Orden");

            $result_final = mysqli_num_rows($query_final);

            $data='';

            if($result_final > 0){

                $data = mysqli_fetch_assoc($query_final);

            }else{
                $data = 0;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } 
    }

    if($_POST['action'] == 'detallePost'){
        
        if(empty($_POST['id_orden']))
       
        {
            echo 'ha ocurrido un error';

        }else{

            $idOrden  = $_POST['id_orden'];

            $query_detalle = mysqli_query($conection, "SELECT * FROM postventa WHERE id_Orden = $idOrden");

            $result_detalle = mysqli_num_rows($query_detalle);

            $data='';

                if($result_detalle > 0){

                    $data = mysqli_fetch_assoc($query_detalle);

                }else{

                    $data = 0;
                }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            mysqli_close($conection); 
        } 
        exit;
    }

    if($_POST['action'] == 'Finalizar_orden'){

        print_r($_POST);
            
        $Orden = $_POST['idOrden'];
        $Cliente = $_POST['cliente'];
        $observacion = $_POST['Oblao'];
        $estatus = $_POST['Estatus'];

        $query = mysqli_query($conection, "SELECT id_Orden, id_cliente, estatus 
                                                FROM orden_servicio 
                                                WHERE id_Orden = $Orden AND estatus = 3");

        $result = mysqli_num_rows($query);

        if($result > 0){
                
            $query_fin = mysqli_query($conection, "INSERT lao(id_Orden, id_cliente, observacion)VALUES('$Orden', '$Cliente', '$observacion')");
            
            if($query_fin == false){

                $alert ='<p class="msg_error">Ha ocurrido un error</p>';
            }else{

                $query_actu = mysqli_query($conection, "UPDATE orden_servicio SET estatus = 4 WHERE id_Orden = $Orden AND estatus = 3");

            }

            $query_actu2 = mysqli_query($conection, "SELECT * FROM lao WHERE id_Orden =$Orden");

            $result_detalle = mysqli_num_rows($query_actu2);
                
                $data='';

                $data = mysqli_fetch_assoc($query_actu2);

            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }


?>