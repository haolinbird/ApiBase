<?php
/**
 * 用户播放视频交互相关日志.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Util\Log;

/**
 * 刷宝用户播放视频交互相关日志.
 */
class Play extends \Util\Log\Base
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
     * 红包接口请求返回发放元宝数为0的校验日志.
     *
     * @param integer $uid     用户ID.
     * @param string  $message 错误信息｜标记日志出处.
     * @param integer $code    错误code.
     * @param array   $params  请求参数.
     * @param array   $content 日志信息.
     *
     * @return mixed
     */
    public function verificationRpLogFromSyceeAmount0($uid, $message, $code, array $params, array $content)
    {
        $log = array(
            'event'   => 'verificationRpLogFromAmount0',
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

}
