<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/25
 * Time: 22:49
 */

require ('../vendor/autoload.php');

use Corepb\Data;
use Neb\Neb\Account;
use Neb\Neb\Transaction;
use Neb\Neb\TransactionBinaryPayload;
use Neb\Neb\TransactionCallPayload;
use Neb\neb\Neb;
use Neb\Neb\Httprequest;


$neb = new Neb();
$neb->setRequest(new Httprequest());

//$from = "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy";
$priv = "8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93";
$address = "n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc";

$priv = "6c41a31b4e689e1441c930ce4c34b74cc037bd5e68bbd6878adb2facf62aa7f3";
$address = "n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6";

$to = "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy";

$chainId = 1001;

$from = new Account($priv);

$fromAddr = $from->getAddressString();

$resp = $neb->api->getAccountState($fromAddr);
$respObj = json_decode($resp);
//print_r($respObj);

/**
 * binary type
 */
//$tx = new Transaction($chainId, $from, $to, "0", $respObj->result->nonce + 1 );
//$tx->hashTransaction();
//$tx->signTransaction();
//echo $tx->toString(), PHP_EOL;
//
//$result = $neb->api->sendRawTransaction($tx->toProtoString());
//print_r($result);

/**
 * call type
 */
//$to = "n1zRenwNRXVwY6akcF4rUNoKhmNWP9bhSq8";
//$payloadType = Transaction::CALL;
//$func = "getOrderByIndex";
//$arg = "[2]";
////$func = "getOrderCount";
////$arg = "";
//$payload = new TransactionCallPayload($func, $arg);

$to = "n1oXdmwuo5jJRExnZR5rbceMEyzRsPeALgm";
$payloadType = Transaction::CALL;
$func = "get";
$arg = '["nebulas"]';
$payload = new TransactionCallPayload($func, $arg);

$tx = new Transaction($chainId, $from, $to, "0", $respObj->result->nonce + 1 , 0,0, $payloadType, $payload);
$tx->hashTransaction();
$tx->signTransaction();
echo $tx->toString(), PHP_EOL;
echo "tx raw data: ", $tx->toProtoString(),PHP_EOL;


$data = "eyJGdW5jdGlvbiI6ImdldCIsIkFyZ3MiOiJbXCJuZWJ1bGFzXCJdIn0=";
$dataString = base64_decode($data);
echo $dataString;
//$result = $neb->api->sendRawTransaction($tx->toProtoString());
//print_r($result);




//
//$a = array("data"=>"datatatatatt");
//echo json_encode($a), PHP_EOL;
//
//$payloadType = Transaction::BINARY;
//$data = "data_very_looooooong";
//$txpayload = new TransactionBinaryPayload($data);
//$payload = $txpayload->toString();
//
//print_r($txpayload);
//echo json_encode($txpayload),PHP_EOL;
//echo "payload string: ", $payload, PHP_EOL;
//
//$dataProto = new Data();
//$dataProto->setPayloadType($payloadType);
//$dataProto->setPayload($payload);
////print_r($dataProto);
//
//$dataString = $dataProto->serializeToString();
//echo 'dataString length: ', strlen($dataString),PHP_EOL;
//echo $dataString,PHP_EOL;
//echo bin2hex($dataString),PHP_EOL;
//print_r( unpack('C*',$dataString));
