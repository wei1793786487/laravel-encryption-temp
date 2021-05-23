<?php


namespace App\Utils;


use PHPUnit\Util\Filesystem;

class Rsa
{
    public $privateKey = '';

    public $publicKey = '';

    public function __construct()
    {
        $ARS_PATH = $_ENV["ARS_PATH"];
        $PRI_PATH = $ARS_PATH . "/ras.pri";
        $PUB_PATH = $ARS_PATH . "/ras.pub";

        $pri_exists = file_exists($PRI_PATH);
        $pub_exists = file_exists($PUB_PATH);


        //如果私钥与公钥都不存在那么创建文件并生成
        if (!($pri_exists||$pub_exists)){
            $pri_file = fopen($PRI_PATH, "w");
            $pub_file = fopen($PUB_PATH, "w");
            $resource = openssl_pkey_new();
            openssl_pkey_export($resource, $this->privateKey);
            $detail = openssl_pkey_get_details($resource);
            $this->publicKey = $detail['key'];
            //写入私钥
            fwrite($pri_file,$this->privateKey);
            //写入公钥
            fwrite($pub_file,$this->publicKey);
            fclose($pri_file);
            fclose($pub_file);

        }else{
            $pri_file = fopen($PRI_PATH, "r");
            $pub_file = fopen($PUB_PATH, "r");
           $this->privateKey=fread($pri_file, filesize ( $PRI_PATH ));
           $this->publicKey=fread($pub_file, filesize ( $PUB_PATH ));
        }
    }

    public function publicEncrypt($data, $publicKey)
    {
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return $encrypted;
    }

    public function publicDecrypt($data, $publicKey)
    {
        openssl_public_decrypt($data, $decrypted, $publicKey);
        return $decrypted;
    }

    public function privateEncrypt($data, $privateKey)
    {
        openssl_private_encrypt($data, $encrypted, $privateKey);
        return $encrypted;
    }

    /**
     * @param $data
     * @param false|string $privateKey
     * @return mixed
     */
    public function privateDecrypt($data, $privateKey)
    {
        if (!$privateKey){
            $privateKey= $this->privateKey;
        }
        //这里需要base64解密
        openssl_private_decrypt(base64_decode($data), $decrypted, $privateKey);
        return $decrypted;
    }
    /**
     * AES解密
     * @param $content
     * @param $key
     * @return false|string
     */
    function aesDecrypt($content, $key)
    {
        return openssl_decrypt($content, 'AES-256-CBC', $key, OPENSSL_ZERO_PADDING, 'SUNWEIQSDIEBYWVJ');
    }
    /**
     * AES加密
     * @param $content
     * @param $key
     * @return false|string
     */
    function aesEncrypt($content, $key)
    {
        return openssl_encrypt($content, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, 'SUNWEIQSDIEBYWVJ');
    }
}
