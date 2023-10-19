<?php

class sandbox_Public_Shipping
{
    /**
     * @param array $rates
     * @param mixed $package
     * 
     * @return array
     * 
     * If minimum amount to get free shipping is reached by cart total
     * Set free shipping to shipping with cost
     */
    public function custom_shipping_costs( $rates, $package ) {
        $free_shipping_cost = 0;
        $cart_total = WC()->cart->cart_contents_total;

        foreach( $rates as $rate_key => $rate ){
            // Excluding free shipping methods
            if( $rate->method_id != 'free_shipping'){
                $rate_settings = get_option(
                    'woocommerce_' . $rate->method_id . '_' . $rate->instance_id . '_settings');
                if(
                    isset($rate_settings['sandbox_free_shipping_min_amount']) &&
                    $cart_total >= $rate_settings['sandbox_free_shipping_min_amount']
                ){
                    // Set rate cost to free if cart total is enough
                    $rates[$rate_key]->cost = $free_shipping_cost;
                }
            }
        }
        return $rates;
   }
}
