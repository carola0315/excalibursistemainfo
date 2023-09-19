$(document).ready(function(){

    $('#verOrden').click(function(e){
        e.preventDefault();
        
        var idOrden  = $(this).attr('id_orden');
        var action = 'verOrden';
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},
    
            success: function(response){
    
                $url ='orden_servicio.php?id_orden='+idOrden;
                window.open($url, "orden_servicio",  ", scrollbar=si, location=no, resizable=si, menubar=no");
            }, 
            error:function(error){
    
            }
        });
    });   

    $('#OrdenServicio').click(function(e){
        e.preventDefault();

        var action = 'OrdenServicio';
        var idOrden = $(this).attr('Orden');

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},

            success: function(response){

                if(response == 0){
    
                    alert("Orden de servicio no existe");
    
                }else{

                    var data = $.parseJSON(response);
                    
                    $('#id_orden').val(data.id_Orden);
                    $('#id_cliente_orden').val(data.id_cliente);
                    $('#Nombre_cliente').val(data.Nombre_cliente);
                    $('#Telefono_cliente').val(data.Telefono_cliente);
                    $('#Correo_cliente').val(data.Correo_cliente);
                    $('#Contacto').val(data.Contacto);
                    $('#Telefono_contacto').val(data.Telefono_contacto);
                    $('#Marca').val(data.Nombre_cliente);
                    $('#nombre_evento').val(data.Nombre_evento);
                    $('#direccion_evento').val(data.direccion_evento);
                    $('#indicaciones').val(data.indicaciones);
                    $('#fecha_evento').val(data.fecha_evento);
                    $('#hora_inicio').val(data.Hora_inicio);
                    $('#hora_final').val(data.Hora_final);
                    $('#asistentes').val(data.asistentes);
                    $('#persona_cargo').val(data.persona_evento);
                    $('#contacto_evento').val(data.contacto_evento);
                    $('#cargo_encargada').val(data.cargo_persona_evento);
                    $('#obligaciones_excalibur').val(data.observaciones1);
                    $('#compromisos_cliente').val(data.Compromisos_cliente);
                }
            }
        });

    });

    $('#add_producto_orden').click(function(e){
        e.preventDefault();
    
        if($('#txt_cantidad_dias').val() > 0 && $('#txt_cantidad').val() > 0){
    
                var codProducto = $('#txt_cod_producto').val();
                var cantidadDias = $('#txt_cantidad_dias').val();
                var cantidadProducto = $('#txt_cantidad').val();
                var precioUni= $('#txt_valor_unitario').val();
                var Orden_servicio = $('#Orden_servicio').val();
                var estatus = $('#Estatus').val();
                var action = 'add_producto_orden';
    
                $.ajax({
                    url: 'ajax2.php',
                    type: "POST",
                    asnyc: true,   
                    data: {action:action,producto:codProducto, cantidadDias:cantidadDias, cantidadProducto:cantidadProducto,
                        precioUni:precioUni, Orden_servicio:Orden_servicio, estatus:estatus},
    
                    success: function(response)
                    {
                        if(response != 'error'){

                            var info = JSON.parse(response);
                    
                            $('#detalle_orden').html(info.detalle);
                            $('#detalles_totales_orden').html(info.totales);
                            //verDetalleOrden(id_Orden);
                            $('#txt_cod_producto').val('');
                            $('#txt_Nombre').html('-');
                            $('#txt_Descripcion').html('-');
                            $('#txt_cantidad_dias').val('');
                            $('#txt_cantidad').val('');
                            $('#txt_valor_unitario').val('');
                            $('#txt_valor_total').html('0');

                            //bloquear cantidades y precio unitario

                            $('#txt_cantidad_dias').attr('disabled','disabled');
                            $('#txt_cantidad').attr('disabled','disabled');
                            $('#txt_valor_unitario').attr('disabled','disabled');

                            //Ocultar boton agregar

                            $('add_producto_orden').slideUp();
                        }else{
                            console.log('no data');
                        }
                    },
                    error: function(error){
                    }
                });   
        }
    });

    // Editar Orden de Servicio

    $('#editar_orden').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_orden tr').length;
        if(rows > 0){

            var id_Orden = $('#Orden_servicio').val();
            var nombre_evento = $('#nombre_evento').val();
            var direccion_evento = $('#direccion_evento').val();
            var indicaciones = $('#indicaciones').val();
            var fecha_evento = $('#fecha_evento').val();
            var hora_inicio = $('#hora_inicio').val();
            var hora_final = $('#hora_final').val();
            var numero_asistentes = $('#asistentes').val();
            var persona_cargo = $('#persona_cargo').val();
            var telefono_encargada = $('#contacto_evento').val();
            var cargo_encargada = $('#cargo_encargada').val();
            var obligaciones_excalibur = $('#obligaciones_excalibur').val();
            var compromisos_cliente = $('#compromisos_cliente').val();
            var estatus = $('#Estatus').val();
            var action = 'editar_orden';

            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,
                data: {action:action, id_Orden:id_Orden, nombre_evento:nombre_evento, direccion_evento:direccion_evento, indicaciones:indicaciones,
                        fecha_evento:fecha_evento, hora_inicio:hora_inicio, hora_final:hora_final, numero_asistentes:numero_asistentes, persona_cargo:persona_cargo,
                        telefono_encargada:telefono_encargada, cargo_encargada:cargo_encargada, obligaciones_excalibur:obligaciones_excalibur, 
                        compromisos_cliente:compromisos_cliente, estatus:estatus},
                
                success: function(response)
                {   
                    console.log(response);
                    
                    if(response != 'error')
                    {
                        var info = JSON.parse(response);
                        location.reload();
                    }else{
                        alert("Error Orden no se puede Editar")
                        console.log('no data');
                    }
                },
                error: function(error){
                }
            });
        }
    });

    $('#imprimir').click(function(e){
        e.preventDefault();

        var Orden = $(this).attr('Orden');;

        imprimirOrden(Orden);

    });

    $('#procesar_orden').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_orden tr').length;
        if(rows > 0){

            var id_cliente = $('#id_cliente_orden').val();
            var id_orden= $('#Orden_servicio').val();
            var action = 'procesarOrden';

            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                async: true,
                data: {action:action, id_cliente:id_cliente, id_orden:id_orden},
                
                success: function(response)
                {
                    if(response != 'error')
                    {
                        //var info = JSON.parse(response);
                        location.reload();

                    }else{
                        alert("Error Orden no se puede Editar")
                        console.log('no data');
                    }
                },
                error: function(error){
                }
            });
        }
    });

    $('#verCheckList').click(function(e){
        e.preventDefault();
        
        var idOrden  = $(this).attr('id_orden');
        var action = 'verCheckList';
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},
    
            success: function(response){
    
                $url ='check_list.php?id_orden='+idOrden;
                window.open($url, "orden_servicio",  ", scrollbar=si, location=no, resizable=si, menubar=no");
            }, 
            error:function(error){
    
            }
        });
    }); 

    $('#CheckList').click(function(e){
        e.preventDefault();

        var action = 'CheckList';
        var idOrden = $(this).attr('Check');

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},

            success: function(response){
                
                if(response == 0){
    
                    alert("Check List no existe");
    
                }else{

                    var data = $.parseJSON(response);
                    
                    $('#id_orden').val(data.id_Orden);
                    $('#Marca').val(data.Nombre_cliente);
                    $('#nombre_evento').val(data.Nombre_evento);
                    $('#direccion_evento').val(data.direccion_evento);
                    $('#indicaciones').val(data.indicaciones);
                    $('#fecha_evento').val(data.fecha_evento);
                    $('#hora_inicio').val(data.Hora_inicio);
                    $('#hora_final').val(data.Hora_final);
                    $('#asistentes').val(data.asistentes);
                    $('#persona_cargo').val(data.persona_evento);
                    $('#contacto_evento').val(data.contacto_evento);
                    $('#cargo_encargada').val(data.cargo_persona_evento);
                    $('#obligaciones_excalibur').val(data.obligaciones_excalibur);
                    $('#compromisos_cliente').val(data.Compromiso_cliente);
                    $('#coordinador').val(data.coordinador);
                    $('#hora_llegada').val(data.Hora_llegada);
                    $('#hora_salida').val(data.Hora_salida);
                    $('#condiciones_lugar').val(data.Condiciones_lugar);
                    $('#Accesos_permisos').val(data.permisos);
                    $('#Observaciones_generales').val(data.observaciones_generales);
                    $('#Observaciones_hse').val(data.hse);
                    $('#Observacion_comercial').val(data.comercial);
                    
                } 
            }
        });

    });

    //Buscar Empleado

    $('#txt_cod_empleado').keyup(function(e){
        e.preventDefault();

        var cedEmpleado = $(this).val();
        var action = 'infoEmpleado';

            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                async: true,
                data: {action:action,cedEmpleado:cedEmpleado},

                success: function(response)
                {
                   if(response != 'error'){

                    var info = JSON.parse(response);

                    $('#nombre_empleado').html(info.Nombre_empleado);
                    $('#telefono_empleado').html(info.Telefono_empleado);
                    $('#actividad').html('-');
                    $('#transporte').html('-');

                    //Activar cantidad
                    $('#actividad').removeAttr('disabled');
                    $('#transporte').removeAttr('disabled');
                    
                    // Activar botón agregar

                    $('#add_empleado').slideDown();
                   
                    }else{

                        $('#nombre_empleado').html('-');
                        $('#telefono_empleado').html('-');
                        $('#actividad').html('-');
                        $('#transporte').html('-');

                    //Bloquear cantidad
                        $('#actividad').attr('disabled','disabled');
                        $('#transporte').attr('disabled','disabled');

                        //ocultar boton agregar
                        $('#add_empleado').slideUp();
                    } 
                }
            });
    });

    $('#add_empleado').click(function(e){
        e.preventDefault();

                var id_che = $('#idChe').val();
                var Orden_servicio = $('#Check_list').val();
                var cedEmpleado = $('#txt_cod_empleado').val();
                var actividad = $('#actividad').val();
                var transporte = $('#transporte').val();
                var estatus = $('#Estatus').val();
                var action = 'add_empleado';
    
                $.ajax({
                    url: 'ajax2.php',
                    type: "POST",
                    asnyc: true,   
                    data: {action:action, id_che:id_che, cedEmpleado:cedEmpleado, actividad:actividad, transporte:transporte, estatus:estatus,
                        Orden_servicio:Orden_servicio},
    
                    success: function(response)
                    {
                        if(response != 'error'){

                            var info = JSON.parse(response);
                    
                            $('#detalle_empleados').html(info.personal);
                            
                            $('#nombre_empleado').html('-');
                            $('#telefono_empleado').html('-');
                            $('#actividad').html('-');
                            $('#transporte').html('-');
                            $('#actividad').removeAttr('disabled');
                            $('#transporte').removeAttr('disabled');
                    
                            // Activar botón agregar

                            $('#add_empleado').slideDown();

                        }else{
                            console.log('no data');
                        } 
                    },
                    error: function(error){
                    }
                });   
    });

    $('#txt_cod_proveedor').keyup(function(e){
        e.preventDefault();

        var cedProveedor = $(this).val();
        var action = 'infoProveedor';

            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                async: true,
                data: {action:action,cedProveedor:cedProveedor},

                success: function(response)
                {
                   if(response != 'error'){

                    var info = JSON.parse(response);

                    $('#nombre_proveedor').html(info.Nombre_proveedor);
                    $('#telefono_proveedor').html(info.Telefono_proveedor);
                    $('#actividad').html('-');
                    $('#transporte').html('-');

                    //Activar cantidad
                    $('#actividad').removeAttr('disabled');
                    $('#transporte').removeAttr('disabled');
                    
                    // Activar botón agregar

                    $('#add_proveedor').slideDown();
                   
                    }else{

                        $('#nombre_empleado').html('-');
                        $('#telefono_empleado').html('-');
                        $('#actividad').html('-');
                        $('#transporte').html('-');

                    //Bloquear cantidad
                        $('#actividad').attr('disabled','disabled');
                        $('#transporte').attr('disabled','disabled');

                        //ocultar boton agregar
                        $('#add_proveedor').slideUp();
                    } 
                }
            });
    });

    $('#add_proveedor').click(function(e){
        e.preventDefault();

            var id_che = $('#idChe').val();
            var Orden_servicio = $('#Check_list').val();
            var idProveedor = $('#txt_cod_proveedor').val();
            var actividad = $('#actividad_pro').val();
            var transporte = $('#transporte_pro').val();
            var estatus = $('#Estatus').val();
            var action = 'add_proveedor';
    
                $.ajax({
                    url: 'ajax2.php',
                    type: "POST",
                    asnyc: true,   
                    data: {action:action, id_che:id_che, idProveedor:idProveedor, actividad:actividad, transporte:transporte, estatus:estatus,
                        Orden_servicio:Orden_servicio},
    
                    success: function(response)
                    {   
                        if(response != 'error'){

                            var info = JSON.parse(response);
                    
                            $('#detalle_proveedor').html(info.proveedor);
                            
                            $('#nombre_proveedor').html('-');
                            $('#telefono_proveedor').html('-');
                            $('#actividad_pro').html('-');
                            $('#transporte_pro').html('-');
                            $('#actividad_pro').removeAttr('disabled');
                            $('#transporte_pro').removeAttr('disabled');
                    
                            // Activar botón agregar

                            $('#add_proveedor').slideDown();

                        }else{

                            console.log('no data');
                        }
                    },
                    error: function(error){
                    }
                });   
    });

    $('#editar_check').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_producto tr').length;

        if(rows > 0){

            var id_Orden = $('#Check_list').val();
            var estatus = $('#Estatus').val();
            var che = $('#idChe').val();
            var obligaciones = $('#obligaciones_excalibur').val();
            var compromiso = $('#compromisos_cliente').val();
            var coordinador = $('#coordinador').val();
            var llegada = $('#hora_llegada').val();
            var salida = $('#hora_salida').val();
            var condiciones = $('#condiciones_lugar').val();
            var permisos = $('#Accesos_permisos').val();
            var generales = $('#Observaciones_generales').val();
            var hse = $('#Observaciones_hse').val();
            var comercial = $('#Observacion_comercial').val();
            var action = 'editar_check';

            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,
                data: {action:action, id_Orden:id_Orden, che:che, obligaciones:obligaciones, compromiso:compromiso, coordinador:coordinador,
                        llegada:llegada, salida:salida, condiciones:condiciones, permisos:permisos, generales:generales, hse:hse,
                        comercial:comercial, estatus:estatus},
                
                success: function(response)
                {   
                    if(response != 'error')
                    {
                        var info = JSON.parse(response);
                        location.reload();
                    }else{
                        console.log('no data');
                    }
                },
                error: function(error){
                } 
            });
        } else {
            alert ("Error no presenta productos")
        }
    });

    $('#procesar_check').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_producto tr').length;

        if(rows > 0){

            var id_chec = $('#idChe').val();
            var id_orden= $('#Check_list').val();
            var action = 'procesarCheck';

            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                async: true,
                data: {action:action, id_chec:id_chec, id_orden:id_orden},
                
                success: function(response)
                { 
                    if(response != 'error')
                    {
                        var info = JSON.parse(response);
                        location.reload();

                    }else{

                        alert("Favor ingresar personal");

                        console.log('no data');
                    } 
                },
                error: function(error){
                }
            });
        }else {
            alert ("Error no presenta productos")
        }
    });

    $('#imprimircheck').click(function(e){
        e.preventDefault();

        var Check = $(this).attr('Check');

        imprimirCheck(Check);

    });

    $('#VerCostos').click(function(e){
        e.preventDefault();
        
        var idOrden  = $(this).attr('id_orden');
        var action = 'verCostos';
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},
    
            success: function(response){

                $url ='costos.php?id_orden='+idOrden;
                window.open($url, "Costos",  ", scrollbar=si, location=no, resizable=si, menubar=no");
            }, 
            error:function(error){
    
            }
        });
    }); 

    $('#Costos').click(function(e){
        e.preventDefault();

        var action = 'Costos';
        var idOrden = $(this).attr('Costo');

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},

            success: function(response){

                if(response == 0){
    
                    alert("Orden de servicio no existe");
    
                }else{

                    var data = $.parseJSON(response);
                    
                    $('#id_orden').val(data.id_Orden);
                    $('#identificacion').val(data.id_cliente);
                    $('#Marca').val(data.Nombre_cliente);
                    $('#nombre_evento').val(data.Nombre_evento)
                    $('#fecha_evento').val(data.fecha_evento);
                    $('#coordinador_evento').val(data.coordinador);
                    $('#Precio_total').val(data.Precio_total);
                }
            }
        });

    });

    $('#add_costo').click(function(e){
        e.preventDefault();

        var Orden_servicio = $('#costos').val();
        var fecompra = $('#fecha_costo').val();
        var numsoporte = $('#n_soporte').val();
        var tipo_costo = $('#tipo_costo').val();
        var descripcion = $('#Descripcion_costo').val();
        var precio = $('#Precio_costo').val();
        var estatus = $('#Estatus').val();
         var action = 'add_costo';
    
            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,   
                data: {action:action, fecompra:fecompra, numsoporte:numsoporte, tipo_costo:tipo_costo, descripcion:descripcion, precio:precio, 
                    estatus:estatus, Orden_servicio:Orden_servicio},
    
                success: function(response)
                {   

                    if(response != 'error'){

                        var info = JSON.parse(response);
                    
                            $('#detalle_costos').html(info.detalle_costos);
                            $('#totales_costos').html(info.totales_costos);

                            verDetalleCosto(Orden_servicio);
                            
                            $('#fecha_costo').html('-');
                            $('#n_soporte').html('-');
                            $('#tipo_costo').html('-');
                            $('#Descripcion_costo').html('-');
                            $('#Precio_costo').html('-');

                            // Activar botón agregar

                            $('#add_costo').slideDown();

                        }else{

                            console.log('no data');
                            alert("Ingrese todos los datos");

                        }
                    },
                error: function(error){
                }
            });   
    });

    $('#add_abono').click(function(e){
        e.preventDefault();

        var OrdenServicio = $('#costos').val();
        var feabono = $('#fecha_pago').val();
        var tipo_pago = $('#tipo_pago').val();
        var descripcion = $('#Descripcion_soporte').val();
        var factura = $('#num_factura').val();
        var pago = $('#Precio_pago').val();
        var action = 'add_abono';
    
            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,   
                data: {action:action, feabono:feabono, tipo_pago:tipo_pago, descripcion:descripcion, factura:factura, pago:pago, OrdenServicio:OrdenServicio},
    
                success: function(response)
                {   

                    if(response != 'error'){

                        var info = JSON.parse(response);

                            $('#detalle_pago').html(info.detallePagos);
                            $('#totales_pago').html(info.totales_pago);
                            
                            verDetalleCosto(OrdenServicio);

                            $('#fecha_pago').html('-');
                            $('#tipo_pago').html('-');
                            $('#Descripcion_soporte').html('-');
                            $('#num_factura').html('-');
                            $('#Precio_pago').html('-');
                            
                            // Activar botón agregar

                            $('#add_abono').slideDown();

                        }else{

                            console.log('no data');
                            alert("Ingrese todos los datos");

                        } 
                    },
                error: function(error){
                } 
            });   
    });

    $('#postventa').click(function(e){
        e.preventDefault();
        
        var idOrden  = $(this).attr('id_orden');
        var action = 'postventa"';
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},
    
            success: function(response){

                $url ='Pos_venta.php?id_orden='+idOrden;
                window.open($url, "Postventa",  ", scrollbar=si, location=no, resizable=si, menubar=no");
            }, 
            error:function(error){
    
            }
        });
    });

    $('#Postventa').click(function(e){
        e.preventDefault();

        var action = 'Postventa';
        var idOrden = $(this).attr('Post');

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},

            success: function(response){

                if(response == 0){
    
                    alert("Orden de Servicio no se encuentra en ejecución");
    
                }else{

                    var data = $.parseJSON(response);
                    
                    $('#id_orden').val(data.id_Orden);
                    $('#identificacion').val(data.id_cliente);
                    $('#Marca').val(data.Nombre_cliente);
                    $('#nombre_evento').val(data.Nombre_evento)
                    $('#fecha_evento').val(data.fecha_evento);
                    $('#coordinador_evento').val(data.coordinador);
                    $('#Persona_cargo').val(data.persona_evento);
                    $('#Contacto_encargada').val(data.contacto_evento);
                }
            }
        });

    });

    $('#add_movimiento').click(function(e){
        e.preventDefault();

        var tipo_movi= $('#tipo_movimiento').val();
        var descripcion_movi= $('#descripcion').val();
        var encargado = $('#encargado').val();
        var valor_movi = $('#Precio_movimiento').val();
        var fecha_limite = $('#fecha_maxima').val();
        var estado = $('#Estado').val();
        var action = 'add_movimiento';
    
            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,   
                data: {action:action, tipo_movi:tipo_movi, descripcion_movi:descripcion_movi, encargado:encargado, valor_movi:valor_movi, fecha_limite:fecha_limite, estado:estado},
    
                success: function(response)
                {   
                    if(response != 'error'){
                        
                        var info = JSON.parse(response);

                            $('#detalleCaja').html(info.detalleCaja);
                            
                            $('#tipo_movimiento').html('-');
                            $('#descripcion').html('-');
                            $('#encargado').html('-');
                            $('#Precio_movimiento').html('-');
                            $('#fecha_maxima').html('-');
                            $('#Estado').html('-');
                            
                            
                            // Activar botón agregar

                            $('#add_movimiento').slideDown();

                            location.reload();

                        }else{

                            console.log('no data');
                            alert("Ingrese todos los datos");

                        } 
                    },  
                error: function(error){ 
                } 
            });  
    });

    $('#addPosventa').click(function(e){
        e.preventDefault();

        var Orden= $('#postventa').val();
        var idCliente = $('#identificacion').val();
        var usuario = $('#usuario').val();
        var meContacto= $('#Persona_cargo').val();
        var contacto= $('#text1').val();
        var calHorarios = $('#text2').val();
        var desHorarios = $('#text3').val();
        var calPersonal = $('#text4').val();
        var desPersonal = $('#text5').val();
        var calElementos = $('#text6').val();
        var desElementos = $('#text7').val();
        var calDinamismo = $('#text8').val();
        var usoEpp = $('#text9').val();
        var Ambiente = $('#text10').val();
        var desAmbiente = $('#text11').val();
        var calGeneral = $('#text12').val();
        var desGeneral = $('#text13').val();
        var utiServicios = $('#text14').val();
        var conocioEx = $('#text15').val();
        var nombre_evento=$('#nombre_evento').val();
        var action = 'addPosventa';
    
            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,   
                data: {action:action, Orden:Orden, idCliente:idCliente, usuario:usuario, meContacto:meContacto, contacto:contacto,
                    calHorarios:calHorarios, desHorarios:desHorarios, calPersonal:calPersonal, desPersonal:desPersonal, calElementos:calElementos, desElementos:desElementos,
                    calDinamismo:calDinamismo, usoEpp:usoEpp, Ambiente:Ambiente, desAmbiente:desAmbiente, calGeneral:calGeneral, desGeneral:desGeneral,
                    utiServicios:utiServicios, conocioEx:conocioEx, nombre_evento:nombre_evento},
    
                success: function(response)
                {   
                    if(response != 'error'){

                        var info = JSON.parse(response);

                         alert("Post Venta realizada");
                    }
                },  
                error: function(error){ 
                } 
            });  
    });

    $('#finalizar').click(function(e){
        e.preventDefault();
        
        var idOrden  = $(this).attr('id_orden');
        var action = 'finalizar';
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            async: true,
            data: {action:action, idOrden:idOrden},
    
            success: function(response){

                $url ='Finalizar_orden.php?id_orden='+idOrden;
                window.open($url, "Postventa",  ", scrollbar=si, location=no, resizable=si, menubar=no");
            }, 
            error:function(error){
    
            }
        });
    });

    $('#Finalizar_orden').click(function(e){
        e.preventDefault();

        var idOrden= $('#finalizar').val();
        var Estatus = $('#Estatus').val();
        var cliente = $('#Cliente').val();
        var Oblao=$('#text_fin').val();
        var action = 'Finalizar_orden';
    
            $.ajax({
                url: 'ajax2.php',
                type: "POST",
                asnyc: true,   
                data: {action:action, idOrden:idOrden, cliente:cliente, Estatus:Estatus, Oblao:Oblao},
    
                success: function(response)
                {   
                    console.log(response);
                },  
                error: function(error){ 
                } 
            });  
    });

    
    
}); // fin ready

