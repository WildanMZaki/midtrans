<?php

namespace WildanMZaki\MidtransCI3;

use Exception;

class Options
{
    public static $banks = [
        'bca', 'bni', 'bri', 'cimb', 'permata', 'mandiri',
    ];
    public static $stores = [
        'alfamart', 'indomaret',
    ];

    public static $properties = [
        'credit_card' => [
            'token_id' => null,
            'authentication' => true,
        ],
        'bank_transfer' => [
            'bank' => 'bca', // Default
        ],
        'echannel' => [
            'bill_info1' => 'Payment',
            'bill_info2' => 'Online purchase',
        ],
        'permata' => [],
        'gopay' => [
            'enable_callback' => true,
            'callback_url' => ''
        ],
        'qris' => [],
        'cstore' => [
            'store' => 'alfamart', // Message
            'message' => 'Online Shop Payment'
        ],
        'akulaku' => [],
        'kredivo' => [],
    ];

    public static $methods = [
        'bank_transfer', 'credit_card', 'permata', 'echannel', 'qris', 'gopay', 'cstore', 'akulaku', 'kredivo'
    ];

    // Return Property yang akan digunakan
    public static $prop = [];

    // Replacer atau merger terhadap prop
    public static $options;

    public static function credit_card()
    {
        if (!isset(self::$options['token_id'])) throw new Exception("'token_id' option must be defined");

        $all = array_merge(self::$prop, self::$options);

        // Filter all keys needed
        $need = ['token_id', 'authorization'];
        return array_intersect_key($all, array_flip($need));
    }

    public static function bank_transfer()
    {
        if (!isset(self::$options['bank'])) throw new Exception("'bank' option must be defined");
        $bank = self::$options['bank'];
        if (!in_array($bank, self::$banks)) throw new Exception("Bank '$bank' not available");

        $all = array_merge(self::$prop, self::$options);

        // Filter all keys needed
        $need = ['bank', 'va_number', 'bca'];
        return array_intersect_key($all, array_flip($need));
    }

    public static function cstore()
    {
        if (!isset(self::$options['store'])) throw new Exception("'store' option must be defined");
        $store = self::$options['store'];
        if (!in_array($store, self::$stores)) throw new Exception("Store '$store' not available");

        $all = array_merge(self::$prop, self::$options);

        // Filter all keys needed
        $need = ['store', 'message'];
        if (self::$options['store'] === 'alfamart') {
            $need = array_merge($need, ['alfamart_free_text_1', 'alfamart_free_text_2', 'alfamart_free_text_3']);
        }

        return array_intersect_key($all, array_flip($need));
    }

    public static function echannel()
    {
        $all = array_merge(self::$prop, self::$options);

        // Filter all keys needed
        $need = ['bill_info1', 'bill_info2', 'bill_key'];
        return array_intersect_key($all, array_flip($need));
    }

    public static function gopay()
    {
        $all = array_merge(self::$prop, self::$options);

        // Filter all keys needed
        $need = ['enable_callback', 'callback_url'];
        return array_intersect_key($all, array_flip($need));
    }

    public static function use($method, $options)
    {
        if (!in_array($method, self::$methods)) throw new Exception("Method '$method' not available");

        self::$options = $options;
        // self::$options['bank'] = $options['choice'];
        // self::$options['store'] = $options['choice'];
        self::$prop = self::$properties[$method];

        if (empty(self::$prop)) return (object)[];
        $conf = self::$method();

        return $conf;
    }
}
