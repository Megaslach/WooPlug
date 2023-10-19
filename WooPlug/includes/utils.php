<?php
function sandbox_display_shipping_scheduled_shipping($shipping_settings)
{
    $html = '<p>';
    if (
        isset($shipping_settings['sandbox_shipping_method_business_delivery_days']) &&
        is_array($shipping_settings['sandbox_shipping_method_business_delivery_days']) &&
        isset($shipping_settings['sandbox_shipping_method_scheduled_delivery_start']) &&
        intval($shipping_settings['sandbox_shipping_method_scheduled_delivery_start']) >= 0 &&
        isset($shipping_settings['sandbox_shipping_method_scheduled_delivery_end']) &&
        intval($shipping_settings['sandbox_shipping_method_scheduled_delivery_end']) >= 0
    ) {
        if (isset($shipping_settings['sandbox_shipping_method_before_delivery'])) {
            $html .= $shipping_settings['sandbox_shipping_method_before_delivery'] . '<br>';
        }

        // Next valid business day for delivery
        $start_day = intval($shipping_settings['sandbox_shipping_method_scheduled_delivery_start']);
        for ($i = 1; $i <= 7; $i++) {
            if (!in_array(
                date('N', strtotime("+$start_day days")),
                $shipping_settings['sandbox_shipping_method_business_delivery_days']
            )) {
                $start_day++;
            }
        }
        $start_timestamp = strtotime("+$start_day days");

        // Initialize delivery display variable in case we don't need to add the delivery end day
        $delivery_html = date_i18n('l d F', $start_timestamp);
        if (
            $shipping_settings['sandbox_shipping_method_scheduled_delivery_start'] !=
            $shipping_settings['sandbox_shipping_method_scheduled_delivery_end']
        ) {
            $end_day = intval($shipping_settings['sandbox_shipping_method_scheduled_delivery_end']);
            for ($i = 1; $i <= 7; $i++) {
                if (!in_array(
                    date('N', strtotime("+$end_day days")),
                    $shipping_settings['sandbox_shipping_method_business_delivery_days']
                )) {
                    $end_day++;
                }
            }
            $end_timestamp = strtotime("+$end_day days");

            $delivery_html = sprintf(
                ' %s %s %s %s',
                __('Between', 'sandbox'),
                date_i18n('l d F', $start_timestamp),
                __('and', 'sandbox'),
                date_i18n('l d F', $end_timestamp)
            );
        }

        $html .= $delivery_html;

        if (isset($shipping_settings['sandbox_shipping_method_after_delivery'])) {
            $html .= '<br>' . $shipping_settings['sandbox_shipping_method_after_delivery'];
        }
    }
    $html .= '</p>';
    echo $html;
}

function wc_get_chosen_shipping_method_instance_infos(){
    $packages = WC()->shipping()->get_packages();
    $infos = array();

    foreach ( $packages as $i => $package ) { 
        if(isset( WC()->session->chosen_shipping_methods[ $i ] ) ){
            $infos['index'] = $i;
            $infos['method'] = $package['rates'][WC()->session->chosen_shipping_methods[ $i ]];
            $infos['method_settings'] = get_option( 'woocommerce_' . $infos['method']->method_id . '_' . $infos['method']->instance_id . '_settings');
            break;
        }
    }
    return $infos;
}