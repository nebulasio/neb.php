<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/29
 * Time: 0:23
 */

namespace Nebulas\Core;

define("MaxBinaryPayLoadLength", 64);

class TransactionBinaryPayload implements TransactionPayload
{
    public $Data;

    function __construct(string $data = null)
    {
        if(strlen($data) > MaxBinaryPayLoadLength){
            throw new \Exception("Payload length exceeds 64 Bytes.");
        }

        $this->Data = $data;
    }

    public static function loadPayload($data){
        return new static($data);
    }

    function toBytes()
    {
        //return json_encode($this);
        return $this->Data;
    }


}