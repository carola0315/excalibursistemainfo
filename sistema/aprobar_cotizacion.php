<?php

    session_start();

    if($_SESSION['perfil'] != 1 and $_SESSION['perfil'] != 2 and $_SESSION['perfil'] != 3 and $_SESSION['perfil'] =! 4){

        header("location: ./");
    }

    include "../conexion_BD.php";

    if(!empty($_POST)){

        if(empty($_POST['id_cotizacion'])){

            header("location: lista_cotizaciones.php");
            exit;
        }
    
        $idCoti = $_POST['id_cotizacion'];
        
        $query_aprobacion = mysqli_query($conection,"CALL Aprobar_cotizacion($idCoti)");

        if($query_aprobacion){

            header("location: lista_cotizaciones.php");

        }else{

            echo "Error al momento de aprobar la cotización";
        }
    }



    if(empty($_REQUEST['id'])){

        header("location: lista_cotizaciones.php");
        mysqli_close($conection);

    }else{
        
        $idcotizacion = $_REQUEST['id'];

        $query = mysqli_query($conection, "SELECT c.id_cotizacion, c.fecha_evento, c.tipo_servicio, c.Usuario as comercial, c.estatus, c.Total_cotizacion,
                                        cl.Nombre_cliente
                                        FROM Cotizaciones c
                                        INNER JOIN usuarios u
                                        ON c.Usuario = u.Usuario 
                                        INNER JOIN clientes cl 
                                        ON c.id_cliente = cl.id_cliente
                                        WHERE id_cotizacion = '$idcotizacion'");

        $result = mysqli_num_rows($query);

        if($result > 0){

            while($data = mysqli_fetch_array($query)){
                
                $id_cotizacion = $data['id_cotizacion'];
                $cliente= $data['Nombre_cliente'];
                $Fecha_evento= $data['fecha_evento'];
                $Servicio_prestado = $data['tipo_servicio'];
            }
        }else{
             header("location: lista_cotizaciones.php");
        }

    }
    

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>APROBAR COTIZACIÓN</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class = "forms_register">
            <i class="far fa-check-circle fa-7x" style="color: #0cd600"></i>
            <h2>APROBAR COTIZACIÓN</h2>
            <br>
            <h2>¿Confirma la aprobación de la cotización y creación de Orden de Servicio?</h2>
            <br>
            <p>N° DE COTIZACION: <span><?php echo $idcotizacion; ?></span></p>
            <p>Cliente: <span><?php echo $cliente; ?></span></p>
            <p>Fecha de evento: <span><?php echo $Fecha_evento; ?></span></p>
            <p>Servicio: <span><?php echo $Servicio_prestado; ?></span></p>
            <br>
            <form method = "post" action="">
                <input type="hidden" name = "id_cotizacion" value="<?php echo $idcotizacion; ?>">
                <a href = "lista_cotizaciones.php" class = "btn_cancel">Cancelar</a>
                <input type = "Submit" value = "Aceptar" class = "btn_aceptar">

            </form>

        </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>