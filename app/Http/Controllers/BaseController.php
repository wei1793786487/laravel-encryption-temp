<?php


namespace App\Http\Controllers;


use App\Utils\Rsa;
use App\Utils\AES;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Utils;

class BaseController extends Controller
{
    function apidata($data, $msg, $code, Request $request)
    {

        $rsa = new Rsa();
        $content = [
            "data" => $data,
            "msg" => $msg,
            "code" => $code,
        ];
        $key = $request->input("key");
        if ($key) {
            return base64_encode($rsa->aesEncrypt(json_encode($content), $key));
        } else {
            return response($content);
        }
    }

}
