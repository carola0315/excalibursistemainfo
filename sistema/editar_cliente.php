<?php

session_start();

if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8)
    {
        header("location: ./");
    }
    
include "../conexion_BD.php";

if(!empty($_POST))
{
    $alert ='';

    if(empty($_POST['Nombre_cliente']) || empty($_POST['Direccion_cliente']) || empty($_POST['Telefono_cliente']) || empty($_POST['Contacto'])
    || empty($_POST['Telefono_contacto']))
    {
        $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 

    }else{
        

        $id_cliente = $_POST['id'];
        $Nombre_cliente= $_POST['Nombre_cliente'];
        $Direccion_cliente = $_POST['Direccion_cliente'];
        $Telefono_cliente = $_POST['Telefono_cliente'];
        $Correo_cliente = $_POST['Correo_cliente'];
        $Contacto = $_POST['Contacto'];
        $Telefono_contacto = $_POST['Telefono_contacto'];
        
         
        $query_clientes = mysqli_query($conection, "SELECT id_cliente FROM clientes WHERE id_cliente = '$id_cliente'");
            
        $result = mysqli_fetch_array($query_clientes);
        
            if($result > 0){
                $sql_update = mysqli_query ($conection, "UPDATE clientes 
                                                         SET Nombre_cliente = '$Nombre_cliente', Direccion_cliente = '$Direccion_cliente', Telefono_cliente = '$Telefono_cliente', Correo_cliente = '$Correo_cliente', Contacto = '$Contacto', Telefono_contacto = '$Telefono_contacto' 
                                                         WHERE id_cliente = '$id_cliente' AND estatus = 1");    

                if($sql_update){
                    $alert ='<p class="msg_error">Cliente Actualizado</p>';
                }else{
                    $alert ='<p class="msg_sav">Ha Ocurrido un error</p>';
                }
            }   
        
    }
    
}
    

    //MOSTRAR DATOS DEL USUARIO

    if(empty($_REQUEST['id'])){

        header('location: lista_clientes.php');
        
    }
    $id_cliente = $_REQUEST['id'];

    $sql = mysqli_query($conection,"SELECT * FROM clientes WHERE id_cliente = '$id_cliente' AND estatus = 1");

    
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
        header('location: lista_clientes.php');
    }else{

        while($data = mysqli_fetch_array($sql)){

            $id_cliente = $data['id_cliente'];
            $Nombre_cliente = $data['Nombre_cliente'];
            $Direccion_cliente = $data['Direccion_cliente'];
            $Telefono_cliente = $data['Telefono_cliente'];
            $Correo_cliente = $data['Correo_cliente'];
            $Contacto = $data['Contacto'];
            $Telefono_contacto = $data['Telefono_contacto'];
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Empleado</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1>ACTUALIZAR DATOS CLIENTE</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">
                
                <input name ="id" value = "<?php echo $id_cliente; ?>" type ="hidden" >
                
                <label for = "Nombre_cliente">Nombre Cliente</label>
                <input type ="text" name ="Nombre_cliente" id = "Nombre_cliente" placeholder= "Nombre" value = "<?php echo $Nombre_cliente; ?>">
                <label for = "Dirección_cliente">Dirección</label>
                <input type ="text" name ="Direccion_cliente" id = "Direccion_cliente" placeholder= "Dirección Cliente" value = "<?php echo $Direccion_cliente; ?>">
                <label for = "Telefono_cliente">Telefono</label>
                <input type ="text" name ="Telefono_cliente" id = "Telefono_cliente" placeholder= "Celular" value = "<?php echo $Telefono_cliente; ?>">
                <label for ="Correo_cliente">Correo Electronico </label>
                <input type = "text" name = "Correo_cliente" id = "Correo_cliente" placeholder = "Correo Electronico" value = "<?php echo $Correo_cliente; ?>">
                <label for ="Contacto">Contacto </label>
                <input type = "text" name = "Contacto" id = "Contato" placeholder = "Nombre del contacto" value = "<?php echo $Contacto; ?>">
                <label for ="Telefono_contacto">Telefono contacto</label>
                <input type = "text" name = "Telefono_contacto" id = "Telefono_contacto" placeholder = "Telefono del contacto" value = "<?php echo $Telefono_contacto; ?>">

                </select>
                <input type = submit value ="ACTUALIZAR" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>