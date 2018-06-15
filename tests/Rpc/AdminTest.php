<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/6/2
 * Time: 16:09
 */

namespace Test\Rpc;

use Nebulas\Rpc\Admin;
use Nebulas\Rpc\Api;
use Nebulas\Rpc\Neb;
use Nebulas\Rpc\HttpProvider;
use PHPUnit\Framework\TestCase;

//define("ApiTestHost","https://testnet.nebulas.io");   //for testnet
define("ApiTestHost","http://172.16.1.6:8685");     //for local node

class AdminTest extends TestCase
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

    function getAccount(){
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->accounts();
        //echo $result,PHP_EOL;
        $Obj = json_decode($result);
        return $Obj->result->addresses[0];
    }

    public function testNewAccount()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->newAccount("passphrase");
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testAccounts()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->accounts();
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testUnlockAccount()
    {
        $account = $this->getAccount();
        echo "account tobe unlocked: ", $account,PHP_EOL;
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->unlockAccount($account,"passphrase", "300000000000");  //300s
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

    }

    public function testLockAccount()
    {
        $account = $this->getAccount();
        echo "account tobe locked: ", $account,PHP_EOL;
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->lockAccount($account);
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }


    public function testSendTransaction()
    {
        $account = $this->getAccount();
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $neb->admin->unlockAccount($account,"passphrase");
        /**
         * default
         */
        $result = $neb->admin->sendTransaction($account,
            $account,
            "100000",
            1,
            "1000000",
            "200000");
        echo "default(binary): ",$result,PHP_EOL,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        /**
         * Binary
         */
        $result = $neb->admin->sendTransaction($account,
            $account,
            "100000",
            1,
            "1000000",
            "200000",
            "binary",
            null,
            "data");
        echo "binary: ",$result,PHP_EOL,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        /**
         * deploy
         */
        $result = $neb->admin->sendTransaction($account,
            $account,
            "100000",
            1,
            "1000000",
            "200000",
            null,
            array(
                'sourceType' => 'js',
                'source' => $this->source
            ),
            null);
        echo "deploy: ", $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        $Obj = json_decode($result);
        $contract = $Obj->result->contract_address;
        echo "contract address:  $contract",PHP_EOL,PHP_EOL;
        /**
         * deploy
         */
//        $result = $neb->admin->sendTransaction($account,
//            $account,
//            "100000",
//            1,
//            "1000000",
//            "200000",
//            "deploy",
//            array(
//                'sourceType' => 'js',
//                'source' => $this->source
//            ),
//            null);
//        echo "deploy: ", $result,PHP_EOL;
//        self::assertStringStartsWith('{"result"', $result);
//        $Obj = json_decode($result);
//        $contract = $Obj->result->contract_address;
//        echo "contract address:  $contract",PHP_EOL,PHP_EOL;

        /**
         * call
         */
        $result = $neb->admin->sendTransaction($account,
            $contract,
            "100000",
            1,
            "1000000",
            "200000",
            "call",
            array(
                "function"=> "getValue",
                "args" => null,
            ),
            null);
        echo $result,PHP_EOL,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testSighHash()
    {
        $account = "n1c3kQyTXJkCEejGrBLYkH4L4qSV9JxntyS";
        $account = $this->getAccount();
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->signHash($account,
             base64_encode(hash("sha3-256","any data", true)), //"W+rOKNqs/tlvz02ez77yIYMCOr2EubpuNh5LvmwceI0=",
            1);
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

    }

    public function testSignTransactionWithPassphrase()
    {

    }

    public function testSendTransactionWithPassphrase()
    {

    }

    public function testStartPprof()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->startPprof("8080");
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testGetConfig()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->getConfig();
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }

    public function testNodeInfo()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->nodeInfo();
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }
}
