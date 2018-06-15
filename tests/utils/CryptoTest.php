<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/30
 * Time: 12:24
 */

namespace Test\utils;

use Nebulas\Utils\Crypto;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{

    public function testGetScrypt()
    {

    }
//
//    public function testRipemd160()
//    {
//
//    }
//
//    public function testGuidv4()
//    {
//
//    }
//
//    public function testSha3()
//    {
//
//    }
//
//    public function testIsValidPublic()
//    {
//
//    }

    public function testPrivateToPublic()
    {
        $private = "6c41a31b4e689e1441c930ce4c34b74cc037bd5e68bbd6878adb2facf62aa7f3";
        $public = "04e465805d7616a330e2448245a1ee96fe4fc49bfe2fa26af64e17f7d3a6e1d82e96814cb44ce2ddb6cbde4b776a331d5c4336f3f32f2086843da17dd3c54e7863";
        self::assertEquals(Crypto::privateToPublic($private), $public);
    }


    public function testSign()
    {
        $hash = "e38544d7c97979277131cbff0aa1cf40b0dd6ec09df0dbdef452917a63a3bdf0";
        $privateKey = "6c41a31b4e689e1441c930ce4c34b74cc037bd5e68bbd6878adb2facf62aa7f3";
        $sign = "78bdb836121c3e95885d52a5f5f724d600795fb0ca09bd98f880d31d48380bef0d8eb8711d2886cbcbed55e34d7f94450b45358e17e9d1caf7732f21c2c4721301";
        self::assertEquals(Crypto::sign($hash,$privateKey), hex2bin($sign));
    }

    public function testCreatePrivateKey()
    {
        $privKey = Crypto::createPrivateKey();
        $this->assertTrue(strlen($privKey) === 64);
    }
}
