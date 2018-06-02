<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:07
 */

namespace Nebulas\Rpc;

use Nebulas\Utils\Utils;

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

    /**
     * Get a list of available addresses under this node.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#accounts}
     *
     * @return mixed
     */
    public function accounts(){
        $param = array();
        return $this->sendRequest("get", "/accounts", $param);
    }

    /**
     * Create a new account in Nebulas network with provided passphrase.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#newaccount}
     *
     * @param string $passphrase
     * @return mixed
     * @example
     *
     *   $neb = new Neb(new HttpProvider(ApiTestHost));
     *   $result = $neb->admin->newAccount("passphrase");
     *   echo $result,PHP_EOL;
     *   $Obj = json_decode($result);
     *   $newAccount = $Obj->result->address;
     *
     */
    public function newAccount(string $passphrase){
        $param = array(
            "passphrase" => $passphrase
        );
        return $this->sendRequest("post", "/account/new", $param);
    }

    /**
     * Unlock account with it's passphrase.
     * After the default unlock time, the account will be locked.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#unlockaccount}
     *
     * @param string $address
     * @param string $passphrase
     * @param string $duration the time duration for this account stay unlocked, <b>Note that the unit is ns</b>
     * @return mixed e.g. {"result":{"result":true}}
     * @example
     *  $neb = new Neb(new HttpProvider(ApiTestHost));
     *  $result = $neb->admin->unlockAccount($account,"passphrase");
     *
     */
    public function unlockAccount(string $address, string $passphrase, string $duration = '30000000000'){   //30s
        $param = array(
            'address' => $address,
            "passphrase" => $passphrase,
            "duration" => $duration,
        );
        return $this->sendRequest("post", "/account/unlock", $param);
    }

    public function lockAccount(string $address){
        $param = array(
            'address' => $address
        );
        return $this->sendRequest("post", "/account/lock", $param);
    }

    /**
     * Send transaction.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#sendtransaction}
     * <p>It's parameters is the same with api/calland api/estimateGas.
     * @param string $from
     * @param string $to
     * @param string $value
     * @param int $nonce
     * @param string $gasPrice
     * @param string $gasLimit
     * @param string|null $type //todo check: $contract 和 $binary 可以同时不为null吗?
     * @param array|null $contract
     * @param string|null $binary
     * @return mixed
     */
    public function sendTransaction(string $from, string $to,       //todo nonce 是int, 其他是string?
                                    string $value, int $nonce,
                                    string $gasPrice, string $gasLimit,
                                    string $type = null, array $contract = null, string $binary = null)
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
        //$param = json_encode($param);       // e.g. "{"address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","height":"0"}"
        $paramString = Utils::JsonEncode($param);
        //echo "payload formatted: ", Utils::JsonEncode($param,JSON_PRETTY_PRINT),PHP_EOL;

        $options = (object) array(
            "method" => $method,
        );
        return $this->provider->request($api, $paramString, $this->apiVersion, $options);
    }


}