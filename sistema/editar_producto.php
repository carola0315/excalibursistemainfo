<?php

session_start();

if($_SESSION['perfil'] == 3 || $_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8)
    {
        header("location: ./");
    }

include "../conexion_BD.php";

if(!empty($_POST))
{
    $alert ='';

    if( empty($_POST['Nombre_producto']) || empty($_POST['Precio']) || empty($_POST['linea']) || empty($_POST['iva']))
    {
        $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 

    }else{
        
        $cod_producto = $_POST['id'];
        $Nombre_producto= $_POST['Nombre_producto'];
        $Descripcion = $_POST['Descripcion'];
        $Precio = $_POST['Precio'];
        $Linea = $_POST['linea'];
        $iva = $_POST['iva'];
         
        $query_producto = mysqli_query($conection, "SELECT Cod_producto FROM productos WHERE Cod_producto = '$cod_producto'");
            
        $result = mysqli_fetch_array($query_producto);
        
            if($result > 0){
                $sql_update = mysqli_query ($conection, "UPDATE productos
                                                         SET Nombre_producto = '$Nombre_producto', Descripcion = '$Descripcion',  Precio = '$Precio', Linea = '$Linea', Iva = '$iva'
                                                         WHERE Cod_producto = '$cod_producto' AND estatus = 1");

                if($sql_update){
                    $alert ='<p class="msg_error">Producto Actualizado</p>';
                }else{
                    $alert ='<p class="msg_sav">Ha Ocurrido un error</p>';
                }
            }   
        
    }
    
}
    

    //MOSTRAR DATOS DEL PRODUCTO

    if(empty($_REQUEST['id'])){

        header('location: lista_productos.php');
        
    }
    $cod_producto = $_REQUEST['id'];

    $sql = mysqli_query($conection,"SELECT p.Cod_producto, p.Nombre_producto, p.Descripcion, p.Precio, l.Nombre_linea, p.Iva, p.Linea 
                                    FROM productos p
                                    JOIN lineas l ON (p.Linea = l.id_lineas)
                                    WHERE Cod_producto = '$cod_producto' AND estatus = 1");

    
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
        header('location: lista_producto.php');
    }else{

        while($data = mysqli_fetch_array($sql)){

            $cod_producto = $data['Cod_producto'];
            $Nombre_producto = $data['Nombre_producto'];
            $Descripcion = $data['Descripcion'];
            $Precio = $data['Precio'];
            $iva = $data['Iva'];
            $Linea =$data['Linea'];



            //$id_linea = $data['id_lineas'];
            $Nombre_linea = $data['Nombre_linea']; 
            /*
            if($id_linea == 1){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            }else if ($id_linea==2){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            }else if ($id_linea==3){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            }else if ($id_linea==4){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            }else if ($id_linea==5){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            }else if ($id_linea==6){
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            }else{
                $option_linea= '<option value = ""'. $id_linea.'" select>'. $Nombre_linea.'</option>';
            } */
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Producto</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1>ACTUALIZAR PRODUCTO</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                <input name ="id" value = "<?php echo $cod_producto; ?>" type ="hidden" >
                <label for = "Nombre_producto">Nombre Producto</label>
                <input type ="text" name ="Nombre_producto" id = "Nombre_producto" placeholder= "Nombre Producto" value = "<?php echo $Nombre_producto;?>">
                <label for = "Descripcion">Descripción</label>
                <input type ="text" name ="Descripcion" id = "Descripcion" placeholder= "Descripcion" value = "<?php echo $Descripcion;?>">
                <label for = "precio">Precio</label>
                <input type ="text" name ="Precio" id = "Precio" placeholder= "Precio" value = "<?php echo $Precio;?>">
                <label for = "iva">Impuesto del producto</label>
                <input type ="number" name ="iva" id = "iva" placeholder= "Iva" value = "<?php echo $iva; ?>">
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
                        while($Linea = mysqli_fetch_array($query_lineas)){
                ?>          
                        <option value = "<?php echo $Linea["id_lineas"]; ?>"><?php echo $Linea["Nombre_linea"]; ?></option>
                <?php
                        }
                    }
                ?>

                <input type = submit value ="Actualizar Producto" class = "btn_save">
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>