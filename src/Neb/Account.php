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
    private $publicKey;     //hex string
    private $address;   //hex string like: "19571b8df1d7065d1f9c36a9dec6d736d252c065b13e39b163d5"

    /**
     * Account constructor.
     * Constructs an account object with given privateKey, or generate a privateKey if the given key is null
     *
     * @param null $privateKey
     */
    function __construct( $privateKey = null) {
        if($privateKey === null)
            $privateKey = Crypto::createPrivateKey();

        $this->privateKey = $privateKey;
        //echo "private key: ", $privateKey, PHP_EOL;
        $this->publicKey = Crypto::privateToPublic($this->privateKey);
        $this->address = $this->addressFromPublicKey();
    }

    /**
     * Make a new account.
     *
     * @return Account object.
     */
    public static function newAccount(){
        $privateKey = Crypto::createPrivateKey();
        return new static($privateKey);
    }

    private function addressFromPublicKey(){
        $publicKey = $this->getPublicKey();
        $content = hash("sha3-256",hex2bin($publicKey));    //hash returns hex string
        $content = hash("ripemd160",hex2bin($content));
        $content = AddressPrefix.NormalType.$content;

        $checksum = hash("sha3-256", hex2bin($content));     //get checksum
        $checksum = substr($checksum,0,8);

        $addressHex = $content.$checksum;   // get address hex string

        //return hex2bin($addressHex);
        return $addressHex;
    }

      private static function _isValidAddress(string $address, $type = null):bool {  //todo isValidAccountAddress & isValidContractAddress & isValidAddress
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

        if($type === null){
            if($addressType!== NormalType && $addressType!== ContractType)
                return false;
        }
        else if($addressType !== $type)
            return false;

        $checksum = hash("sha3-256",substr($addressBin,0,AddressLength-4),true);
        return substr($checksum,0,4) === substr($addressBin,-4);

    }

    /**
     * To check if an address is valid
     * @param string $address
     * @return bool if the given key is valid
     */
    public static function isValidAddress(string $address){
        return Account::_isValidAddress($address);
    }
    public static function isValidAccountAddress(string $address){
        return Account::_isValidAddress($address, NormalType);
    }
    public static function isValidContractAddress(string $address){
        return Account::_isValidAddress($address, ContractType);
    }

    /**
     * Reset the account's private key to a given one, and regenerates it's corresponding public key and address
     *
     * @param $priv
     * @throws \Exception if the privateKey is invalid
     */
    public function setPrivateKey($priv){
        if(is_string($priv) && strlen($priv) == 64){
            $this->privateKey = $priv;
//            unset($this->publicKey);     //to--do : 生成 publicKey & address
//            unset($this->address);
            $this->publicKey = Crypto::privateToPublic($this->privateKey);
            $this->address = $this->addressFromPublicKey();

        }else{
            throw new \Exception("Invalid private key.");
        }
    }

    //the hex string (not bin string)
    public function getPrivateKey(){
        return $this->privateKey;
    }


    public function getPublicKey(){
        return $this->publicKey;
    }

    public function getAddress(){
        return $this->address;
    }

    /**
     * Get account address of Base58 format.
     *
     * To learn the address generation algorithm, please refer to wiki <a href="https://github.com/nebulasio/wiki/blob/master/address.md">Address</a>
     *
     * @link https://github.com/nebulasio/wiki/blob/master/address.md
     * @return string
     * @throws \Exception
     */
    function getAddressString(){
        //$addressHex = $this->getAddress();
        $base58 = new Base58();
        return $base58->encode(hex2bin($this->address));      //input should be an binary string
    }

    /**
     * To generate key-store data, which is an json string
     *
     * @param string $password options for keyStore file
     * @param array $opts options of generating keyStore
     * @return string
     * @throws \Exception
     */
    function toKey(string $password, array $opts = []){ //todo remove opts?? n/p/r
        try{
            $salt = isset($opts['salt']) ? $opts['salt'] : random_bytes(32);
            $iv = isset($opts['iv']) ? $opts['iv'] : random_bytes(16);
        } catch (\Exception $e){
            throw $e;
        }

        $kdf = isset($opts['kdf']) ? $opts['kdf'] : "scrypt";
        $kdfparams = array(
            "dklen" => isset($opts['dklen']) ? $opts['dklen'] : 32,
            'salt' => bin2hex($salt),
        );

        if($kdf === 'pbkdf2'){
            $kdfparams['c'] = isset($opts['c']) ? $opts['c'] : 262144;
            $kdfparams['prf'] = 'hmac-sha256';
            $derivedKey = hash_pbkdf2("sha256", $password, $salt, $kdfparams['c'], $kdfparams['dklen'] * 2, false );
        }else if($kdf = 'scrypt'){
            $kdfparams['n'] = isset($opts['n']) ? $opts['n'] : 4096;
            $kdfparams['r'] = isset($opts['r']) ? $opts['r'] : 8;
            $kdfparams['p'] = isset($opts['p']) ? $opts['p'] : 1;
            $derivedKey =  Crypto::getScrypt($password, $salt , $kdfparams['n'],$kdfparams['r'],$kdfparams['p'],$kdfparams['dklen']);

        }else{
            throw new \Exception('Unsupported kdf');
        }

        $derivedKeyBin = hex2bin($derivedKey); //$derivedKey is a hex string
        $method = 'aes-128-ctr';
        $ciphertext = openssl_encrypt(hex2bin($this->getPrivateKey()), $method, substr($derivedKeyBin,0,16),$options=1 , $iv); //binary strinig

        $mac = hash("sha3-256", substr($derivedKeyBin,16,32) . $ciphertext . $iv . $method);

        try{
            $uuid = Crypto::guidv4(random_bytes(16));
        }catch (\Exception $e){
            throw $e;
        }

        $json = array(
            "version" => KeyCurrentVersion,
            "id" => $uuid,
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
        return json_encode($json);
    }

    /**
     * Restore account from key-store json string.
     *
     * @param $input the key-store string
     * @param string $password the password for this key-store json
     * @return Account  the restored account
     * @throws \Exception
     */
    static function fromKey($input, string $password){
        $json = json_decode($input);

        if($json->version !== KeyVersion3 && $json->version !== KeyCurrentVersion)
            throw new \Exception('Not supported wallet version');


        if($json->crypto->kdf === 'scrypt'){
            $kdfparams = $json->crypto->kdfparams;
            $derivedKey =  Crypto::getScrypt($password, hex2bin($kdfparams->salt) , $kdfparams->n,$kdfparams->r,$kdfparams->p,$kdfparams->dklen); //hex string

        }else if($json->crypto->kdf === 'pbkdf2'){
            $kdfparams = $json->crypto->kdfparams;
            $derivedKey = hash_pbkdf2("sha256", $password, hex2bin($kdfparams->salt), $kdfparams->c, $kdfparams-> dklen * 2, false );
        }else{
            throw new \Exception('Unsupported key derivation scheme');
        }

        $derivedKeyBin = hex2bin($derivedKey);
        $ciphertext = hex2bin($json->crypto->ciphertext);
        $method = $json->crypto->cipher;
        $iv = hex2bin($json->crypto->cipherparams->iv);

        if($json->version === KeyCurrentVersion){
            $mac = hash('sha3-256', substr($derivedKeyBin,16,32) . $ciphertext . $iv . $method );
        }else{
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
        //$this->setPrivateKey(bin2hex($seed));

        return new static(bin2hex($seed));

    }

}