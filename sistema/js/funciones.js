$(document).ready(function(){

    // Activa campos para registrar clientes

    $('.btn_new_cliente').click(function(e){
        e.preventDefault();
        $('#Nombre_cliente').removeAttr('disabled');
        $('#Direccion_cliente').removeAttr('disabled');
        $('#Telefono_cliente').removeAttr('disabled');
        $('#Correo_cliente').removeAttr('disabled');
        $('#Contacto').removeAttr('disabled');  
        $('#Telefono_contacto').removeAttr('disabled');

        $('#div_registro_cliente').slideDown();
    });

    //funcion buscar cliente para registro cotización

    $('#id_cliente').keyup(function(e){
        e.preventDefault();

        var cl = $(this).val();
        var action = 'searchCliente';

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,id_cliente:cl},

            success: function(response)
            {
                if(response == 0){
                    
                    $('#Nombre_cliente').val('');
                    $('#Direccion_cliente').val('');
                    $('#Telefono_cliente').val('');
                    $('#Correo_cliente').val('');
                    $('#Contacto').val('');
                    $('#Telefono_contacto').val('');
                    //Mostrar boton guardar
                    $('.btn_new_cliente').slideDown();
                }else{

                    var data = $.parseJSON(response);

                    $('#Nombre_cliente').val(data.Nombre_cliente);
                    $('#Direccion_cliente').val(data.Direccion_cliente);
                    $('#Telefono_cliente').val(data.Telefono_cliente);
                    $('#Correo_cliente').val(data.Correo_cliente);
                    $('#Contacto').val(data.Contacto);
                    $('#Telefono_contacto').val(data.Telefono_contacto);
                    //Ocultar boton guardar
                    $('.btn_new_cliente').slideUp();

                    //Bloquear campos

                    $('#Nombre_cliente').attr('disabled','disabled');
                    $('#Direccion_cliente').attr('disabled','disabled');
                    $('#Telefono_cliente').attr('disabled','disabled');
                    $('#Correo_cliente').attr('disabled','disabled');
                    $('#Contacto').attr('disabled','disabled');
                    $('#Telefono_contacto').attr('disabled','disabled');

                    //Oculta boton guardar

                    $('#div_registro_cliente').slideUp();

                }
            },
            error: function(error){
            }       
        });

    });

    // Crear nuevo cliente cotización

    $('#form_new_cliente_cotizacion').submit(function(e){
        e.preventDefault();

        $.ajax({
            url: 'ajax.php',
            type: "POST",
            async: true,
            data: $('#form_new_cliente_cotizacion').serialize(),

            success: function(response){

                if(response != 'error'){

                    $('#id_cliente').val(response);

                    //bloquea campos cliente
                    
                    $('#Nombre_cliente').attr('disabled','disabled');
                    $('#Direccion_cliente').attr('disabled','disabled');
                    $('#Telefono_cliente').attr('disabled','disabled');
                    $('#Correo_cliente').attr('disabled','disabled');
                    $('#Contacto').attr('disabled','disabled');
                    $('#Telefono_contacto').attr('disabled','disabled');

                    //Oculta boton agregar
                    $('.btn_new_cliente').slideUp();
                    
                    //Oculta boton guardar
                    $('#div_registro_cliente').slideUp();

                }
                
            },
            error: function(error){
            }
        });
    });

    // Buscar producto

    $('#txt_cod_producto').keyup(function(e){
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProducto';

        if(producto != ''){

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action,txt_cod_producto:producto},

                success: function(response)
                {
                   if(response != 'error'){

                    var info = JSON.parse(response);

                    $('#txt_Nombre').html(info.Nombre_producto);
                    $('#txt_Descripcion').html(info.Descripcion);
                    $('#txt_cantidad_dias').val('1');
                    $('#txt_cantidad').val('1');
                    $('#txt_valor_unitario').val(info.Precio);
                    $('#txt_valor_total').html(info.Precio);

                    //Activar cantidad
                    $('#txt_cantidad_dias').removeAttr('disabled');
                    $('#txt_cantidad').removeAttr('disabled');
                    $('#txt_valor_unitario').removeAttr('disabled');

                    // Activar botón agregar

                    $('#add_producto_cotizacion').slideDown();
                   
                    }else{

                        $('#txt_Nombre').html('-');
                        $('#txt_Descripcion').html('-');
                        $('#txt_cantidad_dias').val('0');
                        $('#txt_cantidad').val('0');
                        $('#txt_valor_unitario').html('0');
                        $('#txt_valor_total').html('0');

                    //Bloquear cantidad
                        $('#txt_cantidad_dias').attr('disabled','disabled');
                        $('#txt_cantidad').attr('disabled','disabled');
                        $('#txt_valor_unitario').attr('disabled','disabled');

                        //ocultar boton agregar
                        $('#add_producto_cotizacion').slideUp();
                    }
                }
            });
        }
    });

    //validaa cantidad dias
    $('#txt_cantidad_dias').keyup(function(e){
        e.preventDefault();

    });

    //Validar cantidad producto y precio total

    $('#txt_cantidad').keyup(function(e){
        e.preventDefault();
        
        var precio_total = $(this).val() * $('#txt_valor_unitario').val() * $('#txt_cantidad_dias').val();
        $('#txt_valor_total').html(precio_total);

        //oculta el boton agregar si la cantidad es -1
        if($(this).val() < 1 || isNaN($(this).val())){
            $('#add_producto_cotizacion').slideUp();
        }else{
            $('#add_producto_cotizacion').slideDown();
        }
    });

    $('#txt_valor_unitario').keyup(function(e){
        e.preventDefault();

        var precio_total = $(this).val() * $('#txt_cantidad').val() * $('#txt_cantidad_dias').val();

        $('#txt_valor_total').val(precio_total);
    });

    // Agregar producto al detalle

    $('#add_producto_cotizacion').click(function(e){
        e.preventDefault();

        if($('#txt_cantidad_dias').val() > 0 && $('#txt_cantidad').val() > 0){

            var codProducto = $('#txt_cod_producto').val();
            var cantidadDias = $('#txt_cantidad_dias').val();
            var cantidadProducto = $('#txt_cantidad').val();
            var precioUni= $('#txt_valor_unitario').val();
            var action = 'add_producto_cotizacion';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                asnyc: true,   
                data: {action:action,producto:codProducto, cantidadDias:cantidadDias, cantidadProducto:cantidadProducto,precioUni:precioUni},

                success: function(response)
                {
                    console.log(response);
                    if(response != 'error'){

                        var info = JSON.parse(response);
                        
                        $('#detalle_cotizacion').html(info.detalle);
                        $('#detalles_totales').html(info.totales);

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

                        $('add_producto_cotizacion').slideUp();

                    }else{
                        console.log('no data');
                    }
                    verProcesar();
                },
                error: function(error){
                }
            });   
        }
    });

    // anular cotización 

    $('#btn_anular_cotizacion').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_cotizacion tr').length;
        if(rows > 0){

            var action = 'anularCotizacion';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action},

                success: function(response)
                {
                    if(response != 'error')
                    {
                        location.reload();
                    }
                },
                error: function(error){
                }
            });
        }
    });

    // Procesar cotizacion

    $('#btn_procesar_cotizacion').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_cotizacion tr').length;
        if(rows > 0){

            
            var id_cliente = $('#id_cliente').val();
            var fecha_evento = $('#fecha_evento').val();
            var ciudad_evento = $('#ciudad_evento').val();
            var lugar_evento = $('#lugar_evento').val();
            var tipo_servicio = $('#tipo_servicio').val();
            var action = 'procesarCotizacion';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action, id_cliente:id_cliente, fecha_evento:fecha_evento, ciudad_evento:ciudad_evento, lugar_evento:lugar_evento,
                        tipo_servicio:tipo_servicio},
                
                success: function(response)
                {
                    console.log(response);

                   if(response != 'error')
                    {
                        var info = JSON.parse(response);
                        //console.log(info);
                        generarExcel(info.id_cliente, info.id_cotizacion);
                        
                        location.reload();

                    }else{
                        console.log('no data');
                    }
                },
                error: function(error){
                }
            });
        }
    });

    // Ver cotizacion

    $('.ver_cotizacion').click(function(e){
        e.preventDefault();

            var id_cliente = $(this).attr('cl');
            var id_cotizacion = $(this).attr('c');

            generarExcel(id_cliente, id_cotizacion);
    });

    // buscar producto editar
    $('#cod_producto_editar').keyup(function(e){
        e.preventDefault();

        var producto = $(this).val();
        var action = 'infoProductoEditar';

        if(producto != ''){
            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action,producto:producto},

                success: function(response)
                {
                    if(response != 'error'){

                        var info = JSON.parse(response);
    
                        $('#Nombre').html(info.Nombre_producto);
                        $('#Descripcion').html(info.Descripcion);
                        $('#cantidad_dias_editar').val('1');
                        $('#cantidad_producto_editar').val('1');
                        $('#txt_valor_unitario').val(info.Precio);
                        $('#txt_valor_total').html(info.Precio);
    
                        //Activar cantidad
                        $('#cantidad_dias_editar').removeAttr('disabled');
                        $('#cantidad_producto_editar').removeAttr('disabled');
                        $('#txt_valor_unitario').removeAttr('disabled');
    
                        // Activar botón agregar
    
                        $('#add_producto_editar').slideDown();
                       
                        }else{
    
                            $('#Nombre').html('-');
                            $('#Descripcion').html('-');
                            $('#cantidad_dias_editar').val('0');
                            $('#cantidad_producto_editar').val('0');
                            $('#txt_valor_unitario').html('0');
                            $('#txt_valor_total').html('0');
    
                        //Bloquear cantidad
                            $('#cantidad_dias_editar').attr('disabled','disabled');
                            $('#cantidad_producto_editar').attr('disabled','disabled');
                            $('#txt_valor_unitario').attr('disabled','disabled');
    
                            //ocultar boton agregar
                            $('#add_producto_editar').slideUp();
                        }
                }
            });
        }  
    });

    //Validar cantidad producto y precio total editar

    $('#cantidad_producto_editar').keyup(function(e){
        e.preventDefault();
        
        var precio_total = $(this).val() * $('#txt_valor_unitario').val() * $('#cantidad_dias_editar').val();
        $('#txt_valor_total').html(precio_total);

        //oculta el boton agregar si la cantidad es -1
        if($(this).val() < 1 || isNaN($(this).val())){
            $('#add_producto_editar').slideUp();
        }else{
            $('#add_producto_editar').slideDown();
        }
    });

    $('#txt_valor_unitario').keyup(function(e){
        e.preventDefault();

        var precio_total = $(this).val() * $('#txt_valor_unitario').val() * $('#cantidad_dias_editar').val();

        $('#txt_valor_total').val(precio_total);
    });

    // Agregar producto editar cotizacion

    $('#add_producto_editar').click(function(e){
        e.preventDefault();
        
        if($('#cantidad_dias_editar').val() > 0 && $('#cantidad_producto_editar').val() > 0){

            var codProducto = $('#cod_producto_editar').val();
            var cantidadDias = $('#cantidad_dias_editar').val();
            var cantidadProducto = $('#cantidad_producto_editar').val();
            var precioUni= $('#txt_valor_unitario').val();
            var action = 'add_producto_editar';
            var id_cotizacion = $(this).attr('cotizacion');

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                asnyc: true,   
                data: {action:action,producto:codProducto, cantidadDias:cantidadDias, 
                    cantidadProducto:cantidadProducto,precioUni:precioUni, id_cotizacion:id_cotizacion},

                success: function(response)
                {
                    console.log(response);
                    location.reload();
                }
            });   
        }
    });

    // Procesar cotizacion editar

    $('#procesar_editar').click(function(e){
        e.preventDefault();

        var rows = $('#detalle_cotizacion_editar tr').length;
        if(rows > 0){

            var id_cliente = $(this).attr('cliente');
            var cotizacion = $(this).attr('cotizacion');
            var fecha_evento = $('#fecha_evento').val();
            var ciudad_evento = $('#ciudad_evento').val();
            var lugar_evento = $('#lugar_evento').val();
            var action = 'procesar_editar';

            $.ajax({
                url: 'ajax.php',
                type: "POST",
                async: true,
                data: {action:action, id_cliente:id_cliente, cotizacion:cotizacion, fecha_evento:fecha_evento, ciudad_evento:ciudad_evento, lugar_evento:lugar_evento,},
                
                success: function(response)
                {
                    if(response != 'error'){
                        var info = JSON.parse(response);
                    
                        generarExcel(info.id_cliente, info.id_cotizacion);
                        
                        location.reload();
                    }else{
                        console.log('no data');
                    }
                    
                },
                error: function(error){
                }
            });
        }
    });
          
});    //Fin ready

