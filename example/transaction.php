<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/25
 * Time: 22:49
 */

require ('../vendor/autoload.php');

use Neb\neb\Neb;
use Neb\Neb\Account;
use Neb\Neb\Transaction;
use Neb\Neb\TransactionBinaryPayload;
use Neb\Neb\TransactionCallPayload;
use Neb\Neb\Httprequest;


$neb = new Neb();
$neb->setRequest(new Httprequest());

$keyJson = '{"version":4,"id":"814745d0-9200-42bd-a4df-557b2d7e1d8b","address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","crypto":{"ciphertext":"fb831107ce71ed9064fca0de8d514d7b2ba0aa03aa4fa6302d09fdfdfad23a18","cipherparams":{"iv":"fb65caf32f4dbb2593e36b02c07b8484"},"cipher":"aes-128-ctr","kdf":"scrypt","kdfparams":{"dklen":32,"salt":"dddc4f9b3e2079b5cc65d82d4f9ecf27da6ec86770cb627a19bc76d094bf9472","n":4096,"r":8,"p":1},"mac":"1a66d8e18d10404440d2762c0d59d0ce9e12a4bbdfc03323736a435a0761ee23","machash":"sha3256"}}';
$password = 'passphrase';

$from = new Account();
$from->fromKey($keyJson, $password);
echo "sender address: ", $from->getAddressString(), PHP_EOL;

$fromAddr = $from->getAddressString();
$to = "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy";

//prepare transaction, get nonce first
$resp = $neb->api->getAccountState($fromAddr);
$respObj = json_decode($resp);
$nonce = $respObj->result->nonce;
$chainId = 1001;
//print_r($respObj);

/*** binary type */
echo "[binary transaction example]",PHP_EOL;
$tx = new Transaction($chainId, $from, $to, "0", $nonce + 1 );
$tx->hashTransaction();
$tx->signTransaction();

$result = $neb->api->sendRawTransaction($tx->toProtoString());
echo "tx result: ", $result, PHP_EOL;

echo PHP_EOL;

/*** call type */
echo "[call transaction example]",PHP_EOL;

$to = "n1oXdmwuo5jJRExnZR5rbceMEyzRsPeALgm";     //Dapp Address

$func = "get";
$arg = '["nebulas"]';
$payload = new TransactionCallPayload($func, $arg);
$payloadType = Transaction::CALL;

$tx = new Transaction($chainId, $from, $to, "0", $nonce + 1 , 0,"200000", $payloadType, $payload);
$tx->hashTransaction();
$tx->signTransaction();

echo "tx string: ", $tx->toString(), PHP_EOL;
//echo "tx raw data: ", $tx->toProtoString(),PHP_EOL,PHP_EOL;

$result = $neb->api->sendRawTransaction($tx->toProtoString());
echo "tx result: ", $result, PHP_EOL;



