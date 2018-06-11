<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/6/7
 * Time: 21:58
 */

namespace Test\utils;

use Nebulas\Utils\Unit;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{

    /**
     * @dataProvider providerToBasic
     */
    public function testToBasic($number, $unit, $expect)
    {
        $result = Unit::toBasic($number, $unit);
        $this->assertEquals($expect, $result);
    }

    /**
     * @dataProvider providerNasToBasic
     */
    public function testNasToBasic($number, $expect)
    {
        $result = Unit::nasToBasic($number);
        $this->assertEquals($expect, $result);
    }

    /**
     * @dataProvider providerFromBasic
     */
    public function testFromBasic($number, $unit, $expect)
    {
        $result = Unit::fromBasic($number, $unit);
        $this->assertEquals($expect, $result);
    }

    /**
     * @dataProvider providerToNas
     */
    public function testToNas($number, $unit, $expect)
    {
        $result = Unit::toNas($number, $unit);
        $this->assertEquals($expect, $result);
    }

    /**
     * @dataProvider providerException
     */
    public function testUnit($number, $unit, $expect)
    {
        $this->expectExceptionMessageRegExp('/The unit undefined, please use*/');
        $result = Unit::toNas($number, $unit);
        $this->assertEquals($expect, $result);
    }

    function providerNasToBasic()
    {
        return array(
            //array(nas, wei)
            array('1',      '1'.'000000'.'000000'.'000000'),
            array('1.1',    '1'.'100000'.'000000'.'000000'),
            array('0.001',  '1000'.'000000'.'000000'),
            array('0.1234567891234567891234','123456789123456789')
        );
    }

    function providerToBasic()
    {
        return array(
            array('1.2',    'wei',    '1'),
            array('1.2',    'kwei',   '1'.'200'),
            array('1.2',    'mwei',   '1'.'200'.'000'),
            array('1.2',    'Gwei',   '1'.'200'.'000000'),
            array('1.2',    'nas',    '1'.'200000'.'000000'.'000000'),
        );
    }

    function providerFromBasic()
    {
        return array(
            array('123456',    'wei',    '123456'),
            array('123456',    'kwei',   '123.456'),
            array('123456',    'mwei',   '0.'.'123456'),
            array('123456',    'Gwei',   '0.'.'000123456'),
            array('123456',    'nas',    '0.'.'000000'.'000000'.'123456'),
        );
    }

    function providerToNas()
    {
        return array(
            array('123456',    'wei',   '0.'.'000000'.'000000'.'123456'),
            array('123456',    'Kwei',  '0.'.'000'.'000000'.'123456'),
            array('123456',    'mwei',  '0.'.'000000'.'123456'),
            array('123456',    'Gwei',  '0.'.'000'.'123456'),
            array('123456',    'nas',   '123456'),
            array('0.123456789123456789'.'1234','nas','0.123456789123456789')
        );
    }

    //unit is wrong
    function providerException(){
        return array(
            array('1.2',    'wee',    '1'),
            array('1.2',    'kkwei',   '1'.'200'),
            array('1.2',    'mnwei',   '1'.'200'.'000'),
            array('1.2',    'GWE',   '1'.'200'.'000000'),
            array('1.2',    'Nass',    '1'.'200000'.'000000'.'000000'),
        );

    }

}
