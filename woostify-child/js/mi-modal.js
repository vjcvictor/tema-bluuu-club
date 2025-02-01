jQuery(document).ready(function($) {
    var precioBase = 0;
    var productId = 0;

    // Cuando se haga clic en el botón "Comprar Ahora"
    $('.comprar-ahora-btn').on('click', function() {
        productId = $(this).data('product-id');
        precioBase = parseFloat($(this).data('precio'));
        
        // Actualizar el texto de cada opción con el precio total
        $('#seleccion-cantidad .btn-group label').each(function() {
            var cantidad = $(this).find('input[name="cantidad"]').val();
            var precioTotal = (precioBase * cantidad).toFixed(2);
            $(this).find('.precio-option').text('$' + precioTotal);
        });
    });


    // $('#modalCheckout').on('shown.bs.modal', function () {
    //     console.log("Modal mostrado, disparando update_checkout");
    //     $(document.body).trigger('update_checkout');
    // });

    // Al pulsar "Continuar con el pago"
    $('#continuar-checkout').on('click', function(e) {
        e.preventDefault();
        var cantidad = $('input[name="cantidad"]:checked').val();

        // Llamada AJAX para actualizar el carrito
        $.ajax({
            url: mi_ajax_obj.ajax_url,
            method: 'POST',
            data: {
                action: 'actualizar_carrito',
                product_id: productId,
                cantidad: cantidad
            },
            success: function(response) {
                if(response.success) {
                    // Ocultar la sección de selección de cantidad
                    $('#seleccion-cantidad').hide();
                    // Mostrar el formulario de checkout
                    // Cargar el checkout vía AJAX
                    $.ajax({
                        url: mi_ajax_obj.ajax_url,
                        method: 'POST',
                        data: { action: 'cargar_checkout_form' },
                        success: function(data) {
                            $('#checkout-formulario').html(data).show();
                            // Disparar evento para que WooCommerce actualice el checkout
                            $(document.body).trigger('updated_checkout');
                            // Re-inicializar el objeto de checkout (si existe)
                            if ( typeof wc_checkout_form !== 'undefined' && typeof wc_checkout_form.init === 'function' ) {
                                wc_checkout_form.init();
                            }
                            // Alternativamente, reinicializar el plugin del formulario de checkout
                            if ( $('form.checkout').length > 0 && $.fn.wc_checkout_form ) {
                                $('form.checkout').wc_checkout_form();
                            }
                        },
                        error: function() {
                            alert('Error al cargar el formulario de checkout.');
                        }
                    });
                } else {
                    alert('Error al actualizar el carrito: ' + response.data.mensaje);
                }
            },
            error: function() {
                alert('Error en la petición AJAX.');
            }
        });
    });
});


