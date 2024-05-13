<?php

namespace WildanMZaki\MidtransCI3;

class Inv
{
    private $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public string $prefix = 'INV-';
    public $orderNumber = 0;
    public int $digits = 3;

    /**
     * Snap: SN
     * Bank Transfer: BT
     * Qris: QR
     * Gopay: GP
     * Credit Card: CC
     * CStore: CS (Counter store) : Alfa, Indomaret
     * Cardless Payment:
     * Akulaku: 'AL'
     * Kredive: 'KV'
     */
    public $code = [
        'snap' => 'SN',
        'bank_transfer' => 'BT',
        'echannel' => 'EM',
        'permata' => 'PM',
        'qris' => 'QR',
        'gopay' => 'GP',
        'credit_card' => 'CC',
        'cstore' => 'CS',
        'akulaku' => 'AL',
        'kredivo' => 'KV',
    ];

    private $method = 'undefined'; // This would be undefined by default

    public function setMethod($payment_method): self
    {
        $this->method = $payment_method;
        return $this;
    }

    public function setValues(string $prefix, int|callable $orderNumber, int $digits = null): self
    {
        $this->prefix = $prefix;
        $this->orderNumber = $orderNumber;
        $this->digits = $digits ?? $this->digits;
        return $this;
    }

    public function generate()
    {
        $this->config->init();

        $prefix = $this->prefix;
        $orderNumber = $this->orderNumber;
        $digits = $this->digits;

        $date = date('Ymd');
        if (Config::$invocedMethod) {
            $prefix .= isset($this->code[$this->method]) ? $this->code[$this->method] : 'UN';
            $prefix .= '-';
        }

        $prefix .= $date;

        $n = (is_callable($orderNumber)) ? $orderNumber($prefix) : $orderNumber;
        $n = $n ?: 1;

        if ($n <= (10 ** $digits) - 1) {
            return $prefix . str_pad($n, $digits, '0', STR_PAD_LEFT);
        }

        return "$prefix{$n}";
    }
}
