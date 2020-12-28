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
class VideoLive extends \Util\Log\Base
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
     * IOS支付单据日志.
     *
     * @param integer $uid     用户ID.
     * @param string  $message 错误信息｜标记日志出处.
     * @param integer $code    错误code.
     * @param array   $params  请求参数.
     * @param array   $content 日志信息.
     *
     * @return mixed
     */
    public function addIOSVerifyReceiptLog($uid, $message, $code, array $params, array $content = array())
    {
        $log = array(
            'event'   => 'ios_verify_receipt',
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

    /**
     * 主播端直播心跳日志.
     *
     * @param integer $uid     用户ID.
     * @param string  $message 错误信息｜标记日志出处.
     * @param integer $code    错误code.
     * @param array   $params  请求参数.
     * @param array   $content 日志信息.
     *
     * @return mixed
     */
    public function addRoomHeartBreakLog($uid, $message, $code, array $params, array $content = array())
    {
        $log = array(
            'event'   => 'room_heart_break',
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

}