function imprimirOrden(Orden){

    var ancho = 1000;
    var alto = 800;

    var x = parseInt((window.screen.width/2) - (ancho/2));
    var y = parseInt((window.screen.height/2) - (alto/2));

    $url ='ordenServicio/generaOrden.php?Orden='+Orden;
    window.open($url, "Orden_Servicio", "left="+x+", top"+y+", height="+alto+", width="+ancho+", scrollbar=si, location=no, resizable=si, menubar=no");

}

function verDetalleOrden(id_Orden){
    
    var action='detalleOrden';
    var id_orden = id_Orden;

    $.ajax({
        url: 'ajax2.php',
        type: "POST",
        async: true,
        data: {action:action, id_orden:id_orden},

        success: function(response){
             
            if(response != 'error'){

                var info = JSON.parse(response);
                
                $('#detalle_orden').html(info.detalle);
                $('#detalles_totales_orden').html(info.totales);
                
            }else{
                console.log('no data');
            } 
            
        }, 
        error:function(error){

        }
    });  
}

function eliminar_product_orden(Cod_orden_detalle){

    var action = 'eliminar_product_orden';
    var Orden_servicio = $('#Orden_servicio').val();
    var estatus = $('#Estatus').val();
    var id_detalle = Cod_orden_detalle;

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,id_detalle:id_detalle,Orden_servicio:Orden_servicio, estatus:estatus},

            success: function(response){

                if(response != 'error'){

                    var info = JSON.parse(response);
                    
                    $('#detalle_orden').html(info.detalle);
                    $('#detalles_totales_orden').html(info.totales);
                    //verDetalleOrden(id_Orden);
                    $('#txt_cod_producto').val('');
                    $('#txt_Nombre').html('-');
                    $('#txt_Descripcion').html('-');
                    $('#txt_cantidad_dias').val('');
                    $('#txt_cantidad').val('');
                    $('#txt_valor_unitario').val('');
                    $('#txt_valor_total').html('0');

                    //bloquear cantidades y precio unitario

                    $('#txt_cantidad_dias').attr('disabled','disabled');
                    $('#txt_cantidad').attr('disabled','disabled');
                    $('#txt_valor_unitario').attr('disabled','disabled');

                    //Ocultar boton agregar

                    $('add_producto_orden').slideUp();

                }else{
                    $('#detalle_orden').html('');
                    $('#detalles_totales_orden').html('');

                }
        },
        error: function(error){
        }
    }); 
}

