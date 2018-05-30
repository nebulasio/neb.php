<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/28
 * Time: 23:34
 */

require ('../vendor/autoload.php');

use Neb\Neb\Httprequest;
use Neb\Neb\Neb;
use Neb\Neb\Api;
use Neb\Neb\Account;


$priv = "6c41a31b4e689e1441c930ce4c34b74cc037bd5e68bbd6878adb2facf62aa7f3";
$address = "n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6";

$acc = new Account($priv);

$accAddr = $acc->getAddressString();
echo "account address: ", $accAddr, PHP_EOL;

//$acc->toKey("passphrase");

echo $json = $acc->toKeyString("passphrase"),PHP_EOL;

$acc->fromKey($json,"passphrase");
echo $acc->getAddressString();



//print_r(openssl_get_cipher_methods());

//$neb = new Neb();
//$neb->setRequest(new Httprequest());

//echo $neb->api->getTransactionReceipt("ff9bdd621ccec49d41cf53ca59100d88e670a78e41065f88b80fc618530bb8b1"), PHP_EOL;
//echo $neb->api->getTransactionReceipt("e3c893fc566393e923dd7e2d9e4abcb35ca1f6fdb56a9951385bf82a16e3c9ea"), PHP_EOL;
//echo $resp = $neb->api->getEventsByHash("e3c893fc566393e923dd7e2d9e4abcb35ca1f6fdb56a9951385bf82a16e3c9ea");
//$data = "test data";

//print_r(get_loaded_extensions ());



//scrypt();
//echo hash("sha3-256",$data),PHP_EOL;
//echo hash("sha256",$data),PHP_EOL;
//
//$hash = hash("sha256",$data);
//echo $hash, PHP_EOL;
//echo "length of hash: " , strlen($hash), PHP_EOL;
//$hash_raw = hash("sha256",$data,true);
//echo $hash_raw,PHP_EOL;
//echo "length of hash_raw: " , strlen($hash_raw), PHP_EOL;
//
//for($a=0; $a<strlen($hash_raw); $a++){
//    echo dechex(ord( $hash_raw[$a] )),','; //每一都位打印出来
//}
//echo PHP_EOL;
//
//echo bin2hex($hash_raw), PHP_EOL;
//
//print_r(unpack('C*', 'hello'));
//print_r(unpack('H*', 'hello'));
//print_r(bin2hex('hello'));
//
//echo PHP_EOL,"bignumber",PHP_EOL;
//
//$bignum = gmp_init(10000);
//echo $bignum,PHP_EOL;
//$bignumString = gmp_export($bignum);
//echo $bignumString, PHP_EOL;
//echo bin2hex($bignumString), PHP_EOL;