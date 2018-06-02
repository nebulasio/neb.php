<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/28
 * Time: 23:18
 */

namespace Test\Rpc;

use Nebulas\Core\Account;
use Nebulas\Rpc\Api;
use Nebulas\Rpc\Neb;
use Nebulas\Rpc\HttpProvider;
use PHPUnit\Framework\TestCase;

define("ApiTestHost","https://testnet.nebulas.io");   //for testnet
//define("ApiTestHost","http://172.0.0.1:8685");     //for local node

class ApiTest extends TestCase
{
    private $source = 'var Contract = function () {}
        Contract.prototype = {
          init: function () {
            this.c = 0;
          },
          add: function (a, b) {
            this.c = a + b;
          },
          getValue: function () {
            return this.c;
          }
        };                     
        module.exports = Contract;';


    public function testSendRawTransaction()
    {
        $rawTx = "CiAdGC4aSvX10KMvh50QSmJd01DNO9XwGL8FR63PdmO+gRIaGVcurysGBxBQh4CCeMovkOpt+dmo6rAsOiIaGhlXLq8rBgcQUIeAgnjKL5DqbfnZqOqwLDoiIhAAAAAAAAAAAAAAAAAAAAAAKAIwtrTF2AU6CAoGYmluYXJ5QAFKEAAAAAAAAAAAAAAAAAAPQkBSEAAAAAAAAAAAAAAAAAADDUBYAWJBGv78KBGOJhQjMLUymzcMLeov6dp1PP3opnIOdNCrUzgbGUjNhAEwLJybX88HMQUZ9e8iG6B8NEdhenVeyN+tKwA=";
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->sendRawTransaction($rawTx);
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"error":"transaction', $result);
    }

    public function testSubscribe()
    {

    }

    public function testEstimateGas()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $api = $neb->api;
        /**
         * default(binary)
         */
        $result = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "100000",
            0,
            "200000",
            "200000");
        echo "estimateGas of default type: ", $result, PHP_EOL, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        /**
         * binary
         */
        $result = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "100000",
            0,
            "200000",
            "200000",
            "binary",
            null,
            "data");
        echo "estimateGas of binary type: ", $result, PHP_EOL, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        /**
         * call
         */
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
        echo "estimateGas of call type: ", $result, PHP_EOL, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        /**
         * deploy
         */
        $result = $api->estimateGas("n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "100000",
            0,
            "200000",
            "200000",
            "deploy",
            array(
                "sourceType"=> "js",
                'source' => $this->source
            ),
            null);
        echo "estimateGas of deploy type: ", $result, PHP_EOL, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testCall()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $api = $neb->api;
        $fromAcc = new Account();
        $from = $fromAcc->getAddressString();
        /**
         * binary
         */
        $result = $api->call($from,
            "n1JmhE82GNjdZPNZr6dgUuSfzy2WRwmD9zy",
            "100000",
            0,
            "200000",
            "200000");

        echo $result, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        /**
         * call
         */
        $result = $api->call($from,
            "n1oXdmwuo5jJRExnZR5rbceMEyzRsPeALgm",
            "0",
            0,
            "200000",
            "200000",
            array("function" => 'get', 'args' => '["nebulas"]'));
        echo $result, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

        /**
         * deploy
         */

        $result = $api->call($from,
            $from,
            "0",
            0,
            "200000",
            "200000",
            array(
                "sourceType" => 'js',
                "source" => $this->source
            ));
        echo $result, PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

    }

    public function testGetEventsByHash()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getEventsByHash("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1");
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetBlockByHeight()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getDynasty(0);
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetNebState()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getNebState();
        echo "NebState: $result", PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGasPrice()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->gasPrice();
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetAccountState()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getAccountState("n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6");
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetBlockByHash()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getBlockByHash("5cce7b5e719b5af679dbc0f4166e9c8665eb03704eb33b97ccb59d4e4ba14352");
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetTransactionByContract()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getTransactionByContract("n1oXdmwuo5jJRExnZR5rbceMEyzRsPeALgm");
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testLatestIrreversibleBlock()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->latestIrreversibleBlock();
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetDynasty()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getDynasty(1);
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetTransactionReceipt()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->api->getTransactionReceipt("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1");
        self::assertStringStartsWith('{"result"', $result);
    }
}
