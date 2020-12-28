<?php
/**
 * 广告系统ShuabaoGrowth.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Service;

/**
 * 广告系统ShuabaoGrowth.
 */
class ShuabaoGrowth extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuabaoGrowth';

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
     * 获取广告系统服务修改广告红包策略的信息.
     *
     * @return array.
     */
    public function showForShuabao()
    {
        try {
            $result = $this->phpClient('RedBagAdConfig')->showForShuabao();
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            $result = empty($result['result']) ? array() : $result['result'];
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'RedBagAdConfig', 'showForShuabao', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

}
