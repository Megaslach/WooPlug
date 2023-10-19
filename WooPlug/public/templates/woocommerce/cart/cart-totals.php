<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files, and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs, the version of the template file will be bumped, and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

// Assurez-vous que le fichier n'est pas chargé directement depuis WordPress.
defined('ABSPATH') || exit;
?>

<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">
    <!-- Div contenant les totaux du panier avec une classe "calculated_shipping" si des frais de livraison ont été calculés. -->
        <div class="shipping_calculator_form_sandbox white-card">
            <form class="woocommerce-shipping-calculator" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

                <h2 class="shipping_calculator_title sandbox_title">Où habitez-vous ?</h2>
                <p class="shipping_calculator_description sandbox_description">Inscrivez votre pays et votre code postal pour decouvrir vos choix de livraison</p>

                <section class="shipping-calculator-form-sandbox">

                    <?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_country', true ) ) : ?>
                        <p class="form-row form-row-wide" id="calc_shipping_country_field">
                            <label for="calc_shipping_country" class="screen-reader-text"><?php esc_html_e( 'Country / region:', 'woocommerce' ); ?></label>
                            <select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state country_select" rel="calc_shipping_state">
                                <option value="default"><?php esc_html_e( 'Select a country / region&hellip;', 'woocommerce' ); ?></option>
                                <?php
                                foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
                                    echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
                                }
                                ?>
                            </select>
                        </p>
                    <?php endif; ?>

                    <?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_state', true ) ) : ?>
                        <p class="form-row form-row-wide" id="calc_shipping_state_field">
                            <?php
                            $current_cc = WC()->customer->get_shipping_country();
                            $current_r  = WC()->customer->get_shipping_state();
                            $states     = WC()->countries->get_states( $current_cc );

                            if ( is_array( $states ) && empty( $states ) ) {
                                ?>
                                <input type="hidden" name="calc_shipping_state" id="calc_shipping_state" placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>" />
                                <?php
                            } elseif ( is_array( $states ) ) {
                                ?>
                                <span>
						<label for="calc_shipping_state" class="screen-reader-text"><?php esc_html_e( 'State / County:', 'woocommerce' ); ?></label>
						<select name="calc_shipping_state" class="state_select" id="calc_shipping_state" data-placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>">
							<option value=""><?php esc_html_e( 'Select an option&hellip;', 'woocommerce' ); ?></option>
							<?php
                            foreach ( $states as $ckey => $cvalue ) {
                                echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                            }
                            ?>
						</select>
					</span>
                                <?php
                            } else {
                                ?>
                                <label for="calc_shipping_state" class="screen-reader-text"><?php esc_html_e( 'State / County:', 'woocommerce' ); ?></label>
                                <input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>" placeholder="<?php esc_attr_e( 'State / County', 'woocommerce' ); ?>" name="calc_shipping_state" id="calc_shipping_state" />
                                <?php
                            }
                            ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', true ) ) : ?>
                        <p class="form-row form-row-wide" id="calc_shipping_city_field">
                            <label for="calc_shipping_city" class="screen-reader-text"><?php esc_html_e( 'City:', 'woocommerce' ); ?></label>
                            <input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>" placeholder="<?php esc_attr_e( 'City', 'woocommerce' ); ?>" name="calc_shipping_city" id="calc_shipping_city" />
                        </p>
                    <?php endif; ?>

                    <?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
                        <p class="form-row form-row-wide" id="calc_shipping_postcode_field">
                            <label for="calc_shipping_postcode" class="screen-reader-text"><?php esc_html_e( 'Postcode / ZIP:', 'woocommerce' ); ?></label>
                            <input type="text" class="input-text" value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>" placeholder="<?php esc_attr_e( 'Postcode / ZIP', 'woocommerce' ); ?>" name="calc_shipping_postcode" id="calc_shipping_postcode" />
                        </p>
                    <?php endif; ?>

                    <p><button type="submit" name="calc_shipping" value="1" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php esc_html_e( 'Update', 'woocommerce' ); ?></button></p>
                    <?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>

                </section>
            </form>

        </div>
    <!--les methodes de livraison-->
        <div class="cart_shipping_method white-card">

            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

                <div class="shipping_calculator_form white-card">
                    <?php woocommerce_shipping_calculator( '' ); ?>
                </div>

                <?php wc_cart_totals_shipping_html(); ?>

                <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

            <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

                <?php woocommerce_shipping_calculator(); ?>

            <?php endif; ?>
        </div>

    <?php do_action('woocommerce_before_cart_totals'); ?>

    <div class="summary_cart white-card">
        <div class="summary_info"
            <h2>Retour 60 jours</h2>
            <p>blablaesfsesfsfsefsfesfsefesfsefsefesfsefsefesfe</p>
            <p>BLALBLBLLA</p>
            <p>blallbalbalbal<br>blalblalbllllsqldeldsledlsldledlsldsledslds<br>deqdeds</p>
        </div>

    </div>

    <div class="total_cart white-card">
        <div class="total_cart_info">
            <div class="coupon">

                    <div class="custom-coupon-form">
                        <h3><?php esc_html_e( 'Coupon', 'woocommerce' ); ?></h3>
                        <form class="coupon" method="post">
                            <p><?php esc_html_e( 'Have a coupon?', 'woocommerce' ); ?> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button></p>
                        </form>
                    </div>
            </div>


            <h2><?php esc_html_e('Cart totals', 'woocommerce'); ?></h2>
        <!-- Titre de la section des totaux du panier. -->

        <table cellspacing="0" class="shop_table shop_table_responsive">
            <!-- Tableau affichant les détails des totaux du panier. -->

            <tr class="cart-subtotal">
                <!-- Ligne affichant le sous-total du panier. -->
                <th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                <td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>


            <!-- Boucle pour afficher les remises (coupons) appliquées au panier. -->
            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                    <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                    <td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></td>
                </tr>
            <?php endforeach; ?>

            <!-- Boucle pour afficher des frais supplémentaires, le cas échéant. -->
            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                <tr class="fee">
                    <th><?php echo esc_html($fee->name); ?></th>
                    <td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php
            if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
                $taxable_address = WC()->customer->get_taxable_address();
                $estimated_text = '';

                if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
                    /* translators: %s location. */
                    $estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
                }

                if ('itemized' === get_option('woocommerce_tax_total_display')) {
                    // Affiche les taxes de manière détaillée.
                    foreach (WC()->cart->get_tax_totals() as $code => $tax) {
                        ?>
                        <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                            <th><?php echo esc_html($tax->label) . $estimated_text; // Affiche l'étiquette de la taxe avec un texte estimatif ?></th>
                            <td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); // Affiche le montant de la taxe ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    // Affiche le total des taxes.
                    ?>
                    <tr class="tax-total">
                        <th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // Affiche le libellé de la taxe ?></th>
                        <td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); // Affiche le montant total des taxes ?></td>
                    </tr>
                    <?php
                }
            }
            ?>

            <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

            <tr class="order-total">
                <!-- Ligne affichant le montant total de la commande. -->
                <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
                <td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>

            <?php do_action('woocommerce_cart_totals_after_order_total'); ?>

        </table>

        <div class="wc-proceed-to-checkout">
            <!-- Affiche le bouton "Proceed to Checkout" pour passer à la page de paiement. -->
            <?php do_action('woocommerce_proceed_to_checkout'); ?>
        </div>
        </div>
</div>
    <?php do_action('woocommerce_after_cart_totals'); ?>

</div>