function verDetalleCheck(id_Orden){
    
    var action='detalleCheck';
    var id_orden = id_Orden;

    $.ajax({
        url: 'ajax2.php',
        type: "POST",
        async: true,
        data: {action:action, id_orden:id_orden},

        success: function(response){
            
            if(response != 'error'){

                var info = JSON.parse(response);
                
                $('#detalle_producto').html(info.detalle);
                $('#detalle_empleados').html(info.personal);
                $('#detalle_proveedor').html(info.proveedor);
                
            }else{
                console.log('no data');
            } 
            
        }, 
        error:function(error){

        }
    });  
}

function eliminar_personal(Cod_check){

    var action = 'eliminar_personal';
    var Orden_servicio = $('#Check_list').val();
    var estatus = $('#Estatus').val();
    var id_personal = Cod_check;

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,id_personal:id_personal,Orden_servicio:Orden_servicio, estatus:estatus},

            success: function(response){

                if(response != 'error'){

                    var info = JSON.parse(response);
                    
                        $('#detalle_empleados').html(info.personal);
                            
                            $('#nombre_empleado').html('-');
                            $('#telefono_empleado').html('-');
                            $('#actividad').html('-');
                            $('#transporte').html('-');
                            $('#actividad').removeAttr('disabled');
                            $('#transporte').removeAttr('disabled');
                    
                            // Activar botón agregar

                            $('#add_empleado').slideDown();
                }else{
                    console.log('no data');
                } 
        },
        error: function(error){
        }
    }); 
}

