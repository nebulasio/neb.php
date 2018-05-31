<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/24
 * Time: 22:29
 */

namespace Nebulas\Core;


class TransactionCallPayload implements TransactionPayload
{
    public $Function;
    public $Args;

    function __construct(string $func = null, string $args = null)
    {
        //$this->checkArgs( $func,  $args);
        $this->Function = $func;
        $this->Args = $args;
    }

//    public static function loadPayload(string $data) {
//        $callPayload = json_decode($data);
//        if($callPayload == null)
//            throw new \Exception("Json decode failed!");
//
//        return new static($callPayload->function, $callPayload->args);
//    }

    function checkArgs(string $func, string $args){
        if(!preg_match('/^[a-zA-Z$][A-Za-z0-9_$]*$/',$func)){
            throw new \Exception("invalid function name of call payload");
        }
        if(!empty($args)){
            $array = json_decode($args);
            if(!is_array($array)){
                throw new \Exception("args is not an array of json");
            }
        }
    }

    function toBytes()
    {
        return json_encode($this);
    }

}

