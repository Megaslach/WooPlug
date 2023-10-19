<?php

class sandbox_Admin_Shipping
{
    public function add_shipping_form_fields(){
        $shipping_methods = WC()->shipping->get_shipping_methods();
        foreach($shipping_methods as $shipping_method) {
            if( $shipping_method->id != "free_shipping" ){
                add_filter('woocommerce_shipping_instance_form_fields_' . $shipping_method->id, array($this, 'shipping_instance_form_add_extra_fields'));
            }
        }
    }

    public function shipping_instance_form_add_extra_fields($settings){
        $delivery_days_options = array(
            '1' => __('Monday', 'sandbox'),
            '2' => __('Tuesday', 'sandbox'),
            '3' => __('Wednesday', 'sandbox'),
            '4' => __('Thursday', 'sandbox'),
            '5' => __('Friday', 'sandbox'),
            '6' => __('Saturday', 'sandbox'),
            '7' => __('Sunday', 'sandbox'),
        );

        // Section title
        $settings['sandbox_display_section'] = array(
            'title' => __('Data to display', 'sandbox'),
            'type' => 'title',
            'description' => __('Configure here the data displayed on shipping listing', 'sandbox')
        );
        // Add option to display minimum amount to get free shipping
        $settings['sandbox_free_shipping_min_amount'] = array(
            'title' => __('Free shipping minimum amount', 'sandbox'),
            'type' => 'number',
            'placeholder' => '0',
            'desc_tip' => __('Set minimum amount to get free shipping', 'sandbox')
        );

        // Add option to display shipping method logo
        $settings['sandbox_shipping_method_logo'] = array(
            'title' => __('Logo id', 'sandbox'),
            'type' => 'number',
            'placeholder' => '0',
            'desc_tip' => __('Set the media id for this shipping method logo', 'sandbox')
        );

        // Add option to set business delivery days
        $settings['sandbox_shipping_method_business_delivery_days'] = array(
            'title' => __('Business delivery days', 'sandbox'),
            'type' => 'multiselect',
            'options' => $delivery_days_options,
            'desc_tip' => __('Choose the business delivery days', 'sandbox')
        );

        // Add option to display before shipping method delivery days
        $settings['sandbox_shipping_method_before_delivery'] = array(
            'title' => __('Before delivery day(s)', 'sandbox'),
            'type' => 'text',
            'placeholder' => __('Shipping', 'sandbox'),
            'desc_tip' => __('Set the text before displaying delivery day(s)', 'sandbox')
        );

        // Add option to display sheduled delivery days
        $settings['sandbox_shipping_method_scheduled_delivery_start'] = array(
            'title' => __('Delivery scheduled starting for', 'sandbox'),
            'type' => 'number',
            'placeholder' => '1',
            'default' => 1,
            'custom_attributes' => array( 
                'min' => '0'
            ),
            'desc_tip' => __('Set the scheduled delivery starting number of days', 'sandbox')
        );

        $settings['sandbox_shipping_method_scheduled_delivery_end'] = array(
            'title' => __('Delivery scheduled ended at', 'sandbox'),
            'type' => 'number',
            'placeholder' => '1',
            'default' => 1,
            'custom_attributes' => array( 
                'min' => '0'
            ),
            'desc_tip' => __('Set the scheduled delivery ending number of days', 'sandbox')
        );

        // Add option to display after shipping method delivery days
        $settings['sandbox_shipping_method_after_delivery'] = array(
            'title' => __('After delivery day(s)', 'sandbox'),
            'type' => 'text',
            'placeholder' => __('Shipping', 'sandbox'),
            'desc_tip' => __('Set the text after displaying delivery day(s)', 'sandbox')
        );

        return $settings;
    }

}
