<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:07
 */

namespace Nebulas\Rpc;


class Admin
{

    private $provider;
    private $path;
    private $apiVersion;

    function __construct(Neb $neb, $apiVersion)
    {
        $this->serProvider($neb->provider);
        $this->apiVersion = $apiVersion;
    }

    public function serProvider($provider){
        $this->provider = $provider;
        $this->path = "/admin";
    }

    public static  function hello(){
        echo "hello from Admin.php";
    }
}