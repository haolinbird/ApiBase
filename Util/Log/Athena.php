<?php
/**
 * 雅典娜后台业务相关日志.
 *
 * @author dengjing<jingd3@jumei.com>
 */

namespace Util\Log;

/**
 * 雅典娜后台业务相关日志.
 */
class Athena extends \Util\Log\Base
{

    /**
     * Instance.
     *
     * @return $this
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * 用户余额充值.
     *
     * @param integer $uid     用户UID.
     * @param integer $code    状态码.
     * @param string  $message 消息说明.
     * @param array   $params  请求参数.
     * @param array   $content 业务内容.
     *
     * @return mixed
     */
    public function addUserBalanceChargeLog($uid, $code, $message, $params = array(), $content = array())
    {
        return $this->addLogByEventName('athena:user_balance_charge', $uid, $message, $code, $params, $content);
    }

}
