<?php

namespace WildanMZaki\MidtransCI3;

use Exception;

trait ParamSetup
{
    private static $params = [];
    private $orderId;
    private int $total = 0;

    private static string $method = 'bank_transfer';
    private static string $option = 'bca';

    private $options = [];

    // Additional
    private $items = [];
    private $customerDetail = [];

    // Inv object for generator invoice
    private $inv;

    // Required For Defining Bank

    public function __construct()
    {
        $this->inv = new Inv();
    }

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
    public function items(array $items = [], bool $countTotal = false, $config = [
        'price_column' => 'price',
        'qty_column' => 'qty',
    ])
    {
        $this->items = $items;
        return $this;
    }

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

    public function va($va): self
    {
        if (!is_numeric($va)) throw new Exception("Virtual account must be numeric value, inpuuted: $va");
        $this->options['va_number'] = $va; // Common bank
        $this->options['bill_key'] = $va; // Only Mandiri
        return $this;
    }

    public function message(string $msg): self
    {
        $this->options['message'] = $msg;
        $this->options['bill_info2'] = $msg;
        return $this;
    }

    public function card_token(string $token_id): self
    {
        $this->options['token_id'] = $token_id;
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    public static function charge($method = ['bank_transfer' => 'bca'])
    {
        self::$method = strtolower(key($method));
        self::$option = strtolower(reset($method));

        self::$params['payment_type'] = self::$method;

        return new self;
    }

    // Menyimpulkan keseluruhan parameter
    public function conclude()
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
