<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/24
 * Time: 22:29
 */

namespace Neb\neb;


//class TransactionBinaryPayload implements TransactionPayload
//{
//    public $Data;
//
//    function __construct(string $data = null)
//    {
//        $this->Data = $data;
//    }
//
//    public static function loadPayload($data){
//        return new static($data);
//    }
//
//    function toBytes()
//    {
//        //return json_encode($this);
//        return $this->Data;
//    }
//    function toString()
//    {
//        //return json_encode($this);
//        return $this->Data;
//    }
//
//}
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

    private function checkArgs(string $func, string $args){

    }

    function toBytes()
    {
        return json_encode($this);
    }
    function toString()
    {
        return json_encode($this);
    }
}

//class TransactionDeployPayload implements TransactionPayload
//{
//    const SourceTypeJavaScript = "js";
//    const SourceTypeTypeScript = "ts";
//
//    public $SourceType;
//    public $Source;
//    public $Args;
//
//    function __construct(string $sourceType, string $source, string $args)
//    {
//        $this->checkArgs();
//        $this->SourceType = $sourceType;
//        $this->Source = $source;
//        $this->Args = $args;
//    }
//
//    private function checkArgs(){}
//
//    function toBytes()
//    {
//        return json_encode($this);
//    }
//    function toString()
//    {
//        return json_encode($this);
//    }
//}