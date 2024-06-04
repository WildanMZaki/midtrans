<?php

namespace WildanMZaki\Midtrans;

#[\AllowDynamicProperties]
class Config
{
    /**
     * Your server key
     */
    public static $serverKey;
    /**
     * Your merchant's client key
     *
     * @static
     */
    public static $clientKey;
    /**
     * True for production
     * false for sandbox mode
     *
     * @static
     */
    public static $isProduction = false;
    /**
     * Set it true to enable 3D Secure by default
     *
     * @static
     */
    public static $is3ds = false;
    /**
     * Enable request params sanitizer (validate and modify charge request params).
     * See Midtrans_Sanitizer for more details
     *
     * @static
     */
    public static $isSanitized = false;
    /**
     * Default options for every request
     *
     * @static
     */
    public static $curlOptions = array();
    /**
     * Define Invoice in every generating Order-id
     */
    public static $invocedMethod = false;

    const SANDBOX_BASE_URL = 'https://api.sandbox.midtrans.com/v2';
    const PRODUCTION_BASE_URL = 'https://api.midtrans.com/v2';
    const SNAP_SANDBOX_BASE_URL = 'https://app.sandbox.midtrans.com/snap/v1';
    const SNAP_PRODUCTION_BASE_URL = 'https://app.midtrans.com/snap/v1';

    private $CI;

    public function __construct(string $config = 'midtrans')
    {
        $this->init();
    }

    /**
     * Initialize config for midtrans local library
     */
    public function init()
    {
        self::$serverKey = config('midtrans.server_key');
        self::$clientKey = config('midtrans.client_key');
        self::$isProduction = config('midtrans.is_production');
        self::$isSanitized = config('midtrans.is_sanitized');
        self::$is3ds = config('midtrans.is_3ds');
        self::$invocedMethod = config('midtrans.invoiced_method');
    }

    /**
     * Initialize config for midtrans global library
     */
    public function midtransInit()
    {
        \Midtrans\Config::$serverKey    = self::$serverKey;
        \Midtrans\Config::$isProduction = self::$isProduction;
        \Midtrans\Config::$isSanitized  = self::$isSanitized;
        \Midtrans\Config::$is3ds        = self::$is3ds;
    }

    /**
     * Get Encoded Server Key
     */
    public function getServerKey($encoded = true)
    {
        $serverKey = $this->config->item('midtrans_server_key');
        if (!$encoded) {
            return $serverKey;
        }
        return base64_encode("$serverKey:");
    }

    /**
     * Get baseUrl
     *
     * @return string Midtrans API URL, depends on $isProduction
     */
    public static function getBaseUrl()
    {
        return Config::$isProduction ?
            Config::PRODUCTION_BASE_URL : Config::SANDBOX_BASE_URL;
    }

    /**
     * Get snapBaseUrl
     *
     * @return string Snap API URL, depends on $isProduction
     */
    public static function getSnapBaseUrl()
    {
        return Config::$isProduction ?
            Config::SNAP_PRODUCTION_BASE_URL : Config::SNAP_SANDBOX_BASE_URL;
    }
}
