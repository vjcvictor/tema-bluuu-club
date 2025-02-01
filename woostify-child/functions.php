<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}


add_filter( 'woocommerce_package_rates', 'condiciones_de_envio_personalizadas', 10, 2 );

function condiciones_de_envio_personalizadas( $rates, $package ) {
    $limite = 250000; // Establece el monto mínimo para el cambio de condiciones de envío
    $total_del_carrito = WC()->cart->subtotal;

    // Elimina la tarifa plana si el carrito supera el limite
    if ( $total_del_carrito >= $limite ) {
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'flat_rate' === $rate->method_id ) {
                unset( $rates[ $rate_id ] );
            }
        }
    }

    // Elimina el envío gratis si el carrito no alcanza el limite
    if ( $total_del_carrito < $limite ) {
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'free_shipping' === $rate->method_id ) {
                unset( $rates[ $rate_id ] );
            }
        }
    }

    return $rates;
}

/*
add_action( 'pre_get_posts', 'ordenar_productos_woocommerce_aleatoriamente', 31 );
function ordenar_productos_woocommerce_aleatoriamente( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        $query->set( 'orderby', 'rand' ); // Ordena los productos aleatoriamente
        $query->set( 'order', '' ); // No es necesario especificar un orden
        $query->set( 'posts_per_page', 12 ); // Puedes ajustar esto al número de productos que quieras mostrar por página
    }
}
*/

// Función para agregar el Pixel de Facebook
function agregar_pixel_facebook() {
?>
<meta name="google-site-verification" content="5m4mnnBTkjlib_CZnW656lPMAVXA6Xpu77-iU9iNOQY" />
<script src="https://www.google.com/recaptcha/enterprise.js?render=6LeJW7wpAAAAAHUTNAs0Nhb9TxToDCyZ8gQbxicm"></script>

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '761143636042274');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=761143636042274&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->


<?php
}
//add_action( 'wp_head', 'agregar_pixel_facebook' );

