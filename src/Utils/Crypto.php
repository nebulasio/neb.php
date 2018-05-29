<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/9
 * Time: 20:26
 */

namespace Neb\Utils;

use Phactor\Key;
use Mdanter\Ecc\EccFactory;
use Elliptic\EC;
use StephenHill\Base58;

class Crypto
{
    public const SECP256K1 = 1;
    public const SCRYPT = 1 << 4;

    function __construct()
    {
        //
    }

    public static function RandomBytes(int $length)
    {
        return random_bytes($length);
    }

    public static function sha3($data)
    {
        return hash("sha256", $data);
    }

    public static function ripemd160($data)
    {
        return hash("ripemd160", $data);
    }

    public static function createPrivateKey()
    {
        $ec = new EC('secp256k1');
        $keyPair = $ec->genKeyPair();
        return $keyPair->getPrivate()->toString(16);
    }

    public static function privateToPublic($priv_hex)
    {
        $ec = new EC('secp256k1');
        $privKey = $ec->keyFromPrivate($priv_hex);
        $pub_hex = $privKey->getPublic("hex");
        //echo "returned public key is : ", $pub_hex, PHP_EOL;
        return $pub_hex;
    }

    public static function sign($hash, $privKey){
        $ec = new EC('secp256k1');
        $key = $ec->keyFromPrivate($privKey);
        $sign = $key->sign($hash);
        //print_r($sign);
        $sign_R = $sign->r->tostring(16);
        $sign_S = $sign->s->tostring(16);

        return hex2bin($sign_R) . hex2bin($sign_S) . chr($sign->recoveryParam);
    }

    public static function isValidPublic($pub_hex)
    {

    }

    //$data is a random, such as openssl_random_pseudo_bytes(16)
    public static function  guidv4($data)
    {
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }



}