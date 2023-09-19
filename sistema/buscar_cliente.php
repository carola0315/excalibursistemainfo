<?php

    session_start();
    
    include "../conexion_BD.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>LISTA DE CLIENTES</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">

        <?php
            $busqueda = ($_REQUEST['busqueda']);

            if(empty($busqueda)){
                header("location: lista_clientes.php");
                mysqli_close($conection);
            }
        ?>
		<h1>LISTA DE CLIENTES</h1>
        <a href="nuevo_cliente.php" class="btn_new">Crear Cliente</a>
        
        <form action = "buscar_cliente.php" method = "get" class = "buscador">
            <input type = "text" name = "busqueda"  id= "busqueda" placeholder="Buscar">
            <input type = "submit" value="Buscar" class = "btn_search">
        </form>

        <table>
            <tr>
                <th>Nit o Cedula</th>
                <th>Nombre Cliente</th>
                <th>Dirección</th>
                <th>Telefono</th>
                <th>Correo Electronico</th>
                <th>Contacto</th>
                <th>Telefono contacto</th>
                <th>Acciones</th>
            </tr>

        <?php

            //paginador

            $sql_registro = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM clientes 
                                WHERE ( id_cliente LIKE '%$busqueda%' OR 
                                Nombre_cliente LIKE '%$busqueda%' OR 
                                Contacto LIKE '%$busqueda%')");

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


            $query = mysqli_query($conection, "SELECT * FROM clientes
                                                WHERE (id_cliente LIKE '%$busqueda%' OR 
                                                        nombre_cliente LIKE '%$busqueda%' OR 
                                                        Contacto LIKE '%$busqueda%')
                                                LIMIT $desde, $por_pagina");
            
            mysqli_close($conection);
            
            $result = mysqli_num_rows($query);
            
            if($result > 0){

                while ($data = mysqli_fetch_array($query)) {
            ?>
        
                <tr>
                    <td><?php echo $data["id_cliente"] ?></td>
                    <td><?php echo $data["Nombre_cliente"] ?></td>
                    <td><?php echo $data["Direccion_cliente"] ?></td>
                    <td><?php echo $data["Telefono_cliente"] ?></td>
                    <td><?php echo $data["Correo_cliente"] ?></td>
                    <td><?php echo $data["Contacto"] ?></td>
                    <td><?php echo $data["Telefono_contacto"] ?></td>

                    <td>
                        <a class = "link_editar" href = "editar_cliente.php?id=<?php echo $data["id_cliente"];?>">Editar</a>
                        |
                    <?php if($_SESSION['perfil'] ==1 || $_SESSION['perfil'] == 2) { ?>

                        <a class = "link_eliminar" href ="eliminar_cliente.php?id=<?php echo $data["id_cliente"];?>">Eliminar</a>
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