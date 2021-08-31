<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Util;

class UtilController extends Controller
{

    public function getExchangeRate()
    {
        return Util::where('name', "exchange_rate")->get();
    }

    public function updateExchangeRate()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, "https://v6.exchangerate-api.com/v6/465d878cd3c4e5d73f6b2ebf/latest/USD");
        $res = curl_exec($curl);
        curl_close($curl);

        Util::where('name', "exchange_rate")->update(['numerical_value' => json_decode($res)->conversion_rates->CNY]);
        return Util::where('name', "exchange_rate")->get();
    }
}
