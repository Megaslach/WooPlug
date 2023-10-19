<?php

class sandbox_Public_Checkout
{
    public function reorganise_checkout_fields( $checkout_fields )
    {
        $checkout_fields = $this->edit_checkout_email_field($checkout_fields);
        $checkout_fields = $this->add_checkout_newsletter_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_phone_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_first_name_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_last_name_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_company_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_country_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_address_fields($checkout_fields);
        $checkout_fields = $this->edit_checkout_postcode_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_city_field($checkout_fields);
        $checkout_fields = $this->edit_checkout_order_notes_field($checkout_fields);
        return $checkout_fields;
    }

    private function edit_checkout_email_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_email']['priority'] = 1;
        $checkout_fields['billing']['billing_email']['placeholder'] = __('Your email address', 'sandbox');
        $checkout_fields['billing']['billing_email']['class'] = array('form-row-half', 'sandbox-field');
        $checkout_fields['billing']['billing_email']['label'] = '<img class="form-row-icon" src="'. SANDBOX_PLUGIN_URL_PATH .'/public/img/email_icon.png"></img>';
        return $checkout_fields;
    }

    private function add_checkout_newsletter_field($checkout_fields){
        $new_field = array(
            'label' => __('Keep me aware of news and offers', 'sandbox'),
            'type' => 'checkbox',
            'class' => array('billing_field', 'sandbox-field'),
            'priority' => 2
        );
        $checkout_fields['billing']['billing_newsletter'] = $new_field;
        return $checkout_fields;
    }

    private function edit_checkout_phone_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_phone']['priority'] = 5;
        $checkout_fields['billing']['billing_phone']['placeholder'] = __('Phone (mandatory)', 'sandbox');
        $checkout_fields['billing']['billing_phone']['class'] = array('form-row-half', 'sandbox-field');
        $checkout_fields['billing']['billing_phone']['label'] = '<img class="form-row-icon" src="'. SANDBOX_PLUGIN_URL_PATH .'/public/img/phone_icon.png"></img>';
        return $checkout_fields;
    }

    private function edit_checkout_first_name_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_first_name']['placeholder'] = __('First name', 'sandbox');
        $checkout_fields['billing']['billing_first_name']['label'] = '<img class="form-row-icon" src="'. SANDBOX_PLUGIN_URL_PATH .'/public/img/identity_card_icon.png"></img>';
        $checkout_fields['billing']['billing_first_name']['class'] = array('form-row-first', 'sandbox-field');
        $checkout_fields['shipping']['shipping_first_name']['placeholder'] = __('First name', 'sandbox');
        $checkout_fields['shipping']['shipping_first_name']['label'] = '<img class="form-row-icon" src="'. SANDBOX_PLUGIN_URL_PATH .'/public/img/identity_card_icon.png"></img>';
        $checkout_fields['shipping']['shipping_first_name']['class'] = array('form-row-first', 'sandbox-field');
        return $checkout_fields;
    }

    private function edit_checkout_last_name_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_last_name']['placeholder'] = __('Last name', 'sandbox');
        $checkout_fields['billing']['billing_last_name']['label'] = false;
        $checkout_fields['billing']['billing_last_name']['class'] = array('form-row-last', 'sandbox-field');
        $checkout_fields['shipping']['shipping_last_name']['placeholder'] = __('Last name', 'sandbox');
        $checkout_fields['shipping']['shipping_last_name']['label'] = false;
        $checkout_fields['shipping']['shipping_last_name']['class'] = array('form-row-last', 'sandbox-field');
        return $checkout_fields;
    }

    private function edit_checkout_company_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_company']['placeholder'] = __('Company (optionnal)', 'sandbox');
        $checkout_fields['billing']['billing_company']['label'] = '<span class="space"></span>';
        $checkout_fields['billing']['billing_company']['class'] = array('form-row-wide', 'sandbox-field');
        $checkout_fields['shipping']['shipping_company']['placeholder'] = __('Company (optionnal)', 'sandbox');
        $checkout_fields['shipping']['shipping_company']['label'] = '<span class="space"></span>';
        $checkout_fields['shipping']['shipping_company']['class'] = array('form-row-wide', 'sandbox-field');
        return $checkout_fields;
    }

    private function edit_checkout_country_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_country']['priority'] = 110;
        $checkout_fields['billing']['billing_country']['class'] = array('form-row-half', 'sandbox-field');
        $checkout_fields['billing']['billing_country']['label'] = '<span class="space"></span>';
        $checkout_fields['shipping']['shipping_country']['priority'] = 110;
        $checkout_fields['shipping']['shipping_country']['class'] = array('form-row-half', 'sandbox-field');
        $checkout_fields['shipping']['shipping_country']['label'] = '<span class="space"></span>';
        return $checkout_fields;
    }

    private function edit_checkout_address_fields($checkout_fields)
    {
        $checkout_fields['billing']['billing_address_1']['placeholder'] = __('Address', 'sandbox');
        $checkout_fields['billing']['billing_address_1']['label'] = '<img class="form-row-icon" src="'. SANDBOX_PLUGIN_URL_PATH .'/public/img/geolocation_icon.png"></img>';
        $checkout_fields['billing']['billing_address_1']['class'] = array('form-row-wide', 'sandbox-field');
        $checkout_fields['shipping']['shipping_address_1']['placeholder'] = __('Address', 'sandbox');
        $checkout_fields['shipping']['shipping_address_1']['label'] = '<img class="form-row-icon" src="'. SANDBOX_PLUGIN_URL_PATH .'/public/img/geolocation_icon.png"></img>';
        $checkout_fields['shipping']['shipping_address_1']['class'] = array('form-row-wide', 'sandbox-field');
        $checkout_fields['billing']['billing_address_2']['placeholder'] = __('Apartment, ... (optionnal)', 'sandbox');
        $checkout_fields['billing']['billing_address_2']['label'] = '<span class="space"></span>';
        $checkout_fields['billing']['billing_address_2']['label_class'] = [];
        $checkout_fields['billing']['billing_address_2']['class'] = array('form-row-wide', 'sandbox-field');
        $checkout_fields['shipping']['shipping_address_2']['placeholder'] = __('Apartment, ... (optionnal)', 'sandbox');
        $checkout_fields['shipping']['shipping_address_2']['label'] = '<span class="space"></span>';
        $checkout_fields['shipping']['shipping_address_2']['label_class'] = [];
        $checkout_fields['shipping']['shipping_address_2']['class'] = array('form-row-wide', 'sandbox-field');
        return $checkout_fields;
    }

    private function edit_checkout_postcode_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_postcode']['placeholder'] = __('Postcode', 'sandbox');
        $checkout_fields['billing']['billing_postcode']['class'] = array('form-row-first', 'sandbox-field');
        $checkout_fields['billing']['billing_postcode']['label'] = '<span class="space"></span>';
        $checkout_fields['shipping']['shipping_postcode']['placeholder'] = __('Postcode', 'sandbox');
        $checkout_fields['shipping']['shipping_postcode']['class'] = array('form-row-first', 'sandbox-field');
        $checkout_fields['shipping']['shipping_postcode']['label'] = '<span class="space"></span>';
        return $checkout_fields;
    }

    private function edit_checkout_city_field($checkout_fields)
    {
        $checkout_fields['billing']['billing_city']['priority'] = 100;
        $checkout_fields['billing']['billing_city']['placeholder'] = __('City', 'sandbox');
        $checkout_fields['billing']['billing_city']['class'] = array('form-row-last', 'sandbox-field');
        $checkout_fields['billing']['billing_city']['label'] = false;
        $checkout_fields['shipping']['shipping_city']['priority'] = 100;
        $checkout_fields['shipping']['shipping_city']['placeholder'] = __('City', 'sandbox');
        $checkout_fields['shipping']['shipping_city']['class'] = array('form-row-last', 'sandbox-field');
        $checkout_fields['shipping']['shipping_city']['label'] = false;
        return $checkout_fields;
    }

    private function edit_checkout_order_notes_field($checkout_fields){
        $checkout_fields['order']['order_comments']['placeholder'] = __('Example: it\'s for my husband birthday', 'sandbox');
        $checkout_fields['order']['order_comments']['label'] = false;
        $checkout_fields['order']['order_comments']['class'] = array('sandbox-text');
        return $checkout_fields;
    }

    public function add_custom_order_fields($checkout){
        echo '<div id="order_packaging">';

        woocommerce_form_field('order_packaging', array(
            'type' => 'checkbox',
            'label' => __('Would you like a reduced packaging to limit ecologique impact ?', 'sandbox'),
            'class' => array('sandbox-text')
        ), $checkout->get_value('order_packaging'));

        echo '</div>';
    }

    public function save_custom_order_fields($order_id){
        if ( ! empty( $_POST['order_packaging'] ) ) {
            update_post_meta( $order_id, 'order_packaging', sanitize_text_field( $_POST['order_packaging'] ) );
        }
    }

    public function display_admin_custom_order_fields($order_id){
        $limited_packaging = get_post_meta($order_id, 'order_packaging', true);
        ob_start();
        ?>
        <tr class="packaging">
            <td class="thumb"></td>
            <td class="name">
                <?php if($limited_packaging): ?>
                    <div class="view"><?php _e('Limited packaging', 'sandbox'); ?></div>
                <?php else: ?>
                    <div class="view"><?php _e('Without Limited packaging', 'sandbox'); ?></div>
                <?php endif; ?>
            </td>
        </tr>
        <?php
        $html = ob_get_clean();
        echo $html;
    }
}