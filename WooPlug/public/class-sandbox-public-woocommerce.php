<?php

class sandbox_Public_Woocommerce
{
    public function custom_redirect_after_login($redirect, $user)
    {
        if( isset($_GET['redirect_to']) && filter_var($_GET['redirect_to'], FILTER_VALIDATE_URL) ){
            $redirect = $_GET['redirect_to'];
        }
        return $redirect;
    }
}
