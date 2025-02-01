<?php
/**
 * Single Product template functions
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'custom_single_product_layout' ) ) {
    /**
     * Custom Single Product Layout with Two Columns
     */
    function custom_single_product_layout() {
        echo '<div class="custom-product-layout">';

        // Left column: Gallery
        echo '<div class="product-column-left">';
        do_action( 'woocommerce_before_single_product_summary' );
        echo '</div>';

        // Right column: Product Information
        echo '<div class="product-column-right">';
        do_action( 'woocommerce_single_product_summary' );
        echo '</div>';

        echo '</div>';
    }
}

// Remove default hooks for gallery and summary
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

// Add custom layout
add_action( 'woocommerce_before_single_product', 'custom_single_product_layout' );
