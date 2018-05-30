<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 23:35
 */

namespace Test;

use Neb\Neb\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private $givenPrivKey = "6c41a31b4e689e1441c930ce4c34b74cc037bd5e68bbd6878adb2facf62aa7f3";
    private $estPubKey = "04e465805d7616a330e2448245a1ee96fe4fc49bfe2fa26af64e17f7d3a6e1d82e96814cb44ce2ddb6cbde4b776a331d5c4336f3f32f2086843da17dd3c54e7863";
    private $keyJson = '{"version":4,"id":"814745d0-9200-42bd-a4df-557b2d7e1d8b","address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","crypto":{"ciphertext":"fb831107ce71ed9064fca0de8d514d7b2ba0aa03aa4fa6302d09fdfdfad23a18","cipherparams":{"iv":"fb65caf32f4dbb2593e36b02c07b8484"},"cipher":"aes-128-ctr","kdf":"scrypt","kdfparams":{"dklen":32,"salt":"dddc4f9b3e2079b5cc65d82d4f9ecf27da6ec86770cb627a19bc76d094bf9472","n":4096,"r":8,"p":1},"mac":"1a66d8e18d10404440d2762c0d59d0ce9e12a4bbdfc03323736a435a0761ee23","machash":"sha3256"}}';
    private $password = "passphrase";
    private $address = "n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6";
    private $addressHex = "19571b8df1d7065d1f9c36a9dec6d736d252c065b13e39b163d5";

    protected $account ;

    public function setUp()
    {
        $this->account = Account::newAccount();
        $this->account->fromKey($this->keyJson, $this->password);
    }

    public function testGetAddress()
    {
        self::assertEquals($this->account->getAddress(), hex2bin($this->addressHex));
    }

    public function testGetAddressString()
    {
        self::assertEquals($this->account->getAddressString(), $this->address);
    }


    public function testIsValidAddress()
    {
        $address = "n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc";
        $this->assertTrue(Account::isValidAddress($address));

        $address = "n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhq1";
        $this->assertFalse(Account::isValidAddress($address));
        $address = "";
        $this->assertFalse(Account::isValidAddress($address));

    }

    public function testFromAddress()
    {
        $acc = Account::fromAddress($this->address);
        self::assertEquals($acc->getAddressString(), $this->address);

    }

    public function testSetPrivateKey()
    {
        $priv = "8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93";
        $acc = Account::newAccount();
        $acc->setPrivateKey($priv);
        self::assertEquals( $acc->getAddressString() ,'n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc');

    }

    public function testGetPrivateKey()
    {
        self::assertEquals($this->account->getPrivateKey(), $this->givenPrivKey);
    }

    public function testGetPublicKey()
    {
        $account = Account::newAccount();
        $account->setPrivateKey($this->givenPrivKey);
        $gotPub = $account->getPublicKey();
        $this->assertTrue($gotPub === $this->estPubKey);
    }

//    public function testGetPublicKeyString(){}
//    public function testGetPrivateKeyString(){}

//    public function testToKey(){}

    public function testToKeyString()
    {
        $password = "passphraseeee";
        $acc1 = Account::newAccount();
        $acc2 = Account::newAccount();
        $keyString = $acc1->toKeyString($password);
        $acc2->fromKey($keyString, $password);
        self::assertEquals($acc1->getAddressString(), $acc2->getAddressString());
    }

//    public function testFromKey()
//    {
//
//    }


//    public function testNewAccount()
//    {
//
//    }

}
