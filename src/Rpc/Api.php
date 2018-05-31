<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:06
 */

namespace Nebulas\Rpc;

use Nebulas\Rpc\Httprequest;
use Nebulas\Rpc\Neb;

class Api
{
    private  $request;
    private $path;
    private $apiVersion;


    function __construct(Neb $neb, $apiVersion)
    {
        $this->setRequest($neb->request);
        $this->apiVersion = $apiVersion;
    }

    public function setRequest( $request){
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
        $action = $this->path . $api;       // e.g. "/user/accountstate"
        $param = json_encode($param);       // e.g. "{"address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","height":"0"}"
        echo "payload: ", $param,PHP_EOL;
        return $this->request->request($method, $action, $param, $this->apiVersion);
    }

    //e.g. https://testnet.nebulas.io/v1/user/getTransactionReceipt
//    function createUrl($api){
//        return $this->request->host . '/' . $this->apiVersion . $this->path . $api;        //
//    }

//     private function sendRequest(string $method, string $api, $param){
//        //$action = $this->path . $api;
//        $url = $this->createUrl($api);
//        //echo "url: ", $url, PHP_EOL;
//
//        $param = json_encode($param);
//        return $this->request->request($method, $url, $param);
//    }

}