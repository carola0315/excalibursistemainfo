<?php
    session_start();

    if($_SESSION['perfil'] == 4 || $_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8 ){

        header("location: ./");
    }


    include "../conexion_BD.php";


    $query_saldo = mysqli_query($conection, "SELECT Saldo FROM caja_menor ORDER BY id_movimiento DESC LIMIT 1");

    $result_saldo = mysqli_num_rows($query_saldo);

    if($result_saldo > 0){

        $saldo_caja = mysqli_fetch_assoc($query_saldo);
        $saldo = $saldo_caja['Saldo'];
    }

    $query_caja = mysqli_query($conection, "SELECT caja.id_movimiento, caja.fecha_movimiento, caja.tipo_movimiento, caja.Descripcion, caja.Responsable,
                                            emple.Nombre_empleado, caja.Valor_movimiento, caja.fecha_limite, caja.Estado, caja.Saldo, caja.Legalizacion
                                            FROM caja_menor caja
                                            INNER JOIN empleados emple
                                            ON caja.Responsable = emple.Ced_empleado
                                            ORDER BY id_movimiento DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
    <title>Caja Menor</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id = "container">
        <div class = "title_page">
            <h1><i class="fas fa-cash-register"></i>Caja Menor</h1>
        </div>
        <div class = "datos_evento">
            <h4>Saldos</h4>
            <div class ="datos">
                <div class = "wd30">
                    <label>Fecha</label>
                    <input type = "text" name = "fecha_actual" id= "fecha_actual" value ="<?php echo fechaC(); ?>">
                </div>
                <div class="wd30">
                    <label>Saldo en caja</label>
                    <input type = "text" name = "saldo" id= "saldo"  value = "<?php echo $saldo ?>" disabled required>
                </div>
            </div>
        </div>
        <div class = "datos_evento">
            <h4>Movimientos Caja Menor</h4>
            <h4></h4> 
        </div>
        <br>
        <table class = "tbl_venta">
            <thead>
                <tr>
                    <th>Tipo Movimiento</th>
                    <th>Descripcion</th>
                    <th>Responsable</th>
                    <th width = "100px">Valor Costo</th>
                    <th>Fecha Limite Legalizar</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
                <tr>
                    <td><select name = "tipo_movimiento" id = "tipo_movimiento">
                        <option value = "Ingreso">Ingreso</option>
                        <option value = "Gasto">Gasto</option>
                    </select></td>
                    <td><input type = "text" name = "descripcion" id = "descripcion"></td>
                    <td>
                        <?php
                            $query_personal = mysqli_query($conection, "SELECT * FROM empleados WHERE Estado LIKE '%activo%'");
                            $result_personal = mysqli_num_rows($query_personal);
                        ?>
                    <select name = "encargado" id = "encargado">
                        <?php
                         if($result_personal > 0)
                        {
                            while($personal = mysqli_fetch_array($query_personal)){
                        ?>          
                        <option value = "<?php echo $personal["Ced_empleado"]; ?>"><?php echo $personal["Nombre_empleado"]; ?></option>
                        <?php
                            }
                         }
                        ?>
                    </select></td>
                    <td><input type ="text" name ="Precio_movimiento" id = "Precio_movimiento"></td>
                    <td><input type = "date" name = "fecha_maxima" id = "fecha_maxima"></td>
                    <td><input type = "text" name = "Estado" id = "Estado" value = "Pendiente"  disabled></td>
                    <td> <a href="#" id ="add_movimiento" class ="link_add"><i class="fas fa-plus"></i>Agregar</a></td>
                    
                </tr>
                <tr>
                    <th width = 100px>Fecha Movimiento</th>
                    <th>Tipo Movimiento</th>
                    <th>Descripcion</th>
                    <th>Responsable</th>
                    <th width = "100px">Valor Costo</th>
                    <th>Fecha Limite Legalizar</th>
                    <th>Estado</th>
                    <th>Legalización n°</th>
                    <th width = "100px">Saldo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id = "detalleCaja">

            <?php   

                $result_caja = mysqli_num_rows($query_caja);

                $detalleCaja ='';
                $arrayData = array();

                if($result_caja > 0){
                
                    while ($data = mysqli_fetch_assoc($query_caja)){
                   
                ?>  
                        <tr>
                            <td><?php echo $data['fecha_movimiento'] ?></td>
                            <td><?php echo $data['tipo_movimiento'] ?></td>
                            <td><?php echo $data['Descripcion'] ?></td>
                            <td><?php echo $data['Nombre_empleado'] ?></td>
                            <td class = "textcenter"><?php echo $data['Valor_movimiento'] ?></td>
                            <td><?php echo $data ['fecha_limite'] ?></td>
                            <td><?php
                                    if($data["Estado"] == "Pendiente"){
                                        $estado = '<span class = "negada">Pendiente</span>';
                                    }else{
                                        $estado ='<span class = "aprobada">Legalizado</span>';
                                    }
                                    echo $estado; ?>
                            </td>
                            <td><?php echo $data['Legalizacion'] ?></td>
                            <td class = "textcenter"> <?php echo $data['Saldo'] ?></td>
                            <td class= "">
                                <a class = "link_delete" href="#" onclick="event.preventDefault();
                                    legalizar(<?php echo $data['id_movimiento'] ?>);"><i class="fas fa-check-circle"></i></a>
                            </td>
                        </tr>
                <?php  } 
                
                    }?>
                <!--Contenido ajax-->
            </tbody>

    </section>
    <?php include "includes/footer.php"; ?>
    <script type= "text/javascript">
    </script>
</body>
</html>