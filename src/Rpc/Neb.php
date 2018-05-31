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
use Nebulas\Rpc\Httprequest;

class Neb
{
    public $api;
    public $admin;
    public $request;

    /**
     * Neb constructor.
     *
     * @param \Nebulas\Rpc\Httprequest|null $request
     * @param string $apiVersion  the nebulas api version,
     *
     */
    public function __construct(Httprequest $request = null, $apiVersion = "v1")
    {

        $this->request = $request;

        $this->api = new Api($this, $apiVersion);
        $this->admin = new Admin($this, $apiVersion);
    }

    public function setRequest(Httprequest $request){
        $this->request = $request;
        $this->api->setRequest($request);
        $this->admin->setRequest($request);
    }

}