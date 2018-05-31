<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/31
 * Time: 22:01
 */

namespace Nebulas\Utils;


class Http
{

    static function request(string $method, string $url, string $payload, $timeout){

        $curl=curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array("Content-type: application/json"),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST =>  strtoupper($method),
            CURLOPT_TIMEOUT => $timeout       //or use CURLOPT_TIMEOUT_MS
        );

        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }

}