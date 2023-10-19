<?php

/**
 * Class sandbox_Public_Woocommerce_Template
 */
class sandbox_Public_Woocommerce_Template
{
	public function add_woocommerce_template_path($template, $template_name, $template_path){
		global $woocommerce;

		$_template = $template;
		if ( ! $template_path ) 
			$template_path = $woocommerce->template_url;

		$plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/templates/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		if( ! $template && file_exists( $plugin_path . $template_name ) )
			$template = $plugin_path . $template_name;

		if ( ! $template )
			$template = $_template;

		return $template;
	}

	public function remove_variation_from_product_title( $title, $cart_item, $cart_item_key ) {
		$_product = $cart_item['data'];
		$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
	
		if ( $_product->is_type( 'variation' ) ) {
			if ( ! $product_permalink ) {
				return $_product->get_title();
			} else {
				return sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() );
			}
		}
	
		return $title;
	}

	public function add_variation_under_product_title($cart_item, $cart_item_key){
		$product = $cart_item['data']; // Get the WC_Product Object

		if( $product->is_type( 'variation' ) ){
			$attributes = $product->get_attributes();
			$variation_names = array();

			if( $attributes ){
				foreach ( $attributes as $key => $value) {
					$key_array = explode('-', $key);
					$variation_key =  end($key_array);
					$variation_names[] = ucfirst($variation_key) .': '. $value;
				}
			}
			ob_start();
			?>
			<div>
				<p class="variation-names">
					<?php echo implode( ', ', $variation_names ); ?>
				</p>
			</div>
			<?php
			echo ob_get_clean();
		}
	}

	public function selectwoo_dequeue_stylesandscripts() {
		if ( class_exists( 'woocommerce' ) ) {
			wp_dequeue_style( 'selectWoo' );
			wp_deregister_style( 'selectWoo' );
		
			wp_dequeue_script( 'selectWoo');
			wp_deregister_script('selectWoo');
	   }
	}

	public function move_payment_methods_under_customer_details(){
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 20 );
	}

	public function edit_paypal_icon($icon_html, $gateway_id){
		if('paypal' == $gateway_id){
			$icon_html = '<img src="';
			$icon_html .= SANDBOX_PLUGIN_URL_PATH . '/public/img/paypal_icon.png"';
			$icon_html .= 'width="30" height="30"';
			$icon_html .= 'alt="' . __('Paypal acceptance mark', 'sandbox') . '">';
		}
		return $icon_html;
	}
}	
