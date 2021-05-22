<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MiniProgramController extends Controller
{
    public function wxlogin()
    {
        $appid = "wx48032850d7cfffe2";
        $secret = "e4eaf3c3401b2d9f5760cb8ac1bfd34b";
        $code = $_GET["code"];
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 2);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $api);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    public function getPhoneNumber()
    {
        $sessionKey = $_GET["sessionKey"];
        $encryptedData = $_GET["encryptedData"];
        $iv = $_GET["iv"];
        $aesKey=base64_decode($sessionKey);
        $aesIV=base64_decode($iv);
        $aesCipher=base64_decode($encryptedData);
        return openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
    }

    public function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if ($tmpStr == $signature ) {
            return $_GET["echostr"];
        } else {
            return "";
        }
    }
}
