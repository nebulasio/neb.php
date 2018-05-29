<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/15
 * Time: 14:50
 */

namespace Test;

use PHPUnit\Framework\TestCase;
use Neb\Utils\Crypto;

class CryptoTest extends TestCase
{
    function testCreatePrivateKey(){
        $c = new Crypto;
        $privKey = $c -> createPrivateKey();
        echo "Private key is: ", print($privKey);
        $this->assertTrue(strlen($privKey) === 64);
    }

}
