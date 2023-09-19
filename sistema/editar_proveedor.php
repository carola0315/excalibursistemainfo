<?php

session_start();

if($_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 8 || $_SESSION['perfil'] == 6)
    {
        header("location: ./");
    }

include "../conexion_BD.php";

if(!empty($_POST))
{
    $alert ='';

    if(empty($_POST['id_proveedor']) || empty($_POST['Nombre_proveedor']) || empty($_POST['Direccion_proveedor']) || empty($_POST['Telefono_proveedor']) || empty($_POST['linea']))
    {
        $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 

    }else{
        
        $cod_proveedor = $_POST['id'];
        $id_proveedor= $_POST['id_proveedor'];
        $Nombre_proveedor= $_POST['Nombre_proveedor'];
        $Direccion_proveedor = $_POST['Direccion_proveedor'];
        $Telefono_proveedor = $_POST['Telefono_proveedor'];
        $Servicio_prestado = $_POST['linea'];
         
        $query_proveedor = mysqli_query($conection, "SELECT cod_proveedor FROM proveedores WHERE cod_proveedor = '$cod_proveedor'");
            
        $result = mysqli_fetch_array($query_proveedor);
        
            if($result > 0){
                $sql_update = mysqli_query ($conection, "UPDATE proveedores 
                                                         SET id_proveedor = '$id_proveedor', Nombre_proveedor = '$Nombre_proveedor', Direccion_proveedor = '$Direccion_proveedor', Telefono_proveedor = '$Telefono_proveedor', Servicio_prestado = '$Servicio_prestado'
                                                         WHERE cod_proveedor = '$cod_proveedor' AND estatus = 1");

                if($sql_update){
                    $alert ='<p class="msg_error">Proveedor Actualizado</p>';
                }else{
                    $alert ='<p class="msg_sav">Ha Ocurrido un error</p>';
                }
            }   
        
    }
    
}
    

    //MOSTRAR DATOS DEL PROVEEDOR

    if(empty($_REQUEST['id'])){

        header('location: lista_proveedor.php');
        
    }
    $cod_proveedor = $_REQUEST['id'];

    $sql = mysqli_query($conection,"SELECT p.cod_proveedor, p.id_proveedor, p.Nombre_proveedor, p.Direccion_proveedor, P.Telefono_proveedor, p.Servicio_prestado, l.Nombre_linea
                                    FROM proveedores p
                                    JOIN lineas l ON (p.Servicio_prestado = l.id_lineas)
                                    WHERE cod_proveedor= '$cod_proveedor' AND estatus = 1");

    
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
        header('location: lista_proveedor.php');
    }else{

        while($data = mysqli_fetch_array($sql)){

            $cod_proveedor = $data['cod_proveedor'];
            $id_proveedor = $data['id_proveedor'];
            $Nombre_proveedor = $data['Nombre_proveedor'];
            $Direccion_proveedor = $data['Direccion_proveedor'];
            $Telefono_proveedor = $data['Telefono_proveedor'];
            $Servicio_prestado = $data['Servicio_prestado'];

            $id_linea = $data['Servicio_prestado'];
            $nombre_linea = $data['Nombre_linea'];  

            if($id_linea == 1){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $nombre_linea.'</option>';
            }else{
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $nombre_linea.'</option>';
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar proveedor</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1>ACTUALIZAR PROVEEDOR</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">
                
                <input name ="id" value = "<?php echo $cod_proveedor; ?>" type ="hidden" >
                
                <label for = "id_proveedor">Id Proveedor</label>
                <input type ="text" name ="id_proveedor" id = "id_proveedor" placeholder= "identificacion proveedor" value = "<?php echo $id_proveedor; ?>">
                <label for = "Nombre_proveedor">Nombre Proveedor</label>
                <input type ="text" name ="Nombre_proveedor" id = "Nombre_proveedor" placeholder= "Nombre" value = "<?php echo $Nombre_proveedor; ?>">
                <label for = "Direccion_proveedor">Dirección</label>
                <input type ="text" name ="Direccion_proveedor" id = "Direccion_proveedor" placeholder= "Dirección proveedor" value = "<?php echo $Direccion_proveedor; ?>">
                <label for = "Telefono_proveedor">Telefono</label>
                <input type ="text" name ="Telefono_proveedor" id = "Telefono_proveedor" placeholder= "Celular" value = "<?php echo $Telefono_proveedor; ?>">
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
                
                <input type = submit value ="ACTUALIZAR" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>