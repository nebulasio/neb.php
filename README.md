# neb.php

neb.php is the Nebulas compatible PHP API. 
Users can sign/send transactions and deploy/call smart contract with it.

## Requirements
neb.php requires the following:

- PHP 7.1 or higher
- ext/gmp 
- ext/curl
- ext/scrypt, [https://github.com/DomBlack/php-scrypt](https://github.com/DomBlack/php-scrypt)

## Installation

You can install this library via Composer:
```sh
composer require nebulas/neb.php:dev-master
```

Or add this in your composer.json:
```jsom
"require": {
    "nebulas/neb.php": "dev-master"
}
```


## Usage

please refer to [examples](/example) to learn how to use neb.php.

#### Account 

```php
use Nebulas\Core\Account;

$account = Account::newAccount();
//$account = new Account();     //This is the same as above
$addr = $account->getAddressString(); //such as "n1HUbJZ45Ra5jrRqWvfVaRMiBMB3CACGhqc"  
$keyStore = $account->toKey();  //Please save your keyStore(json) in to file and keep it safe

```

#### API

```php
use Nebulas\Rpc\HttpProvider;
use Nebulas\Rpc\Neb;

$neb = new Neb(new HttpProvider("https://testnet.nebulas.io"));

$api = $neb->api;
echo $api->getAccountState("n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6"), PHP_EOL;;
echo $api->getTransactionReceipt("8b98a5e4a27d2744a6295fe71e4f138d3e423ced11c81e201c12ac8379226ad1"), PHP_EOL;
```

#### Transaction

```php
use Nebulas\Rpc\Neb;
use Nebulas\Rpc\HttpProvider;
use Nebulas\Core\Account;
use Nebulas\Core\Transaction;
use Nebulas\Core\TransactionBinaryPayload;
use Nebulas\Core\TransactionCallPayload;

$neb = new Neb();
$neb->setProvider(new HttpProvider("https://testnet.nebulas.io"));

$keyJson = '{"version":4,"id":"814745d0-9200-42bd-a4df-557b2d7e1d8b","address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","crypto":{"ciphertext":"fb831107ce71ed9064fca0de8d514d7b2ba0aa03aa4fa6302d09fdfdfad23a18","cipherparams":{"iv":"fb65caf32f4dbb2593e36b02c07b8484"},"cipher":"aes-128-ctr","kdf":"scrypt","kdfparams":{"dklen":32,"salt":"dddc4f9b3e2079b5cc65d82d4f9ecf27da6ec86770cb627a19bc76d094bf9472","n":4096,"r":8,"p":1},"mac":"1a66d8e18d10404440d2762c0d59d0ce9e12a4bbdfc03323736a435a0761ee23","machash":"sha3256"}}';
$password = 'passphrase';

$from = Account:: fromKey($keyJson, $password);

//get nonce value
$resp = $neb->api->getAccountState($fromAddr);
$respObj = json_decode($resp);
$nonce = $respObj->result->nonce;

//make new transaction
$chainId = 1001;
$to = "n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6";
$tx = new Transaction($chainId, $from, $to, $value = "0", $nonce + 1 );
$tx->hashTransaction();
$tx->signTransaction();

//send transaction
$result = $neb->api->sendRawTransaction($tx->toProtoString());

```

## Join in!

We are happy to receive bug reports, fixes, documentation enhancements, and other improvements.

Please report bugs via the [github issue](https://github.com/nebulasio/neb.php/issues).





