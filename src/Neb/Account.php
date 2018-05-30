<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/9
 * Time: 17:35
 */

namespace Neb\Neb;

use Neb\Utils\Crypto;
use StephenHill\Base58;

define("AddressPrefix","19");
define("NormalType",'57');
define("ContractType",'58');
define("AddressStringLength", 35);
define("AddressLength", 26);

define("KeyCurrentVersion", 4);
define("KeyVersion3", 3);

class Account
{
    private $privateKey;    //hex string like : "36852c5e0aff2942c72d7662ced156eebefb1516a1c5986625f9c2f4eb119fdc"
    private $publicKey;
    private $address;   //binary string like
    private $path;


    function __construct( $privateKey = null, $path="") {
        if($privateKey === null)
            $privateKey = Crypto::createPrivateKey();

        $this->privateKey = $privateKey;
        $this->path = $path;
    }

    public static function newAccount(){
        //return __construct(Crypto::RandomBytes(32));
        $privateKey = Crypto::createPrivateKey();
        return new static($privateKey);
    }

    public static function isValidAddress(string $address, $type = null):bool {
        if(!is_string($address) || strlen($address) !== AddressStringLength)
            return false;

        $base58 = new Base58();
        $addressBin = $base58->decode($address);
        if(strlen($addressBin) !== AddressLength)
            return false;

        $addressHex = bin2hex($addressBin);
        if(substr($addressHex,0,2) !== AddressPrefix)
            return false;
        $addressType = substr($addressHex,2,2);
        if($addressType!== NormalType && $addressType!== ContractType)
            return false;

        $checksum = hash("sha3-256",substr($addressBin,0,AddressLength-4),true);
        return substr($checksum,0,4) === substr($addressBin,-4);

    }

    public static function fromAddress($address){
        if($address instanceof Account){
            //echo "fromAddress:: Account obj address: ", $address->getAddressString(), PHP_EOL;
            return $address;
        }

        $acc = new static();
        if(self::isValidAddress($address)){
            //echo "fromAddress:: address string for base58_decode: " , $address,PHP_EOL;
            $base58 = new Base58();
            $acc->address = $base58->decode($address);;
            return $acc;
        }

        throw new \Exception("invalid address");
    }

    public function setPrivateKey($priv){
        if(is_string($priv) && strlen($priv) == 64){
            $this->privateKey = $priv;
            unset($this->publicKey);
            unset($this->address);

        }else{
            echo "invalid private key", PHP_EOL;
        }
    }

    //the hex string (not bin string)
    public function getPrivateKey(){
        return $this->privateKey;
    }

//    public function getPrivateKeyString(){
//        return $this->privateKey;
//    }

    public function getPublicKey(){
        if(!isset($this->publicKey)){
            $this->publicKey = Crypto::privateToPublic($this->privateKey);
        }
        return $this->publicKey;
    }

//    public function getPublicKeyString(){
//        return $this->getPublicKey();
//    }

    //binary string,
    public function getAddress(){
        if(!isset($this->address)){
            if(!isset($this->privateKey) && !isset($this->publicKey))
                throw new \Exception("Account has no private key, cannot generate address");
            $publicKey = $this->getPublicKey();
            $content = hash("sha3-256",hex2bin($publicKey));    //hash returns hex string
            $content = hash("ripemd160",hex2bin($content));
            $content = AddressPrefix.NormalType.$content;

            $checksum = hash("sha3-256", hex2bin($content));
            $checksum = substr($checksum,0,8);

            $addressHex = $content.$checksum;
//            $base58 = new Base58();
//            $address = $base58->encode(hex2bin($addressHex));
//            $this->address = $address;
            $this->address = hex2bin($addressHex);
        }
        return $this->address;
    }

    function getAddressString(){
        $addressHex = $this->getAddress();
        $base58 = new Base58();
        return $base58->encode($addressHex);
    }

