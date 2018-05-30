<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/22
 * Time: 17:25
 */

namespace Neb\Neb;


class Httprequest
{
    private $host;
    private $timeout;
    private $apiVersion;

    function __construct($host = "https://testnet.nebulas.io",
                         $timeout = 0,
                         $apiVersion = "v1")
    {
        $this->host = $host;
        $this->timeout = $timeout;
        $this->apiVersion = $apiVersion;
    }

    function createUrl($api){
        return $this->host . '/' . $this->apiVersion . $api;
    }

    function request(string $method, string $api, string $payload){

        $url = $this->createUrl($api);
        //echo $url, PHP_EOL;

        $curl=curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array("Content-type: application/json","Accept: application/json"),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST =>  strtoupper($method)

        );
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }

}