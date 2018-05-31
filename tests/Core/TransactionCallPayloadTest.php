<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/31
 * Time: 19:21
 */

namespace Test\Core;

use Nebulas\Core\TransactionCallPayload;
use PHPUnit\Framework\TestCase;

class TransactionCallPayloadTest extends TestCase
{

    function testCheckArgs(){

        $funcs = array("abc","1ab","*ab");
        foreach ($funcs as $name){
            $this->CheckArgs($name);
        }
    }

    function CheckArgs($func)
    {
        echo "test function name: ", $func, PHP_EOL;
        $this->expectExceptionMessage("invalid function name of call payload");
        new TransactionCallPayload($func, "");
    }


}
