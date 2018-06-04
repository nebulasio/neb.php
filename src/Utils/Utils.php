<?php
/**
 * Created by PhpStorm.
 * User: yupna
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
     * @param null $opts
     * @return string
     */
    static public function JsonEncode( $array, $opts = null){

        $object = (object) array_filter((array)$array, function ($val){
            return !is_null($val);
        });
        return json_encode($object, $opts);

    }

}