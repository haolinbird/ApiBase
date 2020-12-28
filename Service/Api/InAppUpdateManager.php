<?php
/**
 * 应用内升级管理工具.
 *
 * @author wenqiang tao<wenqiangt@jumei.com>
 */

namespace Service\Api;

/**
 * 应用内升级管理工具.
 */
class InAppUpdateManager extends \Service\ServiceBase
{

    public static $className = 'Api\InAppUpdateManager';

    /**
     * 获取升级信息.
     *
     * @param string  $platform  平台.
     * @param string  $version   客户端版本.
     * @param string  $utmSource 渠道.
     * @param integer $uid       用户ID.
     * @param string  $deviceId  设备号.
     *
     * @return array 升级信息，为空表示没有升级信息,
     * @throws \RpcBusinessException 业务异常.
     */
    public function getUpdateInfo($platform, $version, $utmSource, $uid, $deviceId = '')
    {
        $updateInfo = $this->phpClient(self::$className)->getUpdateInfo($platform, $version, $utmSource, $uid, $deviceId);
        // 如果出现错误，则直接返回空
        if (\PHPClient\Text::hasErrors($updateInfo)) {
            return array();
        }
        return $updateInfo;
    }

}
