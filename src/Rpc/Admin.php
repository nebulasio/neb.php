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

    /**
     * Method get info about nodes in Nebulas Network.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#nodeinfo}
     * @return mixed
     */
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

    /**
     * Lock account.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#lockaccount}
     * @param string $address
     * @return mixed
     */
    public function lockAccount(string $address){
        $param = array(
            'address' => $address
        );
        return $this->sendRequest("post", "/account/lock", $param);
    }

    /**
     * Send transaction.
     * The transaction's sender addrees must be unlocked before sendTransaction.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#sendtransaction}
     * <p>It's parameters is the same with api/calland api/estimateGas.
     * @param string $from
     * @param string $to
     * @param string $value
     * @param int $nonce
     * @param string $gasPrice
     * @param string $gasLimit
     * @param string|null $type
     * @param array|null $contract
     * @param string|null $binary
     * @return mixed
     */
    public function sendTransaction(string $from, string $to,
                                    string $value, int $nonce,
                                    string $gasPrice, string $gasLimit,
                                    string $type = null, array $contract = null, string $binary = null)
    {
        $param = array(
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

    /**
     * Sign hash.
     * The account must be unlocked before sign.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#signhash}
     *
     * @param string $address account string
     * @param string $hash  a base64 encoded  sha3-256 hash string
     * @param int|null $alg the sign algorithm, an int value,
     * @return mixed
     */
    public function signHash(string $address, string $hash, int $alg = 1){
        $param = array(
            'address' => $address,
            'hash' => $hash,
            'alg' => $alg
        );
        return $this->sendRequest("post", "/sign/hash", $param);
    }

    public function signTransactionWithPassphrase(string $from, string $to,
                                                  string $value, string $nonce,
                                                  string $gasPrice, string $gasLimit,
                                                  string $type, string $contract, string $binary,
                                                  string $passphrase)
    {
        $tx = array(
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

    /**
     * Sign transaction with passphrase.
     * The transaction's sender addrees must be unlock before send.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#signtransactionwithpassphrase}
     *
     * @param string $from
     * @param string $to
     * @param string $value
     * @param string $nonce
     * @param string $gasPrice
     * @param string $gasLimit
     * @param string $type
     * @param string $contract
     * @param string $binary
     * @param string $passphrase
     * @return mixed
     */
    public function sendTransactionWithPassphrase(string $from, string $to,
                                                  string $value, string $nonce,
                                                  string $gasPrice, string $gasLimit,
                                                  string $type, string $contract, string $binary,
                                                  string $passphrase)
    {
        $tx = array(
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

    /**
     * Start listen to provided port.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#startpprof}
     *
     * @param string $listen the specified port
     * @return mixed
     * @example
     *
     *
     */
    public function startPprof(string $listen){
        $param = array(
            'listen' => $listen
        );
        return $this->sendRequest("post", "/pprof", $param);
    }

    /**
     * Get config of node in Nebulas Network.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md#getConfig}
     *
     * @return mixed
     */
    public function getConfig(){
        $param = array();
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