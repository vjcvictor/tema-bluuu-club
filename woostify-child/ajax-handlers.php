<?php

add_action( 'wp_ajax_woostify_child_get_product_data', 'woostify_child_get_product_data_callback' );
add_action( 'wp_ajax_nopriv_woostify_child_get_product_data', 'woostify_child_get_product_data_callback' );

function woostify_child_get_product_data_callback() {
  $product_id = $_POST['product_id'];
  error_log( "ID del producto: " . $product_id ); // Imprimir el ID del producto en el log de errores

  $product = wc_get_product( $product_id );

  if ( $product ) {
    wp_send_json_success( array(
      'name'  => $product->get_name(),
      'price' => $product->get_price()
    ) );
  } else {
    wp_send_json_error();
  }
}

add_action( 'wp_ajax_woostify_child_get_checkout_form', 'woostify_child_get_checkout_form_callback' );
add_action( 'wp_ajax_nopriv_woostify_child_get_checkout_form', 'woostify_child_get_checkout_form_callback' );

function woostify_child_get_checkout_form_callback() {
  $product_id = $_POST['product_id'];
  // Aquí puedes generar el formulario de pago de WooCommerce.
  // Puedes usar `woocommerce_checkout()` o construirlo manualmente.
  // Asegúrate de incluir los campos necesarios para el producto y la cantidad.

  // Ejemplo con `woocommerce_checkout()` (puede requerir adaptaciones):
  ob_start();

  // Asegúrate de que el carrito tenga el producto correcto con la cantidad deseada
  WC()->cart->empty_cart(); 
  WC()->cart->add_to_cart( $product_id, 1 ); // Agrega 1 unidad del producto al carrito

  woocommerce_checkout();
  $checkout_form = ob_get_clean();

  echo $checkout_form;
  wp_die();
}