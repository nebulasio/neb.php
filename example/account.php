<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 22:55
 */
require ('../vendor/autoload.php');



use Neb\Neb\Account;
use Neb\Utils\Crypto;
use Neb\Neb\Admin;


echo "Hello, neb.php!",PHP_EOL,PHP_EOL;

$account = Account::newAccount();
$priv = $account->getPrivateKeyString();
echo "get private key: " ,$priv, PHP_EOL;

$pub = $account->getPublicKey();
echo "get pbulic key: ", $pub, PHP_EOL, PHP_EOL;

$address = $account->getAddressString();
echo "get address: " , $address, PHP_EOL, PHP_EOL;

echo "verify: set priv and get public & address: ", PHP_EOL;
$priv = "8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93";
echo "given private key: ", $priv, PHP_EOL;
echo "given private key length: ", strlen($priv), PHP_EOL;
$account->setPrivateKey($priv);
$priv = $account->getPrivateKey();
echo "check set private key: ", $priv, PHP_EOL;
$public = $account->getPublicKey();
echo "Public key: ", $public, PHP_EOL;
$address = $account->getAddressString();
echo "account address: " , $address, PHP_EOL;

/**
 * example:
 * address: n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc
 * privatekey: 8d464aeeca0281523fda55da220f1257219052b1450849fea6b23695ee6b3f93
 * publicKey: 5cdb458a302e8e5348072641f3a930a48385a2fe67857d78564211a994c66007ee52f3acb76f9809f95f9561ca48baf18d91fa4ba3a0e104d01d66275e0838fb
 *
 *
 */

/*
{
    "version": 4,
	"id": "e38cf1c0-4450-43fd-8dfe-9face081ad62",
	"address": "n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc",
	"crypto": {
    "ciphertext": "f351f2f6d205ac4d608ade0440eed4208712352970c478143e9cc99da4a09ede",
		"cipherparams": {
        "iv": "2e4fe06209dbe7a7e4f14d13525f0260"
		},
		"cipher": "aes-128-ctr",
		"kdf": "scrypt",
		"kdfparams": {
        "dklen": 32,
			"salt": "a4f022a370892ae3ca417a66d655f34a31e00c5dbdbe44fbda63e222580b688b",
			"n": 4096,
			"r": 8,
			"p": 1
		},
		"mac": "413a7f984ef3dca7885ca185346ee4f94d25e40cf0d340b461a354b60eed730b",
		"machash": "sha3256"
	}
}
*/

