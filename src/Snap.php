<?php

namespace WildanMZaki\Midtrans;

class Snap
{
    use ParamSetup;

    public static function setup()
    {
        self::$method = 'snap';
        self::$option = 'snap';

        return new self;
    }

    public function getToken()
    {
        self::$method = 'snap';
        self::$option = 'snap';

        $this->conclude();
        $this->conf->midtransInit();
        $snapToken = \Midtrans\Snap::getSnapToken(self::$params);
        return $snapToken;
    }
}
