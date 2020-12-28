<?php
/**
 * Util Validator.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
namespace Util;

class Validator {
    /**
     * 验证是否是手机号.
     *
     * @param string $mobile Mobile.
     *
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        return !(preg_match('/^1\d{10}$/',$mobile) == '0');
    }

    /**
     * Match email.
     *
     * @param string $email Email.
     *
     * @return boolean
     */
    public static function isMail($email)
    {
        $pattern = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
        preg_match_all($pattern, $email, $emailArr);
        if (empty($emailArr[0][0])) {
            return false;
        }
        return true;
    }

    /**
     * 验证参数是否为空.
     *
     * @param string $validate 待验证的参数.
     *
     * @return boolean
     */
    public static function isEmpty($validate)
    {
        if (is_string($validate)) {
            $validate = trim($validate);
        }
        return empty($validate);
    }
}
