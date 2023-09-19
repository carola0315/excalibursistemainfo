<?php

    session_start();
    if($_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 5){

        header("location: ./");
    }
    include "../conexion_BD.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista Empleados</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

        <?php
            $busqueda = ($_REQUEST['busqueda']);

            if(empty($busqueda)){
                header("location: lista_empleados.php");
                mysqli_close($conection);
            }
        ?>
		<h1>EMPLEADOS</h1>
        <a href="nuevo_empleado.php" class="btn_new">Crear nuevo empleado</a>
        
        <form action = "buscar_empleado.php" method = "get" class = "buscador">
            <input type = "text" name = "busqueda"  id = "busqueda" placeholder="Buscar" value = "<?php echo $busqueda; ?>">
            <input type = "submit" value="Buscar" class = "btn_search">
        </form>

        <table>
            <tr>
                <th>Cedula</th>
                <th>Nombre Completo</th>
                <th>Telefono</th>
                <th>Dirección</th>
                <th>Correo Electronico</th>
                <th>Cargo</th>
                <th>Estado</th>
                <th>Perfil</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        <?php

            //paginador

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM empleados 
                                WHERE ( Ced_empleado LIKE '%$busqueda%' OR 
                                Nombre_empleado LIKE '%$busqueda%' OR 
                                Cargo LIKE '%$busqueda%')");

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


            $query = mysqli_query($conection, "SELECT e.Ced_empleado, e.Nombre_empleado, e.Telefono_empleado, e.Direccion_empleado, e.Correo_electronico, e.Cargo, e.Estado, p.perfil, u.Usuario 
                                                FROM empleados e INNER JOIN perfil p  INNER JOIN usuarios u ON e.id_perfil = p.id_perfil 
                                                WHERE (e.Ced_empleado LIKE '%$busqueda%' OR 
                                                        e.Nombre_empleado LIKE '%$busqueda%' OR 
                                                        e.Cargo LIKE '%$busqueda%')
                                                AND e.Ced_empleado = u.Ced_empleado
                                                LIMIT $desde, $por_pagina");
            
            mysqli_close($conection);
            
            $result = mysqli_num_rows($query);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query)) {
            ?>
        
                <tr>
                    <td><?php echo $data["Ced_empleado"] ?></td>
                    <td><?php echo $data["Nombre_empleado"] ?></td>
                    <td><?php echo $data["Telefono_empleado"] ?></td>
                    <td><?php echo $data["Direccion_empleado"] ?></td>
                    <td><?php echo $data["Correo_electronico"] ?></td>
                    <td><?php echo $data["Cargo"] ?></td>
                    <td><?php echo $data["Estado"] ?></td>
                    <td><?php echo $data["perfil"] ?></td>
                    <td><?php echo $data["Usuario"] ?></td>

                    <td>
                        <a class = "link_editar" href = "editar_empleado.php?id=<?php echo $data["Ced_empleado"];?>">Editar</a>
                        |
                    <?php 
                        if($data["Ced_empleado"] != 900612569){
                    ?>
                        <a class = "link_eliminar" href ="eliminar_empleado.php?id=<?php echo $data["Ced_empleado"];?>">Eliminar</a>
                    <?php } ?>
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

                <li><a href= "?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
                <li><a href= "?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
            <?php
                }
                
                for($i = 1; $i <= $total_paginas; $i++){

                    if($i == $pagina){
                        echo '<li class= "pageSelected">'.$i.'</li>';
                    }else{
                    echo '<li><a href="?pagina='.$i. '&busqueda='.$busqueda.'">'.$i.'</a></li>';
                    }
                }
                if($pagina != $total_paginas){
            ?>
                <li><a href= "?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
                <li><a href= "?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
            <?php } ?>    
            </ul>
        </div>
    <?php } ?>
    
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>