<?php
namespace Service;

class ServiceBaseBackend extends \Module\ModuleBase
{

    const RECV_TIME_OUT = 18; // 默认接收数据超时时间.
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabaoBackend';

    /**
     * Get Instance.
     *
     * @param boolean $sington 是否单例.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 封装的请求其他服务的接口(PHPClient).
     *
     * @param string  $className   服务的类名.
     * @param integer $recvTimeOut 接收数据超时时间.
     *
     * @return \PHPClient\Text
     * @throws \RpcBusinessException 参数异常.
     */
    protected function phpClient($className = '', $recvTimeOut = self::RECV_TIME_OUT)
    {
        if (empty($className) && isset(static::$className)) {
            $className = static::$className;
        }
        if (empty($className)) {
            throw new \RpcBusinessException('invalid serviceName or className');
        }
        \PHPClient\Text::$recvTimeOut = $recvTimeOut;
        return \PHPClient\Text::inst(static::$serviceName)->setClass($className);
    }

    /**
     * 封装的请求其他服务的接口(ThriftClient).
     *
     * @return \Thrift\Client
     */
    protected function thriftClient()
    {
        return \Thrift\Client::instance(static::$serviceName);
    }
    
    /**
     * 直接调用未封装的RPC方法.
     *
     * @param string $name      方法名字.
     * @param mixed  $arguments 方法参数.
     *
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function __call($name, $arguments)
    {
        $obj = static::instance()->phpClient();
        $a = microtime(true);
        try {
            $response = call_user_func_array(array($obj, $name), $arguments);
            if (isset(\Config\Util::$logElapsedTime) && ($elapsedTime = microtime(true) - $a) >= \Config\Util::$logElapsedTime) {
                \Util\Log\Service::getInstance()->phpClientLog(static::$serviceName, static::$className, $name, $arguments, $response, $elapsedTime);
            }
        } catch (\Exception $e) {
            \Util\Log\Service::getInstance()->phpClientLog(static::$serviceName, static::$className, $name, $arguments, $e->getMessage());
            throw $e;
        }
        if (\PHPClient\Text::hasErrors($response)) {
            throw new \RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 检查返回值.
     *
     * @param array   $result                返回值.
     * @param string  $apiName               接口名.
     * @param array   $params                参数.
     * @param boolean $isResultOriginalValue 是否返回请求原值.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function checkThriftResult($result, $apiName, array $params = array(), $isResultOriginalValue = false)
    {
        $result = json_decode($result, true);
        if (empty($result)) {
            return array();
        }
        if ($result['code'] != 0) {
            if ($isResultOriginalValue) {
                return $result;
            }
            \Util\Log\Service::getInstance()->thriftLog(static::$serviceName, $apiName, $params, $result);
            return array();
        }
        return isset($result['data']) ? $result['data'] : array();
    }
}
