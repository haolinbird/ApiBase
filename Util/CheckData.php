<?php
/**
 * 数据公共检查类.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

/**
 * 数据公共检查类.
 */
class CheckData
{
    /**
     * 是否为整数.
     */
    const TYPE_INT = '整数';

    /**
     * 字符串形式的整数或者就是整数且大于0的数.
     */
    const TYPE_POSITIVE_INT = '正整数';
    
    const TYPE_POSITIVE_NUMBER = '正数';
    
    /**
     * 字符串或者数字类型的数据.
     */
    const TYPE_STRING = '字符串';

    /**
     *车牌号.
     */
    const TYPE_PLATENUMBER = '车牌号';

    /**
     * 一个非空字符串或者数字类型的数据.
     */
    const TYPE_STRING_NOT_EMPTY = '非空字符串';

    /**
     *身份证.
     */
    const TYPE_ID_CARD = '身份证';

    /**
     * 是否数字类型.
     */
    const TYPE_NUMBER = '数字类型';

    /**
     *手机号码.
     */
    const TYPE_MOBILE = '手机号码';


    /**
     * 验证是否数组.
     */
    const TYPE_ARRAY = '数组';

    /**
     * 验证是否非空数组.
     */
    const TYPE_ARRAY_NOT_EMPTY = '非空数组';

    /**
     * 验证是否浮点型.
     */
    const TYPE_BOOLEAN = '布尔型';
    
    /**
     * 纯数字.
     */
    const TYPE_DIGIT = '纯数字';
    
    /**
     * 纯数字.
     */
    const TYPE_EMAIL = '邮箱';

    /**
     *手机或电话号码.
     */
    const TYPE_MOBILEORTELEPHONE = '手机或电话号码';
    
    const TYPE_DATE = '日期';

    /**
     * 经度.
     */
    const TYPE_LONGITUDE = '经度';

    /**
     * 经度.
     */
    const TYPE_LATITUDE = '纬度';
    /**
     * url链接类型
     */
    const TYPE_URL = 'url';

    /**
     * 字母开头或下划线+字母数字下划线
     */
    const TYPE_STANDARD_NAME = 'standard_name';

    /**
     * 验证数据类型.
     *
     * @param mixed   $data    需要验证的数据所有类型都是可以的.
     * @param string  $type    需要匹配的类型.
     * @param string  $message 提示语言.
     * @param integer $code    异常码.
     *
     * @return boolean.
     *
     * @throws CheckDataException 没有验证通过.
     */
    public static function checkDataType($data, $type, $message, $code = 0)
    {
        $right = false;
        switch ($type) {
            case self::TYPE_POSITIVE_INT:
                $right = (ctype_digit($data) || is_int($data)) && $data > 0;
                break;
            case self::TYPE_INT:
                $right = ctype_digit($data) || is_int($data);
                break;
            case self::TYPE_NUMBER:
                $right = is_numeric($data);
                break;
            case self::TYPE_STRING:
                $right = is_string($data) || is_numeric($data);
                break;
            case self::TYPE_STRING_NOT_EMPTY:
                $right = (is_string($data) && trim($data) !== '') || is_numeric($data);
                break;
            case self::TYPE_ARRAY:
                $right = is_array($data);
                break;
            case self::TYPE_ARRAY_NOT_EMPTY:
                $right = is_array($data) && !empty($data);
                break;
            case self::TYPE_BOOLEAN:
                $right = is_bool($data);
                break;
            case self::TYPE_DIGIT:
                $right = is_numeric($data) && ctype_digit(''.$data);
                break;
            case self::TYPE_ID_CARD:
                $right = \Util\IdNumber::checkIdCard($data);
                break;
            case self::TYPE_EMAIL:
                self::checkDataType($data, self::TYPE_STRING_NOT_EMPTY, $message, $code);
                $right = preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+\.([a-zA-Z0-9_-])+/', $data) == 1;
                break;
            case self::TYPE_MOBILEORTELEPHONE:
                self::checkDataType($data, self::TYPE_STRING_NOT_EMPTY, $message, $code);
                $right = true;
                $dataTmp = explode(',', $data);
                foreach ($dataTmp as $v) {
                	self::checkMobileOrTelephone($v, $message, $code);
                }
                break;
            case self::TYPE_MOBILE:
                self::checkDataType($data, self::TYPE_STRING_NOT_EMPTY, $message, $code);
                self::checkMobile($data, $message, $code);
                $right = true;
                break;
            case self::TYPE_DATE:
                self::checkDataType($data, self::TYPE_STRING_NOT_EMPTY, $message, $code);
                $right = strtotime($data) != false;
                break;
            case self::TYPE_POSITIVE_NUMBER:
                self::checkDataType($data, self::TYPE_NUMBER, $message, $code);
                $right = $data > 0;
                break;
            case self::TYPE_LONGITUDE:
                $right = $data === '' || (is_numeric($data) && $data >= 0 && $data <= 180);
                break;
            case self::TYPE_LATITUDE:
                $right = $data === '' || (is_numeric($data) && $data >= 0 && $data <= 90);
                break;
            case self::TYPE_URL:
                self::checkUrl($data, $message, $code);
                $right = true;
                break;
            case self::TYPE_STANDARD_NAME:
                $right = preg_match('/^([a-zA-Z_])+([a-zA-Z_0-9]){0,}/', $data) == 1;
                break;
            default:
                self::exception('参数验证异常', $code);
                break;
        }
        if (!$right) {
            self::exception($message, $code);
        }
        return true;
    }

