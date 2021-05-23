<?php

namespace App\Http\Controllers;

use App\Utils\Rsa;
use Illuminate\Http\Request;

class SecurityController extends BaseController
{
    //
    public function getPublickey(Request $request)
    {
        $ARS_PATH = $_ENV["ARS_PATH"];
        $PUB_PATH = $ARS_PATH . "/ras.pub";
        $pub_file = fopen($PUB_PATH, "r");
        $publickey = fread($pub_file, filesize($PUB_PATH));
        return $this->apidata($publickey,"获取成功","200",$request);
    }
}
