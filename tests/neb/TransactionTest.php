<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/24
 * Time: 23:48
 */

namespace Test\neb;

require('../../vendor/autoload.php');

use Neb\Core\Transaction;
use Neb\Core\Account;
use PHPUnit\Framework\TestCase;
use Neb\Core\TransactionBinaryPayload;

class TransactionTest extends TestCase
{
    private $tx = "";

    public function prepare(){

        $chainID = 1001;
        $from = Account::newAccount();
        $privHex = "8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93";
        $from->setPrivateKey($privHex);

        $to = "n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc";
        $value = "1000000000000000000";
        $nonce = 1;
        $payloadType = Transaction::BINARY;
        $payload = new TransactionBinaryPayload("data") ;
        $timestamp = 1527177398;
        $gasPrice = "1000000";
        $gasLimit = "20000";
        //$data = '{"payloadType":"binary","payload":null}';
        $hash = "9dedc6db0d895e346355f2c702a7a8e462993fee16a1ec8847b2852d49245564";
        $sign = "ee291ab49ba4ad1c5874a3842bcf02ce3e948ea0938289835eea353394297a166d8b3a93c4d10ffb115a30466c4499fd38e2288586efb36f9fb83399350d3ce600";
        $rawData = "CiCd7cbbDYleNGNV8scCp6jkYpk/7hah7IhHsoUtSSRVZBIaGVcgewayZc0Og2LO7RuSi1alpXte7NOSLwsaGhlXIHsGsmXNDoNizu0bkotWpaV7XuzTki8LIhAAAAAAAAAAAA3gtrOnZAAAKAEwtsGb2AU6CAoGYmluYXJ5QOkHShAAAAAAAAAAAAAAAAAAD0JAUhAAAAAAAAAAAAAAAAAAAE4gWAFiQe4pGrSbpK0cWHSjhCvPAs4+lI6gk4KJg17qNTOUKXoWbYs6k8TRD/sRWjBGbESZ/TjiKIWG77Nvn7gzmTUNPOYA";

        $this->tx = new Transaction($chainID, $from, $to, $value, $nonce,  $gasPrice, $gasLimit, $payloadType, $payload);

    }


    public function testHashTransaction()
    {

    }

    public function testFromProto()
    {

    }


    public function testSignTransaction()
    {


    }

    public function testToString()
    {

    }

    public function testToProto()
    {

    }

    public function testToProtoString()
    {

    }
}
