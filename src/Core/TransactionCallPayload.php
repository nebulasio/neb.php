<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/24
 * Time: 22:29
 */

namespace Nebulas\Core;

use Nebulas\Utils\Utils;

define("MaxCallPayLoadLength", 128 * 1024);

class TransactionCallPayload implements TransactionPayload
{
    public $Function;
    public $Args;

    function __construct(string $func , string $args = null)
    {
        $this->Function = $func;
        $this->Args = $args;
        $this->checkArgs( $func,  $args);
    }

//    public static function loadPayload(string $data) {
//        $callPayload = json_decode($data);
//        if($callPayload == null)
//            throw new \Exception("Json decode failed!");
//
//        return new static($callPayload->function, $callPayload->args);
//    }

    function checkArgs(string $func, string $args){
        if(strlen($this->toBytes()) > MaxCallPayLoadLength){
            throw new \Exception("Payload length exceeds max length: ", MaxCallPayLoadLength);
        }

        if(!preg_match('/^[a-zA-Z$][A-Za-z0-9_$]*$/', $func)){
            throw new \Exception("Invalid function name of call payload");
        }
        if(!empty($args)){
            $array = json_decode($args);

            if(!is_array($array)){
                throw new \Exception("Args is not an array of json");
            }
        }
    }

    function toBytes()
    {
        return Utils::JsonEncode($this);
    }

}

