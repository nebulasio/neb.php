<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/22
 * Time: 17:25
 */

namespace Nebulas\Rpc;


class Httprequest
{
    public $host;
    private $timeout;


    /**
     * Httprequest constructor.
     * @param string $host url, such as for testnet: "https://testnet.nebulas.io"
     * @param int $timeout the maximum number of seconds to allow cURL functions to execute.
     * @throws \Exception if $host is empty.
     */
    function __construct(string $host, $timeout = 30)
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