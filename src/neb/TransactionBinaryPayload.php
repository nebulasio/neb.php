<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/29
 * Time: 0:23
 */

namespace Neb\neb;


class TransactionBinaryPayload implements TransactionPayload
{
    public $Data;

    function __construct(string $data = null)
    {
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
    function toString()
    {
        //return json_encode($this);
        return $this->Data;
    }

}