    /**
     * 验证数据是否被包含.
     *
     * @param mixed   $data    需要验证的数据.
     * @param array   $range   可选值.
     * @param string  $message 提示语言.
     * @param integer $code    异常码.
     * @param boolean $strict  是否验证类型.
     *
     * @return boolean.
     */
    public static function inArray($data, array $range, $message, $code = 0, $strict = false)
    {
        self::checkDataType($data, self::TYPE_STRING, $message, $code);
        if (!in_array($data, $range, $strict)) {
            self::exception($message, $code);
        }
        return true;
    }

    /**
     * 验证数据长度.
     *
     * @param mixed   $data    需要验证的数据.
     * @param string  $message 提示语言.
     * @param integer $min     最小长度null不会验证.
     * @param integer $max     最大长度null不会验证.
     * @param integer $code    异常码.
     *
     * @return boolean.
     */
    public static function length($data, $message, $min = null, $max = null, $code = 0)
    {
        self::checkDataType($data, self::TYPE_STRING, $message, $code);
        $length = strlen($data);
        if ((!is_null($min) && $length < $min) || (!is_null($max) && $length > $max)) {
            self::exception($message, $code);
        }
        return true;
    }

    /**
     * 抛异常.
     *
     * @param string  $message 提示语言.
     * @param integer $code    异常码.
     *
     * @throws CheckDataException 异常信息.
     */
    public static function exception($message, $code)
    {
        throw new \RpcBusinessException($message, $code);
    }

    /**
     * 验证小数.
     *
     * @param mixed   $data      需要验证的数据.
     * @param string  $message   提示语言.
     * @param integer $precision 小数位位数上线.
     * @param integer $code      异常码.
     *
     * @return boolean.
     */
    public static function decimal($data, $message, $precision = 2, $code = 0)
    {
        self::checkDataType($data, self::TYPE_NUMBER, $message, $code);
        $temp = explode('.', (string)$data);
        if (count($temp) > 1 && strlen(rtrim($temp[1], '0')) > $precision) {
            self::exception($message, $code);
        }
        return true;
    }

    /**
     * 判断联系电话.
     *
     * @param string  $phone   手机号.
     * @param string  $message 报错提示.
     * @param integer $code    报错CODE.
     *
     * @return boolean.
     */
    public static function checkMobileOrTelephone($phone, $message, $code)
    {
        $isMob="/^1[3-9]{1}[0-9]{9}$/";
        
        $isTel="/^([0-9]{3,4})?(-)?[0-9]{7,8}\-?[0-9]{0,6}$/";
        //修改座机号座机号是以0开头的
        //$isTel = "/^(0[0-9]{2,3})(\-)?([2-9][0-9]{6,7})+(\-?[0-9]{1,4})?$/";
        if (!preg_match($isMob, $phone) && !preg_match($isTel, $phone)) {
            self::exception($message, $code);
        }
        return true;
    }

    /**
     *检查是否手机号.
     *
     * @param string  $phone   手机号.
     * @param string  $message 报错提示.
     * @param integer $code    报错CODE.
     *
     * @return boolean.
     */
    public static function checkMobile($phone, $message, $code)
    {
        $isMob="/^1[3-9]{1}[0-9]{9}$/";
        if (!preg_match($isMob, $phone)) {
            self::exception($message, $code);
        }
        return true;
    }
    /**
     * 检测url链接
     * @param string $url
     * @param string $message
     * @param integer $code
     * @return boolean
     */
    public static function checkUrl($url,$message,$code)
    {
        $preg = '/^(http[s]?:)?\/\/([a-zA-Z0-9_]+)\.(\w+)(.*)$/isU';
        if (!preg_match($preg, $url)){
            self::exception($message, $code);
        }
        return true;
    }

    /**
     *正则验证.
     *
     * @param string  $data    验证的数据.
     * @param string  $pattern 规则.
     * @param string  $message
     * @param integer $code
     * @return boolean.
     */
    public static function pregMatch($data, $pattern, $message, $code = 0)
    {
        if (!preg_match($pattern, $data)){
            self::exception($message, $code);
        }
        return true;
    }
}
