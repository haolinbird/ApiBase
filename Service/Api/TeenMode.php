<?php
/**
 * 青少年模式调用.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Service\Api;

/**
 * 青少年模式接口.
 */
class TeenMode extends \Service\ServiceBase
{

    /**
     * 类标识.
     *
     * @var string
     */
    public static $className = 'Api\TeenMode';

    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabao';

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
     * 通过uid获取是否青少年模式信息.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception 异常信息.
     */
    public function getTeenModeByUid($uid)
    {
        try {
            $result = $this->phpClient('Api\TeenMode')->getTeenModeByUid($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\TeenMode', 'getTeenModeByUid', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 设置青少年模式.
     *
     * @param integer $uid      用户ID.
     * @param integer $status   青少年模式状态(0:关闭青少年模式 1:开启青少年模式).
     * @param string  $password 监护密码.
     *
     * @return array
     * @throws \Exception 异常信息.
     */
    public function setTeenMode($uid, $status, $password)
    {
        try {
            $result = $this->phpClient('Api\TeenMode')->setTeenMode($uid, $status, $password);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\TeenMode', 'setTeenMode', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 忘记密码进行关闭青少年模式.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception 异常信息.
     */
    public function closeTeenMode($uid)
    {
        try {
            $result = $this->phpClient('Api\TeenMode')->closeTeenMode($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\TeenMode', 'closeTeenMode', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 青少年模式密码验证.
     *
     * @param integer $uid      用户ID.
     * @param string  $password 监护密码.
     *
     * @return boolean
     * @throws \Exception 异常信息.
     */
    public function verifyTeenModeByPassword($uid, $password)
    {
        try {
            $result = $this->phpClient('Api\TeenMode')->verifyTeenModeByPassword($uid, $password);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\TeenMode', 'verifyTeenModeByPassword', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

}