// Función que inserta el código antes del cierre de </body>
function insertar_codigo_footer() {
    ?>
    <!-- Aquí va tu código personalizado -->


    <?php
}
// Hook para ejecutar la función en el footer
add_action('wp_footer', 'insertar_codigo_footer');
/*
function optimizar_carga_css() {
    if (is_front_page()) {
		error_log('Optimizando carga CSS para la página de inicio.');
        wp_dequeue_style('wc_mercadopago_checkout_components-css');
        wp_dequeue_style('elementor-icons-css');
        wp_dequeue_style('elementor-frontend-css');
        wp_dequeue_style('swiper-css');
        wp_dequeue_style('elementor-post-1022-css');
        wp_dequeue_style('sbistyles-css');
        wp_dequeue_style('elementor-post-13-css');
        wp_dequeue_style('google-fonts-1-css');
    }
}
//('wp_enqueue_scripts', 'optimizar_carga_css', 100);
add_action('wp_enqueue_scripts', 'optimizar_carga_css', PHP_INT_MAX);

function custom_dequeue_photoswipe_on_shop_page() {
    if (is_shop()) { // Esto verifica si el usuario está viendo la página principal de la tienda
        wp_dequeue_script('photoswipe');
        wp_dequeue_script('photoswipe-ui-default');
        wp_dequeue_script('photoswipe-init');
    }
}
add_action('wp_enqueue_scripts', 'custom_dequeue_photoswipe_on_shop_page', 100);

*/
/*
add_action( 'woocommerce_before_thankyou', 'wp_add_whatsapp_button', 10);
function wp_add_whatsapp_button( $order_id ) {
    if ( ! $order_id )
        return;

    $order = wc_get_order( $order_id );

    // Extraemos y ordenamos los componentes de la dirección de facturación
    $billing_address_1 = $order->get_billing_address_1();
    $billing_address_2 = $order->get_billing_address_2();
    $billing_city = $order->get_billing_city();
    $billing_state = $order->get_billing_state();
    $billing_postcode = $order->get_billing_postcode();
    $custom_field_10 = $order->get_meta('billing_wooccm10');
    $custom_field_11 = $order->get_meta('billing_wooccm11');
    $order_notes = $order->get_customer_note();
    $payment_method = $order->get_payment_method_title();

    // Formatear los datos de facturación con verificación de campos vacíos
    $billing_data = sprintf(
        "Hola DAVIMA acabo de realizar un pedido y quiero confirmarlo con los siguientes datos:\n*Pedido #%d*\n*Cliente:* %s %s\n*Email:* %s\n*Teléfono:* %s\n*Dirección:* %s\n",
        $order_id,
        $order->get_billing_first_name(),
        $order->get_billing_last_name(),
        $order->get_billing_email(),
        $order->get_billing_phone(),
        $billing_address_1
    );

    // Campos adicionales del formulario de facturación
    if (!empty($billing_address_2)) {
        $billing_data .= "*Apto/Casa:* $billing_address_2\n";
    }
    if (!empty($billing_city)) {
        $billing_data .= "*Municipio:* $billing_city\n";
    }
    if (!empty($billing_state)) {
        $billing_data .= "*Departamento:* $billing_state\n";
    }
    if (!empty($billing_postcode)) {
        $billing_data .= "*Código Postal:* $billing_postcode\n";
    }
    if (!empty($custom_field_10)) {
        $billing_data .= "*Campo Personalizado 10:* $custom_field_10\n";
    }
    if (!empty($custom_field_11)) {
        $billing_data .= "*Campo Personalizado 11:* $custom_field_11\n";
    }
    if (!empty($order_notes)) {
        $billing_data .= "*Notas del Pedido:* $order_notes\n";
    }
    if (!empty($payment_method)) {
        $billing_data .= "*Método de Pago:* $payment_method\n";
    }

    // Productos y total
    $items_data = "*Productos:*\n";
    foreach ($order->get_items() as $item_id => $item) {
        $product_name = $item->get_name();
        $quantity = $item->get_quantity();
        $price = wc_price($item->get_total() / $quantity);  
        $price = str_replace('&#36;', '$', strip_tags($price));  
        $items_data .= sprintf("%s (Cantidad: %d, Precio: %s)\n", $product_name, $quantity, $price);
    }

    $total_price = wc_price($order->get_total());  
    $total_price = str_replace('&#36;', '$', strip_tags($total_price));  
    $total_data = sprintf("*Total del Pedido:* %s\n", $total_price);

    $order_data = urlencode($billing_data . $items_data . $total_data);

    $phone_number = '573052479836';  // Cambia esto por tu número de WhatsApp real
	
    $whatsapp_button = '<div style="text-align: center; margin: 20px; "><a href="#" id="sendToWhatsApp" class="button alt" data-order="' . $order_data . '" style="margin-top: 20px;padding: 10px 14px;font-size: 0.85rem;">Confirmar mi pedido por WhatsApp</a></div>';
	

    echo $whatsapp_button;
}





add_action( 'wp_footer', 'wp_whatsapp_send_script' );
function wp_whatsapp_send_script() {
    if ( ! is_wc_endpoint_url( 'order-received' ) ) return;
    ?>
    <script type="text/javascript">
        document.getElementById('sendToWhatsApp').addEventListener('click', function(event) {
            event.preventDefault();
            var orderInfo = this.getAttribute('data-order');
            var whatsappUrl = 'https://wa.me/573052479836?text=' + orderInfo; // Asegúrate de cambiar el número.
            window.open(whatsappUrl, '_blank');
        });
    </script>
    <?php
}
*/


