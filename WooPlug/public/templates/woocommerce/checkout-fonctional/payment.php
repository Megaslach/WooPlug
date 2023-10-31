<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
$submit_button_text = __('Secured payment', 'sandbox'); 
?>
<div id="payment" class="woocommerce-checkout-payment" class="checkout_steps" data-display="payment_step" style="display: none;">

	<h3 class="sandbox-title"><?php esc_html_e( 'Payment method', 'sandbox' ); ?></h3>

	<p class="checkout-billing-description sandbox-description">
		<?php _e('Please select your preferred payment method. Payments by credit card are provided by our partner Crédit Agricole.', 'sandbox'); ?>
	</p>
	<div class="woocommerce-payment-fields__wrapper">
		<?php if ( WC()->cart->needs_payment() ) : ?>
			<ul class="wc_payment_methods payment_methods methods">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
				}
				?>
			</ul>
		<?php endif; ?>
		<div class="form-row place-order">
			<noscript>
				<?php
				/* translators: $1 and $2 opening and closing emphasis tags respectively */
				printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
				?>
				<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
			</noscript>
			
			<div class="woocommerce-additional-fields">
				<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

				<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
					<span class="sandbox-text"><?php esc_html_e( 'You want to make a precision, a request ?', 'sandbox' ); ?></span>

					<div class="woocommerce-additional-fields__field-wrapper">
						<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
							<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
						<?php endforeach; ?>
					</div>

				<?php endif; ?>

				<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
			</div>

			<?php wc_get_template( 'checkout/terms.php' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<button 
				type="submit" 
				class="validate-payment-button button button-green alt" 
				name="woocommerce_checkout_place_order" 
				id="place_order" 
				value="<?php esc_html_e($submit_button_text); ?>">
				<?php esc_html_e( $submit_button_text ) ?> 
			</button>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
		</div>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
