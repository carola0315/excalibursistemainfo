<?php

session_start();

if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8)
    {
        header("location: ./");
    }

include "../conexion_BD.php";

    if (!empty($_POST))
    {
        $alert ='';
        if(empty($_POST['id_cliente']) || empty($_POST['Nombre_cliente']) || empty($_POST['Direccion_cliente']) ||
            empty($_POST['Telefono_cliente']) || empty($_POST['Contacto']) || empty($_POST['Telefono_contacto']))
        {
            $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 
        }else{
            

            $id_cliente = $_POST['id_cliente'];
            $Nombre_cliente= $_POST['Nombre_cliente'];
            $Direccion_cliente = $_POST['Direccion_cliente'];
            $Telefono_cliente = $_POST['Telefono_cliente'];
            $Correo_cliente = $_POST['Correo_cliente'];
            $Contacto = $_POST['Contacto'];
            $Telefono_contacto = $_POST['Telefono_contacto'];
            

            $query = mysqli_query($conection, "SELECT id_cliente FROM clientes WHERE id_cliente = '$id_cliente'");
            
            $result = mysqli_fetch_array($query);

            if($result > 0){

                $alert ='<p class="msg_error">cliente ya existe</p>';
                
            }else{
                $query_insert = mysqli_query($conection, "INSERT INTO clientes (id_cliente, Nombre_cliente, Direccion_cliente,
                Telefono_cliente, Correo_cliente, Contacto, Telefono_contacto) VALUES ('$id_cliente', '$Nombre_cliente','$Direccion_cliente',
                '$Telefono_cliente', '$Correo_cliente', '$Contacto', '$Telefono_contacto')");

                if($query_insert == false){

                    $alert ='<p class="msg_error">Ha ocurrido un error</p>';

                }else{

                    $alert ='<p class="msg_sav">Cliente Creado con exito</p>';
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
	<title>Nuevo Cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1><i class="fas fa-user-plus"></i>Registro nuevo cliente</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                <label for = "id_cliente">Nit o Cedula</label>
                <input type ="text" name ="id_cliente" id = "id_cliente" placeholder= "Nit o cedula cliente">
                <label for = "Nombre_cliente">Nombre Completo</label>
                <input type ="text" name ="Nombre_cliente" id = "Nombre_cliente" placeholder= "Nombre completo">
                <label for = "Dirección_cliente">Dirección</label>
                <input type ="text" name ="Direccion_cliente" id = "Direccion_cliente" placeholder= "Dirección Cliente">
                <label for = "Telefono_cliente">Telefono</label>
                <input type ="text" name ="Telefono_cliente" id = "Telefono_cliente" placeholder= "Celular">
                <label for ="Correo_cliente">Correo Electronico </label>
                <input type = "text" name = "Correo_cliente" id = "Correo_cliente" placeholder = "Correo Electronico">
                <label for ="Contacto">Contacto </label>
                <input type = "text" name = "Contacto" id = "Contato" placeholder = "Nombre del contacto">
                <label for ="Telefono_contacto">Telefono contacto</label>
                <input type = "text" name = "Telefono_contacto" id = "Telefono_contacto" placeholder = "Telefono del contacto ">
                
                <input type = submit value ="Crear Cliente" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>