/*
// Función para ajustar tarifas de envío según la ciudad
function ajustar_tarifa_envio_cali( $rates, $package ) {
    // Define la ciudad objetivo y la tarifa específica
    $ciudad_objetivo = 'Cali';
    $tarifa_especifica = 10000; // Cambia esto por la tarifa que deseas aplicar

    // Chequea si la dirección de envío es Cali
    if ( isset( $package['destination']['city'] ) && $package['destination']['city'] == $ciudad_objetivo ) {
        foreach ( $rates as $rate_key => $rate ) {
            // Aplica la tarifa específica para envíos a Cali
            if ( 'flat_rate' === $rate->method_id ) {
                $rates[ $rate_key ]->cost = $tarifa_especifica;
            }
        }
    }

    return $rates;
}

add_filter( 'woocommerce_package_rates', 'ajustar_tarifa_envio_cali', 10, 2 );

*/




// Función para ajustar tarifas de envío según la ciudad y clase de envío
function ajustar_tarifa_envio_cali( $rates, $package ) {
    // Define la ciudad objetivo y la tarifa específica
    $ciudad_objetivo = 'Cali';
    $tarifa_especifica = 10000; // Cambia esto por la tarifa que deseas aplicar
    $clase_envio_gratis = 'envio-gratis'; // Clase de envío específica

    // Chequea si la dirección de envío es Cali
    if ( isset( $package['destination']['city'] ) && $package['destination']['city'] == $ciudad_objetivo ) {
        foreach ( $rates as $rate_key => $rate ) {
            // Verifica si la clase de envío de los productos en el paquete incluye la clase de envío gratis
            $apply_free_shipping = false;
            foreach ( $package['contents'] as $item_id => $values ) {
                $product = wc_get_product( $values['product_id'] );
                if ( has_term( $clase_envio_gratis, 'product_shipping_class', $product->get_id() ) ) {
                    $apply_free_shipping = true;
                    break;
                }
            }

            // Aplica la tarifa específica para envíos a Cali solo si no tiene la clase de envío gratis
            if ( 'flat_rate' === $rate->method_id && ! $apply_free_shipping ) {
                $rates[ $rate_key ]->cost = $tarifa_especifica;
            }

            // Aplica tarifa 0 para productos con la clase de envío gratis
            if ( 'flat_rate' === $rate->method_id && $apply_free_shipping ) {
                $rates[ $rate_key ]->cost = 0;
            }
        }
    }

    return $rates;
}

add_filter( 'woocommerce_package_rates', 'ajustar_tarifa_envio_cali', 10, 2 );

// Agregar el botón "Comprar Ahora" en la página de producto individual
add_action('woocommerce_after_add_to_cart_button', 'agregar_boton_comprar_ahora');

function agregar_boton_comprar_ahora() {
    global $product;
    if ( ! $product->is_in_stock() ) {
        return;
    }
    echo '<button id="comprar-ahora-boton" type="button" class="button comprar-ahora" alt" data-product-id="' . esc_attr( $product->get_id() ) . '"><svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" width="17" height="17" viewBox="0 0 17 17" id="svg50" sodipodi:docname="shopping-cart-2.svg" inkscape:version="1.0.2-2 (e86c870879, 2021-01-15)">
  <metadata id="metadata56">
    <rdf:rdf>
      <cc:work rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"></dc:type>
        <dc:title></dc:title>
      </cc:work>
    </rdf:rdf>
  </metadata>
  <defs id="defs54"></defs>
  <sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="2400" inkscape:window-height="1271" id="namedview52" showgrid="false" inkscape:zoom="48.823529" inkscape:cx="8.5" inkscape:cy="8.5" inkscape:window-x="2391" inkscape:window-y="-9" inkscape:window-maximized="1" inkscape:current-layer="svg50"></sodipodi:namedview>
  <g id="g46" transform="matrix(-1,0,0,1,16.926,0)"></g>
  <path d="m 14.176,12.5 c 0.965,0 1.75,0.785 1.75,1.75 0,0.965 -0.785,1.75 -1.75,1.75 -0.965,0 -1.75,-0.785 -1.75,-1.75 0,-0.965 0.785,-1.75 1.75,-1.75 z m 0,2.5 c 0.414,0 0.75,-0.337 0.75,-0.75 0,-0.413 -0.336,-0.75 -0.75,-0.75 -0.414,0 -0.75,0.337 -0.75,0.75 0,0.413 0.336,0.75 0.75,0.75 z m -8.5,-2.5 c 0.965,0 1.75,0.785 1.75,1.75 0,0.965 -0.785,1.75 -1.75,1.75 -0.965,0 -1.75,-0.785 -1.75,-1.75 0,-0.965 0.785,-1.75 1.75,-1.75 z m 0,2.5 c 0.414,0 0.75,-0.337 0.75,-0.75 0,-0.413 -0.336,-0.75 -0.75,-0.75 -0.414,0 -0.75,0.337 -0.75,0.75 0,0.413 0.336,0.75 0.75,0.75 z M 3.555,2 3.857,4 H 17 l -1.118,8.036 H 3.969 L 2.931,4.573 2.695,3 H -0.074 V 2 Z M 4,5 4.139,6 H 15.713 L 15.852,5 Z M 15.012,11.036 15.573,7 H 4.278 l 0.561,4.036 z" fill="#000000" id="path48"></path>
</svg>Pedir Contra Entrega</button>';
	?>
     <script type="text/javascript">
      //document.getElementById('comprar-ahora-boton').addEventListener('click', function() {
      //    const quantity = document.querySelector('form.cart input[name="quantity"]').value || 1;
      //    const url = "<?php //echo esc_url( add_query_arg(array('comprar_ahora' => 'true'), home_url()) ); ?>";
      //    const finalUrl = url + '&product_id=<?php //echo $product->get_id(); ?>&quantity=' + quantity;
      //    window.location.href = finalUrl;
      //});
    </script>
    <?php
}



