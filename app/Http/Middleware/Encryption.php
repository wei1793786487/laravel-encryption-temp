<?php

namespace App\Http\Middleware;

use App\Utils\Rsa;
use Closure;
use Illuminate\Http\Request;

//解密中间件
class Encryption
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//      经过这个过滤器就会生成ras
        $rsa = new Rsa();
        if ($request->is("auth")) {
//            校验的无需经过次加密
            return $next($request);
        } else {
//            获取公钥加密后的key
            $key = $request->header('key');
            $content = $request->getContent();
            $privateDecrypt = $rsa->privateDecrypt($key, null);
            $data = $rsa->aesDecrypt($content, $privateDecrypt);
            $contentArr = json_decode(trim($data), true);
//            替换参数
            $request->replace($contentArr);
            //将解密出来的aes秘钥放入请求,为的是返回数据的时候加密
            $request['key'] = $privateDecrypt;
            //todo 异常处理,判断time，判断是否登录
            return $next($request);
        }
    }
}
