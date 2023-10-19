<?php

class sandbox_Public_Product_Extras
{
    /**
     * Add a custom parameter to add-on field settings
     * 
     * @param mixed $group_key
     * @param mixed $item_key
     * @param mixed $item
     * @param mixed $post_id
     * 
     * @return void
     */
    public function pewc_add_addons_parameters( $group_key, $item_key, $item, $post_id ) {
        $cart_name = isset( $item['field_cart_name'] ) ? $item['field_cart_name'] : '';
        ob_start();
        ?>
        <div class="pewc-fields-wrapper pewc-my-new-fields">
            <div class="product-extra-field-third">
                <label> <?php _e('Display name in cart', 'sandbox'); ?></label>
                <input type="text" 
                    class="pewc-field-field_cart_name" 
                    name="<?php echo '_product_extra_groups_' . esc_attr( $group_key ) . '_' . esc_attr( $item_key ) . '[field_cart_name]'; ?>" 
                    value="<?php echo esc_html( $cart_name ); ?>"
                >
            </div>
        </div>
        <?php
        echo ob_get_clean();
    }

    /**
     * Add the custom param to field data
     * 
     * @param mixed $params
     * @param mixed $field_id
     * 
     * @return array
     */
    public function pewc_add_custom_field_params( $params, $field_id ) {
        $params[] = 'field_cart_name';
        return $params;
    }

    /**
     * Add product addons field configuration to cart item meta data
     * 
     * @param mixed $cart_item_data
     * @param mixed $item
     * @param mixed $group_id
     * @param mixed $field_id
     * @param mixed $value
     * 
     * @return array
     */
    public function add_group_item_field_to_cart_item_data($cart_item_data, $item, $group_id, $field_id, $value){
        $cart_item_data['product_extras']['groups'][$group_id][$field_id]['items'] = $item;
        return $cart_item_data;
    }

    /**
     * Edit cart custom meta data property to display in our way
     * 
     * @param mixed $other_data
     * @param mixed $cart_item
     * @param mixed $groups
     * 
     * @return array
     */
    public function edit_cart_item_data($other_data, $cart_item, $groups){
        if(is_array($groups)){
            foreach($groups as $item){
                if(isset($item['items'])){
                    foreach($other_data as $key => $metadata){
                        if($metadata['name'] == $item['label']){
                            $other_data[$key]['display'] = $item['items'];
                        }
                    }
                }
            }
        }
        return $other_data;
    }

    public function sandbox_update_gift_message(){
        global $woocommerce;
        $product_extras = pewc_get_extra_fields($_POST['product_id']);
        if( 
            isset($woocommerce->cart->cart_contents[$_POST['product_key']]) &&
            isset($woocommerce->cart->cart_contents[$_POST['product_key']]['product_extras'])
        ){
            foreach ($product_extras as $group_id => $group) {
                if( isset( $group['items'] ) ){
                    foreach ($group['items'] as $field_id => $items) {
                        if($items['id'] == $_POST['product_meta_id']){
                            $woocommerce->cart->cart_contents[$_POST['product_key']]['product_extras']['groups'][$group_id][$field_id]['value'] = $_POST['value'];
                        }
                    }
                }
            }

            $woocommerce->cart->set_session();
        }

        wp_die();
    }

    public function sandbox_delete_gift_option(){
        global $woocommerce;
        if( 
            isset($woocommerce->cart->cart_contents[$_POST['product_key']]) &&
            isset($woocommerce->cart->cart_contents[$_POST['product_key']]['product_extras'])
        ){
            unset($woocommerce->cart->cart_contents[$_POST['product_key']]['product_extras']['groups'][$_POST['group_id']]);
            // reset price without extras
            $woocommerce->cart->cart_contents[$_POST['product_key']]['product_extras']['price_with_extras'] = $woocommerce->cart->cart_contents[$_POST['product_key']]['product_extras']['original_price'];
            $woocommerce->cart->set_session();
        }

        wp_die();
    }

    public function sandbox_add_gift_option(){
        global $woocommerce;

        if(isset($woocommerce->cart->cart_contents[$_POST['product_key']])){
            // Use ultimate product addons function to create correct custom meta
            $cart_item_data = pewc_add_cart_item_data(
                $woocommerce->cart->cart_contents[$_POST['product_key']],
                $woocommerce->cart->cart_contents[$_POST['product_key']]['product_id'],
                $woocommerce->cart->cart_contents[$_POST['product_key']]['variation_id'],
                $woocommerce->cart->cart_contents[$_POST['product_key']]['quantity']
            );
            $woocommerce->cart->cart_contents[$_POST['product_key']] = $cart_item_data;
            $woocommerce->cart->set_session();
        }

        wp_die();
    }

    public function add_textarea_default_value($value, $id, $item, $posted){
        if($item['field_type'] == 'textarea' && empty($value)){
            $value = " ";
        }
        return $value;
    }
}