// Agregar el botón "Comprar Ahora" en la página de producto individual
add_action('woocommerce_after_add_to_cart_button', 'agregar_boton_comprar_ahora_movil');

function agregar_boton_comprar_ahora_movil() {
    global $product;
    if ( ! $product->is_in_stock() ) {
        return;
    }
    echo '<button id="comprar-ahora-boton-movil" type="button" class="button comprar-ahora"><svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" width="17" height="17" viewBox="0 0 17 17" id="svg50" sodipodi:docname="shopping-cart-2.svg" inkscape:version="1.0.2-2 (e86c870879, 2021-01-15)">
  <metadata id="metadata56">
    <rdf:rdf>
      <cc:work rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"></dc:type>
        <dc:title></dc:title>
      </cc:work>
    </rdf:rdf>
  </metadata>
  <defs id="defs54"></defs>
  <sodipodi:namedview pagecolor="#ffffff" bordercolor="#666666" borderopacity="1" objecttolerance="10" gridtolerance="10" guidetolerance="10" inkscape:pageopacity="0" inkscape:pageshadow="2" inkscape:window-width="2400" inkscape:window-height="1271" id="namedview52" showgrid="false" inkscape:zoom="48.823529" inkscape:cx="8.5" inkscape:cy="8.5" inkscape:window-x="2391" inkscape:window-y="-9" inkscape:window-maximized="1" inkscape:current-layer="svg50"></sodipodi:namedview>
  <g id="g46" transform="matrix(-1,0,0,1,16.926,0)"></g>
  <path d="m 14.176,12.5 c 0.965,0 1.75,0.785 1.75,1.75 0,0.965 -0.785,1.75 -1.75,1.75 -0.965,0 -1.75,-0.785 -1.75,-1.75 0,-0.965 0.785,-1.75 1.75,-1.75 z m 0,2.5 c 0.414,0 0.75,-0.337 0.75,-0.75 0,-0.413 -0.336,-0.75 -0.75,-0.75 -0.414,0 -0.75,0.337 -0.75,0.75 0,0.413 0.336,0.75 0.75,0.75 z m -8.5,-2.5 c 0.965,0 1.75,0.785 1.75,1.75 0,0.965 -0.785,1.75 -1.75,1.75 -0.965,0 -1.75,-0.785 -1.75,-1.75 0,-0.965 0.785,-1.75 1.75,-1.75 z m 0,2.5 c 0.414,0 0.75,-0.337 0.75,-0.75 0,-0.413 -0.336,-0.75 -0.75,-0.75 -0.414,0 -0.75,0.337 -0.75,0.75 0,0.413 0.336,0.75 0.75,0.75 z M 3.555,2 3.857,4 H 17 l -1.118,8.036 H 3.969 L 2.931,4.573 2.695,3 H -0.074 V 2 Z M 4,5 4.139,6 H 15.713 L 15.852,5 Z M 15.012,11.036 15.573,7 H 4.278 l 0.561,4.036 z" fill="#000000" id="path48"></path>
</svg>Pedir Contra Entrega</button>';
	?>
     <script type="text/javascript">
        // document.getElementById('comprar-ahora-boton-movil').addEventListener('click', function() {
            // const quantity = document.querySelector('form.cart input[name="quantity"]').value || 1;
            // const url = "<?php //echo esc_url( add_query_arg(array('comprar_ahora' => 'true'), home_url()) ); ?>";
            // const finalUrl = url + '&product_id=<?php //echo $product->get_id(); ?>&quantity=' + quantity;
            // window.location.href = finalUrl;
        // });
    </script>
    <?php
}

