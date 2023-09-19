<?php

    session_start();

    if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8){

        header("location: ./");
    }

    include "../conexion_BD.php";

    $query = mysqli_query($conection, "SELECT DISTINCT COUNT(fecha_evento) FROM cotizaciones WHERE estatus = 2 GROUP BY fecha_evento LIMIT 1");
            
        $result = mysqli_fetch_array($query);
        
            if($result >= 2){

                //echo '<script language="javascript">alert("ALERTA!! TIENE 3 EVENTOS APROBADOS PARA LA MISMA FECHA");</script>';
                $alert ='<p class="msg_sav">""</p>';

            }else{
                    //$alert ='<p class="msg_sav">Ha Ocurrido un error</p>';
                    echo '<script language="javascript">alert("ALERTA!! TIENE 3 EVENTOS APROBADOS PARA LA MISMA FECHA");</script>';
                    
            }   

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista Cotizaciones</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1><i class="fas fa-users"></i>LISTA DE COTIZACIONES</h1>
        <a href="Cotizaciones.php" class="btn_new">Nueva Cotización</a>
        
        <form action = "buscar_cotizaciones.php" method = "get" class = "buscador">
            <i class="fas fa-search"></i><input type = "text" name = "busqueda"  id= "busqueda" placeholder="N° Cotización">
            <input type = "submit" value="Buscar" class = "btn_search">
        </form>

        <!--<div>
            <h5 class = "textleft">Buscar por fecha</h5>
            <form action = "buscar_cotizaciones.php" method="get" class="form_search_date">
                <label>Desde: </label>
                <input type="date" name="fecha_desde" id="fecha_desde" required>
                <label>Hasta: </label>
                <input type ="date" name="fecha_hasta" id = "fecha_hasta" required>
                <button type= "submit" class ="btn_view"><i class= "fas fa-search"></i></button>
            </form>
        </div> -->

        <table>
            <tr>
                <th>N° Cotizacion</th>
                <th>Fecha Evento</th>
                <th>Cliente</th>
                <th>Tipo servicio</th>
                <th>Comercial</th>
                <th>Estado</th>
                <th class = "textright">Total Cotización</th>
                <th class ="textcenter">Acciones</th>
            </tr>
        <?php

            //paginador

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM cotizaciones");
            $result_registro = mysqli_fetch_array($sql_registro);
            $total_registro = $result_registro['total_registro'];


            $por_pagina = 10;

            if(empty($_GET['pagina'])){
                $pagina = 1;
            }else{
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina-1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query_cotizaciones = mysqli_query($conection, "SELECT c.id_cotizacion, c.id_cliente, c.fecha_evento, c.tipo_servicio, c.Usuario as comercial, c.estatus, c.Total_cotizacion,
                                                            cl.Nombre_cliente
                                                            FROM Cotizaciones c
                                                            INNER JOIN usuarios u
                                                            ON c.Usuario = u.Usuario 
										                    INNER JOIN clientes cl 
										                    ON c.id_cliente = cl.id_cliente
                                                            ORDER BY c.estatus AND c.fecha_evento  ASC
                                                            LIMIT $desde, $por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query_cotizaciones);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query_cotizaciones)) {
                    if($data["estatus"] == 0){
                        $estado = '<span class = "negada">Negada</span>';
                    }else if($data["estatus"]== 2){
                        $estado = '<span class = "aprobada">Aprobada</span>';
                    }else{
                        $estado ='<span class = "pendiente">Pendiente</span>';
                    }
            ?>
                    
                <tr>
                    <td><?php echo $data["id_cotizacion"] ?></td>
                    <td><?php echo $data["fecha_evento"] ?></td>
                    <td><?php echo $data["Nombre_cliente"] ?></td>
                    <td><?php echo $data["tipo_servicio"] ?></td>
                    <td><?php echo $data["comercial"] ?></td>
                    <td><?php echo $estado ?></td>
                    <td class = "total_cotizacion"><span>$</span><?php echo $data["Total_cotizacion"];?></td>

                    <td  class = "textright">
                        <div class = "div_acciones">
                            <div>
                                <?php if($_SESSION['perfil'] ==1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil']== 4 || $_SESSION['perfil']== 3) { 
                                            if ($data["estatus"] == 1 ) {
                                ?>
                                                <a class = "editar_cotizacion" id = "editar_cotizacion" href ="editar_cotizacion.php?cliente=<?php echo $data["id_cliente"];?>&coti=<?php echo $data['id_cotizacion'];?>"><i class="fas fa-edit"></i></a>
                                            <?php } else { ?>
                                            <div class = "div_cotizacion">
                                            <button type="button" class="inactive"><i class="fas fa-edit"></i></button>
                                            <?php } ?>
                                     <?php   
                                        if($data["estatus"] == 1){
                                    ?>
                                        <a href = "aprobar_cotizacion.php?id=<?php echo $data["id_cotizacion"];?>"><i class="far fa-check-circle"></i></a>
                                    <?php } else { ?>
                                    <div class = "div_cotizacion">
                                        <button type="button" class="inactive"><i class="far fa-check-circle"></i></button>
                                    <?php } ?>                                                                   
                                <button type="button" class="ver_cotizacion" cl="<?php echo $data["id_cliente"];?>" c="<?php echo $data['id_cotizacion'];?>"><i class="far fa-eye"></i></button>
                                <?php } ?>
                            </div>  
                                <?php if($_SESSION['perfil'] ==1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil']== 4) {

                                    if($data["estatus"]== 1){
                                ?>
                                    <div class = "div_cotizacion">
                                    <a href = "negar_cotizacion.php?id=<?php echo $data["id_cotizacion"];?>"><i class="fas fa-trash-alt"></i></a>
                                    <?php } else { ?>
                                    <button type="button" class="inactive"><i class="fas fa-trash-alt"></i></button>
                                    <?php } 
                                } ?>
                            </div>
                        </div>  
                    </td>
                </tr>
        <?php

            }
        }

        ?>
        
        </tabel>
        <div class = "paginador">
            <ul>
            <?php
                if($pagina !=1){
            ?>

                <li><a href= "?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
                <li><a href= "?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-backward"></i></a></li>
            <?php
                }

                for($i = 1; $i <= $total_paginas; $i++){

                    if($i == $pagina){
                        echo '<li class= "pageSelected">'.$i.'</li>';
                    }else{
                    echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                    }
                }
                if($pagina != $total_paginas){
            ?>
            
                <li><a href= "?pagina=<?php echo $pagina + 1; ?>"><i class="fas fa-forward"></i></a></li>
                <li><a href= "?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
            <?php } ?>   
            
            </ul>
        </div>

	</section>

	<?php include "includes/footer.php"; ?>
    
</body>
</html>