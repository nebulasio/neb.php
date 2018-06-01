<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/30
 * Time: 11:42
 */

namespace Nebulas\Core;

use Nebulas\Utils\Utils;

define("MaxDeployPayLoadLength", 128 * 1024);

class TransactionDeployPayload implements TransactionPayload
{
    const SourceTypeJavaScript = "js";
    const SourceTypeTypeScript = "ts";

    public $SourceType;
    public $Source;
    public $Args;

    function __construct(string $sourceType, string $source, string $args)
    {
        $this->SourceType = $sourceType;
        $this->Source = $source;
        $this->Args = $args;
        $this->checkArgs($sourceType, $source, $args);
    }

    public function checkArgs(string $sourceType, string $source, string $args)
    {
        if(strlen($this->toBytes()) > MaxDeployPayLoadLength){
            throw new \Exception("Payload length exceeds max length: ", MaxDeployPayLoadLength);
        }

        if (empty($source)) {
            throw new \Exception("Invalid source of deploy payload");
        }

        if (! $sourceType === TransactionDeployPayload::SourceTypeJavaScript &&
            ! $sourceType === TransactionDeployPayload::SourceTypeTypeScript ) {
            throw new \Exception("Invalid source type of deploy payload");
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