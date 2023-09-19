<?php

    session_start();
    
    include "../conexion_BD.php";

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

        <?php
            $busqueda = ($_REQUEST['busqueda']);

            if(empty($busqueda)){
                header("location: lista_cotizaciones.php");
                mysqli_close($conection);
            }
        ?>
		<h1>LISTA DE COTIZACIONES</h1>
        <a href="Cotizaciones.php" class="btn_new">Nueva Cotización</a>
        
        <form action = "buscar_cotizaciones.php" method = "get" class = "buscador">
            <input type = "text" name = "busqueda"  id= "busqueda" placeholder="Buscar">
            <input type = "submit" value="Buscar" class = "btn_search">
        </form>

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

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro 
                                                      FROM cotizaciones coti
                                                      JOIN clientes cl ON (coti.id_cliente = cl.id_cliente)
                                                      WHERE (coti.id_cotizacion  LIKE '%$busqueda%' OR 
                                                      coti.estatus  LIKE '%$busqueda%' OR 
                                                      coti.tipo_servicio LIKE '%$busqueda%' OR
                                                      cl.Nombre_cliente LIKE '%$busqueda%')");

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


            $query = mysqli_query($conection, "SELECT c.id_cotizacion, c.id_cliente, cl.Nombre_cliente, c.fecha_evento, c.tipo_servicio, c.Usuario as comercial, c.estatus, c.Total_cotizacion,
                                                cl.Nombre_cliente
                                                FROM Cotizaciones c
                                                INNER JOIN usuarios u
                                                ON c.Usuario = u.Usuario 
                                                INNER JOIN clientes cl 
                                                ON c.id_cliente = cl.id_cliente
                                                WHERE (c.id_cotizacion LIKE '%$busqueda%' OR 
                                                        c.estatus LIKE '%$busqueda%' OR 
                                                        c.tipo_servicio LIKE '%$busqueda%' OR
                                                        cl.Nombre_cliente LIKE '%$busqueda%')
                                                LIMIT $desde, $por_pagina");
            
            mysqli_close($conection);
            
            $result = mysqli_num_rows($query);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query)) {

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
                                <?php if($_SESSION['perfil'] ==1 || $_SESSION['perfil'] == 2 || $_SESSION['perfil']== 4 || $_SESSION['perfil']== 3) {
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

    <?php
        if($total_registro != 0){

    ?>
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