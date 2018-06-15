<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/24
 * Time: 14:48
 */


require ('../vendor/autoload.php');

use Nebulas\Rpc\HttpProvider;
use Nebulas\Rpc\Neb;

//$neb = new Neb();
//$neb = new Neb(new HttpProvider("http://172.0.0.1:8685"));    //local node
//$neb->setRequest(new HttpProvider("https://testnet.nebulas.io")); //mainnet
$neb = new Neb(new HttpProvider("https://testnet.nebulas.io")); //testnet

$api = $neb->api;

echo $api->getNebState(), PHP_EOL;  // {"result":{"chain_id":1001,"tail":"aaec....
echo $neb->api->getNebState(), PHP_EOL;

//echo $api->latestIrreversibleBlock(), PHP_EOL;
//
//echo $api->getAccountState("n1GDCCpQ2Z97o9vei2ajq6frrTPyLNCbnt7"), PHP_EOL;
//echo $api->getBlockByHash("5cce7b5e719b5af679dbc0f4166e9c8665eb03704eb33b97ccb59d4e4ba14352"), PHP_EOL;
//echo $api->getBlockByHeight(1000), PHP_EOL;
//echo $api->getBlockByHeight("1000"), PHP_EOL;

//echo $api->getTransactionReceipt("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1"), PHP_EOL;
//echo $api->gasPrice(), PHP_EOL;

//echo $api->getTransactionByContract("n1zRenwNRXVwY6akcF4rUNoKhmNWP9bhSq8");

//echo $api->getEventsByHash("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1");

/**
 * Get account state
 */
$resp = $api->getAccountState("n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6");
$respObj = json_decode($resp);
$nonce = $respObj->result->nonce;
echo "account state: ", $resp, PHP_EOL; //account state: {"result":{"balance":"100999717112000000","nonce":"14","type":87}}
echo "account nonce: ", $nonce, PHP_EOL;

/**
 * simulate an binary type transaction
 */
$resp = $api->call("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
    "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
    "100000",
    $respObj->result->nonce + 1,
    "200000",
    "200000");

echo $resp, PHP_EOL;
//{"result":{"result":"","execute_err":"","estimate_gas":"20000"}}

/**
 * simulate an call type transaction
 */
$resp = $neb->api->call("n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6",
    "n1oXdmwuo5jJRExnZR5rbceMEyzRsPeALgm",
    "0",
    $nonce + 1,
    "200000",
    "200000",
    "call", //or use null instead
    array("function" => 'get', 'args' => '["nebulas"]'));

echo $resp, PHP_EOL;
//{"result":{"result":"{\"key\":\"nebulas\",\"value\":\"forever nebulas!\",\"author\":\"n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy\"}","execute_err":"","estimate_gas":"20221"}}


/**
 * estimate gas consumption
 */
$resp = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
    "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
    "100000",
    0,
    "200000",
    "200000");
echo $resp, PHP_EOL;

/**
 * example of api subscribe
 */
echo "Subscribe event.....",PHP_EOL;
$topics = ["chain.linkBlock", "chain.pendingTransaction","chain.newTailBlock"];
$neb->api->subscribe($topics,"eventCallback");

/**
 * Explanation of eventCallback function:
 * It is the function that handle the subscribed data,
 * Actually it is the "CURLOPT_WRITEFUNCTION" option for curl, please refer @link http://php.net/manual/en/function.curl-setopt.php
 *
 */
function eventCallback($curlHandle, $data){
    echo "received data: $data",PHP_EOL; //handle the subscribed event data
    return strlen($data);   //return the received data length, or the http connection will close.
}


/**
 * Example of the returned subscribed event data
 *
 * {"result":{"topic":"chain.newTailBlock","data":"{\"height\": 183, \"hash\": \"da986d807f1bf57e3ef5be45428d0405b9bd4673f1ae84f9f970cb31ac7947e2\", \"parent_hash\": \"7dd473f65f2edd45944c276c8b5f75857dc6c60adc410c25f8c562db5e3ba76e\", \"acc_root\": \"6ef637266686ee0a3a25e67c70891857c79a147781642b185ea349902b4e5b64\", \"timestamp\": 1529045610, \"tx\": 1, \"miner\": \"n1GmkKH6nBMw4rrjt16RrJ9WcgvKUtAZP1s\", \"random\": \"/vrf_seed/e50f8186130f355d53856f1bd575bc98daa05690cf7454e351d3d64786f6d2a5/vrf_proof/a0933eb57e0a957eb9355067a209e82f00a7c82f67b407a72fc809e72f43b4bd674126ade24025da8d04dc9914bf198d42cc4dbfcd8486cb2a8f67593d9e690b0436f8169f43679e2251400a050cd044e47ff0f6af7c972b525524ee5642c83efddcc80ebaca7a3d5f5322a818bbd8a2897eac0e56484b6f41a2cf6ae4fef26aa8\"}"}}
 *
 * {"result":{"topic":"chain.pendingTransaction","data":"{\"chainID\":100,\"data\":\"\",\"from\":\"n1NrMKTYESZRCwPFDLFKiKREzZKaN1nhQvz\",\"gaslimit\":\"2000000\",\"gasprice\":\"2000000\",\"hash\":\"dbde8d409647387c99630bba201b31e000dd95344b1d3afacf7d21286e3042f8\",\"nonce\":29,\"timestamp\":1529045611,\"to\":\"n1NrMKTYESZRCwPFDLFKiKREzZKaN1nhQvz\",\"type\":\"binary\",\"value\":\"0\"}"}}
 *
 */


