<?php

session_start();

if($_SESSION['perfil'] == 3 || $_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8)
    {
        header("location: ./");
    }


include "../conexion_BD.php";

    if (!empty($_POST))
    {
        $alert ='';

        if( empty($_POST['Nombre_producto']) || empty($_POST['Precio']) || empty($_POST['linea'])) 

        {
            $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 

        }else{
            
            $Nombre_producto= ($_POST['Nombre_producto']);
            $Descripcion = $_POST['Descripcion'];
            $Precio = $_POST['Precio'];
            $iva = $_POST['iva'];
            $Linea = $_POST['linea'];

            $query = mysqli_query($conection, "SELECT Descripcion FROM productos WHERE Descripcion = '$Descripcion'");
            
            $result = mysqli_fetch_array($query);

            if($result > 0){

                $alert ='<p class="msg_error">el producto ya existe</p>';
                
            }else{

                $query_insert = mysqli_query($conection, "INSERT INTO productos (Nombre_producto, Descripcion, Precio, linea, Iva) 
                                        VALUES ('$Nombre_producto','$Descripcion', '$Precio', '$Linea', '$iva')");

                if($query_insert == false){

                    $alert ='<p class="msg_error">Ha ocurrido un error</p>';

                }else{

                    $alert ='<p class="msg_sav">Producto Creado con exito</p>';
                }
            }
        }
    }
?>  
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Nuevo Producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1><i class="fas fa-cart-plus"></i>Ingreso Nuevo Producto</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                <label for = "Nombre_producto">Nombre Producto</label>
                <input type ="text" name ="Nombre_producto" id = "Nombre_producto" placeholder= "Nombre Producto">
                <label for = "Descripcion">Descripción</label>
                <input type ="text" name ="Descripcion" id = "Descripcion" placeholder= "Descripcion">
                <label for = "precio">Precio</label>
                <input type ="text" name ="Precio" id = "Precio" placeholder= "Precio">
                <label for = "iva">Impuesto del producto</label>
                <input type ="text" name ="iva" id = "iva" placeholder= "Iva">
                <label for ="Linea">Linea </label>
                <?php
                    $query_lineas = mysqli_query($conection, "SELECT * FROM lineas");
                    mysqli_close($conection);
                    $result_lineas = mysqli_num_rows($query_lineas);
                ?>
                <select name = "linea" id = "linea">
                <?php
                    if($result_lineas > 0)
                    {
                        while($lineas = mysqli_fetch_array($query_lineas)){
                ?>          
                        <option value = "<?php echo $lineas["id_lineas"]; ?>"><?php echo $lineas["Nombre_linea"]; ?></option>
                <?php
                        }
                    }
                ?>

                <input type = submit value ="Crear Producto" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>