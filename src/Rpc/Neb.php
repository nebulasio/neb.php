<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/28
 * Time: 11:46
 */

namespace Nebulas\Rpc;

use Nebulas\Rpc\Api;
use Nebulas\Rpc\Admin;
use Nebulas\Rpc\HttpProvider;

class Neb
{
    public $api;
    public $admin;
    public $provider;

    const MainNet = "https://mainnet.nebulas.io";
    const TestNet = "https://testnet.nebulas.io";
    /**
     * Neb constructor.
     *
     * @param \Nebulas\Rpc\HttpProvider|null $request
     * @param string $apiVersion  the nebulas api version,
     *
     */
    public function __construct(HttpProvider $request = null, $apiVersion = "v1")
    {

        $this->provider = $request;

        $this->api = new Api($this, $apiVersion);
        $this->admin = new Admin($this, $apiVersion);
    }

    public function setProvider(HttpProvider $provider){
        $this->provider = $provider;
        $this->api->setProvidr($provider);
        $this->admin->serProvider($provider);
    }

}