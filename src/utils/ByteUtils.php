<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/25
 * Time: 10:49
 */

namespace Neb\utils;


class ByteUtils
{

    public static function padToBigEndian(string $value, int $bytes){
        $prefix = hex2bin("00000000"."00000000"."00000000"."00000000"); //16bytes, 128bit
        $bigNum = gmp_init($value);
        $bigNumString = $prefix.gmp_export($bigNum);

        return substr($bigNumString, -$bytes);
    }
}