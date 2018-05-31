<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:07
 */

namespace Neb\Neb;


class Admin
{

    private $request;
    private $path;
    private $apiVersion;

    function __construct(Neb $neb, $apiVersion)
    {
        $this->setRequest($neb->request);
        $this->apiVersion = $apiVersion;
    }

    public function setRequest( $request){
        $this->request = $request;
        $this->path = "/admin";
    }

    public static  function hello(){
        echo "hello from Admin.php";
    }
}