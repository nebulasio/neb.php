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

$keyJson = '{"version":4,"id":"814745d0-9200-42bd-a4df-557b2d7e1d8b","address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","crypto":{"ciphertext":"fb831107ce71ed9064fca0de8d514d7b2ba0aa03aa4fa6302d09fdfdfad23a18","cipherparams":{"iv":"fb65caf32f4dbb2593e36b02c07b8484"},"cipher":"aes-128-ctr","kdf":"scrypt","kdfparams":{"dklen":32,"salt":"dddc4f9b3e2079b5cc65d82d4f9ecf27da6ec86770cb627a19bc76d094bf9472","n":4096,"r":8,"p":1},"mac":"1a66d8e18d10404440d2762c0d59d0ce9e12a4bbdfc03323736a435a0761ee23","machash":"sha3256"}}';
$password = 'passphrase';

$address = "n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6";

$to = "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy";

$chainId = 1001;

$from = new Account();
$from->fromKey($keyJson, $password);
echo "sender address: ", $from->getAddressString(), PHP_EOL;

$fromAddr = $from->getAddressString();

$resp = $neb->api->getAccountState($fromAddr);
$respObj = json_decode($resp);
$nonce = $respObj->result->nonce;
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

$tx = new Transaction($chainId, $from, $to, "0", $nonce + 1 , 0,0, $payloadType, $payload);
$tx->hashTransaction();
$tx->signTransaction();
echo $tx->toString(), PHP_EOL;
echo "tx raw data: ", $tx->toProtoString(),PHP_EOL,PHP_EOL;


$data = "eyJGdW5jdGlvbiI6ImdldCIsIkFyZ3MiOiJbXCJuZWJ1bGFzXCJdIn0=";
$dataString = base64_decode($data);
echo "dataString: ", $dataString;
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
