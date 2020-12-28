<?php
/**
 * 字符串处理基类.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
namespace Util;

class Strings
{
    /**
     * 自定定义多进制字符串
     * @var string
     */
    const DIGIT_STR = 'PAS2DFG3HJKZ8XCVB6NM459QWERT7YU';

    /**
     * 检查参数是否为正整数.
     *
     * @param string|integer $num 要检查的数字.
     *
     * @return boolean true = 是正整数, false = 不是正整数.
     */
    public static function isPositiveInteger($num)
    {
        return ctype_digit((string)$num) && $num;
    }

    /**
     * 对用户姓名进行遮罩.
     *
     * @param stirng $name 姓名.
     *
     * @return string
     */
    public static function maskName($name)
    {
        return !empty($name) ? '*' . mb_substr($name, 1, mb_strlen($name), 'UTF-8') : $name;
    }

    /**
     * 对身份证进行遮罩.
     *
     * @param stirng $idCard 姓名.
     *
     * @return string
     */
    public static function maskIdCard($idCard)
    {
        return !empty($idCard) ? substr($idCard, 0, 3) . str_pad('', strlen($idCard) - 6, '*') . substr($idCard, -3) : $idCard;
    }

    /**
     * 对车牌号进行遮罩.
     *
     * @param stirng $plateNumber 车牌号.
     *
     * @return string
     */
    public static function maskPlateNumber($plateNumber)
    {
        return !empty($plateNumber) ? mb_substr($plateNumber, 0, 3, 'UTF-8') . '***' . mb_substr($plateNumber, -2, 2, 'UTF-8') : $plateNumber;
    }

    /**
     * 对手机号进行遮罩.
     *
     * @param stirng $mobile 手机号.
     *
     * @return string
     */
    public static function maskMobile($mobile)
    {
        return !empty($mobile) ? substr($mobile, 0, 3) . "****" . substr($mobile, -4):  '';
    }

    /**
     * 生成随机字符串.
     *
     * @param integer $length 长度.
     *
     * @return string
     */
    public static function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取APP的页面路径.
     *
     * @param string       $page   配置的页面标识.
     * @param string|array $params 页面需要的参数,数组或字符.
     *
     * @return string APP的页面路径.
     * @throws \Exception 配置找不到页面标识.
     */
    public static function schemeUrl($page, $params = [])
    {
        if (!isset(\Config\Common::$appJumpUrl[$page])) {
            throw new \Exception("scheme [{$page}] is undefined");
        }
        $scheme = \Config\Common::$appJumpUrl[$page];
        if (!empty($params)) {
            $scheme .= '?' . http_build_query($params);
        }
        return $scheme;
    }

    /**
     *
     * @param integer $num 十进制数字
     * @param string  $str 自定义的多进制字符串
     * @param string $minLen 最低位数
     *
     * @return string $xbin 对应的多进制数字
     */
    public static function numToStr($num, $str = self::DIGIT_STR, $minLen = 0 )
    {
        $num = floatval($num);
        $x = strlen($str);
        $arr = str_split($str);
        // 取第一个字符串
        $sStr = current($arr);
        $digit = fmod($num, $x);
        $xbin = isset($arr[$digit]) ? $arr[$digit] : null;
        $preDigit= floor($num / $x);
        if($preDigit >= 1){
            $preDigit = self::numToStr($preDigit, $str);
            $xbin = $preDigit.$xbin;
        }
        // 计算最低位数不够拿第一位来补
        if ($minLen > 0){
            $xbinLen = strlen($xbin);
            if ($minLen > $xbinLen){
                $rLen = $minLen - $xbinLen;
                $xbin = str_repeat($sStr, $rLen) . $xbin;
            }
        }
        return $xbin;
    }

    /**
     * 自定义多进制，根据设置的多进制字符串获取多进制对应的十进制.
     *
     * @param $val 对应的多进制数字
     * @param $str 自定义的多进制字符串
     *
     * @return integer $num 对应的十进制
     */
    public static function strToNum($val, $str = self::DIGIT_STR)
    {
        $x = strlen($str);
        $arr = str_split($str);
        // 进行key value 互换
        $arr = array_flip($arr);
        if ($val === ""){
            return $arr[0];
        }
        $vArr = str_split($val);
        $vArr = array_reverse($vArr);
        $num = 0;
        foreach ($vArr as $key => $v){
            if (isset($arr[$v]) && is_numeric($arr[$v]) && $arr[$v] > 0){
                $dNum = $arr[$v];
                $pNum = pow($x,$key);
                $num += $dNum * $pNum;
            }
        }
        return $num;
    }

    /**
     * 查找评论中是否包含用户自己的邀请码.
     *
     * @param string  $content 评论内容.
     * @param integer $uid     用户uid.
     *
     * @return array array('status' => true, 'code' => 'S9K8VQ', 'code_num' => 77885694), status为true表示查找到评论内容中有用户自己的邀请码, code为匹配到的邀请码, code_num为邀请码解析出来的数字.
     */
    public static function findCodeInComment($content, $uid)
    {
        $result = ['status' => false, 'code' => '', 'code_num' => ''];
        if (preg_match_all('/[a-zA-Z0-9]/', $content, $matches)) {
            $code = implode($matches[0]);
            $length = strlen($code);
            $codeLen = 6;
            if ($length > $codeLen) {
                // 数字字母超过6位.
                for ($i = 0; $i <= $length - $codeLen; $i++) {
                    $codeStr = strtoupper(substr($code, $i, $codeLen));
                    $codeNum = self::strToNum($codeStr);
                    if ($codeNum == $uid) {
                        $result = ['status' => true, 'code' => $codeStr, 'code_num' => $codeNum];
                        break;
                    }
                }
            } elseif ($length == $codeLen) {
                // 数字字母刚好6位.
                $strArr = str_split(self::DIGIT_STR);
                $codeStr = strtoupper($code);
                $codeNum = self::strToNum($codeStr);
                if ($codeNum == $uid) {
                    // 解析出来是评论用户的uid.
                    $result = ['status' => true, 'code' => $codeStr, 'code_num' => $codeNum];
                } elseif (ctype_digit((string)$codeNum) && $codeNum) {
                    $codeArr = str_split($codeStr);
                    $cnt = count(array_intersect($codeArr, $strArr));
                    if ($cnt == $codeLen) {
                        // 正好6位数字都是邀请码的字符.
                        $result = ['status' => true, 'code' => $codeStr, 'code_num' => $codeNum];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 替换配置中的通配符*为对应的数字.
     *
     * @param array $eachCase 每组配置.
     * @param array $replace  需要替换的数字数组.
     *
     * @return array
     */
    public static function replaceGlob(&$eachCase, $replace)
    {
        $newVal = [];
        foreach ($eachCase as $key => $value) {
            if (strpos($value, '*') !== false) {
                foreach ($replace as $num) {
                    $newVal[] = str_replace('*', $num, $value); // 把配置的*替换成数字.
                }
                unset($eachCase[$key]); // 替换之后删除带*号的配置
            }
        }
        return $newVal;
    }

}
