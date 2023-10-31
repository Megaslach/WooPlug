<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
$items_count = WC()->cart->cart_contents_count;
$submit_button_text = __('Secured payment', 'sandbox');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="woocommerce-checkout-review-order-table">
    <table class="shop_table sandbox-review-order-table">

        <tbody>
        <?php
        do_action( 'woocommerce_review_order_before_cart_contents' );?>


        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                <td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
            </tr>
        <?php endforeach; ?>

        <tr class="shipping-cost">
            <th><?php esc_html_e('Shipping', 'woocommerce'); ?></th>
            <td data-title="<?php esc_attr_e('Shipping', 'woocommerce'); ?>">
                <?php foreach( WC()->session->get('shipping_for_package_0')['rates'] as $method_id => $rate ){
                    if( WC()->session->get('chosen_shipping_methods')[0] == $method_id ){
                        $rate_label = $rate->label; // The shipping method label name
                        $rate_cost_excl_tax = floatval($rate->cost); // The cost excluding tax
                        // The taxes cost
                        $rate_taxes = 0;
                        foreach ($rate->taxes as $rate_tax)
                            $rate_taxes += floatval($rate_tax);
                        // The cost including tax
                        $rate_cost_incl_tax = $rate_cost_excl_tax + $rate_taxes;

                        echo '<p class="shipping-total">
                        
                        <span >' . wc_price($rate_cost_incl_tax) . '</span>
                    </p>';
                        break;
                    }
                } ?>
            </td>
        </tr>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <tr class="fee">
                <th><?php echo esc_html( $fee->name ); ?></th>
                <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <th><?php echo esc_html( $tax->label ); ?></th>
                        <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="tax-total">
                    <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
                    <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
        <tr class="order-items-count sandbox-text">
            <th>
                <?php echo sprintf(
                /* translators: %d is replace by number of items in cart */
                    _n('%d item', '%d items', $items_count, 'sandbox'),
                    $items_count
                ); ?>
            </th>
            <td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                <?php wc_cart_totals_subtotal_html(); ?>
            </td>
        </tr>

        <tr class="order-total">
            <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
            <td><?php wc_cart_totals_order_total_html(); ?></td>
        </tr>

        <?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>
        </tbody>
    </table>
    <div>
        <?php
        echo '<strong><p class="shipping-total">
            ' . $rate_label . '
           
        </p></strong>';
        ?>
    </div>

    <div class="info-after-recap">
        <p>
            <span>Blabla</span>
        </p>

        <p>
            <span>Blabla</span>
        </p>

        <p>
            <span>Blabla</span>
        </p>
    </div>



    <div class="step-checkout">
        <div class="wc-back-to-cart" data-display="customer_details_step">
            <a href="<?php echo wc_get_cart_url(); ?>" class="back-to-cart button button-transparent alt">
                <?php _e('Back to cart', 'sandbox'); ?>
            </a>
        </div>
        <div class="wc-proceed-to-payment checkout_steps" data-display="customer_details_step" >
            <a href="" class="payment-button button button-green alt" data-step="payment_step">
                <?php _e('Next step', 'sandbox'); ?>
            </a>
        </div>
        <div class="wc-previous-step checkout_steps" data-display="payment_step" style="display: none;">
            <a href="" class="previous-step button button-transparent alt" data-step="customer_details_step">
                <?php _e('Previous step', 'sandbox'); ?>
            </a>
        </div>
        <div class="wc-validate-payment" data-display="payment_step" style="display: none;">
            <button
                    type="submit"
                    class="validate-payment-button button button-green alt"
                    name="woocommerce_checkout_place_order"
                    id="place_order"
                    value="<?php esc_html_e($submit_button_text); ?>">
                <?php esc_html_e( $submit_button_text ) ?>
            </button>
        </div>
    </div>
</div>