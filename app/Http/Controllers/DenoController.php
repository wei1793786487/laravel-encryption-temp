<?php

namespace App\Http\Controllers;

use App\Utils\Rsa;
use Illuminate\Http\Request;

class DenoController extends BaseController
{
    //
    public function index(Request $request)
    {
//    print_r($request->input("key"));
//    print_r($request);
     return $this->apidata("helow","dadad",200,$request);
    }
}
