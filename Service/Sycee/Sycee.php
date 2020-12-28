<?php
namespace Service\Sycee;
use Service\ServiceBase;

/**
 * 获取元宝相关接口
 * @user qiangd<qiangd@jumei.com
 * @date 2018年10月31日
 */
class Sycee extends ServiceBase
{
    protected static $className = 'Sycee\Sycee';

    /**
     * 获取用户元宝信息
     * @param $uid
     * @return mixed
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function getInfoByUid($uid)
    {
        try {
            $response = $this->phpClient('Sycee\Sycee')->getInfoByUid($uid);
            if (\PHPClient\Text::hasErrors($response)) {
                return [];
            }
        } catch (\Exception $exception) {
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Sycee\Sycee',
                'getInfoByUid',
                func_get_args(),
                $exception->getMessage()
            );
        }
        return $response;
    }

    /**
     * 扣减用户元宝
     * @param $uid
     * @param $amount
     * @param $source
     * @param $desc
     * @return mixed
     */
    public function reduceSycee($uid, $amount, $source, $desc)
    {
        try {
            $response = $this->phpClient('Sycee\Sycee')->reduceSycee($uid, $amount, $source, $desc);
            if (\PHPClient\Text::hasErrors($response)) {
                return false;
            }
        } catch (\Exception $exception) {
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Sycee\Sycee',
                'reduceSycee',
                func_get_args(),
                $exception->getMessage()
            );
        }
        return $response;
    }

    /**
     * 获取用户元宝账户信息
     *
     * @param integer $uid    用户ID.
     * @param boolean $master 是否主库.
     *
     * @return array
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function getSyceeInfoByUid($uid, $master = false)
    {
        try {
            $response = $this->phpClient('Sycee\Sycee')->getSyceeInfoByUid($uid, $master);
            if (\PHPClient\Text::hasErrors($response)) {
                return [];
            }
        } catch (\Exception $exception) {
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Sycee\Sycee',
                'getSyceeInfoByUid',
                func_get_args(),
                $exception->getMessage()
            );
        }
        return $response;
    }

    /**
     * 获取用户元宝+余额账户信息
     *
     * @param integer $uid    用户ID.
     * @param boolean $master 是否主库.
     *
     * @return array
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function getSyceeAndBalanceByUid($uid, $master = false)
    {
        try {
            $response = $this->phpClient('Sycee\Sycee')->getSyceeAndBalanceByUid($uid, $master);
            if (\PHPClient\Text::hasErrors($response)) {
                return [];
            }
        } catch (\Exception $exception) {
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Sycee\Sycee',
                'getSyceeAndBalanceByUid',
                func_get_args(),
                $exception->getMessage()
            );
        }
        return $response;
    }

}