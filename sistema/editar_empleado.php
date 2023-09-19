<?php

session_start();

if($_SESSION['perfil'] == 4 || $_SESSION['perfil'] == 5 || $_SESSION['perfil'] == 6 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == 8){

    header("location: ./");
}

include "../conexion_BD.php";

if(!empty($_POST))
{
    $alert ='';

    if(empty($_POST['Nombre_empleado']) || empty($_POST['Telefono_empleado']) || empty($_POST['Direccion_empleado']) || empty($_POST['Cargo'])
    || empty($_POST['Usuario']) || empty($_POST['Estado']))
    {
        $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 

    }else{
        

        $id_user = $_POST['id'];
        $Nombre_empleado = $_POST['Nombre_empleado'];
        $Telefono_empleado = $_POST['Telefono_empleado'];
        $Direccion_empleado = $_POST['Direccion_empleado'];
        $Correo_electronico = $_POST['Correo_electronico'];
        $Cargo = $_POST['Cargo'];
        $Usuario = $_POST['Usuario'];
        $clave = md5($_POST['clave']); 
        $Estado = $_POST['Estado'];
        $perfil = $_POST['perfil'];

         
        $query = mysqli_query($conection, "SELECT empleados.Ced_empleado, usuarios.Usuario FROM empleados INNER JOIN usuarios ON empleados.Ced_empleado = '$id_user' AND usuarios.Usuario = '$Usuario'");
            
        $result = mysqli_fetch_array($query);
        
            if($result > 0){

                if(empty($_POST['clave'])){

                    $sql_update = mysqli_query ($conection, "UPDATE empleados 
                                                             JOIN usuarios 
                                                             ON empleados.Ced_empleado = usuarios.Ced_empleado 
                                                             SET empleados.Nombre_empleado = '$Nombre_empleado', empleados.Telefono_empleado = '$Telefono_empleado', empleados.Direccion_empleado = '$Direccion_empleado', 
                                                             empleados.Correo_electronico = '$Correo_electronico', empleados.Cargo = '$Cargo', empleados.Estado = '$Estado', empleados.id_perfil ='$perfil'
                                                             WHERE empleados.Ced_empleado = '$id_user' AND usuarios.Ced_empleado = '$id_user';");
                }else{
                    $sql_update = mysqli_query ($conection, "UPDATE empleados 
                                                             JOIN usuarios 
                                                             ON empleados.Ced_empleado = usuarios.Ced_empleado 
                                                             SET empleados.Nombre_empleado = '$Nombre_empleado', empleados.Telefono_empleado = '$Telefono_empleado', empleados.Direccion_empleado = '$Direccion_empleado', 
                                                             empleados.Correo_electronico = '$Correo_electronico', empleados.Cargo = '$Cargo', usuarios.clave = '$clave', empleados.Estado = '$Estado', empleados.id_perfil ='$perfil'
                                                             WHERE empleados.Ced_empleado = '$id_user' AND usuarios.Ced_empleado = '$id_user';");    
                }                                               
                    if($sql_update){
                        $alert ='<p class="msg_error">Empleado Actualizado</p>';
                    }else{
                        $alert ='<p class="msg_sav">Ha Ocurrido un error</p>';
                    }
            }   
        
    }
    
}
    

    //MOSTRAR DATOS DEL USUARIO

    if(empty($_REQUEST['id'])){

        header('location: lista_empleados.php');
        
    }
    $id_user = $_REQUEST['id'];

    $sql = mysqli_query($conection,"SELECT e.Ced_empleado, e.Nombre_empleado, e.Telefono_empleado, e.Direccion_empleado, e.Correo_electronico, e.Cargo, u.usuario, e.Estado, p.id_perfil, p.perfil 
    FROM empleados e INNER JOIN usuarios u INNER JOIN perfil p ON e.id_perfil = p.id_perfil WHERE e.Ced_empleado = $id_user AND u.Ced_empleado = $id_user");

    
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
        header('location: lista_empleados.php');
    }else{
        $option_perfil = '';
        $option_estado = '';

        while($data = mysqli_fetch_array($sql)){

            $id_user = $data['Ced_empleado'];
            $Nombre_empleado = $data['Nombre_empleado'];
            $Telefono_empleado = $data['Telefono_empleado'];
            $Direccion_empleado = $data['Direccion_empleado'];
            $Correo_electronico = $data['Correo_electronico'];
            $Cargo = $data['Cargo'];
            $Usuario = $data['usuario'];
            $Estado = $data['Estado'];

            $option_estado ='<option value = ""'. $Estado.'" select>'. $Estado.'</option>';

            $id_perfil = $data['id_perfil'];
            $perfil = $data['perfil'];  

            if($id_perfil == 1){
                $option_perfil= '<option value = ""'. $id_perfil.'" select>'. $perfil.'</option>';
            }else if ($id_perfil==2){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else if ($id_perfil==3){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else if ($id_perfil==4){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else if ($id_perfil==5){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else if ($id_perfil==6){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else if ($id_perfil==7){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else if ($id_perfil==8){
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }else{
                $option_perfil = '<option value = ""'. $id_perfil.'"select>'. $perfil.'</option>';
            }
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
		    <h1>ACTUALIZAR DATOS</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                
                <input name ="id" value = "<?php echo $id_user; ?>" type ="hidden" >
                <label for = "Nombre_empleado">Nombre y Apellido</label>
                <input type ="text" name ="Nombre_empleado" id = "Nombre_empleado" placeholder= "Nombre completo" value = "<?php echo $Nombre_empleado; ?>">
                <label for = "Telefono_empleado">Telefono</label>
                <input type ="text" name ="Telefono_empleado" id = "Telefono_empleado" placeholder= "Celular"  value = "<?php echo $Telefono_empleado ?>">
                <label for = "Dirección_empleado">Dirección</label>
                <input type ="text" name ="Direccion_empleado" id = "Direccion_empleado" placeholder= "Dirección Residencia" value = "<?php echo $Direccion_empleado ?>">
                <label for ="Correo_electronico">Correo Electronico </label>
                <input type = "text" name = "Correo_electronico" id = "Correo_electronico" placeholder = "Correo Electronico" value = "<?php echo $Correo_electronico ?>">
                <label for ="Cargo">Cargo </label>
                <input type = "text" name = "Cargo" id = "Cargo" placeholder = "Cargo del empleado" value = "<?php echo $Cargo ?>">
                <label for ="Usuario">Usuario </label>
                <input type = "text" name = "Usuario" id = "Usuario" placeholder = "Usuario" value = "<?php echo $Usuario ?>">
                <label for ="clave">Clave</label>
                <input type = "text" name = "clave" id = "clave" placeholder = "clave">
                <label for = "Estado">Estado del Empleado</label>
                <select name = "Estado" id = "Estado" class = "notItemOne">
                    <?php echo $option_estado; ?>

                    <option value = "Activo">Activo</option>
                    <option value = "Inactivo">Inactivo</option>
                </select>
                <label for = "perfil">Perfil</label>
                <?php

                include "../conexion_BD.php";

                    $query_perfiles = mysqli_query($conection, "SELECT * FROM perfil");
                    mysqli_close($conection);
                    $result_perfiles = mysqli_num_rows($query_perfiles);
                ?>
                <select name = "perfil" id = "perfil" class="notItemOne">
                <?php
                    echo $option_perfil;

                    if($result_perfiles > 0)
                    {
                        while($perfil = mysqli_fetch_array($query_perfiles)){
                ?>          
                        <option value = "<?php echo $perfil["id_perfil"]; ?>"><?php echo $perfil["perfil"]; ?></option>
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