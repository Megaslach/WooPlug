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

defined('ABSPATH') || exit;
$items_count = WC()->cart->cart_contents_count;
$submit_button_text = __('Secured payment', 'sandbox'); 
?>
<div class="woocommerce-checkout-review-order-table">
	<table class="shop_table sandbox-review-order-table">
		<tbody>
			<?php
			do_action('woocommerce_review_order_before_cart_contents');
			?>
			<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
				<tr class="cart-discount sandbox-text coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
					<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
					<td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php foreach (WC()->cart->get_fees() as $fee) : ?>
				<tr class="fee sandbox-text">
					<th><?php echo esc_html($fee->name); ?></th>
					<td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php
			if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = '';

				if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
					/* translators: %s location. */
					$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
				}

				if ('itemized' === get_option('woocommerce_tax_total_display')) {
					foreach (WC()->cart->get_tax_totals() as $code => $tax) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			?>
						<tr class="tax-rate sandbox-text tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
							<th><?php echo esc_html($tax->label) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?></th>
							<td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr class="tax-total sandbox-text">
						<th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?></th>
						<td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
			<?php
				}
			}
			?>

			<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

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

			<!-- TODO Gift option -->

			<tr class="order-shipping-cost sandbox-text">
				<th><?php _e('Shipping', 'sandbox'); ?></th>
				<td data-title="<?php esc_attr_e('Shipping cost', 'sandbox'); ?>">
					<?php echo WC()->cart->get_cart_shipping_total(); ?>
				</td>
			</tr>

			<tr class="order-total sandbox-bigger-text-bold">
				<th>
					<strong>
						<?php esc_html_e('ATI', 'sandbox'); ?>
					</strong>
				</th>
				<td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
					<?php wc_cart_totals_order_total_html(); ?>
				</td>
			</tr>
			<?php
			do_action('woocommerce_review_order_after_cart_contents');
			?>
		</tbody>
	</table>
	<div class="shipping_method_selected">
		<?php
		$current_shipping_method = wc_get_chosen_shipping_method_instance_infos();
		?>
		<label 
			class="shipping_method_wrap" 
			for="<?php printf('shipping_method_%1$s_%2$s', $current_shipping_method['index'], esc_attr( sanitize_title( $current_shipping_method['method']->id ) ) ); ?>">
			<div class="shipping_method_label">
				<?php
				if(isset($current_shipping_method['method_settings']['sandbox_shipping_method_logo'])){
					echo wp_get_attachment_image(
						$current_shipping_method['method_settings']['sandbox_shipping_method_logo'], 
						'full', 
						false,
						array( 
							'class' => 'shipping_method_logo'
						)
					);
				}
				?>
				<p><?php echo $current_shipping_method['method']->label;?></p>
			</div>
			<div class="shipping_method_shipping_when">
				<?php sandbox_display_shipping_scheduled_shipping($current_shipping_method['method_settings']); ?>
			</div>
			<div class="shipping_method_input">
				<?php
				printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $current_shipping_method['index'], esc_attr( sanitize_title( $current_shipping_method['method']->id ) ), esc_attr( $current_shipping_method['method']->id ) ); // WPCS: XSS ok.
				?>
			</div>
		</label>
	</div>
	<div class="order_informations">
		<p class="backorders sandbox-text">
			<img src="<?php echo SANDBOX_PLUGIN_URL_PATH . '/public/img/round_arrow_icon.png'; ?>" alt="<?php _e('Backorders', 'sandbox'); ?>" class="backorders-icon">
			<span><?php _e('Backorders within 60 days', 'sandbox'); ?></span>
		</p>
		<p class="contact sandbox-text">
			<img src="<?php echo SANDBOX_PLUGIN_URL_PATH . '/public/img/contact_icon.png'; ?>" alt="<?php _e('Contact', 'sandbox'); ?>" class="contact-icon">
			<span>
				<?php _e('Question ?', 'sandbox'); ?>
				<a class="underline-link" href="tel:0143433434">01.43.43.34.34</a>
			</span>
		</p>
		<p class="secure_payment sandbox-text">
			<img src="<?php echo SANDBOX_PLUGIN_URL_PATH . '/public/img/lock_icon.png'; ?>" alt="<?php _e('Secure payment', 'sandbox'); ?>" class="secure_payment-icon">
			<span><?php _e('Secured payment', 'sandbox'); ?></span>
		</p>
	</div>
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