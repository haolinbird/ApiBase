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
class Debugger extends \Util\Log\Base
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
     *
     * @return mixed
     */
    public function addLog($event, $code, $message, $params = array(), $content = array())
    {
        $log = array(
            'event'   => 'DEBUG:' . $event,
            'code'    => $code,
            'message' => $message,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

    /**
     * 调试日志相关(升级版).
     *
     * @param string  $event   服务名字.
     * @param integer $code    状态码.
     * @param string  $message 消息说明.
     * @param array   $params  请求参数.
     * @param array   $content 业务内容.
     *
     * @return mixed
     */
    public function addDebugLog($event, $code, $message, $params = array(), $content = array())
    {
        $log = array(
            'event'   => $event,
            'code'    => $code,
            'message' => $message,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setDebugLogData($log);
    }

    /**
     * 全文本调试日志.
     *
     * @param string $event 服务名字.
     *
     * @return boolean
     */
    public function addFullTextLog($event)
    {
        $args = func_get_args();
        return call_user_func_array(array($this,'logFullText'), $args);
    }

}
