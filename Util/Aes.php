<?php
/**
 * @file \Util\Aes.php
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

/**
 * php Aes加解密类
 */
class Aes {

    //key长度应该为16
    private $key;

    //iv长度应该为16
    private $iv;

    //配合Java端，使用128位，256位java端默认是不支持的
    private $method = 'AES-128-CBC';

    /**
     * 初始化密钥和向量
     *
     * @param string $key 加密密钥.
     * @param string $iv  加密向量.
     * 
     */
    public function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv  = $iv;
    }

    /**
     * AES加密
     * 
     * @param string $clearText 明文数据.
     * 
     */
    public function encrypt($clearText)
    {
        $encrypted = openssl_encrypt($clearText, $this->method, $this->key, OPENSSL_RAW_DATA, $this->iv);

        return base64_encode($encrypted);
    }

    /**
     * AES解密
     *
     * @param string $encryptData base64编码后的加密数据.
     *
     * $key
     */
    public function decrypt($encryptData)
    {
        $encryptData = base64_decode($encryptData);

        $decrypted = openssl_decrypt($encryptData, $this->method, $this->key, OPENSSL_RAW_DATA, $this->iv);

        return trim($decrypted);
    }
}
