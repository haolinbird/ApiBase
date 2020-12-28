<?php
/**
 * 常驻通知栏消息任务调用.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Service\Api;

/**
 * 常驻通知栏消息任务接口.
 */
class PermanentNotificationBarTask extends \Service\ServiceBase
{

    /**
     * 类标识.
     *
     * @var string
     */
    public static $className = 'Api\PermanentNotificationBarTask';

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
     * 获取uid常驻通知栏消息任务信息.
     *
     * @param integer $uid       用户ID.
     * @param string  $platform  用户系统平台信息.
     * @param string  $version   用户app当前版本信息.
     * @param string  $utmSource 渠道信息.
     * @param string  $appScene  APP场景.
     *
     * @return array.
     * @throws \Exception 异常.
     */
    public function getPnbInfoByUid($uid, $platform, $version, $utmSource, $appScene = '')
    {
        try {
            $result = $this->phpClient(self::$className)->getPnbInfoByUid($uid, $platform, $version, $utmSource, $appScene);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getPnbInfoByUid', func_get_args(), $ex->getMessage());
            // throw $ex;
            return array();
        }
    }

    /**
     * 常驻通知栏信息点击后激励元宝发放.
     *
     * @param integer $uid   用户ID.
     * @param integer $id    任务ID.
     * @param integer $sycee 要发放的元宝数.
     *
     * @return boolean.
     * @throws \Exception 异常.
     */
    public function syceePnbInfoByUid($uid, $id, $sycee)
    {
        try {
            $result = $this->phpClient(self::$className)->syceePnbInfoByUid($uid, $id, $sycee);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'syceePnbInfoByUid', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 未登录时使用device_id来判断获取常驻通知栏消息任务信息.
     *
     * @param string $deviceId  设备的唯一标识device_id.
     * @param string $platform  用户系统平台信息.
     * @param string $version   用户app当前版本信息.
     * @param string $utmSource 渠道信息.
     * @param string $appScene  APP场景.
     *
     * @return array.
     * @throws \Exception 异常.
     */
    public function getPnbInfoByDeviceId($deviceId, $platform, $version, $utmSource, $appScene = '')
    {
        try {
            $result = $this->phpClient(self::$className)->getPnbInfoByDeviceId($deviceId, $platform, $version, $utmSource, $appScene);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getPnbInfoByDeviceId', func_get_args(), $ex->getMessage());
            // throw $ex;
            return array();
        }
    }

}