function eliminar_proveedor(Cod_check_pro){

    var action = 'eliminar_proveedor';
    var Orden_servicio = $('#Check_list').val();
    var estatus = $('#Estatus').val();
    var id_proveedor = Cod_check_pro;

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,id_proveedor:id_proveedor,Orden_servicio:Orden_servicio, estatus:estatus},

            success: function(response){

                if(response != 'error'){

                    var info = JSON.parse(response);
                    
                        $('#detalle_proveedor').html(info.proveedor);
                            
                            $('#nombre_proveedor').html('-');
                            $('#telefono_proveedor').html('-');
                            $('#actividad_pro').html('-');
                            $('#transporte_pro').html('-');
                            $('#actividad_pro').removeAttr('disabled');
                            $('#transporte_pro').removeAttr('disabled');
                    
                            // Activar botón agregar

                            $('#add_proveedor').slideDown();
                }else{
                    console.log('no data');
                } 
        },
        error: function(error){
        }
    }); 
}

function imprimirCheck(Check){

    var ancho = 1000;
    var alto = 800;

    var x = parseInt((window.screen.width/2) - (ancho/2));
    var y = parseInt((window.screen.height/2) - (alto/2));

    $url ='checkList/generaCheck.php?Check='+Check;
    window.open($url, "Check_List", "left="+x+", top"+y+", height="+alto+", width="+ancho+", scrollbar=si, location=no, resizable=si, menubar=no");

}

