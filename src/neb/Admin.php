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

    function __construct(Neb $neb)
    {
        $this->setRequest($neb->request);
    }

    public function setRequest( Httprequest $request){
        $this->request = $request;
        $this->path = "/";
    }

    public static  function hello(){
        echo "hello from Admin.php";
    }
}