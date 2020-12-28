<?php
/**
 * Curl.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

/**
 * Curl.
 */
class Curl
{
    /**
     * 提交数据Post方式.
     *
     * @param string $url    Url.
     * @param mixed  $data   Post 数据.
     * @param array  $header Header.
     *
     * @return mixed
     */
    public static function post($url, $data, $header = array())
    {
        $fieldsString = '';
        foreach ($data as $key => $value) {
            $fieldsString .= $key . '=' . urlencode($value) . "&";
        }
        $fieldsString = rtrim($fieldsString, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($header) {
            foreach ($header as $name => $val) {
                $header[$name] = $name . ':' . $val;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_values($header));
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
        $resp = curl_exec($ch);
        curl_close($ch);

        return $resp;
    }

    /**
     * Post Json Data.
     *
     * @param string $url    Url.
     * @param string $data   Json 数据.
     * @param array  $header Header.
     *
     * @return mixed
     */
    public static function postJsonData($url, $data, $header = array(), $timeOut = 3)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($header) {
            foreach ($header as $name => $val) {
                $header[$name] = $name . ':' . $val;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_values($header));
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        $resp = curl_exec($ch);
        curl_close($ch);
        return $resp;
    }
}