function verDetalleCosto(id_Orden){
    
    var action='detalleCostos';
    var id_orden = id_Orden;

    $.ajax({
        url: 'ajax2.php',
        type: "POST",
        async: true,
        data: {action:action, id_orden:id_orden},

        success: function(response){
 
            if(response != 'error'){
                var info = JSON.parse(response);

                $('#detalleCostos').html(info.detalle_costos);
                $('#totalesCostos').html(info.totales_costos);
                $('#detalle_pago').html(info.detalle_pago);
                $('#totales_pago').html(info.totales_pago);
                
            }else{
                console.log('no data');
            } 
            
        }, 
        error:function(error){

        }
    });  
}

function eliminar_costo(id_costo){

    var action = 'eliminar_costo';
    var Orden_servicio = $('#costos').val();
    var id_costo = id_costo;

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,id_costo:id_costo,Orden_servicio:Orden_servicio},

            success: function(response){

                if(response != 'error'){
                    
                    var info = JSON.parse(response);
                    
                    $('#detalleCostos').html(info.detalle_costos);
                    $('#totalesCostos').html(info.totales_costos);

                    verDetalleCosto(Orden_servicio);
                            
                    $('#fecha_costo').html('-');
                    $('#n_soporte').html('-');
                    $('#tipo_costo').html('-');
                    $('#Descripcion_costo').html('-');
                    $('#Precio_costo').html('-');  
                    
                    // Activar botón agregar

                    $('#add_costo').slideDown();
                }else{
                    console.log('no data');
                } 
        },
        error: function(error){
        }
    }); 
}

