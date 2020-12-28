<?php
/**
 * 增长活动service.
 *
 * @author weiy01 <weiy01@jumei.com>
 */

namespace Service;

class GrowthSystem extends ServiceBase
{

    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'GrowthSystemService';


    /**
     * 一分领元宝活动-是否购买过.
     *
     * @param integer $uid 用户id.
     *
     * @return integer
     */
    public function isBought($uid)
    {
        $result = array();
        try {
            $result = $this->phpClient('PennySycee\PennySycee')->isBought($uid);
            if ($result['error_code'] != 0) {
                $this->RpcBusinessException($result['message'], $result['error_code']);
            }
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'PennySycee\PennySycee', 'isBouht', func_get_args(), $ex->getMessage());
        }
        return empty($result['data']) ? 0 : $result['data'];
    }

    /**
     * 一分领元宝提现.
     *
     * @param array $param 参数.
     *
     * @return array
     * @throws \Exception
     */
    public function pennySyceeWithdraw($param)
    {
        try {
            $result = $this->phpClient('PennySycee\PennySycee')->withdraw($param);
            if ($result['error_code'] != 0) {
                $this->RpcBusinessException($result['message']);
            }
            return $result['data'];
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'PennySycee\PennySycee', 'withdraw', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 保存提现id.
     *
     * @param integer $uid        用户id.
     * @param integer $withdrawId 提现id.
     *
     * @return mixed
     */
    public function saveWithdrawId($uid, $withdrawId)
    {
        try {
            $result = $this->phpClient('PennySycee\PennySycee')->saveWithdrawId($uid, $withdrawId);
            if ($result['error_code'] != 0) {
                $this->RpcBusinessException($result['message']);
            }
            return $result['data'];
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'PennySycee\PennySycee', 'saveWithdrawId', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 提现状态.
     *
     * @param integer $uid     用户id.
     * @param integer $orderId 订单id.
     *
     * @return integer
     */
    public function withdrawStatus($uid, $orderId)
    {
        try {
            $result = $this->phpClient('PennySycee\PennySycee')->withdrawStatus($uid, $orderId);
            if ($result['error_code'] != 0) {
                $this->RpcBusinessException($result['message']);
            }
            return $result['data'];
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'PennySycee\PennySycee', 'withdrawStatus', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 通过UID获取用户提现次数.
     *
     * @param integer $uid 用户id.
     *
     * @return array
     * @throws
     */
    public function getWithdrawalChoiceInfoByUid($uid)
    {
        try {
            $result = $this->phpClient('WithdrawalUnlock\WithdrawalUnlock')->getWithdrawalChoiceInfoByUid($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                return array();
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'WithdrawalUnlock\WithdrawalUnlock', 'getWithdrawalChoiceInfoByUid', func_get_args(), $ex->getMessage());
        }

        return array();
    }

    /**
     * 使用一次提现解锁次数.
     *
     * @param integer $uid 用户id.
     *
     * @return integer
     */
    public function useWithDrawChoiceOnce($uid)
    {
        try {
            $res = $this->phpClient('WithdrawalUnlock\WithdrawalUnlock')->useWithDrawChoiceOnce($uid);
            if (\PHPClient\Text::hasErrors($res)) {
                return false;
            }
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'WithdrawalUnlock\WithdrawalUnlock', 'useWithDrawChoiceOnce', func_get_args(), $ex->getMessage());
            return false;
        }

        return true;
    }

}