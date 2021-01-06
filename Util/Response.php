<?php
/**
 * 输出json响应结果.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

/**
 * Helper_Util.
 */
class Response
{
    private static $response = array(
        'SUCCESS'       => array('code' => 0,     'http_code' => 200, 'message' => 'SUCCESS'),
        'UNKNOW'        => array('code' => 1,     'http_code' => 200, 'message' => '未知错误'),
        'FAILED'        => array('code' => 2,     'http_code' => 200, 'message' => '系统繁忙，请稍后重试！'),
        'NO_AUTHORIZED' => array('code' => 3,     'http_code' => 200, 'message' => '无权操作'),
        'SHUTDOWN'      => array('code' => 10000, 'http_code' => 200, 'message' => '服务器停机维护，请稍后访问~'),
        'NOT_LOGIN'     => array('code' => 10001, 'http_code' => 200, 'message' => '用户尚未登陆'),
    );

    /**
     * 输出响应信息并退出.
     *
     * @param string  $type    类型.
     * @param mixed   $result  结果信息.
     * @param mixed   $message 结果信息.
     * @param string  $action  展示方式.
     *
     * @return void
     */
    public static function responseExit($type, $result = false, $message = false, $action = 'toast', $popWindows = array())
    {
        if (!isset(self::$response[$type])) {
            $type = 'UNKNOW';
        }
        $response = self::$response[$type];
        $code     = $response['code'];
        $httpCode = $response['http_code'];

        $message  = $message ? $message : $response['message'];

        if (isset($response['action'])) {
            $action = $response['action'];
        }
        self::outPutJsonResponse($message, $result, $code, $httpCode, $action, $popWindows);
    }

    /**
     * 输出成功响应信息并退出.
     *
     * @param array  $result  结果数据.
     * @param string $message 提示信息.
     *
     * @return void
     */
    public static function responseSuccess($result = [], $message = '')
    {
        $response = self::$response['SUCCESS'];
        $code     = $response['code'];

        $message  = $message ? $message : $response['message'];

        self::outPutJsonResponse($message, $result, $code);
    }


    /**
     * 输出失败响应信息并退出.
     *
     * @param string $errorKey 错误错误 KEY.
     *
     * @return void
     */
    public static function responseFailed($errorKey)
    {
        $response = isset(self::$response[$errorKey]) ? self::$response[$errorKey] : self::$response['FAILED'];
        $code     = $response['code'];
        $httpCode = $response['http_code'];

        $message  = $message ? $message : $response['message'];

        if (isset($response['action'])) {
            $action = $response['action'];
        }
        self::outPutJsonResponse($message, $result, $code, $httpCode, $action, $popWindows);
    }

    /**
     * 输出json响应结果.
     *
     * @param string  $message  消息.
     * @param array   $result   数据.
     * @param string  $code     返回码.
     * @param integer $httpCode Http状态码.
     * @param string  $action   Action.
     *
     * @return void
     */
    public static function outPutJsonResponse($message, $result = array(), $code = '40001', $httpCode = 400, $action = 'toast', $popWindows = array())
    {
        $result = is_bool($result) ? $result : (array) $result;
        if (empty($result)) {
            $result = (Object)array();
        }
        $res = array(
            'code' => (string)$code,
            'action' => (string)$action,
            'message' => (string)$message,
            'data' => $result,
            'popup_windows' => is_array($popWindows) ? $popWindows : array(),
        );

        // $date = date('Y-m-d H:i:s', time());
        header("http/1.0 $httpCode");
        // header("Date:  {$date}");
        header("Content-type: application/json; charset=utf8");
        if (isset(\Config\Env::$env) && \Config\Env::$env != 4) {
            echo json_encode($res, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($res);
        }
        exit();
    }
}
