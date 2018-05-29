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
    private $givenPrivKey = "8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93";
    private $estPubKey = "045cdb458a302e8e5348072641f3a930a48385a2fe67857d78564211a994c66007ee52f3acb76f9809f95f9561ca48baf18d91fa4ba3a0e104d01d66275e0838fb";
    protected $account ;

    public function setUp()
    {
        //$this->$account = Account::newAccount();
    }

    public function testGetAddress()
    {

    }

    public function testGetAddressString()
    {

    }

    public function testToKeyString()
    {

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

    }

    public function testGetPublicKey()
    {

    }

    public function testFromKey()
    {

    }

    public function testSetPrivateKey()
    {

    }

    public function testGetPrivateKey()
    {

    }

    public function testToKey()
    {

    }

    public function testGetPublicKeyString()
    {
        $account = Account::newAccount();
        $account->setPrivateKey($this->givenPrivKey);
        $gotPub = $account->getPublicKeyString();
        $this->assertTrue($gotPub === $this->estPubKey);


    }

    public function testNewAccount()
    {

    }

    public function testGetPrivateKeyString()
    {

    }
}
