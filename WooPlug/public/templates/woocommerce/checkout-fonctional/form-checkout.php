<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>
<div class="before_cart_form white-card">
	<div class="before_cart_left">
		<h1 class="cart-title sandbox-title">
			<?php _e('Your cart.', 'sandbox'); ?>
		</h1>
		<span class="cart-tips sandbox-subtitle" style="font-weight: normal">
			<?php echo sprintf(
				'%s %s',
				__('Need help, ', 'sandbox'),
				'<a style="font-weight:normal;" href="#" class="contact_link underline-link" target="_blank">' . __('call us', 'sandbox') . '</a>'
				); 
			?>
		</span>
	</div>
	<div class="before_cart_right">
		<span class="cart-subtotal-text uppercase-text">
			<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>
		</span>
		<span class="cart-subtotal uppercase-text sandbox-price-text">
			<?php wc_cart_totals_subtotal_html(); ?>
		</span>
	</div>
</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="checkout_div__wrapper">
		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set checkout-main white-card">
				<div id="customer_details" data-display="customer_details_step" class="checkout_steps">
					<div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>
		
					<div class="col-2">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
					<?php if( wp_is_mobile() ): ?>
					<div class="multi-step_buttons">
						<div class="wc-back-to-cart" data-display="customer_details_step">
							<a href="<?php echo wc_get_cart_url(); ?>" class="back-to-cart button button-transparent alt">
								<?php _e('Back to cart', 'sandbox'); ?>
							</a>
						</div>
						<div class="wc-proceed-to-payment checkout_steps" data-display="customer_details_step">
							<a href="" class="payment-button button button-green alt" data-step="payment_step">
								<?php _e('Next step', 'sandbox'); ?>
							</a>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			</div>

		<?php endif; ?>
		
		<div class="checkout-sidebar white-card"<?php echo (wp_is_mobile()) ? ' data-display="payment_step"' : ''; ?>>
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			
			<h3 id="order_review_heading" class="sandbox-title"><?php esc_html_e( 'Order summary', 'sandbox' ); ?></h3>
			
			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
		
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
		
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
