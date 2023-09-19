<?php

session_start();


if($_SESSION['perfil'] != 1){

    header("location: ./");
}



include "../conexion_BD.php";

    if (!empty($_POST))
    {
        $alert ='';
        if( empty($_POST['Nombre_linea']))

        {
            $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 
        }else{
            
            $Nombre_linea= ($_POST['Nombre_linea']);
            

            $query = mysqli_query($conection, "SELECT * FROM lineas WHERE Nombre_linea = '$Nombre_linea'");
            
            $result = mysqli_fetch_array($query);

            if($result > 0){

                $alert ='<p class="msg_error">La linea ya existe</p>';
                
            }else{

                $query_insert = mysqli_query($conection, "INSERT INTO lineas (Nombre_linea) 
                                        VALUES ('$Nombre_linea')");

                if($query_insert == false){

                    $alert ='<p class="msg_error">Ha ocurrido un error</p>';

                }else{

                    $alert ='<p class="msg_sav">Linea Creada con exito</p>';
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
            <br>
            <br>
		    <h1><i class="fas fa-plus-square"></i>Crear Nueva Linea de Productos</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                <label for = "Nombre_producto">Nombre Linea</label>
                <input type ="text" name ="Nombre_linea" id = "Nombre_linea" placeholder= "Nombre Linea">
                

                <input type = submit value ="Crear Linea" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>