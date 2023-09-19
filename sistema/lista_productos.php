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
	<title>LISTA DE PRODUCTOS</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

		<h1><i class="fas fa-shopping-cart"></i>LISTA DE PRODUCTOS</h1>
        <a href="nuevo_producto.php" class="btn_new">Crear Producto</a>
        
        <form action = "buscar_producto.php" method = "get" class = "buscador">
            <i class="fas fa-search"></i><input type = "text" name = "busqueda"  id= "busqueda" placeholder="Buscar">
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

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM productos WHERE estatus = 1");
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


            $query_productos= mysqli_query($conection, "SELECT p.Cod_producto, p.Nombre_producto, p.Descripcion, p.Precio, l.Nombre_linea	   
                                                        FROM productos p
                                                        JOIN lineas l ON (p.Linea = l.id_lineas)
                                                        WHERE estatus = 1 LIMIT $desde, $por_pagina");

            mysqli_close($conection);

            $result = mysqli_num_rows($query_productos);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query_productos)) {
            ?>
                <tr>
                    <td><?php echo $data["Cod_producto"] ?></td>
                    <td><?php echo $data["Nombre_producto"] ?></td>
                    <td><?php echo $data["Descripcion"] ?></td>
                    <td><?php echo $data["Precio"] ?></td>
                    <td><?php echo $data["Nombre_linea"] ?></td>

                    <td>
                        <a class = "link_editar" href = "editar_producto.php?id=<?php echo $data["Cod_producto"];?>"><i class="fas fa-edit"></i>Editar</a>
                        |
                        <?php if($_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2) { ?>

                        <a class = "link_eliminar" href ="eliminar_producto.php?id=<?php echo $data["Cod_producto"];?>"><i class="fas fa-trash-alt"></i>Eliminar</a>
                        <?php } ?>
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