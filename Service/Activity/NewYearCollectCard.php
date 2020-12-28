<?php
/**
 * 新春集卡.
 *
 * @author liming <mingl2@jumei.com>
 */

namespace Service\Activity;

use Service\ServiceBase;

/**
 * Class NewYearCollectCard.
 */
class NewYearCollectCard extends ServiceBase
{

    protected static $className = 'Activity\NewYearCollectCard';
    
    /**
     * 抽幸运奖.
     * 
     * @param integer $uid        用户uid.
     * @param array   $deviceInfo 设备id.
     * 
     * @return mixed
     */
    public function luckyReward($uid, $deviceInfo)
    {
        $response = $this->phpClient('Activity\NewYearCollectCard')->luckyReward($uid, $deviceInfo);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 获取邀请新用户人数.
     *
     * @param integer $uid 用户id.
     *
     * @return array
     */
    public function getInviteNewUserNum($uid)
    {
        try {
            $result = $this->phpClient(self::$className)->getInviteNewUserNum($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $e) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getInviteNewUserNum', func_get_args(), $e->getMessage());
            throw $e;
        }

    }

    /**
     * 万能卡-获取万能卡兑换信息.
     *
     * @param integer $uid 用户id.
     *
     * @return mixed
     */
    public function getWanCardInfo($uid)
    {
        try {
            $result = $this->phpClient(self::$className)->getWanCardInfo($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $e) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getWanCardInfo', func_get_args(), $e->getMessage());
            return array();
        }

    }

    /**
     * 万能卡-获取已拥有卡片.
     *
     * @param integer $uid 用户id.
     *
     * @return mixed
     */
    public function getOwnedCards($uid)
    {
        try {
            $result = $this->phpClient(self::$className)->getOwnedCards($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $e) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getOwnedCards', func_get_args(), $e->getMessage());
            return array();
        }
    }

    /**
     * 万能卡-替换卡片.
     *
     * @param integer $uid    用户id.
     * @param integer $cardNo 要替换的卡片编号.
     *
     * @return array
     * @throws \Exception 基本异常.
     */
    public function exchangeCard($uid, $cardNo)
    {

        try {
            $result = $this->phpClient(self::$className)->exchangeCard($uid, $cardNo);

            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $e) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'exchangeCard', func_get_args(), $e->getMessage());
            throw $e;
        }

    }

}
