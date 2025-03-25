<?php


namespace Altum\PaymentGateways;

/* Helper class for Paddle */
class Paddle {
    static public $sandbox_api_url = 'https://sandbox-vendors.paddle.com/api/';
    static public $live_api_url = 'https://vendors.paddle.com/api/';

    public static function get_api_url() {
        return settings()->paddle->mode == 'live' ? self::$live_api_url : self::$sandbox_api_url;
    }

}
