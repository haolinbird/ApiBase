<?php
/**
 * 外部服务相关日志.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Util\Log;

/**
 * 外部服务相关日志.
 */
class Service extends \Util\Log\Base
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
     * Rpc日志.
     *
     * @param string $serivce     服务名字.
     * @param string $class       类名.
     * @param string $method      方法.
     * @param mixed  $params      请求参数.
     * @param mixed  $response    返回结果.
     * @param float  $elapsedTime 耗时.
     *
     * @return mixed
     */
    public function phpClientLog($serivce, $class, $method, $params, $response, $elapsedTime = null)
    {
        $log = array(
            'event'   => 'phpClient',
            'params'  => $params,
            'message' => $response,
            'content' => array(
                'service' => $serivce,
                'class'   => $class,
                'method'  => $method
            ),
        );
        if (null != $elapsedTime) {
            $log['content']['elapsed_time'] = sprintf("%0.3f", $elapsedTime);
        }
        return $this->setServiceLogData($log);
    }

    /**
     * Thrift日志.
     *
     * @param string $service     服务名字.
     * @param string $method      方法.
     * @param mixed  $params      请求参数.
     * @param mixed  $response    返回结果.
     * @param float  $elapsedTime 耗时.
     *
     * @return mixed
     */
    public function thriftLog($service, $method, $params, $response, $elapsedTime = null)
    {
        return $this->thriftClientLog($service, $method, $params, $response, $elapsedTime);
    }

    /**
     * ThriftClient日志.
     *
     * @param string $service     服务名字.
     * @param string $method      方法.
     * @param mixed  $params      请求参数.
     * @param mixed  $response    返回结果.
     * @param float  $elapsedTime 耗时.
     *
     * @return mixed
     */
    public function thriftClientLog($service, $method, $params, $response, $elapsedTime = null)
    {
        $log = array(
            'event'       => 'thriftClient',
            'params'  => $params,
            'message' => $response,
            'content' => array(
                'service' => $service,
                'method'  => $method
            ),
        );
        if (null != $elapsedTime) {
            $log['content']['elapsed_time'] = sprintf("%0.3f", $elapsedTime);
        }
        return $this->setServiceLogData($log);
    }

    /**
     * HttpClient日志.
     *
     * @param string $service     服务名字.
     * @param string $class       类名.
     * @param string $method      方法.
     * @param mixed  $params      请求参数.
     * @param mixed  $response    返回结果.
     * @param float  $elapsedTime 耗时.
     *
     * @return mixed
     */
    public function httpClientLog($service, $class, $method, $params, $response, $elapsedTime = null)
    {
        $log = array(
            'event'   => 'httpClient',
            'params'  => $params,
            'message' => $response,
            'content' => array(
                'service' => $service,
                'class'   => $class,
                'method'  => $method
            ),
        );
        if (null != $elapsedTime) {
            $log['content']['elapsed_time'] = sprintf("%0.3f", $elapsedTime);
        }
        return $this->setServiceLogData($log);
    }

    /**
     * HttpClient代理日志.
     *
     * @param string $service     服务名字.
     * @param string $method      方法.
     * @param mixed  $params      请求参数.
     * @param mixed  $response    返回结果.
     * @param float  $elapsedTime 耗时.
     *
     * @return mixed
     */
    public function thriftClientProxyLog($service, $method, $params, $response, $elapsedTime = null)
    {
        $log = array(
            'event'   => 'thriftClientProxy',
            'params'  => $params,
            'message' => $response,
            'content' => array(
                'service' => $service,
                'method'  => $method
            ),
        );
        if (null != $elapsedTime) {
            $log['content']['elapsed_time'] = sprintf("%0.3f", $elapsedTime);
        }
        return $this->setServiceLogData($log);
    }

    /**
     * Redis 错误日志.
     *
     * @param string $message  说明.
     * @param array  $params   请求参数.
     * @param array  $response 返回结果.
     *
     * @return mixed
     */
    public function redisLog($message, $params = array(), $response = array())
    {
        $log = array(
            'event'   => 'redis',
            'message' => $message,
            'params'  => $params,
            'content' => $response,
        );
        return $this->setServiceLogData($log);
    }

    /**
     * JMPaymentClient日志.
     *
     * @param string $service     服务名字.
     * @param string $method      方法.
     * @param mixed  $params      请求参数.
     * @param mixed  $response    返回结果.
     * @param float  $elapsedTime 耗时.
     *
     * @return mixed
     */
    public function JMPaymentClientLog($service, $method, $params, $response, $elapsedTime = null)
    {
        $log = array(
            'event'       => 'JMPaymentClient',
            'params'  => $params,
            'message' => $response,
            'content' => array(
                'service' => $service,
                'method'  => $method
            ),
        );
        if (null != $elapsedTime) {
            $log['content']['elapsed_time'] = sprintf("%0.3f", $elapsedTime);
        }
        return $this->setServiceLogData($log);
    }

}
