<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/28
 * Time: 23:18
 */

namespace Test\Rpc;

use Nebulas\Core\Api;
use Nebulas\Rpc\Neb;
use Nebulas\Rpc\HttpProvider;
use PHPUnit\Framework\TestCase;

define("host","https://testnet.nebulas.io");

class ApiTest extends TestCase
{
    public function testSendRawTransaction()
    {
        $rawTx = "CiAdGC4aSvX10KMvh50QSmJd01DNO9XwGL8FR63PdmO+gRIaGVcurysGBxBQh4CCeMovkOpt+dmo6rAsOiIaGhlXLq8rBgcQUIeAgnjKL5DqbfnZqOqwLDoiIhAAAAAAAAAAAAAAAAAAAAAAKAIwtrTF2AU6CAoGYmluYXJ5QAFKEAAAAAAAAAAAAAAAAAAPQkBSEAAAAAAAAAAAAAAAAAADDUBYAWJBGv78KBGOJhQjMLUymzcMLeov6dp1PP3opnIOdNCrUzgbGUjNhAEwLJybX88HMQUZ9e8iG6B8NEdhenVeyN+tKwA=";
        $neb = new Neb(new HttpProvider(host));
        $result = $neb->api->sendRawTransaction($rawTx);
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"error":"transaction', $result);
    }

    public function testSubscribe()
    {

    }

    public function testEstimateGas()
    {
        $neb = new Neb(new HttpProvider(host));
        $api = $neb->api;
        $result = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "100000",
            0,
            "200000",
            "200000");
        echo "estimateGas of default type", $result, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

        $result = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "100000",
            0,
            "200000",
            "200000",
            "binary",
            null,
            "data");
        echo "estimateGas of binary type", $result, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

        $result = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "n1oXdmwuo5jJRExnZR5rbceMEyzRsPeALgm",
            "100000",
            0,
            "200000",
            "200000",
            "call",
            array(
                "function"=> "get",
                'args' => '["nebulas"]'
            ),
            null);
        echo "estimateGas of binary type", $result, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testCall()
    {

    }

    public function testGetEventsByHash()
    {

    }

    public function testGetBlockByHeight()
    {

    }

    public function testGetNebState()
    {

    }

    public function testGasPrice()
    {
        $neb = new Neb(new HttpProvider(host));
        $result = $neb->api->gasPrice();
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetAccountState()
    {
        $neb = new Neb(new HttpProvider(host));
        $result = $neb->api->getAccountState("n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6");
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetBlockByHash()
    {

    }

    public function testGetTransactionByContract()
    {

    }

    public function testSetRequest()
    {

    }

    public function testLatestIrreversibleBlock()
    {

    }

    public function testGetDynasty()
    {

    }

    public function testGetTransactionReceipt()
    {

    }
}
