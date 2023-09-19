<?php

$alert="";
session_start(); //guardar datos de inicio de sesion

if(!empty($_SESSION['active']))
{
    header('location: sistema/');
}else{

    if(!empty($_POST))
    {
        if(empty($_POST["Usuario"]) || empty($_POST["clave"]))
        {
            $alert = "Ingrese su usuario y contraseña";
        }else{

            require_once "conexion_BD.php";

            $Usuario = mysqli_real_escape_string($conection,$_POST["Usuario"]);
            $clave =md5(mysqli_real_escape_string($conection,$_POST["clave"]));

            $query = mysqli_query($conection, "SELECT u.Usuario, u.clave, u.perfil, u.Ced_empleado, e.Nombre_empleado 
                                                FROM usuarios u INNER JOIN empleados e 
                                                WHERE Usuario = '$Usuario' AND clave = '$clave' AND e.Ced_empleado = u.Ced_empleado");

            //mysqli_close($conection);

            $resultado = mysqli_num_rows($query);

            if($resultado > 0)
            {
                $datos_sesion = mysqli_fetch_array($query); // llama los datos de la base de datos

                $_SESSION['active'] = true;
                $_SESSION ['Usuario'] = $datos_sesion ['Usuario'];
                $_SESSION ['clave'] = $datos_sesion['clave'];
                $_SESSION ['perfil'] = $datos_sesion ['perfil'];
                $_SESSION ['Ced_empleado'] = $datos_sesion ['Ced_empleado'];
                $_SESSION ['Nombre_empleado'] = $datos_sesion['Nombre_empleado'];

            }else {
                $alert = 'El usuario o la clave son incorrectos';
                session_destroy();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="css/Login.css">
    </head>
    <body>
        <header>
            <img class="encabezado" src="imagenes/logo.jpg" alt="logo excalibur">
        </header>

        <div class ="login_superior"> 
            <div class="login-box">
                <img class="inicio" src="imagenes/logo.jpg" alt="logo excalibur">
        
                <section class="form-login">

                    <form action ="" method="POST">

                        <h1>Bienvenido</h1>
                        <input class="controls" type="text" name="Usuario" placeholder="Usuario">
                        <p><input class="controls" type="password" name="clave" placeholder="clave"></p>
                        <div class = "alert"><?php echo isset($alert) ? $alert:'';?></div>
                        <p><input class="buttons" type="submit" name="" value="Ingresar"></p>
                    </form>
                </section>
            </div>
        </div>
    </body>
</html>