<?php

// Fonction pour supprimer le formulaire de coupon de réduction
function supprimer_formulaire_coupon() {
    remove_action('woocommerce_after_cart_table', 'woocommerce_cart_coupon');
}
add_action('init', 'supprimer_formulaire_coupon', 999);
