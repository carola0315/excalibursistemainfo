<?php

session_start();
if($_SESSION['perfil'] != 1 ){

    header("location: ./");
}

include "../conexion_BD.php";

    if (!empty($_POST))
    {
        $alert ='';
        if(empty($_POST['Ced_empleado']) || empty($_POST['Nombre_empleado']) || empty($_POST['Telefono_empleado']) ||
            empty($_POST['Direccion_empleado']) || empty($_POST['Cargo']) || empty($_POST['Usuario']) || empty($_POST['clave'])
            || empty($_POST['Estado']) || empty($_POST['perfil']))
        {

            $alert ='<p class="msg_error">Complete los campos obligatorios.</p>'; 

        }else{
            

            $Ced_empleado = $_POST['Ced_empleado'];
            $Nombre_empleado = $_POST['Nombre_empleado'];
            $Telefono_empleado = $_POST['Telefono_empleado'];
            $Direccion_empleado = $_POST['Direccion_empleado'];
            $Correo_electronico = $_POST['Correo_electronico'];
            $Cargo = $_POST['Cargo'];
            $Usuario = $_POST['Usuario'];
            $clave = md5($_POST['clave']); 
            $Estado = $_POST['Estado'];
            $perfil = $_POST['perfil'];


            $query = mysqli_query($conection, "SELECT empleados.Ced_empleado, usuarios.Usuario FROM empleados INNER JOIN usuarios ON empleados.Ced_empleado = '$Ced_empleado' AND usuarios.Usuario = '$Usuario'");
            //mysqli_close($conection);
            $result = mysqli_fetch_array($query);

            if($result > 0){

                $alert ='<p class="msg_error">Usuario ya existe</p>';
                
            }else{
                $query_insert = mysqli_query($conection, "INSERT INTO empleados(Ced_empleado, Nombre_empleado, Telefono_empleado,
                Direccion_empleado, Correo_electronico, Cargo, Estado, 	id_perfil) VALUES ($Ced_empleado, '$Nombre_empleado','$Telefono_empleado',
                '$Direccion_empleado', '$Correo_electronico', '$Cargo', '$Estado', $perfil)");

                if($query_insert == false){

                    $alert ='<p class="msg_error">Ha ocurrido un error</p>';

                }else{

                    $query_insert2 = mysqli_query($conection, "INSERT INTO usuarios(Usuario, clave, perfil, Ced_empleado) VALUES ('$Usuario', '$clave', '$perfil', '$Ced_empleado')");
                    
                    if($query_insert2){
                        $alert ='<p class="msg_sav">Empleado creado</p>';
                    }else{
                        $alert ='<p class="msg_error">Usuario ya existe</p>';
                    }
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
	<title>Nuevo Empleado</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
        <div class = "forms_register">
		    <h1><i class="fas fa-user-plus"></i>Registro nuevo empleado</h1>
            <hr>
            <div class="alert"><?php echo isset ($alert) ? $alert : ''; ?> </div>

            <form action="" method ="post">

                <label for = "Ced_empleado">Cedula</label>
                <input type ="text" name ="Ced_empleado" id = "Ced_empleado" placeholder= "Numero de cedula">
                <label for = "Nombre_empleado">Nombre y Apellido</label>
                <input type ="text" name ="Nombre_empleado" id = "Nombre_empleado" placeholder= "Nombre completo">
                <label for = "Telefono_empleado">Telefono</label>
                <input type ="text" name ="Telefono_empleado" id = "Telefono_empleado" placeholder= "Celular">
                <label for = "Dirección_empleado">Dirección</label>
                <input type ="text" name ="Direccion_empleado" id = "Direccion_empleado" placeholder= "Dirección Residencia">
                <label for ="Correo_electronico">Correo Electronico </label>
                <input type = "text" name = "Correo_electronico" id = "Correo_electronico" placeholder = "Correo Electronico">
                <label for ="Cargo">Cargo </label>
                <input type = "text" name = "Cargo" id = "Cargo" placeholder = "Cargo del empleado">
                <label for ="Usuario">Usuario </label>
                <input type = "text" name = "Usuario" id = "Usuario">
                <label for ="clave">Clave</label>
                <input type = "password" name = "clave" id = "clave">
                <label for = "Estado">Estado del Empleado</label>
                <select name = "Estado" id = "Estado">
                    <option value ="Activo">Activo</option>
                    <option value ="Inactivo">Inactivo</option>
                </select>
                <label for = "perfil">Perfil</label>
                <?php
                    $query_perfiles = mysqli_query($conection, "SELECT * FROM perfil");
                    mysqli_close($conection);
                    $result_perfiles = mysqli_num_rows($query_perfiles);
                ?>
                <select name = "perfil" id = "perfil">
                <?php
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
                <button type = "submit" class = "btn_save"><i class="fas fa-save"></i>Crear Empleado</button>
            </form>
        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>