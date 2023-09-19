<?php

    session_start();
    if($_SESSION['perfil'] != 1){
        
        header("location: ./");
    }
    include "../conexion_BD.php";

    if(!empty($_POST)){
        
        if($_POST['id_user'] == 900612569){

            header("location: lista_empleados.php");
            mysqli_close($conection);
            exit;
        }

        $id_user = $_POST['id_user'];
        
        $query_delete = mysqli_query($conection,"DELETE FROM empleados WHERE Ced_empleado = $id_user");

        if($query_delete){

            header("location: lista_empleados.php");

        }else{

            echo "El empleado no se ha podido eliminar";
        }
    }



    if(empty($_REQUEST['id']) || ($_REQUEST['id'] == 900612569)){

        header("location: lista_empleados.php");
        mysqli_close($conection);

    }else{
        
        $id_user = $_REQUEST['id'];

        $query = mysqli_query($conection, "SELECT e.Ced_empleado, e.Nombre_empleado, u.Usuario 
            FROM empleados e INNER JOIN usuarios u ON e.Ced_empleado = u.Ced_empleado WHERE e.Ced_empleado = '$id_user';");

        mysqli_close($conection);
        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                
                $id_user = $data['Ced_empleado'];
                $Nombre_empleado = $data['Nombre_empleado'];
                $Usuario = $data['Usuario'];
            }
        }else{
             header("location: lista_empleados.php");
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
            <p>Cedula Empleado: <span><?php echo $id_user; ?></span></p>
            <p>Nombre Empleado: <span><?php echo $Nombre_empleado; ?></span></p>
            <p>Usuario Registrado: <span><?php echo $Usuario; ?></span></p>
            <br>
            <form method = "post" action="">
                <input type="hidden" name = "id_user" value="<?php echo $id_user; ?>">
                <a href = "lista_empleados.php" class = "btn_cancel">Cancelar</a>
                <input type = "Submit" value = "Aceptar" class = "btn_aceptar">
            </form>

        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>