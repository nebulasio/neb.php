<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/6/1
 * Time: 23:46
 */

namespace Test\Core;

use Nebulas\Core\TransactionDeployPayload;
use PHPUnit\Framework\TestCase;

class TransactionDeployPayloadTest extends TestCase
{

    //normal function name
    public function providerValidSource()
    {
        return array(
            array("12134567"),
            array("asdfgjkhj"),
            array("!@#$%^&123456")
        );
    }
    //normal args name
    public function providerValidArgs()
    {
        return array(
            array(''),
            array('[]'),
            array('[1]'),
            array('[1,2]'),
            array('["arg"]'),
            array('["arg1","arg2"]')
        );
    }
    //invalid function name
    public function providerInvalidSource()
    {
        return array(
            array(""),
        );
    }
    //invalid args
    public function providerInvalidArg()
    {
        return array(
            array('string'),
            array("['arg']"),
        );
    }

    /**
     *
     */
    function testCheckArgs_payloadTooLong()
    {
        $this->expectExceptionMessageRegExp('/Payload length exceeds max*/');
        $args = str_repeat("s",128*1024);
        echo "arg length: ", strlen($args)/1024.0, "K ", PHP_EOL;
        new TransactionDeployPayload("js","function", $args);
    }

    /**
     * @dataProvider providerValidSource
     */
    function testCheckArgs_ValidSource($func)
    {
        echo "test function name: ", $func, PHP_EOL;
        $t = new TransactionDeployPayload("js", $func, "");
        self::assertInstanceOf('\Nebulas\Core\TransactionDeployPayload', $t);
    }

    /**
     * @dataProvider providerValidArgs
     */
    function testCheckArgs_ValidArg($arg)
    {
        echo "test arg name: ", $arg, PHP_EOL;
        $t = new TransactionDeployPayload("js","source", $arg);
        self::assertInstanceOf('\Nebulas\Core\TransactionDeployPayload', $t);
    }

    /**
     * @dataProvider providerInvalidSource
     */
    function testCheckArgs_InvalidSource($func)
    {
        $this->expectExceptionMessage("Invalid source of deploy payload");

        echo "test function name: ", $func, PHP_EOL;
        new TransactionDeployPayload("js", $func, "");
    }

    /**
     * @dataProvider providerInvalidArg
     */
    function testCheckArgs_InvalidArgs($args)
    {
        $this->expectExceptionMessage("Args is not an array of json");

        echo "test arg name: ", $args, PHP_EOL;
        new TransactionDeployPayload("js","source", $args);
    }

}
