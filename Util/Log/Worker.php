<?php
/**
 * Worker系统相关日志.
 *
 * @author Zhongxing Wang<zhongxingw@jumei.com>
 */

namespace Util\Log;

/**
 * Worker相关日志.
 */
class Worker extends \Util\Log\Base
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
     * 系统异常相关日志.
     *
     * @param string  $event   服务名字.
     * @param integer $code    状态码.
     * @param string  $message 消息说明.
     * @param array   $params  请求参数.
     * @param array   $content 业务内容.
     *
     * @return mixed
     */
    public function addLog($event, $code, $message, array $params = array(), $content = array())
    {
        $params = array(
            'url'      => \Util\Util::getCurrentSelfUrl(),
            '$_GET'    => $_GET,
            '$_POST'   => $_POST,
            '$_COOKIE' => $_COOKIE
        ) + $params;
        return $this->addLogByEventName('worker:' . $event, 0, $message, $code, $params, $content);
    }

}