function eliminar_abono(id_abono){

    var action = 'eliminar_abono';
    var Orden_servicio = $('#costos').val();
    var id_abono = id_abono;

        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,id_abono:id_abono,Orden_servicio:Orden_servicio},

            success: function(response){

                if(response != 'error'){

                    var info = JSON.parse(response);
                    
                    $('#detalle_pago').html(info.detallePagos);
                    $('#totales_pago').html(info.totales_pago);

                    verDetalleCosto(Orden_servicio);
                            
                    $('#fecha_pago').html('-');
                    $('#tipo_pago').html('-');
                    $('#Descripcion_soporte').html('-');
                    $('#num_factura').html('-');
                    $('#Precio_pago').html('-');
                    
                    // Activar botón agregar

                    $('#add_abono').slideDown();
                }else{
                    console.log('no data');
                } 
        },
        error: function(error){
        }
    }); 
}

function legalizar(id_movimiento){

    var action = 'legalizar';
    var movimiento = id_movimiento;

    $('.modal').fadeIn();
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,movimiento:movimiento},

            success: function(response){
                
                if(response != 'error'){
                    
                    var info = JSON.parse(response);
                    
                    $('#id_movimiento').val(info.id_movimiento);
                    $('.datos_movimiento').html(info.Nombre_empleado);
                    $('.fecha_movimiento').html(info.fecha_movimiento);
                    $('.precio_movimiento').html(info.Valor_movimiento);
                    $('#valor_movimiento').val(info.Valor_movimiento);
                    $('#id_responsable').val(info.Responsable);


                }  
            },
            error: function(error){
                console.log(error);
            } 
    }); 
}

