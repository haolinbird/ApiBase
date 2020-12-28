<?php
/**
 * 获取基于时间的唯一数字.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
namespace Util;

class IdNumber
{

    /**
     *  校验身份证格式是否正确.
     *
     * @param string $idNum 身份证字符串.
     *
     * @return boolean
     */
    public static function checkIdCard($idNum)
    {
        // 18位
        if (strlen($idNum) != 18) {
            return false;
        }
        // 17位数字
        $idNum17 = substr($idNum, 0, 17);
        if (!is_numeric($idNum17)) {
            return false;
        }
        // 最后1位
        $idNum18 = substr($idNum, 17, 18);
        // 检验码
        if ($idNum18 != self::iso7064($idNum17)) {
            return false;
        }
        return true;
    }

    /**
     * 验证身份证格式ISO 7064:1983.MOD.
     *
     * @param string $vString 身份证字符串(17位返回验证位，18位).
     *
     * @return string
     */
    public static function iso7064($vString)
    {
        /*
         * $s 为某个 17 位身份证号码，不包含校验位
         * echo iso7064($s); 获得校验位的值
         * echo iso7064("$s?");包含校验位的结果
         */
        $wi = array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6);
        $hash_map = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $i_size = strlen($vString);
        $bModify = '?' == substr($vString, -1);
        $i_size1 = $bModify ? $i_size : $i_size + 1;
        $sigma = 0;
        for ($i = 1; $i <= $i_size; $i++) {
            $i1 = $vString[$i - 1] * 1;
            $w1 = $wi[($i_size1 - $i) % 10];
            $sigma += ($i1 * $w1) % 11;
        }
        if ($bModify) {
            return str_replace('?', $hash_map[($sigma % 11)], $vString);
        } else {
            return $hash_map[($sigma % 11)];
        }
    }
}
