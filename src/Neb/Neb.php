<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/28
 * Time: 11:46
 */

namespace Neb\neb;

use Neb\Neb\Api;
use Neb\Neb\Admin;
use Neb\Neb\Httprequest;

class Neb
{
    public $api;
    public $admin;
    public $request;

    public function __construct(Httprequest ...$request)
    {
        if(sizeof($request) >= 1)
            $request = $request[0];
        else
            $request = new Httprequest();

        $this->request = $request;

        $this->api = new Api($this);
        $this->admin = new Admin($this);
    }

    public function setRequest(Httprequest $request){
        $this->request = $request;
        $this->api->setRequest($request);
        $this->admin->setRequest($request);
    }

}