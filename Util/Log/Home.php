<?php
/**
 * 业务调试相关日志.
 *
 * @author dengjing<jingd3@jumei.com>
 */

namespace Util\Log;

/**
 * 业务调试相关日志.
 */
class Home extends \Util\Log\Base
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
     * 调试日志相关.
     *
     * @param string  $event   服务名字.
     * @param integer $code    状态码.
     * @param string  $message 消息说明.
     * @param array   $params  请求参数.
     * @param array   $content 业务内容.
     * @param integer $uid     用户id.
     *
     * @return mixed
     */
    public function addLog($event, $code, $message, $params = array(), $content = array(), $uid = 0)
    {
        $log = array(
            'event'   => $event,
            'uid'     => $uid,
            'code'    => $code,
            'message' => $message,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

}
