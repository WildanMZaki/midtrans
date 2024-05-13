<?php

namespace WildanMZaki\MidtransCI3;

use Exception;

class CoreAPI
{
    use ParamSetup;

    public function send($params = null)
    {
        // Penyimpulan kebutuhan parameter berdasarkan trait ParamSetup
        try {
            $this->conclude();
            $this->conf->midtransInit();
            $parameters = is_null($params) ? self::$params : $params;
            $response = \Midtrans\CoreApi::charge($parameters);
        } catch (Exception $th) {
            $errorCode = $th->getCode();
            if ($errorCode == 406) {
                return \Midtrans\Transaction::status($this->orderId);
            }
            throw $th;
        }

        return $response;
    }
}
