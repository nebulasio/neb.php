<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/24
 * Time: 22:27
 */

namespace Nebulas\Core;


interface TransactionPayload
{
    public function toBytes();
    //public function toString();
}