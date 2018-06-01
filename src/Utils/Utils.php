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
     * @param array $array
     */
    static public function JsonEncode( $array){

        $object = (object) array_filter((array)$array, function ($val){
            return !is_null($val);
        });
        return json_encode($object);

    }

}