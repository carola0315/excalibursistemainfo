<?php

    session_start();

    if($_SESSION['perfil'] != 1){

        header("location: ./");
    }

    include "../conexion_BD.php";

    if(!empty($_POST)){

        if(empty($_POST['id_cliente'])){

            header("location: lista_clientes.php");
            exit;
        }
    
        $id_cliente = $_POST['id_cliente'];
        
        $query_delete = mysqli_query($conection,"UPDATE clientes SET estatus = 0 WHERE id_cliente = '$id_cliente'");

        if($query_delete){

            header("location: lista_clientes.php");

        }else{

            echo "El cliente no se ha podido eliminar";
        }
    }



    if(empty($_REQUEST['id'])){

        header("location: lista_clientes.php");
        mysqli_close($conection);

    }else{
        
        $id_cliente = $_REQUEST['id'];

        $query = mysqli_query($conection, "SELECT * FROM clientes WHERE id_cliente = '$id_cliente'");

        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                
                $id_cliente = $data['id_cliente'];
                $Nombre_cliente = $data['Nombre_cliente'];
                $contacto = $data['Contacto'];
            }
        }else{
             header("location: lista_clientes.php");
        }
    }
    

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>ELIMINAR EMPLEADO</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class = "data_delete">
            <i class="fa fa-user-times fa-7x" style="color: #A93226"></i>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <br>
            <p>Identificación cliente: <span><?php echo $id_cliente; ?></span></p>
            <p>Nombre cliente: <span><?php echo $Nombre_cliente; ?></span></p>
            <p>Contacto: <span><?php echo $contacto; ?></span></p>
            <br>
            <form method = "post" action="">
                <input type="hidden" name = "id_cliente" value="<?php echo $id_cliente; ?>">
                <a href = "lista_clientes.php" class = "btn_cancel">Cancelar</a>
                <input type = "Submit" value = "Aceptar" class = "btn_aceptar">
            </form>

        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>