// Redirigir a la página de checkout si se hace clic en el botón "Comprar Ahora"
//add_action('template_redirect', 'procesar_boton_comprar_ahora');

function procesar_boton_comprar_ahora() {
    if ( isset($_GET['comprar_ahora']) && 'true' === $_GET['comprar_ahora'] ) {
        if ( isset($_GET['product_id']) && isset($_GET['quantity']) ) {
            global $woocommerce;

            $product_id = intval($_GET['product_id']);
            $quantity = intval($_GET['quantity']);

            // Vaciar el carrito actual
            $woocommerce->cart->empty_cart();

            // Añadir el producto al carrito con la cantidad especificada
            $woocommerce->cart->add_to_cart($product_id, $quantity);

            // Redirigir al checkout
            wp_safe_redirect(wc_get_checkout_url());
            exit;
        }
    }
}

// Aumentar el valor del producto en un 9% si se selecciona ADDI o un 3.3% si se selecciona MercadoPago
add_action('woocommerce_cart_calculate_fees', 'aumentar_precio_con_metodos_especiales', 20, 1);
function aumentar_precio_con_metodos_especiales($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $payment_method = WC()->session->chosen_payment_method;

    if ($payment_method == 'addi') {
        $porcentaje_incremento = 0.09; // 9% para ADDI
    } elseif ($payment_method == 'woo-mercado-pago-pse') {
		$payment_method = "PSE";
        $porcentaje_incremento = 0.05; // 3.3% para MercadoPago
    } elseif ($payment_method == 'woo-mercado-pago-basic') {
		$payment_method = "Mercado Pago";
        $porcentaje_incremento = 0.05; // 3.3% para MercadoPago
    } else {
        return;
    }

    $monto_total = $cart->get_cart_contents_total();
    $incremento = $monto_total * $porcentaje_incremento;

    $cart->add_fee(__('Recargo por pago con ' . ucfirst($payment_method), 'tu-text-domain'), $incremento);
}

// Mostrar mensaje informativo sobre el recargo en la página de pago si el método de pago seleccionado es ADDI o MercadoPago
add_action('woocommerce_review_order_before_payment', 'mostrar_mensaje_recargo_metodos_especiales');
function mostrar_mensaje_recargo_metodos_especiales() {
    $payment_method = WC()->session->chosen_payment_method;

    if ($payment_method == 'addi') {
        $mensaje = __('Si seleccionas ADDI como método de pago, se aplicará un recargo del 9%.', 'tu-text-domain');
    } elseif ($payment_method == 'woo-mercado-pago-pse') {
        $mensaje = __('Si seleccionas PSE como método de pago, se aplicará un recargo del 5%.', 'tu-text-domain');
    } elseif ($payment_method == 'woo-mercado-pago-basic') {
        $mensaje = __('Si seleccionas Mercado Pago como método de pago, se aplicará un recargo del 5%.', 'tu-text-domain');
    } else {
        return;
    }

    echo '<div class="woocommerce-info">' . $mensaje . '</div>';
}

