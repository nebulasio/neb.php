<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/9
 * Time: 20:24
 */

namespace Neb\Neb;

use Neb\Neb\Account;
use GMP;
use Corepb\Data;
use Neb\Utils\ByteUtils;
use Neb\Utils\Crypto;

class Transaction
{
    private $chainID;
    private $from;
    private $to;
    private $value;
    private $nonce;
    private $timestamp;
    private $contract;
    private $gasPrice;
    private $gasLimit;
    private $data;
    private $hash;
    private $sign;
    private $alg;

    const BINARY = "binary";
    const DEPLOY = "deploy";
    const CALL = "call";


    function __construct(int $chainID,  $from,  $to, string $value, int $nonce,
                         string $gasPrice = '0', string $gasLimit = '0',
                         string $payloadType = Transaction::BINARY, TransactionPayload $payload = null)
    {
        $this->chainID = $chainID;
        $this->from = Account::fromAddress($from);
        $this->to = Account::fromAddress($to);
        $this->value = $value;
        $this->nonce = $nonce;
        $this->timestamp = floor(strtotime("now"));
        $this->gasPrice = $gasPrice;
        $this->gasLimit = $gasLimit;

        //print_r($this->from);
        //print_r($this->to);

        $this->alg = Crypto::SECP256K1;

        if(gmp_cmp($this->gasPrice, "0") <= 0)
            $this->gasPrice = "1000000";
        if(gmp_cmp($this->gasLimit, "0") <= 0)
            $this->gasLimit = "20000";
        if($payload === null)
            $payload = new TransactionBinaryPayload();

        $payloadData = $payload->toBytes();
        echo "payloadData: ", $payloadData,PHP_EOL;
        //$this->data = $this->data2Proto($payloadType, $payloadData);
        $this->data = array(
            "payloadType" => $payloadType,
            "payload" => $payloadData,
        );

    }

    function data2Proto($payloadType,$payload){
        $data = new Data();
        $data->setPayloadType($payloadType);
        $data->setPayload($payload);
        //return $data->serializeToString();
        return $data;
    }

    function hashTransaction(){
        $hashArgs = $this->from->getAddress();
        $hashArgs .= $this->to->getAddress();
        $hashArgs .= ByteUtils::padToBigEndian($this->value,16);
        $hashArgs .= ByteUtils::padToBigEndian($this->nonce,8);
        $hashArgs .= ByteUtils::padToBigEndian($this->timestamp,8);
        //$hashArgs .= $this->data;
        $hashArgs .= $this->data2Proto( $this->data["payloadType"],$this->data["payload"])->serializeToString();
        $hashArgs .= ByteUtils::padToBigEndian($this->chainID,4);
        $hashArgs .= ByteUtils::padToBigEndian($this->gasPrice,16);
        $hashArgs .= ByteUtils::padToBigEndian($this->gasLimit,16);
        $this->hash = hash("sha3-256",$hashArgs,true);      // hash(, , true) retuens raw binary string
        //echo "get tx hash: ", bin2hex($this->hash), PHP_EOL;

    }

    function signTransaction(){
        $privKey = $this->from->getprivateKey();
        if(!isset($privKey) || $privKey === null)
            throw new \Exception("transaction sender address's private key is invalid");

        $signBin = Crypto::sign(bin2hex($this->hash),$privKey); //->toDER('hex');
        //echo "got tx sign: ", bin2hex($signBin), PHP_EOL;
        $this->sign = $signBin;
    }

    function toString(){
        $payload = empty($this->data['payload']) ? null: json_decode($this->data['payload']);
        $txArray = array(
            "chainId" => $this->chainID,
            "from" => $this->from->getAddressString(),
            "to" => $this->to->getAddressString(),
            "value" => $this->value,
            "nonce" => $this->nonce,
            "timestamp" => $this->timestamp,
            "data" => array(
                "payloadType" => $this->data['payloadType'],
                "payload"=>  $payload,
            ),
            "gasPrice" => $this->gasPrice,
            "gasLimit" => $this->gasLimit,
            "hash" => bin2hex($this->hash),
            "alg" => $this->alg,
            "sign" => bin2hex($this->sign),
        );
        //print_r($txArray);
        return json_encode($txArray);
    }

    function toProto(){
//        if(!isset($this->sign))
//            throw new \Exception("You should sign transaction before this operation.");

        $tx = new \Corepb\Transaction();
        $tx->setHash($this->hash);
        $tx->setFrom($this->from->getAddress());
        $tx->setTo($this->to->getAddress());

        $tx->setValue(ByteUtils::padToBigEndian($this->value,16));
        $tx->setNonce($this->nonce);
        $tx->setTimestamp($this->timestamp);
        $tx->setData($this->data2Proto( $this->data["payloadType"],$this->data["payload"]));
        $tx->setChainId($this->chainID);
        $tx->setGasPrice(ByteUtils::padToBigEndian($this->gasPrice,16));
        $tx->setGasLimit(ByteUtils::padToBigEndian($this->gasLimit,16));
        $tx->setAlg($this->alg);
        $tx->setSign($this->sign);


        return $tx->serializeToString();

    }

    function toProtoString(){
        return base64_encode($this->toProto());
    }

    function fromProto(){}

}

