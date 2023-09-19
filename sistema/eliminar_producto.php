<?php

    session_start();

    if($_SESSION['perfil'] != 1 and $_SESSION['perfil'] != 2){

        header("location: ./");
    }

    include "../conexion_BD.php";

    if(!empty($_POST)){

        if(empty($_POST['Cod_producto'])){

            header("location: lista_productos.php");
            exit;
        }
    
        $cod_producto = $_POST['Cod_producto'];
        
        $query_delete = mysqli_query($conection,"UPDATE productos SET estatus = 0 WHERE Cod_producto = '$cod_producto'");

        if($query_delete){

            header("location: lista_productos.php");

        }else{

            echo "El producto no se ha podido eliminar";
        }
    }



    if(empty($_REQUEST['id'])){

        header("location: lista_productos.php");
        mysqli_close($conection);

    }else{
        
        $cod_producto = $_REQUEST['id'];

        $query = mysqli_query($conection, "SELECT p.Cod_producto, p.Nombre_producto, p.Descripcion, p.Precio, l.Nombre_linea	   
                                            FROM productos p
                                            JOIN lineas l ON (p.Linea = l.id_lineas)
                                            WHERE Cod_producto = '$cod_producto'");

        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                
                $cod_producto = $data['Cod_producto'];
                $Nombre_producto = $data['Nombre_producto'];
                $Descripcion= $data['Descripcion'];
                $Linea = $data['Nombre_linea'];
            }
        }else{
             header("location: lista_producto.php");
        }
    }
    

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>ELIMINAR PRODUCTO</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class = "data_delete">
            <i class="fas fa-shopping-cart fa-7x" style="color: #A93226"></i>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <br>
            <p>Codigo producto: <span><?php echo $cod_producto; ?></span></p>
            <p>Nombre producto: <span><?php echo $Nombre_producto; ?></span></p>
            <p>Descripción: <span><?php echo $Descripcion; ?></span></p>
            <p>Linea: <span><?php echo $Linea; ?></span></p>
            <br>
            <form method = "post" action="">
                <input type="hidden" name = "Cod_producto" value="<?php echo $cod_producto; ?>">
                <a href = "lista_productos.php" class = "btn_cancel">Cancelar</a>
                <input type = "Submit" value = "Aceptar" class = "btn_aceptar">

            </form>

        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>