// Actualizar la información del pago en tiempo real al cambiar de método
add_action('wp_footer', 'actualizar_mensaje_recargo_metodos_especiales');
function actualizar_mensaje_recargo_metodos_especiales() {
    if (is_checkout()) {
        ?>
        <script type="text/javascript">
            jQuery(function($) {
                $('form.checkout').on('change', 'input[name="payment_method"]', function() {
                    var metodo_pago = $(this).val();
                    var mensaje = '';

                    if (metodo_pago === 'addi') {
                        mensaje = '<?php _e('Si seleccionas ADDI como método de pago, se aplicará un recargo del 9%.', 'tu-text-domain'); ?>';
                    } else if (metodo_pago === 'woo-mercado-pago-basic') {
                        mensaje = '<?php _e('Si seleccionas MercadoPago como método de pago, se aplicará un recargo del 5%.', 'tu-text-domain'); ?>';
                    } else if (metodo_pago === 'woo-mercado-pago-pse') {
                        mensaje = '<?php _e('Si seleccionas PSE como método de pago, se aplicará un recargo del 5%.', 'tu-text-domain'); ?>';
                    }

                    $('.woocommerce-info').remove(); // Eliminar cualquier mensaje previo

                    if (mensaje) {
                        $('<div class="woocommerce-info">' + mensaje + '</div>').insertBefore('.woocommerce-checkout-payment');
                    }
                });
            });
        </script>
        <?php
    }
}

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );



add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_script( 'wc-additional-variation-images' ); // Galería adicional para variaciones
    wp_enqueue_script( 'zoom' ); // Zoom de la galería
    wp_enqueue_script( 'photoswipe' ); // Lightbox
});



function cargar_css_y_js_detalle_producto_woocommerce() {
    // Verifica si es una página singular de productos
    $timestamp = time();
    if (is_singular('product')) {
        // Encola tu archivo CSS
        wp_enqueue_style('detalle-producto-css', get_stylesheet_directory_uri() . '/css/detalle-producto.css', array(),  $timestamp);
        
        wp_enqueue_script('detalle-producto-js', get_stylesheet_directory_uri() . '/js/detalle-producto.js', array('jquery'), $timestamp, true);
    }
}
add_action('wp_enqueue_scripts', 'cargar_css_y_js_detalle_producto_woocommerce');





// Desactivar el stock predeterminado del tema padre.
add_action( 'init', 'desactivar_stock_tema_padre', 20 );

function desactivar_stock_tema_padre() {
    remove_filter( 'woocommerce_get_stock_html', 'woostify_modified_quantity_stock', 10, 2 );
}

// Personalizar el stock de WooCommerce.
add_action( 'woocommerce_single_product_summary', 'mostrar_stock_producto', 10 );

function mostrar_stock_producto() {
    global $product;

    if ( $product->is_in_stock() ) {
        $stock_quantity = $product->get_stock_quantity();
        echo '<p class="stock in-stock">' . sprintf( __( '<b>%s Und</b> Disponibles', 'woostify' ), $stock_quantity ) . '</p>';
    } else {
        echo '<p class="stock out-of-stock">Producto agotado</p>';
    }
}

// Reordenar elementos en la página de producto.
add_action( 'woocommerce_single_product_summary', 'reordenar_elementos_detalle_producto', 5 );

function reordenar_elementos_detalle_producto() {
    // Eliminar los elementos existentes.
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

    // Agregar los elementos en el nuevo orden.
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 ); // Título del producto.
    add_action( 'woocommerce_single_product_summary', 'mostrar_stock_producto', 10 ); // Stock del producto.
    add_action( 'woocommerce_single_product_summary', 'precio_con_oferta', 15 ); // Precio con "oferta".
}

