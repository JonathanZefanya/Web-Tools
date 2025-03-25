<?php


namespace Altum;

class Currency {
    public static $currency = null;
    public static $default_currency = null;

    /* Languages directory path */
    public static $path = APP_PATH . 'languages/';

    public static function initialize() {

        self::$default_currency = settings()->payment->default_currency;
        self::$currency = self::$default_currency;

        if(\Altum\Authentication::check() && \Altum\Authentication::$user->currency && array_key_exists(\Altum\Authentication::$user->currency, (array) settings()->payment->currencies)) {
            self::$currency = \Altum\Authentication::$user->currency;
        }

        if(isset($_COOKIE['set_currency']) && array_key_exists($_COOKIE['set_currency'], (array) settings()->payment->currencies)) {
            self::$currency = $_COOKIE['set_currency'];
        }

    }

}
