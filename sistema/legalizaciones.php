<?php
    session_start();

    include "../conexion_BD.php";


    $query_caja = mysqli_query($conection, "SELECT legal.id_legalizacion, legal.fecha_legalizacion, legal.observacion_lega, legal.responsable,
                                            emple.Nombre_empleado, legal.saldo_legalizacion, legal.Estatus
                                            FROM legalizaciones legal
                                            INNER JOIN empleados emple
                                            ON legal.responsable = emple.Ced_empleado
                                            ORDER BY id_legalizacion DESC");

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
            <div class ="datos">
                <label>Fecha</label>
                <input type = "text" name = "fecha_actual" id= "fecha_actual" value ="<?php echo fechaC(); ?>">
        </div>
        <div class = "datos_evento">
            <h4>Legalizaciones</h4>
            <h4></h4> 
        </div>
        <br>
        <table class = "tbl_venta">
            <thead>
                <tr>
                    <th width = 100px>Fecha Legalizacion</th>
                    <th>N° Legalización</th>
                    <th>Responsable</th>
                    <th width = "100px">Saldo</th>
                    <th>Observación</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id = "detalleLegalizaciones">

            <?php   

                $result_caja = mysqli_num_rows($query_caja);

                $detalleCaja ='';
                $arrayData = array();

                if($result_caja > 0){
                
                    while ($data = mysqli_fetch_assoc($query_caja)){
                   
                ?>  
                        <tr>
                            <td><?php echo $data['fecha_legalizacion'] ?></td>
                            <td><?php echo $data['id_legalizacion'] ?></td>
                            <td><?php echo $data['Nombre_empleado'] ?></td>
                            <td><?php echo $data['saldo_legalizacion'] ?></td>
                            <td><?php echo $data ['observacion_lega'] ?></td>
                            <td>
                            <?php 
                                    if($data["Estatus"] == 1){
                                        $estado = '<span class = "negada">Pendiente</span>';
                                    }else{
                                        $estado ='<span class = "aprobada">Pago</span>';
                                    }
                                    echo $estado; ?>
                            </td>
                            <td class= "">
                                <a class = "link_delete" href="#" onclick="event.preventDefault();
                                    pago_legalizaciones(<?php echo $data['id_legalizacion'] ?>);"><i class="fas fa-check-circle"></i></a>
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