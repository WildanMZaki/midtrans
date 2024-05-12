<?php

namespace WildanMZaki\MidtransCI3;

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
        // Get CodeIgniter instance
        $this->CI = &get_instance();

        // Load CodeIgniter config
        $this->CI->load->config($config, false);

        $this->init();
    }

    /**
     * Initialize config for midtrans local library
     */
    public function init()
    {
        self::$serverKey = $this->CI->config->item('midtrans_server_key');
        self::$clientKey = $this->CI->config->item('midtrans_client_key');
        self::$isProduction = $this->CI->config->item('midtrans_is_production');
        self::$isSanitized = $this->CI->config->item('midtrans_is_sanitized');
        self::$is3ds = $this->CI->config->item('midtrans_is_3ds');
        self::$invocedMethod = $this->CI->config->item('midtrans_invoiced_method');
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
