<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/03
 * Time: 上午16:45
 */
class TaskShare extends \Service\ServiceBase
{
    public static $className = 'TaskShare';

    /**
     * 获取分享信息
     *
     * @param integer $uid     用户ID
     * @param string  $type    分享类型
     * @param array   $parma   分享的参数信息
     * @param integer $shareId 分享ID
     *
     * @return array
     * @throws \Exception
     */
    public function getShareInfo($uid, $type, $parma = array(), $shareId = 0)
    {
        try {
            $result = $this->phpClient('TaskShare')->getShareInfo($uid, $type, $parma, $shareId);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'TaskShare', 'getShareInfo', func_get_args(), $ex->getMessage());
        }
        return array();
    }

    /**
     * 记录填写邀请码信息.
     *
     * @param integer $mobile     手机号.
     * @param string  $inviteCode 邀请码.
     * @param integer $timeStamp  时间戳.
     * @param string  $sign       签名.
     * @param string  $openId     微信openid.
     * @param string  $appId      公众号id.
     * @param integer $taskId     任务id.
     * @param string  $ipAddr     IP地址.
     * @param string  $source     来源.
     *
     * @return boolean
     */
    public function recordInviteMobileByTask($mobile, $inviteCode, $timeStamp, $sign, $openId, $appId, $taskId, $ipAddr = "", $source = '')
    {
        try {
            $result = $this->phpClient('TaskShare')->recordInviteMobileByTask($mobile, $inviteCode, $timeStamp, $sign, $openId, $appId, $taskId, $ipAddr, $source);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $e) {
            \Utils\Log\Logger::instance()->log($e);
            throw $e;
        }
        return $result;
    }

    /**
     * 获取用户邀请码
     *
     * @param integer $uid 用户ID
     *
     * @return array
     * @throws \Exception
     */
    public function getInviteCodeByUid($uid)
    {
        try {
            $result = $this->phpClient('TaskShare')->getInviteCodeByUid($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'TaskShare', 'getInviteCodeByUid', func_get_args(), $ex->getMessage());
            return false;
        }
    }
}