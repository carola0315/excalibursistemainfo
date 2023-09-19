<?php

session_start();

if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 6)
    {
        header("location: ./");
    }

include "../conexion_BD.php";

    if (!empty($_POST))
    {
        $alert ='';
        if( empty($_POST['Nombre_proveedor']) || empty($_POST['Direccion_proveedor']) || empty($_POST['Telefono_proveedor']) || 
            empty($_POST['linea']))
        {
            $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 
        }else{
            

            $id_proveedor = $_POST['id_proveedor'];
            $Nombre_proveedor= $_POST['Nombre_proveedor'];
            $Direccion_proveedor = $_POST['Direccion_proveedor'];
            $Telefono_proveedor = $_POST['Telefono_proveedor'];
            $Servicio_prestado = $_POST['linea'];
            
            $query = mysqli_query($conection, "SELECT Nombre_proveedor FROM proveedores WHERE Nombre_proveedor = '$Nombre_proveedor'");
            
            $result = mysqli_fetch_array($query);

            if($result > 0){

                $alert ='<p class="msg_error">cliente ya existe</p>';
                
            }else{
                $query_insert = mysqli_query($conection, "INSERT INTO proveedores (id_proveedor, Nombre_proveedor, Direccion_proveedor,
                Telefono_proveedor, Servicio_prestado) VALUES ('$id_proveedor', '$Nombre_proveedor','$Direccion_proveedor',
                '$Telefono_proveedor', '$Servicio_prestado')");

                if($query_insert == false){

                    $alert ='<p class="msg_error">Ha ocurrido un error</p>';

                }else{

                    $alert ='<p class="msg_sav">Proveedor Creado con exito</p>';
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
	<title>Nuevo Proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1><i class="fas fa-store-alt"></i>Registro Nuevo Proveedor</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                <label for = "id_proveedor">Nit o Cedula</label>
                <input type ="text" name ="id_proveedor" id = "id_proveedor" placeholder= "Nit o cedula proveedor">
                <label for = "Nombre_proveedor">Nombre Completo</label>
                <input type ="text" name ="Nombre_proveedor" id = "Nombre_proveedor" placeholder= "Nombre completo">
                <label for = "Direccion_proveedor">Dirección</label>
                <input type ="text" name ="Direccion_proveedor" id = "Direccion_proveedor" placeholder= "Dirección">
                <label for = "Telefono_proveedor">Telefono</label>
                <input type ="text" name ="Telefono_proveedor" id = "Telefono_proveedor" placeholder= "Celular">
                <label for ="Servicio_prestado">Servicio Prestado </label>
                <?php
                    $query_lineas = mysqli_query($conection, "SELECT * FROM lineas");
                    mysqli_close($conection);
                    $result_lineas = mysqli_num_rows($query_lineas);
                ?>
                <select name = "linea" id = "linea">
                <?php
                    if($result_lineas > 0)
                    {
                        while($linea = mysqli_fetch_array($query_lineas)){
                ?>          
                        <option value = "<?php echo $linea["id_lineas"]; ?>"><?php echo $linea["Nombre_linea"]; ?></option>
                <?php
                        }
                    }
                ?>

                </select>

                <input type = submit value ="Crear Proveedor" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>