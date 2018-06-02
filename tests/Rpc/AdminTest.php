<?php
/**
 * Created by PhpStorm.
 * User: yupna
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
    private $newAccount;
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

    public function testNewAccount()
    {
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->newAccount("passphrase");
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
        $Obj = json_decode($result);
        $this->newAccount = $Obj->result->address;
        echo "new account: ", $this->newAccount,PHP_EOL;

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
        $account = "n1c3kQyTXJkCEejGrBLYkH4L4qSV9JxntyS";
        //$account = $this->newAccount;
        echo "account tobe unclocked: ", $account,PHP_EOL;
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->unlockAccount($account,"passphrase");
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);

    }

    public function testLockAccount()
    {
        $account = "n1c3kQyTXJkCEejGrBLYkH4L4qSV9JxntyS";
        //$account = $this->newAccount;
        echo "account tobe clocked: ", $account,PHP_EOL;
        $neb = new Neb(new HttpProvider(ApiTestHost));
        $result = $neb->admin->lockAccount($account);
        echo $result,PHP_EOL;
        self::assertStringStartsWith('{"result"', $result);
    }


    public function testSendTransaction()
    {
        $account = "n1c3kQyTXJkCEejGrBLYkH4L4qSV9JxntyS";
        //$account = $this->newAccount;
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
        $result = $neb->admin->sendTransaction($account,
            $account,
            "100000",
            1,
            "1000000",
            "200000",
            "deploy",
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

    public function testSendTransactionWithPassphrase()
    {

    }

    public function testStartPprof()
    {

    }

    public function testGetConfig()
    {

    }

    public function testSignTransactionWithPassphrase()
    {

    }

    public function testNodeInfo()
    {

    }
}
