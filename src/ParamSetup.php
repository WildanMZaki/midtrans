<?php

namespace WildanMZaki\MidtransCI3;

use Exception;

trait ParamSetup
{
    // Property utama yang akan diurus di trait ini
    private static $params = [];

    // Required data by this library
    private $orderId;
    private int $total = 0;

    private static string $method = 'bank_transfer';
    private static string $option = 'bca';

    // Configuration Options
    private $options = [];

    // Additional
    private $items = [];
    private $customerDetail = [];

    // Inv object for generator invoice
    public $inv;

    // Storage of Config object
    public $conf;

    public function __construct()
    {
        $this->inv = new Inv();
        $this->conf = new Config();
    }

    /**
     * Initialization method, ini digunakan untuk untuk membuat instance. Dan memungkinkan proses setting method secara berantai:
     * ->method1()->otherMethod().....
     */
    public static function method($method = 'bank_transfer', $option = 'bca')
    {
        if (empty($method)) throw new Exception('Payment method can\'t be empty');
        self::$method = strtolower($method);
        self::$option = strtolower($option ?? '');

        self::$params['payment_type'] = self::$method;

        return new self;
    }

    /**
     * Setter for config file that would be used
     */
    public function config($configName = 'midtrans'): self
    {
        $this->conf = new Config($configName);
        return $this;
    }

    /**
     * Getter or setter. You can use this method to set the params property, or you can use this method as getter to get params that has been setup by this library
     */
    public function params($params = null): self|array
    {
        if (!is_null($params) && is_array($params)) {
            self::$params = array_merge(self::$params, $params);
            return $this;
        } else {
            $this->conclude();
            return self::$params;
        }
    }

    /**
     * Setter for invoice or in this library: orderId property
     * You can use this if you already has the invoice and do not want to use inv utilities
     */
    public function invoice(string|callable $invoice): self
    {
        $invStr = is_callable($invoice) ? $invoice() : $invoice;
        if (!is_string($invStr)) throw new Exception('Invoice must be string value');
        $this->orderId = $invStr;
        return $this;
    }

    /**
     * setter for invoice using default generator
     */
    public function inv(string $prefix = 'INV-', int|callable $orderNumber = 0, $digits = null): self
    {
        $this->inv->setValues($prefix, $orderNumber, $digits);
        return $this;
    }

    /**
     * Setter for total order
     */
    public function total(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Setter for detail items ordered
     * {
     *   "id": "a01",
     *   "price": 7000,
     *   "quantity": 1,
     *   "name": "Apple"
     * }
     */
    public function items(array $items = [], $config = [
        'price_column' => 'price',
        'qty_column' => 'qty',
    ])
    {
        $total = 0;
        foreach ($items as $i => $item) {
            $q = isset($item['quantity'])
                ? $item['quantity']
                : (isset($item[$config['qty_column']])
                    ? $item[$config['qty_column']]
                    : 0);
            $items[$i]['quantity'] = $q;
            $price = isset($item[$config['price_column']])
                ? $item[$config['price_column']]
                : 0;
            $items[$i]['price'] = $price;
            $total += $q * $price;
        }
        $this->total($total);

        $this->items = $items;
        return $this;
    }

    /**
     * setter for customer detail properties
     */
    public function customer(array $details = [])
    {
        $this->customerDetail = $details;
        return $this;
        /*
         "customer_details": {
            "first_name": "Budi",
            "last_name": "Susanto",
            "email": "budisusanto@example.com",
            "phone": "+628123456789",
            "billing_address": {
              "first_name": "Budi",
              "last_name": "Susanto",
              "email": "budisusanto@example.com",
              "phone": "08123456789",
              "address": "Sudirman No.12",
              "city": "Jakarta",
              "postal_code": "12190",
              "country_code": "IDN"
            },
            "shipping_address": {
              "first_name": "Budi",
              "last_name": "Susanto",
              "email": "budisusanto@example.com",
              "phone": "0812345678910",
              "address": "Sudirman",
              "city": "Jakarta",
              "postal_code": "12190",
              "country_code": "IDN"
            }
          }
         */
    }

    /**
     * Setter for custom virtual account number
     */
    public function va($va): self
    {
        if (!is_numeric($va)) throw new Exception("Virtual account must be numeric value, inpuuted: $va");
        $this->options['va_number'] = $va; // Common bank
        $this->options['bill_key'] = $va; // Only Mandiri
        return $this;
    }

    /**
     * Setter for custom message information
     */
    public function message(string $msg): self
    {
        $this->options['message'] = $msg;
        $this->options['bill_info2'] = $msg;
        return $this;
    }

    /**
     * Setter for card token when you use card payment method
     */
    public function card_token(string $token_id): self
    {
        $this->options['token_id'] = $token_id;
        return $this;
    }

    /**
     * Setter for options that not required by all payment, but it can be used to make fill the options that required in any payment method that be used
     */
    public function options(array $options): self
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * Menyimpulkan keseluruhan method, digunakan di dalam method yang memerlukan data self::$params secara utuh
     */
    private function conclude()
    {
        $this->inv->setMethod(self::$method);
        $orderId = is_null($this->orderId) ? $this->inv->generate() : $this->orderId;
        $this->orderId = $orderId;
        $total = $this->total;

        self::$params['transaction_details']['order_id'] = $orderId;
        self::$params['transaction_details']['gross_amount'] = $total;

        // Defaults tambahkan semua option
        $this->options['bank'] = self::$option;
        $this->options['store'] = self::$option;

        if (self::$method != 'snap') {
            self::$params[self::$method] = Options::use(self::$method, $this->options);
        }

        if (!empty($this->items)) {
            self::$params['item_details'] = $this->items;
        }
        if (!empty($this->customerDetail)) {
            self::$params['customer_details'] = $this->customerDetail;
        }
    }
}
