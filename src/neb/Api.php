<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:06
 */

namespace Neb\Neb;

use Neb\Neb\Httprequest;
use Neb\neb\Neb;

class Api
{
    private  $request;
    private $path;

    function __construct(Neb $neb)
    {
        $this->setRequest($neb->request);
    }

    public function setRequest( Httprequest $request){
        $this->request = $request;
        $this->path = "/user";
    }


    public function getNebState(){
        $param = array();
        return $this->sendRequest("get", "/nebstate", $param);
    }

    public function latestIrreversibleBlock(){
        $param = array();
        return $this->sendRequest("get", "/lib", $param);
    }

    public function getAccountState(string $address,string $height = '0'){
        $param = array(
            "address" => $address,
            "height" => $height
        );
        return $this->sendRequest("post", "/accountstate", $param);
    }

    public function call(string $from, string $to, $value, $nonce, $gasprice, $gasLimit, array $contract = null){
        $param = array(
            "from" => $from,
            "to" => $to,
            "value" => $value,
            "nonce" => $nonce,
            "gasPrice" => $gasprice,
            "gasLimit" => $gasLimit,
            "contract" => $contract,
        );
        return $this->sendRequest("post", "/call", $param);
    }

    public function sendRawTransaction(string $data){
        $param = array(
            "data" => $data
        );
        return $this->sendRequest("post", "/rawtransaction", $param);
    }

    public function getBlockByHash(string $hash, bool $isFull = false){
        $param = array(
            "hash" => $hash,
            "full_fill_transaction" => $isFull, // json_encode($isFull)
        );
        return $this->sendRequest("post", "/getBlockByHash", $param);
    }

    public function getBlockByHeight(string $height){
        $param = array(
            "height" => $height,
        );
        return $this->sendRequest("post", "/getBlockByHeight", $param);
    }

    public function getTransactionReceipt(string $hash){
        $param = array(
            "hash" => $hash,
        );
        return $this->sendRequest("post", "/getTransactionReceipt", $param);
    }

    public function getTransactionByContract(string $address){
        $param = array(
            "address" => $address,
        );
        return $this->sendRequest("post", "/getTransactionByContract", $param);

    }

    public function subscribe(){}       //todo:

    public function gasPrice(){
        $param = array();
        return $this->sendRequest("get", "/getGasPrice", $param);
    }

    public function estimateGas(string $from, $to, $value, $nonce, $gasPrice, $gasLimit, array $contract = null, string $binary = null){
        $param = array(
            "from" => $from,
            "to" => $to,
            "value" => $value,
            "nonce" => $nonce,
            "gasPrice" => $gasPrice,
            "gasLimit" => $gasLimit,
            "contract" => $contract,
            "binary" => $binary,
        );
        return $this->sendRequest("post", "/estimateGas", $param);
    }

    public function getEventsByHash(string $hash){
        $param = array("hash" => $hash);
        return $this->sendRequest("post", "/getEventsByHash", $param);
    }

    public function getDynasty(string $height){
        $param = array("height" => $height);
        return $this->sendRequest("post", "/dynasty", $param);
    }


    function sendRequest(string $method, string $api, $param){
        $action = $this->path . $api;
        $param = json_encode($param);
        return $this->request->request($method,$action,$param);
    }

}