function generarExcel(cliente, cotizacion){
    var ancho = 1000;
    var alto = 800;

    var x = parseInt((window.screen.width/2) - (ancho/2));
    var y = parseInt((window.screen.height/2) - (alto/2));

    $url ='Cotizacion_plantilla.php?cl='+cliente+'&c='+cotizacion;
    window.open($url, "cotizacion", "left="+x+", top"+y+", height="+alto+", width="+ancho+", scrollbar=si, location=no, resizable=si, menubar=no");

}

// funcion envio de id_cotizacion

function add_producto_editar(id_cotizacion){
    
    var action = 'add_producto_editar';
    var id_cotizacion = id_cotizacion;

}

 //Eliminar producto cotización temporal
function del_product_detalle(cod_cotizacion_temp){

    var action = 'del_product_detalle';
    var id_detalle = cod_cotizacion_temp;

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        asnyc: true,   
        data: {action:action,id_detalle:id_detalle},

        success: function(response)
        {
            
            if(response != 'error'){
                
                var info = JSON.parse(response);

                $('#detalle_cotizacion').html(info.detalle);
                $('#detalles_totales').html(info.totales);
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

                $('add_producto_cotizacion').slideUp();

            }else{
                $('#detalle_cotizacion').html('');
                $('#detalles_totales').html('');
            }
        verProcesar();
        },
        error: function(error){
        }
    }); 
}

// ocultar boton procesar

function verProcesar(){

    if($('#detalle_cotizacion tr').length > 0){

        $('#btn_aprobar_cotizacion').show();
    }else{
        $('#btn_aprobar_cotizacion').hide();
    }
}

// ver detalle de cotizacion

function verDetalle(usuario){

    var action = 'verDetalles';
    var user = usuario;

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        asnyc: true,   
        data: {action:action,user:user},

        success: function(response)
        {
            
            if(response != 'error'){

                var info = JSON.parse(response);
                
                $('#detalle_cotizacion').html(info.detalle);
                $('#detalles_totales').html(info.totales);
                
            }else{
                console.log('no data');
            } 
        },
        error: function(error){
        }
    }); 
}


function eliminar_detalle_editar(id_coti_detalle){

    var action = 'eliminar_detalle_editar';
    var id_detalle = id_coti_detalle;

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        asnyc: true,   
        data: {action:action,id_detalle:id_detalle},

        success: function(response)
        {
            console.log(response);
            location.reload();
        },
        error: function(error){
        }
    }); 
} 