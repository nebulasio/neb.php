<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/24
 * Time: 14:48
 */


require ('../vendor/autoload.php');

use Neb\Neb\Httprequest;
use Neb\neb\Neb;
use Neb\Neb\Api;

//$request = new Httprequest();
//$result = $request->request("get", "/user/getGasPrice", "");
//print_r($request);
//print_r($result);
//echo ($result);

$neb = new Neb();
$neb->setRequest(new Httprequest());

//echo $neb->api->getNebState(), PHP_EOL;
//echo $neb->api->latestIrreversibleBlock(), PHP_EOL;
//
//echo $neb->api->getAccountState("n1GDCCpQ2Z97o9vei2ajq6frrTPyLNCbnt7"), PHP_EOL;
//echo $neb->api->getBlockByHash("5cce7b5e719b5af679dbc0f4166e9c8665eb03704eb33b97ccb59d4e4ba14352"), PHP_EOL;
//echo $neb->api->getBlockByHeight(1000), PHP_EOL;
//echo $neb->api->getBlockByHeight("1000"), PHP_EOL;

//echo $neb->api->getTransactionReceipt("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1"), PHP_EOL;

//$resp = $neb->api->getTransactionByContract("n1zRenwNRXVwY6akcF4rUNoKhmNWP9bhSq8");
//print_r(json_decode($resp));

//echo $neb->api->gasPrice(), PHP_EOL;

//echo $neb->api->getEventsByHash("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1");

$resp = $neb->api->getAccountState("n1GDCCpQ2Z97o9vei2ajq6frrTPyLNCbnt7");
$respObj = json_decode($resp);
print_r($respObj);

//$resp = $neb->api->call("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
//    "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
//    "100000",
//    $respObj->result->nonce + 1,
//    "200000",
//    "200000");
//
//echo $resp, PHP_EOL;

$resp = $neb->api->call("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
    "n1zRenwNRXVwY6akcF4rUNoKhmNWP9bhSq8",
    "100000",
    $respObj->result->nonce + 1,
    "200000",
    "200000",
    array("function" => 'getOrderCount', 'args' => ''));

echo $resp, PHP_EOL;

$resp = $neb->api->call("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
    "n1zRenwNRXVwY6akcF4rUNoKhmNWP9bhSq8",
    "100000",
    $respObj->result->nonce + 1,
    "200000",
    "200000",
    array("function" => 'getOrderByIndex', 'args' => '[4]'));

echo $resp, PHP_EOL;

//$resp = $neb->api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
//    "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
//    "100000",
//    $respObj->result->nonce + 1,
//    "200000",
//    "200000");
//
//echo $resp, PHP_EOL;




