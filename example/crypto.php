<?php
/**
 * Created by PhpStorm.
 * User: yupnano
 * Date: 2018/5/22
 * Time: 21:07
 */
require ('../vendor/autoload.php');

use Nebulas\Utils\Crypto;


$hash = "bd1d8e4fc93a2bdf4b0c93c9cacf8a908f9abe65afb82f0ed4bb08ed87662a21";
$privKey = "6c41a31b4e689e1441c930ce4c34b74cc037bd5e68bbd6878adb2facf62aa7f3";

$signBin = Crypto::sign(($hash),$privKey); //verify spec256k1 sign

echo "get tx sign length: ", strlen($signBin) , PHP_EOL;
echo "get tx   sign: ", bin2hex($signBin) , PHP_EOL;

echo "expected sign: ", "2aec7ad2a986a973cf0c4402984bc3009fff5651fc29bf8463ba4e5872954dc0785599558def2d88fcff2f993da08e46cd22d41b812f7de61afaa9bbe482743d00" , PHP_EOL;



