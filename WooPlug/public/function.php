<?php
// Fonction pour mettre à jour la quantité du produit dans le panier via une requête AJAX
function update_cart_quantity() {
    if (isset($_POST['cart_item_key']) && isset($_POST['new_quantity'])) {
        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        $new_quantity = intval($_POST['new_quantity']);

        if ($new_quantity > 0) {
            WC()->cart->set_quantity($cart_item_key, $new_quantity);
            WC()->cart->calculate_totals();
            WC_AJAX::get_refreshed_fragments();
            wp_send_json_success();
        } else {
            wp_send_json_error('La quantité doit être supérieure à zéro.');
        }
    }
}
/**
 * @snippet       Automatically Update Cart on Quantity Change - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 7
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_action( 'wp_footer', 'bbloomer_cart_refresh_update_qty' );

function bbloomer_cart_refresh_update_qty() {
    if ( is_cart() || ( is_cart() && is_checkout() ) ) {
        wc_enqueue_js( "
         $('div.woocommerce').on('click', 'input.qty', function(){
            $('[name=\'update_cart\']').trigger('click');
         });
      " );
    }
}

add_action('wp_ajax_update_cart_quantity', 'update_cart_quantity');
add_action('wp_ajax_nopriv_update_cart_quantity', 'update_cart_quantity');

