<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:07
 */

namespace Nebulas\Rpc;


class Admin
{

    private $provider;
    private $path;
    private $apiVersion;

    function __construct(Neb $neb, $apiVersion)
    {
        $this->serProvider($neb->provider);
        $this->apiVersion = $apiVersion;
    }

    public function serProvider($provider){
        $this->provider = $provider;
        $this->path = "/admin";
    }

    public function nodeInfo(){
        $param = array();
        return $this->sendRequest("get", "/nodeinfo", $param);
    }

    public function accounts(){
        $param = array();
        return $this->sendRequest("get", "/accounts", $param);
    }

    public function newAccount(string $passphrase){
        $param = array(
            "passphrase" => $passphrase
        );
        return $this->sendRequest("post", "/account/new", $param);
    }

    public function unlockAccount(string $address, string $passphrase, string $duration){
        $param = array(
            '$address' => $address,
            "passphrase" => $passphrase,
            "duration" => $duration,
        );
        return $this->sendRequest("post", "/account/unlock", $param);
    }

    public function lockAccount(string $address){
        $param = array(
            '$address' => $address
        );
        return $this->sendRequest("post", "/account/lock", $param);
    }

    public function sendTransaction(string $from, string $to,       //todo 都是string吗?
                                    string $value, string $nonce,
                                    string $gasPrice, string $gasLimit,
                                    string $type, string $contract, string $binary)
    {
        $param = array(             //todo: 顺序可以任意调整吧?
            "from"     => $from,
            "to"       => $to,
            "value"    => $value,
            "nonce"    => $nonce,
            "gasPrice" => $gasPrice,
            "gasLimit" => $gasLimit,
            'type'     => $type,
            "contract" => $contract,
            "binary"   => $binary
        );
        return $this->sendRequest("post", "/transaction", $param);
    }

    public function signTransactionWithPassphrase(string $from, string $to,
                                                  string $value, string $nonce,
                                                  string $gasPrice, string $gasLimit,
                                                  string $type, string $contract, string $binary,
                                                  string $passphrase)
    {
        $tx = array(             //todo: 顺序可以任意调整吧?
            "from"     => $from,
            "to"       => $to,
            "value"    => $value,
            "nonce"    => $nonce,
            "gasPrice" => $gasPrice,
            "gasLimit" => $gasLimit,
            'type'     => $type,
            "contract" => $contract,
            "binary"   => $binary
        );
        $param = array(
            "transaction" => $tx,
            "passphrase" => $passphrase
        );
        return $this->sendRequest("post", "/sign", $param);
    }


    public function sendTransactionWithPassphrase(string $from, string $to,
                                                  string $value, string $nonce,
                                                  string $gasPrice, string $gasLimit,
                                                  string $type, string $contract, string $binary,
                                                  string $passphrase)
    {
        $tx = array(             //todo: 顺序可以任意调整吧?
            "from"     => $from,
            "to"       => $to,
            "value"    => $value,
            "nonce"    => $nonce,
            "gasPrice" => $gasPrice,
            "gasLimit" => $gasLimit,
            'type'     => $type,
            "contract" => $contract,
            "binary"   => $binary
        );
        $param = array(
            "transaction" => $tx,
            "passphrase" => $passphrase
        );
        return $this->sendRequest("post", "/transactionWithPassphrase", $param);
    }

    public function startPprof(string $listen){
        $param = array(
            'listen' => $listen
        );
        return $this->sendRequest("post", "/pprof", $param);
    }

    public function getConfig(string $listen){
        $param = array(
            'listen' => $listen
        );
        return $this->sendRequest("get", "/getConfig", $param);
    }

    function sendRequest(string $method, string $api, array $param){
        $api = $this->path . $api;       // e.g. "/user/accountstate"
        $param = json_encode($param);       // e.g. "{"address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","height":"0"}"
        //echo "payload: ", $param,PHP_EOL;
        $options = (object) array(
            "method" => $method,
        );
        return $this->provider->request($api, $param, $this->apiVersion, $options);
    }


}