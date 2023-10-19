<?php
/**
 * Page de Panier
 *
 * Ce modèle peut être surchargé en le copiant dans votre thème actif sous votretheme/woocommerce/cart/cart.php.
 *
 * CEPENDANT, de temps en temps, WooCommerce devra mettre à jour les fichiers de modèle, et vous
 * (le développeur du thème) devrez copier les nouveaux fichiers dans votre thème pour
 * maintenir la compatibilité. Nous essayons de le faire le moins souvent possible, mais cela arrive.
 * Lorsque cela se produit, la version du fichier de modèle sera incrémentée et le readme indiquera les modifications importantes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

// S'assure que WordPress est correctement chargé
defined( 'ABSPATH' ) || exit;
?>

<!-- Formulaire de panier avec classe CSS 'woocommerce-cart-form' -->
<form class="woocommerce-cart-form product-space " action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <div class="before_cart_info white-card">
        <div class="left_info">
            <h2>Votre Panier</h2>
        </div>

        <div class="right_info">
            <span class="cart-subtotal-text uppercase-text">
				<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>
			</span>
            <span class="cart-subtotal uppercase-text menstate-price-text">
				<?php wc_cart_totals_subtotal_html(); ?>
			</span>
        </div>
    </div>
    <?php do_action( 'woocommerce_before_cart_table' ); ?>

    <!-- Tableau affichant les éléments du panier -->
    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents cart-tab white-card" cellspacing="0">

        <tbody>
        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        // Boucle parcourant les articles du panier
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            // Obtient les informations sur le produit et le produit associé
            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            // Obtient le nom du produit
            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                // Obtient le lien vers le produit
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>
                <tr class="woocommerce-cart-form__cart-item product-tab <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                    <!-- Colonne d'image miniature du produit -->
                    <td class="product-thumbnail">
                        <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                        if ( ! $product_permalink ) {
                            echo $thumbnail; // Affiche l'image miniature
                        } else {
                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // Affiche le lien avec l'image miniature
                        }
                        ?>
                    </td>

                    <!-- Colonne du nom du produit -->
                    <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                        <?php
                        if ( ! $product_permalink ) {
                            echo wp_kses_post( $product_name . '&nbsp;' );
                        } else {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                        }

                        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                        // Affiche les métadonnées du produit
                        echo wc_get_formatted_cart_item_data( $cart_item );

                        // Affiche la notification de réapprovisionnement
                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                        }
                        ?>
                    </td>

                    <!-- Colonne du prix du produit -->
                    <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                        <?php
                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                        ?>
                    </td>

                    <!-- Colonne de la quantité du produit -->
                    <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                        <?php
                        if ( $_product->is_sold_individually() ) {
                            $min_quantity = 1;
                            $max_quantity = 1;
                        } else {
                            $min_quantity = 0;
                            $max_quantity = $_product->get_max_purchase_quantity();
                        }

                        $product_quantity = woocommerce_quantity_input(
                            array(
                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                'input_value'  => $cart_item['quantity'],
                                'max_value'    => $max_quantity,
                                'min_value'    => $min_quantity,
                                'product_name' => $product_name,
                            ),
                            $_product,
                            false
                        );

                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                        ?>
                    </td>
                    <td class="product-remove">
                        <?php
                        // Affiche le lien pour supprimer l'article du panier
                        echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                /* translators: %s is the product name */
                                esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                esc_attr( $product_id ),
                                esc_attr( $_product->get_sku() )
                            ),
                            $cart_item_key
                        );
                        ?>
                    </td>

                    <!-- Colonne du sous-total du produit -->
                    <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                        <?php
                        // Affiche le sous-total du produit
                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                        ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>

        <!-- Point d'accroche pour le contenu après les éléments du panier -->
        <?php do_action( 'woocommerce_cart_contents' ); ?>
        <tr>
            <td colspan="6" class="actions hide">

                <?php if ( wc_coupons_enabled() ) { ?>
                    <div class="coupon">
                        <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
                        <?php do_action( 'woocommerce_cart_coupon' ); ?>
                    </div>
                <?php } ?>

                <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

                <?php do_action( 'woocommerce_cart_actions' ); ?>

                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </td>
        </tr>

        </tbody>
    </table>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>



<!-- Point d'accroche pour le contenu avant les éléments de consolidation du panier -->
<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
    <?php
    /**
     * Point d'accroche pour les éléments de consolidation du panier.
     *
     * @hooked woocommerce_cross_sell_display - Affiche les produits associés
     * @hooked woocommerce_cart_totals - 10 - Affiche les totaux du panier
     */
    do_action( 'woocommerce_cart_collaterals' );

    // Supprime l'affichage des produits associés (s'il y en a)
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    ?>
</div>

<!-- Point d'accroche pour le contenu après les éléments de consolidation du panier -->
<?php do_action( 'woocommerce_after_cart' ); ?>



