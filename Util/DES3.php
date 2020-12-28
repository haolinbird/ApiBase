<?php
namespace Util;
/**
 * DES3 加解密
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
class DES3
{
    /**
     * 获取密文.
     *
     * @param string $data 明文.
     * @param string $key  密钥.
     *
     * @return string
     */
    static public function encrypt($data, $key) {
        $data = str_pad($data, 16, chr(0));
        $php_v = substr(PHP_VERSION, 0, 3);
        if ( $php_v >= '5.6') {
            $key = strlen($key) > 24 ? substr($key, 0, 24) : str_pad($key, 24, "\0");
        }
        // $iv_size = @mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        // $iv = @mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted = @mcrypt_encrypt(MCRYPT_3DES, $key, $data, MCRYPT_MODE_ECB);
        list(, $hex) = unpack("H32", $encrypted);
        return $hex;
    }

    /**
     * 解密.
     *
     * @param string $data 密文.
     * @param string $key  密钥.
     *
     * @return string
     */
    static public function decrypt($data, $key) {
        $php_v = substr(PHP_VERSION, 0, 3);
        if ( $php_v >= '5.6') {
            $key = strlen($key) > 24 ? substr($key, 0, 24) : str_pad($key, 24, "\0");
        }
        $encrypted = pack("H32", $data);
        // $iv_size = @mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        // $iv = substr($encrypted, 0, $iv_size);
        // $encrypted = substr($encrypted, $iv_size);
        $decrypted = @mcrypt_decrypt(MCRYPT_3DES, $key, $encrypted, MCRYPT_MODE_ECB);
        return rtrim($decrypted);
    }
}