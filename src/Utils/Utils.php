<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/6/1
 * Time: 23:04
 */

namespace Nebulas\Utils;


class Utils
{
    /**
     * Encode array into json string and omit null value.
     *
     * @param $array
     * @param null $opts such as JSON_PRETTY_PRINT {@link http://www.php.net/manual/en/function.json-encode.php}
     * @return string
     */
    static public function JsonEncode( $array, $opts = null){

        $object = (object) array_filter((array)$array, function ($val){
            return !is_null($val);
        });
        return json_encode($object, $opts);

    }

}