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
    public $host;
    private $timeout;

    function __construct($host,              // = "https://testnet.nebulas.io",  todo: check host? how to check
                         $timeout = 30)
    {
        if(empty($host))
            throw new \Exception("Host is empty.");

        $this->host = $host;
        $this->timeout = $timeout;
    }


    function request(string $method, string $url, string $payload){

        //$url = $this->createUrl($api);
        //echo $url, PHP_EOL;

        $curl=curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array("Content-type: application/json"),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST =>  strtoupper($method),
            CURLOPT_TIMEOUT => $this->timeout       //or use CURLOPT_TIMEOUT_MS
        );

        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;

    }

}