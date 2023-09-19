<?php

    session_start();

    if($_SESSION['perfil'] != 1 AND $_SESSION['perfil'] != 2){

        header("location: ./");
    }

    include "../conexion_BD.php";

    if(!empty($_POST)){

        if(empty($_POST['cod_proveedor'])){

            header("location: lista_proveedores.php");
            exit;
        }
    
        $cod_proveedor = $_POST['cod_proveedor'];
        
        $query_delete = mysqli_query($conection,"UPDATE proveedores SET estatus = 0 WHERE cod_proveedor = '$cod_proveedor'");

        if($query_delete){

            header("location: lista_proveedores.php");

        }else{

            echo "El proveedor no se ha podido eliminar";
        }
    }



    if(empty($_REQUEST['id'])){

        header("location: lista_proveedor.php");
        mysqli_close($conection);

    }else{
        
        $cod_proveedor = $_REQUEST['id'];

        $query = mysqli_query($conection, "SELECT  p.cod_proveedor, p.id_proveedor, p.Nombre_proveedor, p.Direccion_proveedor, P.Telefono_proveedor, p.Servicio_prestado, l.Nombre_linea
                                            FROM proveedores p
                                            JOIN lineas l ON (p.Servicio_prestado = l.id_lineas) 
                                            WHERE cod_proveedor = '$cod_proveedor'");

        $result = mysqli_num_rows($query);

        if($result > 0){
            while($data = mysqli_fetch_array($query)){
                
                $cod_proveedor = $data['cod_proveedor'];
                $id_proveedor = $data['id_proveedor'];
                $Nombre_proveedor= $data['Nombre_proveedor'];
                $Servicio_prestado = $data['Nombre_linea'];
            }
        }else{
             header("location: lista_proveedores.php");
        }
    }
    

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>ELIMINAR PROVEEDOR</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class = "data_delete">
            <i class="fas fa-store fa-7x" style="color: #A93226"></i>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <br>
            <p>Codigo proveedor: <span><?php echo $cod_proveedor; ?></span></p>
            <p>Identificación Proveedor: <span><?php echo $id_proveedor; ?></span></p>
            <p>Nombre proveedor: <span><?php echo $Nombre_proveedor; ?></span></p>
            <p>Servicio prestado: <span><?php echo $Servicio_prestado; ?></span></p>
            <br>
            <form method = "post" action="">
                <input type="hidden" name = "cod_proveedor" value="<?php echo $cod_proveedor; ?>">
                <a href = "lista_proveedores.php" class = "btn_cancel">Cancelar</a>
                <input type = "Submit" value = "Aceptar" class = "btn_aceptar">

            </form>

        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>