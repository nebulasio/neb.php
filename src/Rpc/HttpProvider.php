<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/22
 * Time: 17:25
 */

namespace Nebulas\Rpc;

use Nebulas\Utils\Http;

class HttpProvider
{
    public $host;
    private $timeout;


    /**
     * HttpProvider constructor.
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

    //e.g. https://testnet.nebulas.io/v1/user/getTransactionReceipt
    function createUrl($apiVersion, $api){
        return $this->host . '/' . $apiVersion . $api;
    }

    function request(string $api, string $payload, string $apiVersion, /*object*/ $options){
        $url = $this->createUrl($apiVersion, $api);
        //echo "url: ", $url, PHP_EOL;

        if(empty($options->method))
            throw new \Exception("HTTP method has not specified.");

        if(is_callable($options->callback)){
            return Http::request_alive($options->method,  $url,  $payload, $options->callback);
        }

        return Http::request($options->method,  $url,  $payload, $this->timeout);
    }


}