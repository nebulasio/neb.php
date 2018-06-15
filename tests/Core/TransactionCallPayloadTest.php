<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/31
 * Time: 19:21
 */

namespace Test\Core;

use Nebulas\Core\TransactionCallPayload;
use PHPUnit\Framework\TestCase;

class TransactionCallPayloadTest extends TestCase
{

    //normal function name
    public function providerValidFunc()
    {
        return array(
            array("abcdABCD1234"),
            array("A___B"),
            array("A12B_")
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
    public function providerInvalidFunc()
    {
        return array(
            array(""),
            //array(null),
            array("_abc"),
            array("123"),
            array("[1ab_"),
            array("-abCD")
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
        new TransactionCallPayload("function", $args);
    }

    /**
     * @dataProvider providerValidFunc
     */
    function testCheckArgs_ValidFunc($func)
    {
        echo "test function name: ", $func, PHP_EOL;
        $t = new TransactionCallPayload($func, "");
        self::assertInstanceOf('\Nebulas\Core\TransactionCallPayload', $t);
    }

    /**
     * @dataProvider providerValidArgs
     */
    function testCheckArgs_ValidArg($arg)
    {
        echo "test arg name: ", $arg, PHP_EOL;
        $t = new TransactionCallPayload("function", $arg);
        self::assertInstanceOf('\Nebulas\Core\TransactionCallPayload', $t);
    }

    /**
     * @dataProvider providerInvalidFunc
     */
    function testCheckArgs_InvalidFunc($func)
    {
        $this->expectExceptionMessage("Invalid function name of call payload");

        echo "test function name: ", $func, PHP_EOL;
        new TransactionCallPayload($func, "");
    }

    /**
     * @dataProvider providerInvalidArg
     */
    function testCheckArgs_InvalidArgs($args)
    {
        $this->expectExceptionMessage("Args is not an array of json");

        echo "test arg name: ", $args, PHP_EOL;
        new TransactionCallPayload("function", $args);
    }


}