// Mostrar precio con el rectángulo de "oferta".
function precio_con_oferta() {
    global $product;

    echo '<div class="precio-con-oferta">';
    echo $product->get_price_html(); // Precio del producto.
    echo '<span class="rectangulo-oferta">Oferta</span>';
    echo '</div>';
}

// Agregar la descripción corta debajo de los metadatos.
add_action( 'woocommerce_product_meta_end', 'custom_add_short_description' );

function custom_add_short_description() {
    global $post;

    if ( ! $post ) {
        return;
    }

    $product = wc_get_product( $post->ID );

    if ( $product && $product->get_short_description() ) {
        echo '<div class="custom-short-description">';
        echo wp_kses_post( $product->get_short_description() );
        echo '</div>';

        // Agregar comentarios personalizados con ACF si están disponibles.
        if ( function_exists( 'have_rows' ) && have_rows( 'comentarios', $post->ID ) ) {
            echo '<h3 class="ttcoment">Opinión del cliente</h3>';
            echo '<div class="custom-comments-section">';
            

            // Iterar sobre los comentarios.
            while ( have_rows( 'comentarios', $post->ID ) ) {
                the_row();

                // Obtener subcampos.
                $img = get_sub_field( 'img' );
                $nombre = get_sub_field( 'nombre' );
                $coment = get_sub_field( 'coment' );

                echo '<div class="custom-comment">';
                
                 // Mostrar imagen o inicial del nombre si no hay imagen.
        if ( $img ) {
            echo '<div class="custom-comment-img">';
            echo '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $nombre ) . '">';
            echo '</div>';
        } else {
            $inicial = strtoupper(mb_substr($nombre, 0, 1)); // Obtener la primera letra en mayúscula.
            echo '<div class="custom-comment-img-inicial">';
            echo '<span>' . esc_html( $inicial ) . '</span>';
            echo '</div>';
        }

        // Mostrar nombre y comentario.
        echo '<div class="custom-comment-content">';
        echo '<h4>' . esc_html( $nombre ) . '</h4>';
        echo '<p>' . esc_html( $coment ) . '</p>';
        echo '</div>';

                echo '</div>'; // Cierra custom-comment.
            }

            echo '</div>'; // Cierra custom-comments-section.
        }
    }
}


// Agregar título con estrellas y texto "4.9" justo debajo del título principal.
add_action( 'woocommerce_single_product_summary', 'mostrar_titulo_con_estrellas', 6 );

function mostrar_titulo_con_estrellas() {
    global $product;

    // Obtener el título del producto.
    $titulo_producto = $product->get_name();

    echo '<div class="titulo-con-estrellas">';
   

    // Mostrar 4 estrellas completas y 1 estrella a la mitad con el texto "4.9".
    echo '<div class="estrellas-y-rating">';
    echo '<span class="rating-text"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg> 4.9 </span>'; // Texto del rating.
    echo '<span class="estrellas">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFD700" class="bi bi-star-fill" viewBox="0 0 16 16">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
    </svg>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFD700" class="bi bi-star-fill" viewBox="0 0 16 16">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
    </svg>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFD700" class="bi bi-star-fill" viewBox="0 0 16 16">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
    </svg>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFD700" class="bi bi-star-fill" viewBox="0 0 16 16">
        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
    </svg>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#FFD700" class="bi bi-star-half" viewBox="0 0 16 16">
        <path d="M5.354 5.119 7.538.792A.52.52 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.54.54 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.5.5 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.6.6 0 0 1 .085-.302.51.51 0 0 1 .37-.245zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.56.56 0 0 1 .162-.505l2.907-2.77-4.052-.576a.53.53 0 0 1-.393-.288L8.001 2.223 8 2.226z"/>
    </svg>';
    echo '</span>';
    //echo '<p>5.0 ' . esc_html( $titulo_producto ) . '</p>'; // Mostrar el título.
    
    echo '</div>'; // Cierra estrellas-y-rating.

    echo '</div>'; // Cierra titulo-con-estrellas.
}



require_once( get_stylesheet_directory() . '/ajax-handlers.php' );