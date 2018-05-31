<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/30
 * Time: 11:42
 */

namespace Nebulas\Core;


class TransactionDeployPayload implements TransactionPayload
{
    const SourceTypeJavaScript = "js";
    const SourceTypeTypeScript = "ts";

    public $SourceType;
    public $Source;
    public $Args;

    function __construct(string $sourceType, string $source, string $args)
    {
        $this->checkArgs();
        $this->SourceType = $sourceType;
        $this->Source = $source;
        $this->Args = $args;
    }

    private function checkArgs(){}

    function toBytes()
    {
        return json_encode($this);
    }

}