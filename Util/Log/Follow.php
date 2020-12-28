<?php
/**
 * 业务调试相关日志.
 *
 * @author Zhongxing Wang<zhongxingw@jumei.com>
 */

namespace Util\Log;

/**
 * 业务调试相关日志.
 */
class Follow extends \Util\Log\Base
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
     * 业务异常日志相关.
     *
     * @param integer $uid     用户UID.
     * @param string  $message 消息说明.
     * @param integer $code    状态码.
     * @param array   $params  请求参数.
     * @param array   $content 业务内容.
     *
     * @return mixed
     */
    public function addExceptionLog($uid, $message, $code, $params = array(), $content = array())
    {
        return $this->addLogByEventName('follow_redis_exception', $uid, $message, $code, $params, $content);
    }

}
