<?php
    session_start();
    include "..//conexion_BD.php";
    
    if(empty($_REQUEST['id_orden']))
	{
		echo "No es posible generar la Orden de Servicio.";

	}else{

		$idOrden = $_REQUEST['id_orden'];
		
		$query = mysqli_query($conection, "SELECT orden.id_Orden, orden.id_cliente, cli.Nombre_cliente, orden.fecha_evento, orden.Nombre_evento, orden.direccion_evento,
                                            orden.persona_evento, orden.contacto_evento, che.coordinador, orden.estatus
                                            FROM orden_servicio orden
                                            INNER JOIN clientes cli
                                            ON orden.id_cliente = cli.id_cliente
                                            INNER JOIN check_list che
                                            ON orden.id_Orden = che.id_Orden
                                            WHERE orden.id_Orden = $idOrden AND orden.estatus = 3");

        $result = mysqli_num_rows($query);
                    
        if($result > 0){

            $orden = mysqli_fetch_assoc($query);
            $idOrden = $orden['id_Orden'];
            
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/scripts.php"; ?>
    <title>Orden de Servicio</title>
</head>
<body>
    <?php include "includes/header.php"; ?>
    <section id = "container">
        <div class = "title_page">
            <h1><i class="fal fa-list-ul"></i>Postventa</h1>
        </div>
        <div class="datos_cliente">
            <div class ="action_cliente">
                <h4>Datos del cliente</h4>
            </div>
                <div class="wd30">
                    <input type="hidden" name = "postventa"  id= "postventa" value="<?php echo $orden['id_Orden']; ?>">
                    <input type="hidden" name = "Estatus"  id= "Estatus" value="<?php echo $orden['estatus']; ?>">
                    <input type="hidden" name = "usuario" id="usuario" value="<?php echo $_SESSION['Usuario']; ?>">

                       <a href="#" id = "Postventa" Post = "<?php echo $orden['id_Orden']; ?>"
                            onclick="event.preventDefault();
                                    verPostventa(<?php echo $orden['id_Orden']?>);"><i class="far fa-eye fa-2x"></i></a>

                </div>
            <form name ="nueva_posventa" id= "nueva_posventa" class="datos">
                <div class="wd30">
                    <label>Nit o Cedula de cliente</label>
                    <input type = "text" name = "identificacion" id= "identificacion" disabled required>
                    <label>Nombre Marca</label>
                    <input type = "text" name = "Marca" id= "Marca" disabled required>
                </div>
                <div class="wd30">
                    <label>Nombre de evento</label>
                    <input type = "text" name = "nombre_evento" id = "nombre_evento" disabled required>
                    <label>Fecha evento</label>
                    <input type = "date" name = "fecha_evento" id = "fecha_evento" disabled required>
                </div>
                <div class="wd30">
                    <label>Coordinador del evento</label>
                    <input type = "text" name = "coordinador_evento" id= "coordinador_evento" disabled required>
                    <label>Persona a Cargo</label>
                    <input type = "text" name = "Persona_cargo" id= "Persona_cargo" disabled required>
                </div>
                <div class="wd30">
                    <label>Telefono encargada</label>
                    <input type = "text" name = "Contacto_encargada" id= "Contacto_encargada" disabled required>
                </div>
            </form>
        </div>
        <div class = "datos_evento">
            <h4>Encuesta Postventa</h4>
            <div class ="datos">
                <div class = "wd30">
                    <label>1. Forma de contacto</label>
                    <select name = "text1" id = "text1">
                        <option value = ""></option>
                        <option value = "Telefonico">Telefonico</option>
                        <option value = "Presencial">Presencial</option>
                        <option value = "Correo Electronico">Correo Electronico</option>
                        <option value = "whatsapp">whatsapp</option>
                    </select>

                    <label>2. Se cumplieron los horarios establecidos</label>
                    <select name = "text2" id = "text2">
                        <option value = ""></option>
                        <option value = "1">1</option>
                        <option value = "2">2</option>
                        <option value = "3">3</option>
                        <option value = "4">4</option>
                        <option value = "5">5</option>
                    </select>

                    <label>2.5 Observaciones Horarios</label>
                    <textarea rows= "5" cols = "30" id= "text3"></textarea>

                    <label>3. La atención del personal fue amable y cordial</label>
                    <select name = "text4" id = "text4">
                        <option value = ""></option>
                        <option value = "1">1</option>
                        <option value = "2">2</option>
                        <option value = "3">3</option>
                        <option value = "4">4</option>
                        <option value = "5">5</option>
                    </select>

                    <label>3.5 Observaciones atencion personal</label>
                    <textarea rows= "5" cols = "30" id= "text5" required></textarea>

                    <label>4. Los elementos utilizados cumplieron sus expectativas de limpieza, orden y operatividad?</label>
                    <select name = "text6" id = "text6">
                        <option value = ""></option>
                        <option value = "1">1</option>
                        <option value = "2">2</option>
                        <option value = "3">3</option>
                        <option value = "4">4</option>
                        <option value = "5">5</option>
                    </select>
                </div>
                <div class = "wd30">
                <label>4.5 Observaciones de los elementos</label>
                    <textarea rows= "5" cols = "30" id= "text7" required></textarea>

                    <label>5. Las actividades programadas fueron realizadas con el dinamismo y profesionalismo esperado?</label>
                    <select name = "text8" id = "text8">
                        <option value = ""></option>
                        <option value = "1">1</option>
                        <option value = "2">2</option>
                        <option value = "3">3</option>
                        <option value = "4">4</option>
                        <option value = "5">5</option>
                    </select>
                    
                    <label>6. El personal estuvo durante toda su operación con dotación de las empresa, EPP y cumpliendo con los elementos de bioseguridad</label>
                    <select name = "text9" id = "text9">
                        <option value = ""></option>
                        <option value = "si">SI</option>
                        <option value = "no">NO</option>
                    </select>

                    <label>7. El personal implementó actividades para la protección del medio ambiente o reducción de la contaminación</label>
                    <select name = "text10" id = "text10">
                        <option value = ""></option>
                        <option value = "si">SI</option>
                        <option value = "no">NO</option>
                    </select>
                </div>
                <div class = "wd30">
                    <label>8. Observaciones medio ambiente</label>
                    <textarea rows= "5" cols = "30" id= "text11" required></textarea>

                    <label>9. Como califica la calidad del servicio en general?</label>
                    <select name = "text12" id = "text12">
                        <option value = ""></option>
                        <option value = "1">1</option>
                        <option value = "2">2</option>
                        <option value = "3">3</option>
                        <option value = "4">4</option>
                        <option value = "5">5</option>
                    </select>

                    <label>10. Observaciones Generales</label>
                    <textarea rows= "5" cols = "30" id= "text13" required></textarea>

                    <label>11. Utilizaría nuevamente los servicios de Excalibur Producciones?</label>
                    <select name = "text14" id = "text14">
                        <option value = ""></option>
                        <option value = "si">SI</option>
                        <option value = "no">NO</option>
                        <option value = "tal vez">TAL VEZ</option>
                    </select>

                    <label>12. ¿Cómo nos conoció?</label>
                    <select name = "text15" id = "text15">
                        <option value = ""></option>
                        <option value = "Internet">Internet</option>
                        <option value = "Redes sociales">Redes Sociales</option>
                        <option value = "Referido">Referido</option>
                        <option value = "Cliente antiguo">Cliente Antiguo</option>
                        <option value = "Otro">Otro</option>
                    </select>
                    <div id = "acciones_cotizacion">
                        <a href = "#" class = "btn_new textcenter" id = "addPosventa"><i class="fas fa-check"></i>Guardar</a>
                    </div>
                </div>
            </div>
        </div>
        
    </section>
    <?php include "includes/footer.php"; ?>

    <script type= "text/javascript">
       
    </script>
    
</body>
</html>