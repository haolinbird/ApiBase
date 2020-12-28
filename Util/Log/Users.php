<?php
/**
 * 刷宝用户相关日志.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Util\Log;

/**
 * 刷宝用户相关日志.
 */
class Users extends \Util\Log\Base
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
     * 新注册用户调用服务时中断消息重试异常日志.
     *
     * @param integer $uid     用户ID.
     * @param string  $message 错误信息.
     * @param integer $code    错误code.
     * @param array   $params  请求参数.
     * @param array   $content 日志信息.
     *
     * @return mixed
     */
    public function againAddUserExceptionLog($uid, $message, $code, array $params, array $content)
    {
        $log = array(
            'event'   => 'againAddUserException',
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

    /**
     * 用户注册登录的日志.
     *
     * @param integer $uid     用户ID.
     * @param string  $message 错误信息.
     * @param integer $code    错误code.
     * @param array   $params  请求参数.
     * @param array   $content 日志信息.
     *
     * @return mixed
     */
    public function signupLog($uid, $message, $code, array $params, array $content)
    {
        $log = array(
            'event'   => 'signup',
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

    /**
     * 用户常驻通知栏信息的日志.
     *
     * @param integer $uid     用户ID.
     * @param string  $message 错误信息.
     * @param integer $code    错误code.
     * @param array   $params  请求参数.
     * @param array   $content 日志信息.
     *
     * @return mixed
     */
    public function pnbLog($uid, $message, $code, array $params, array $content)
    {
        $log = array(
            'event'   => 'pnb',
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => array(
                    'url'      => \Util\Util::getCurrentSelfUrl(),
                    '$_GET'    => $_GET,
                    '$_POST'   => $_POST,
                    '$_COOKIE' => $_COOKIE
                ) + $params,
            'content' => $content,
        );
        return $this->setLogData($log);
    }

    /**
     * 用户常驻通知栏信息的数据统计日志.
     *
     * @param string  $event        日志标识.
     * @param integer $uid          用户ID.
     * @param string  $platform     平台.
     * @param string  $clientV      版本号.
     * @param string  $utmSource    用户来源.
     * @param string  $deviceId     设备ID.
     * @param string  $scene        APP场景.
     * @param integer $foreground   客户端进场发起请求场景(-1:未传递参数 0:后台 1:前台).
     * @param integer $isActiveUser 是否活跃用户(-1:未传递参数 0:保活用户 1:活跃用户).
     * @param string  $message      日志备注.
     * @param integer $code         日志code.
     * @param array   $params       请求参数.
     * @param array   $content      请求返回数据.
     *
     * @return mixed
     */
    public function pnbStatisticsLog($event, $uid, $platform, $clientV, $utmSource, $deviceId, $scene = '', $foreground = -1, $isActiveUser = -1, $message = '', $code = 0, array $params = array(), array $content = array())
    {
        $log = array(
            'event'          => $event,
            'uid'            => $uid,
            'platform'       => $platform,
            'client_v'       => $clientV,
            'utm_source'     => $utmSource,
            'device_id'      => $deviceId,
            'scene'          => $scene,
            'foreground'     => $foreground,
            'is_active_user' => $isActiveUser,
            'message'        => $message,
            'code'           => $code,
            'params'         => array(
                    'url'      => \Util\Util::getCurrentSelfUrl(),
                    '$_GET'    => $_GET,
                    '$_POST'   => $_POST,
                    '$_COOKIE' => $_COOKIE
                ) + $params,
            'content'        => $content,
        );
        return $this->setStatisticsLog($log);
    }

}
