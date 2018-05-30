<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 22:55
 */
require ('../vendor/autoload.php');



use Neb\neb\Account;
use Neb\utils\Crypto;
use Neb\neb\Admin;


echo "Hello, neb.php!",PHP_EOL,PHP_EOL;

//restore account from keystore file
$keyJson = '{"version":4,"id":"814745d0-9200-42bd-a4df-557b2d7e1d8b","address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","crypto":{"ciphertext":"fb831107ce71ed9064fca0de8d514d7b2ba0aa03aa4fa6302d09fdfdfad23a18","cipherparams":{"iv":"fb65caf32f4dbb2593e36b02c07b8484"},"cipher":"aes-128-ctr","kdf":"scrypt","kdfparams":{"dklen":32,"salt":"dddc4f9b3e2079b5cc65d82d4f9ecf27da6ec86770cb627a19bc76d094bf9472","n":4096,"r":8,"p":1},"mac":"1a66d8e18d10404440d2762c0d59d0ce9e12a4bbdfc03323736a435a0761ee23","machash":"sha3256"}}';
$acc = new Account();
$acc->fromKey($keyJson,"passphrase");
echo "restored account: ", $acc->getAddressString(), PHP_EOL, PHP_EOL;

//make a new account and get their privateKey/ publicKey/ address
$account = Account::newAccount();
$priv = $account->getPrivateKey();
echo "get private key: " ,$priv, PHP_EOL;
$pub = $account->getPublicKey();
echo "get pbulic key: ", $pub, PHP_EOL;
$address = $account->getAddressString();
echo "get address: " , $address, PHP_EOL, PHP_EOL;

//echo "verify: set priv and get public & address: ", PHP_EOL;
//$priv = "8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93";
//echo "given private key: ", $priv, PHP_EOL;
//$account->setPrivateKey($priv);
//$priv = $account->getPrivateKey();
//echo "check set private key: ", $priv, PHP_EOL, PHP_EOL;

//export account to keystore file
$keystore = $account->toKeyString("passphrase");
echo $keystore, PHP_EOL;

//
echo "restore account from keystore file",PHP_EOL;
$acc = Account::newAccount();
$acc->fromKey($keystore,"passphrase");
echo "restored account: ", $acc->getAddressString(),PHP_EOL;
echo "it should be: ", $account->getAddressString() , PHP_EOL;

/**
 * example:
 * address: n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc
 * privatekey: 8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93
 * publicKey: 5cdb458a302e8e5348072641f3a930a48385a2fe67857d78564211a994c66007ee52f3acb76f9809f95f9561ca48baf18d91fa4ba3a0e104d01d66275e0838fb
 *
 *
 */


