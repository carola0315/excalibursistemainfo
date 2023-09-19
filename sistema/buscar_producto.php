<?php

    session_start();
    
    if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8)
    {
        header("location: ./");
    }

    include "../conexion_BD.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>buscar producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

        <?php
            $busqueda = ($_REQUEST['busqueda']);

            if(empty($busqueda)){
                header("location: lista_productos.php");
                mysqli_close($conection);
            }
        ?>
		<h1><i class="fas fa-shopping-cart"></i>LISTA DE PRODUCTOS</h1>
        <a href="nuevo_producto.php" class="btn_new">Crear Producto</a>
        
        <form action = "buscar_producto.php" method = "get" class = "buscador">
            <input type = "text" name = "busqueda"  id= "busqueda" placeholder="Buscar">
            <input type = "submit" value="Buscar" class = "btn_search">
        </form>

        <table>
            <tr>
                <th>Codigo Producto</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Linea</th>
                <th>Acciones</th>
            </tr>

        <?php

            //paginador

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro 
                                FROM productos p
                                JOIN lineas l ON (p.Linea = l.id_lineas)
                                WHERE ( p.Cod_producto LIKE '%$busqueda%' OR 
                                p.Nombre_producto LIKE '%$busqueda%' OR 
                                l.Nombre_linea LIKE '%$busqueda%')");

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


            $query = mysqli_query($conection, "SELECT p.Cod_producto, p.Nombre_producto, p.Descripcion, p.Precio, l.Nombre_linea 
                                                FROM productos p
                                                JOIN lineas l ON (p.Linea = l.id_lineas)
                                                WHERE (p.Cod_producto LIKE '%$busqueda%' OR 
                                                        p.Nombre_producto LIKE '%$busqueda%' OR 
                                                        l.Nombre_linea LIKE '%$busqueda%')
                                                LIMIT $desde, $por_pagina");
            
            mysqli_close($conection);
            
            $result = mysqli_num_rows($query);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query)) {
            ?>
        
                <tr>

                    <td><?php echo $data["Cod_producto"] ?></td>
                    <td><?php echo $data["Nombre_producto"] ?></td>
                    <td><?php echo $data["Descripcion"] ?></td>
                    <td><?php echo $data["Precio"] ?></td>
                    <td><?php echo $data["Nombre_linea"] ?></td>

                    <td>
                        <a class = "link_editar" href = "editar_producto.php?id=<?php echo $data["Cod_producto"];?>">Editar</a>
                        |
                    <?php if($_SESSION['perfil'] ==1 || $_SESSION['perfil'] == 2) { ?>

                        <a class = "link_eliminar" href ="eliminar_producto.php?id=<?php echo $data["Cod_producto"];?>">Eliminar</a>
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