<?php

    session_start();
    
    include "../conexion_BD.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista Ordenes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

        <?php
            $busqueda = ($_REQUEST['busqueda']);

            if(empty($busqueda)){
                header("location: lista_ordenes.php");
                mysqli_close($conection);
            }
        ?>
		<h1>LISTA DE ORDENES DE SERVICIO</h1>

        <form action = "buscar_ordenes.php" method = "get" class = "buscador">
            <input type = "text" name = "busqueda"  id= "busqueda" placeholder="Buscar">
            <input type = "submit" value="Buscar" class = "btn_search">
        </form>

        <table>
            <tr>
                <th>N° Orden de Servicio</th>
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

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM orden_servicio
                                WHERE (id_cotizacion  LIKE '%$busqueda%' OR 
                                id_Orden  LIKE '%$busqueda%' OR 
                                estatus  LIKE '%$busqueda%' OR
                                fecha_evento LIKE '%$busqueda%')");

            $result_registro = mysqli_fetch_array($sql_registro);
            $total_registro = $result_registro['total_registro'];


            $por_pagina = 5;

            if(empty($_GET['pagina'])){
                $pagina = 1;
            }else{
                $pagina = $_GET['pagina'];
            }

            $desde = ($pagina-1) * $por_pagina;
            $total_paginas = ceil($total_registro / $por_pagina);


            $query = mysqli_query($conection, "SELECT o.id_Orden, o.id_cotizacion, o.id_cliente, o.fecha_evento, c.tipo_servicio, c.Usuario as comercial, o.estatus, o.Precio_total, cl.Nombre_cliente
                                                FROM orden_servicio o
                                                INNER JOIN clientes cl 
                                                ON o.id_cliente = cl.id_cliente
                                                INNER JOIN cotizaciones c
                                                ON o.id_cotizacion = c.id_cotizacion
                                                INNER JOIN usuarios u
                                                ON c.Usuario = u.Usuario
                                                WHERE (o.id_cotizacion LIKE '%$busqueda%' OR 
                                                        o.id_Orden LIKE '%$busqueda%' OR 
                                                        o.estatus LIKE '%$busqueda%' OR 
                                                        o.fecha_evento LIKE '%$busqueda%')
                                                LIMIT $desde, $por_pagina");
            
            mysqli_close($conection);
            
            $result = mysqli_num_rows($query);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query)) {

                    while ($data = mysqli_fetch_array($query)) {
                        if($data["estatus"] == 0){
                            $estado = '<span class = "negada">Anulada</span>';
                        }else if($data["estatus"]== 1){
                            $estado = '<span class = "pendiente">Orden aprobada</span>';
                        }else if($data["estatus"]== 2){
                            $estado = '<span class = "xejecutar">Pendiente ejecutar</span>';
                        }else if($data["estatus"]==3){
                            $estado = '<span class = "ejecucion">Ejecución</span>';
                        }else{
                            $estado ='<span class = "aprobada">Ejecutada</span>';
                        }
            ?>
        
        <tr>
                    <td><?php echo $data["id_Orden"] ?></td>
                    <td><?php echo $data["fecha_evento"] ?></td>
                    <td><?php echo $data["Nombre_cliente"] ?></td>
                    <td><?php echo $data["tipo_servicio"] ?></td>
                    <td><?php echo $data["comercial"] ?></td>
                    <td><?php echo $estado ?></td>
                    <td class = "total_cotizacion"><span>$</span><?php echo $data["Precio_total"];?></td>

                    <td  class = "textright">
                        <div class = "div_acciones">
                            <div>                                                             
                                    <a href = "lao.php?id=<?php echo $data["id_Orden"];?>"><i class="far fa-eye"></i></button>
                            </div>  
                                <?php if($_SESSION['perfil'] ==1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil']== 4 || $_SESSION['perfil']== 3) {
                                    if($data["estatus"]== 1 || $data["estatus"==2]){
                                ?>
                                    <div class = "div_cotizacion">
                                    <a href = "negar_Orden.php?id=<?php echo $data["id_Orden"];?>"><i class="fas fa-trash-alt"></i></a>
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
            <?php } }?>   
            
            </ul>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>