    function toKey(string $password, array $opts = []){

        $salt = isset($opts['salt']) ? $opts['salt'] : random_bytes(32);
        $iv = isset($opts['iv']) ? $opts['iv'] : random_bytes(16);

        $kdf = isset($opts['kdf']) ? $opts['kdf'] : "scrypt";
        $kdfparams = array(
            "dklen" => isset($opts['dklen']) ? $opts['dklen'] : 32,
            'salt' => bin2hex($salt),
        );

        if($kdf === 'pbkdf2'){
            $kdfparams['c'] = isset($opts['c']) ? $opts['c'] : 262144;
            $kdfparams['prf'] = 'hmac-sha256';
            $derivedKey = '';
        }else if($kdf = 'scrypt'){
            $kdfparams['n'] = isset($opts['n']) ? $opts['n'] : 4096;
            $kdfparams['r'] = isset($opts['r']) ? $opts['r'] : 8;
            $kdfparams['p'] = isset($opts['p']) ? $opts['p'] : 1;
            $derivedKey =  Crypto::getScrypt($password, $salt , $kdfparams['n'],$kdfparams['r'],$kdfparams['p'],$kdfparams['dklen']);

        }else{
            throw new \Exception('Unsupported kdf');
        }

//        echo "salt: ", bin2hex($salt),PHP_EOL;
//        echo "kdfparams: ", json_encode($kdfparams),PHP_EOL;
//        echo  "derivedKey: ", ($derivedKey),PHP_EOL;

        $derivedKeyBin = hex2bin($derivedKey); //$derivedKey is a hex string
        $method = isset($opts['cipher']) ? $opts['cipher'] : 'aes-128-ctr';
        $ciphertext = openssl_encrypt(hex2bin($this->getPrivateKey()), $method, substr($derivedKeyBin,0,16),$options=1 , $iv); //binary strinig

//        echo "ciphertext: ", bin2hex($ciphertext), PHP_EOL;
//        echo "uuid: ", Crypto::guidv4(random_bytes(16)), PHP_EOL;

        $mac = hash("sha3-256", substr($derivedKeyBin,16,32) . $ciphertext . $iv . $method);

        return array(
            "version" => KeyCurrentVersion,
            "id" => Crypto::guidv4(random_bytes(16)),
            "address" => $this->getAddressString(),
            'crypto' => array(
                'ciphertext' => bin2hex($ciphertext),
                'cipherparams' => array(
                    'iv' => bin2hex($iv),
                ),
                'cipher' => $method,
                'kdf' => $kdf,
                'kdfparams' => $kdfparams,
                'mac' => $mac,
                'machash' => 'sha3256'

            ),
        );

    }

    function toKeyString($password, array $opts = []){
        return json_encode($this->toKey($password, $opts));
    }

    function fromKey($input, string $password){
        $json = json_decode($input);
        //print_r($json);
        if($json->version !== KeyVersion3 && $json->version !== KeyCurrentVersion)
            throw new \Exception('Not supported wallet version');


        if($json->crypto->kdf === 'scrypt'){
            $kdfparams = $json->crypto->kdfparams;
            $derivedKey =  Crypto::getScrypt($password, hex2bin($kdfparams->salt) , $kdfparams->n,$kdfparams->r,$kdfparams->p,$kdfparams->dklen); //hex string

        }else if($json->crypto->kdf === 'pbkdf2'){
            //echo "currently not supported!";        //todo
            $derivedKey = '';
        }else{
            throw new \Exception('Unsupported key derivation scheme');
        }

        $derivedKeyBin = hex2bin($derivedKey);
        $ciphertext = hex2bin($json->crypto->ciphertext);
        //echo "ciphertext: ", bin2hex($ciphertext), PHP_EOL;
        $method = $json->crypto->cipher;
        $iv = hex2bin($json->crypto->cipherparams->iv);

        if($json->version === KeyCurrentVersion){
            $mac = hash('sha3-256', substr($derivedKeyBin,16,32) . $ciphertext . $iv . $method );
            //echo "mac: ", $mac, PHP_EOL;
        }else{
            //echo "version 3 ", PHP_EOL;
            $mac = hash('sha3-256', substr($derivedKeyBin,16,32) . $ciphertext);
        }

        if($mac !== $json->crypto->mac){
            throw new \Exception('Key derivation failed - possibly wrong passphrase');
        }

        $seed = openssl_decrypt($ciphertext, $method, substr($derivedKeyBin,0,16), $options=1, $iv);

        if(strlen($seed) < 32){
            $string = hex2bin("00000000"."00000000"."00000000"."00000000"."00000000"."00000000"."00000000"."00000000").$seed;
            $seed = substr($string,-32);
        }

        //echo "seed: ", bin2hex($seed) ,PHP_EOL;

        $this->setPrivateKey(bin2hex($seed));
        return $this;

    }

}