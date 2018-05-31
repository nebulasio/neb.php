<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/29
 * Time: 0:23
 */

namespace Nebulas\Core;


class TransactionBinaryPayload implements TransactionPayload
{
    public $Data;

    function __construct(string $data = null)
    {
        if(strlen($data)>64)
            throw new \Exception("Payload length exceeds 64 Bytes.");

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