function enviarDatosLegalizacion(){
    
   $('.alert_legalizacion').html('');

   $.ajax({
    url: 'ajax2.php',
    type: "POST",
    asnyc: true,
    data: $('#legalizacion').serialize(),

    success: function(response){
        console.log(response);
        
        if(response == 'error'){
            
            $('.alertLegalizacion').html('<p style="color:red;">Error al legalizar</p>');

        }else{

            var info = JSON.parse(response);

            $('#detalleCaja').html(info.detalleCaja);
                            
            $('#valor_lega').val('');
            $('#saldo_lega').val('');
            $('#observacion_lega').val('');

            $('.alertLegalizacion').html('<p>Movimiento legalizado</p>');
        } 
    },
    error: function(error){
        console.log(error);
    } 
}); 

}

function closeModal(){
    $('#valor_lega').val('');
    $('#saldo_lega').val('');
    $('#observacion_lega').val('');

    $('.modal').fadeOut();
}

function pago_legalizaciones(id_legalizacion){

    var action = 'pago_legalizaciones';
    var legalizacion = id_legalizacion;
    
        $.ajax({
            url: 'ajax2.php',
            type: "POST",
            asnyc: true,
            data: {action:action,legalizacion:legalizacion},

            success: function(response){

                if(response != 'error'){
                    
                    var info = JSON.parse(response);
                    alert("Legalización actualizada");
                    location.reload();

                }
            },
            error: function(error){
                console.log(error);
            } 
    }); 
}

function verPostventa(id_Orden){
    
    var action='detallePost';
    var id_orden = id_Orden;

    $.ajax({
        url: 'ajax2.php',
        type: "POST",
        async: true,
        data: {action:action, id_orden:id_orden},

        success: function(response){
             
            console.log(response);

            if(response != 'error'){

                var info = JSON.parse(response);

                $('#text1').val(info.Metodo_llamada);
                $('#text2').val(info.cumple_horarios);
                $('#text3').val(info.observacion_horarios);
                $('#text4').val(info.personal)
                $('#text5').val(info.observaciones_personal);
                $('#text6').val(info.elementos);
                $('#text7').val(info.observaciones_elementos);
                $('#text8').val(info.dinamismo_actividades);
                $('#text9').val(info.personal_dotacion);
                $('#text10').val(info.medio_ambiente);
                $('#text11').val(info.observaciones_medio);
                $('#text12').val(info.servicio_general);
                $('#text13').val(info.observaciones_generales);
                $('#text14').val(info.excalibur_nuevamente);
                $('#text15').val(info.como_conocio);
                
            }else{
                console.log('no data');
            } 
            
        }, 
        error:function(error){

        }
    });  
}


