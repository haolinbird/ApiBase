<?php
/**
 * @file \Util\InviteCode.php
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

/**
 * class \Util\InviteCode.
 */
class InviteCode
{
    /**
     * 自定定义多进制字符串
     * @var string
     */
    const DIGIT_STR = 'PAS2DFG3HJKZ8XCVB6NM459QWERT7YU';

    /**
     * 签名字符串
     * @var string
     */
    const SIGN_STR = 'dsfewrq143241';

    /**
     * 获取
     * @param integer $uid
     * @return string
     */
    public static function getStrByUid($uid)
    {
        $str = self::DIGIT_STR;
        return self::numToStr($str, $uid, 6);
    }

    /**
     * 自定义多进制，根据设置的多进制字符串获取十进制对应的多进制
     * @param $str 自定义的多进制字符串
     * @param $num 十进制数字
     * @param $minLen 最低位数
     * @return $xbin 对应的多进制数字
     */
    public static function numToStr($str, $num, $minLen = 0 ){
        $num = floatval($num);
        $x = strlen($str);
        $arr = str_split($str);
        // 取第一个字符串
        $sStr = current($arr);
        $digit = fmod($num, $x);
        $xbin = isset($arr[$digit]) ? $arr[$digit] : null;
        $preDigit= floor($num / $x);
        if($preDigit >= 1){
            $preDigit = self::numToStr($str, $preDigit);
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
     * 自定义多进制，根据设置的多进制字符串获取多进制对应的十进制
     * @param $str 自定义的多进制字符串
     * @param $val 对应的多进制数字
     * @return $num 对应的十进制
     */
    public static function strToNum($str, $val)
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
     * 通过字符串解出UID
     * @param string $val
     * @return integer
     */
    public static function getUidByStr($val)
    {
        $str = self::DIGIT_STR;
        $val = strtoupper($val);
        return self::strToNum($str, $val);
    }

}