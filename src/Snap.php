<?php

namespace WildanMZaki\MidtransCI3;

class Snap
{
    use ParamSetup;

    private $conf;

    public function __construct()
    {
        $this->inv = new Inv();
        $this->conf = new Config();
    }

    public static function charge()
    {
        self::$method = 'snap';
        self::$option = 'snap';

        return new self;
    }

    public function getToken()
    {
        $this->conclude();
        $this->conf->midtransInit();
        $snapToken = \Midtrans\Snap::getSnapToken(self::$params);
        return $snapToken;
    }
}
