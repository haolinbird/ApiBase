<?php
/**
 * Created by JuMei.
 * Author: boh <boh@jumei.com>
 * Date  : 18/11/4
 * Time  : 下午3:55
 */

namespace Service;


class Task extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabao';

    /**
     * Get Instance.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 获取用户登录后的首页的弹框的接口.
     *
     * @param integer $uid      用户的uid.
     * @param string  $platform 平台.
     * @param string  $clientV  版本号.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getHomePageTipBox($uid, $platform, $clientV)
    {

        $response = $this->phpClient('Task\Task')->getHomePage($uid, $platform, $clientV, \JMSystem::GetClientIp());
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;

    }

    /**
     * 获取未登录情况下的弹框信息.
     *
     * @param string  $platform 平台.
     * @param string  $clientV  版本号.
     *
     * @return mixed
     */
    public function getNoLoginTipBox($platform, $clientV)
    {

        $response = $this->phpClient('Task\Task')->getNoLoginTipBox($platform, $clientV);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 获取用户的任务列表.
     *
     * @param integer $uid 用户uid.
     *
     * @return mixed
     */
    public function getTaskLists($uid)
    {
        $response = $this->phpClient('Task\Task')->getTaskList($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 获取任务红点情况与弹窗信息.
     *
     * @return mixed
     */
    public function getTaskRedDot($uid, $platform, $clientV, $utmSource = "")
    {
        $response = $this->phpClient('Task\Task')->getTaskRedDot($uid, $platform, $clientV, \JMSystem::GetClientIp(), $utmSource);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 获取新用户元宝奖励.
     * @param integer uid   用户uid.
     * @param integer $type 新人红包处理结果1 领取,0放弃.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handleNewUserSycee($uid, $type)
    {
        $response = $this->phpClient('Task\Task')->handleNewUserSycee($uid, $type, \JMSystem::GetClientIp());
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 获取新用户元宝奖励.
     * @param integer $uid       用户uid.
     * @param integer $awardNum  新人红包.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handleNewUserAward($uid, $awardNum)
    {
        $response = $this->phpClient('Task\Task')->handleNewUserAward($uid, $awardNum);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 获取初始任务列表.
     *
     * @param integer $uid 用户ID.
     * @param array   $ext 额外信息.
     *
     * @return array
     * @throws \Exception
     */
    public function initTask($uid, $ext = array())
    {
        try {
            $result = $this->phpClient('Api\Tasks')->initTask($uid, $ext);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'initTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取每日签到任务列表.
     *
     * @param integer $uid    用户ID,传0代表展示默认数据.
     * @param string  $source 签到来源,刷宝APP：app，刷宝小程序：applet.
     *
     * @return array
     * @throws \Exception
     */
    public function getSignInList($uid, $source = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->getSignInList($uid, $source);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getSignInList', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 每日签到.
     *
     * @param integer $uid    用户ID.
     * @param string  $source 签到来源,刷宝APP：app，刷宝小程序：applet.
     *
     * @return array
     * @throws \Exception.
     */
    public function addSignInFromToday($uid, $source = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->addSignInFromToday($uid, $source);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'addSignInFromToday', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 领取添加到我的小程序元宝.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception.
     */
    public function receiveAddMyAppletSycee($uid)
    {
        try {
            $result = $this->phpClient('Api\Tasks')->receiveAddMyAppletSycee($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'receiveAddMyAppletSycee', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 完成任务(支持任务类型：add_my_applet、clock_sign_in_applet、follow_applet、watch_video_applet、sign_in)
     *
     * @param integer $uid      用户ID.
     * @param string  $taskType 任务类型.
     * @param array   $ext      额外信息.
     *
     * @return mixed
     * @throws \Exception
     */
    public function doTask($uid, $taskType, $ext = array())
    {
        try {
            $result = $this->phpClient('Api\Tasks')->doTask($uid, $taskType, $ext);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'doTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

	/**
     * AB的元宝兑换测试方案,B方案中的元宝兑换也的列表信息
     *
     * @param integer $uid 用户uid.
     *
     * @return mixed
     */
    public function getSyceeChangeLists($uid)
    {
        $response = $this->phpClient('Task\Task')->getSyceeChangeLists($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 完成任务记录接口.
     *
     * @return mixed
     */
    public function complateVideoTask( $uid,$show_id, $source = "", $ip = "")
    {
        $response = $this->phpClient('Task\Task')->complateVideoTask( $uid,$show_id, $source, $ip);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }
    
    /**
     * 获取新版任务列表详情
     *
     * @return mixed
     */
    public function getTaskPageInfo( $uid, $device, $ip)
    {
        $response = $this->phpClient('Task\Task')->getTaskPageInfo( $uid, $device, $ip);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        
        return $response;
    }
    
    /**
     * 首次兑换奖励
     * @param integer $uid 用户UID
     * @param string $source 来源
     * @return array |boolean 弹框信息或false
     */
    public function firstChangeReward($uid, $source = "")
    {
        $response = $this->phpClient('Task\Task')->firstChangeReward( $uid, $source);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        
        return $response;
    }

    /**
     * 获取任务列表的banner配置信息
     *
     * @param integer $uid     用户UID,传0代表展示非登录数据
     * @param array   $extInfo 扩展信息array('platform' => 'ios', 'client_v' => '1.3').
     *
     * @return array
     * @throws \Exception
     */
    public function getBannerTaskList($uid, array $extInfo = array())
    {
        try {
            $result = $this->phpClient('Api\Tasks')->getBannerTaskList($uid, $extInfo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getBannerTaskList', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    // 获取邀请到的用户列表
    public function getInviteList($uid)
    {
        try {
            $result = $this->phpClient('Task\Task')->getInviteList($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getInviteList', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 更新问卷调查的任务
     * @param $uid
     * @param $task_record_id
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function updateQuestSurvey( $uid, $task_record_id, $params )
    {
        try {
            $result = $this->phpClient('Task\Task')->updateQuestSurvey( $uid, $task_record_id, $params );
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getInviteList', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 任务列表引导用户绑定手机号
     * @param $uid
     * @param $task_record_id
     * @param $phone
     * @return mixed
     * @throws \Exception
     */
    public function bindPhone( $uid, $task_record_id, $phone )
    {
        try {
            $result = $this->phpClient('Task\Task')->bindPhone( $uid, $task_record_id, $phone );
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'bindPhone', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 扫码领红包主页.
     *
     * @param integer $uid    用户ID.
     * @param array   $params 参数.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getScanReceiveRed($uid, $params = array())
    {
        try {
            $result = $this->phpClient('Task\Task')->getScanReceiveRedPage($uid, $params);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->rpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'getScanReceiveRedPage', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 是否展示扫码领红包.
     *
     * @param integer $uid    用户id.
     * @param array   $device 设备信息.
     *
     * @return boolean
     */
    public function showScanReceiveRed($uid, $device = array())
    {
        $show = false;
        try {
            $result = $this->phpClient('Task\Task')->showScanReceiveRed($uid, $device);
            if (!\PHPClient\Text::hasErrors($result)) {
                $show = $result;
            }
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'showScanReceiveRed', func_get_args(), $ex->getMessage());
        }
        return $show;
    }

    /**
     * 扫码领红包奖励A.
     *
     * @param integer $uid     用户ID.
     * @param array   $extInfo 额外信息.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMyReward($uid, $extInfo)
    {
        try {
            $result = $this->phpClient('Task\Task')->getMyReward($uid, $extInfo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->rpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'getMyReward', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 扫码领红包奖励落地页.
     *
     * @param integer $uid        用户ID.
     * @param string  $inviteCode 邀请码.
     * @param integer $timeStamp  发起邀请时间.
     * @param string  $sign       签名.
     * @param string  $ua         UA.
     * @param array   $deviceInfo 设备信息.
     *
     * @return mixed
     * @throws \Exception
     */
    public function rewardScanReceiveRed($uid, $inviteCode, $timeStamp, $sign, $ua, $deviceInfo)
    {
        try {
            $result = $this->phpClient('Task\Task')->rewardScanReceiveRed($uid, $inviteCode, $timeStamp, $sign, $ua, $deviceInfo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->rpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'rewardScanReceiveRed', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 扫码小红点.
     *
     * @param integer $uid 用户ID.
     *
     * @return mixed
     * @throws \Exception
     */
    public function showScanRedDot($uid)
    {
        try {
            $result = $this->phpClient('Task\Task')->showScanRedDot($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->rpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'showScanRedDot', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取新邀请用户逻辑页面详情.
     *
     * @return mixed
     */
    public function getNewInviteProfitInfo($uid)
    {
        $response = $this->phpClient('Task\Task')->getNewInviteProfitInfo($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 获取新邀请用户逻辑页面详情.
     *
     * @return mixed
     */
    public function getNewInviteInvitedUsers($uid, $page = 1, $pageSize = 3)
    {
        $response = $this->phpClient('Task\InviteRecord')->getNewInviteInvitedUsers($uid, $page, $pageSize);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 获取低频用户看视频得鼓励元宝列表.
     *
     * @param integer $uid      用户ID.
     * @param string  $platform 客户端操作系统平台.
     * @param string  $clientV  客户端版本号.
     *
     * @return array
     * @throws \Exception
     */
    public function getScanVideoGainEncourageSyceeList($uid, $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->getScanVideoGainEncourageSyceeList($uid, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getScanVideoGainEncourageSyceeList', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 低频用户看视频领取鼓励元宝.
     *
     * @param integer $uid      用户ID.
     * @param string  $platform 客户端操作系统平台.
     * @param string  $clientV  客户端版本号.
     *
     * @return array
     * @throws \Exception.
     */
    public function addScanVideoGainEncourageSyceeFromToday($uid, $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->addScanVideoGainEncourageSyceeFromToday($uid, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'addScanVideoGainEncourageSyceeFromToday', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 是否命中唤醒老用户ab策略.
     *
     * @param integer $uid 用户的uid.
     *
     * @return boolean
     * @throws \Exception
     */
    public function isHitWakeUpAb($uid)
    {
        $response = $this->phpClient('Task\WakeUp')->isHitAb($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 用户唤醒预期收益等信息.
     *
     * @param integer $uid 用户uid.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getWakeUpProfitInfo($uid)
    {
        $response = $this->phpClient('Task\WakeUp')->getProfitInfo($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 获取唤醒用户列表.
     *
     * @param integer $uid      用户uid.
     * @param integer $page     页码数.
     * @param integer $pageSize 每页条数.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getWakeUpUsers($uid, $page, $pageSize)
    {
        $response = $this->phpClient('Task\WakeUp')->getList($uid, $page, $pageSize);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 发送唤醒消息.
     *
     * @param integer $uid     发送人.
     * @param string  $type    发送范围方式.
     * @param integer $pushUid 发送对象.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function sendWakeUpMessage($uid, $type, $pushUid)
    {
        $response = $this->phpClient('Task\WakeUp')->sendWakeUpMessage($uid, $type, $pushUid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 是否有填写唤醒邀请码的任务.
     *
     * @param integer $uid 用户uid.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function hasFillWakeUpCodeTask($uid)
    {
        $response = $this->phpClient('Task\WakeUp')->hasFillWakeUpCodeTask($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 唤醒邀请码的弹窗弹出记录.
     *
     * @param integer $uid 用户id.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function setFillWakeUpCodePopBoxRecord($uid)
    {
        $response = $this->phpClient('Task\WakeUp')->setFillWakeUpCodePopBoxRecord($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 填写唤醒邀请码详情.
     *
     * @param integer $uid 用户id.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function getFillWakeUpCodeDetail($uid)
    {
        $response = $this->phpClient('Task\WakeUp')->getFillWakeUpCodeDetail($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 提交邀请码.
     *
     * @param integer $uid        用户id.
     * @param string  $inviteCode 邀请码.
     * @param array   $deviceInfo 设备信息.
     * @param string  $ip         IP.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function submitWakeUpCode($uid, $inviteCode, $deviceInfo, $ip)
    {
        $response = $this->phpClient('Task\WakeUp')->submitInviteCode($uid, $inviteCode, $deviceInfo, $ip);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 提交邀请码.
     *
     * @param integer $uid      用户id.
     * @param array   $userInfo 用户信息.
     *
     * @return mixed
     * @throws \Exception 异常.
     */
    public function getSleepDay($uid, $userInfo = array())
    {
        $response = $this->phpClient('Task\WakeUp')->getSleepDay($uid, $userInfo);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 判断是否为看视频得时段奖励的用户.
     *
     * @param integer $uid                           用户ID.
     * @param array   $scanVideoPeriodRewardTaskInfo 看视频得时段奖励任务配置信息.
     * @param string  $platform                      客户端操作系统平台.
     * @param string  $clientV                       客户端版本号.
     *
     * @return array
     * @throws \Exception
     */
    public function getScanVideoPeriodRewardUserByUid($uid, array $scanVideoPeriodRewardTaskInfo = array(), $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->getScanVideoPeriodRewardUserByUid($uid, $scanVideoPeriodRewardTaskInfo, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getScanVideoPeriodRewardUserByUid', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 用户看视频领取时段奖励.
     *
     * @param integer $uid      用户ID.
     * @param string  $platform 客户端操作系统平台.
     * @param string  $clientV  客户端版本号.
     *
     * @return array
     * @throws \Exception.
     */
    public function addScanVideoPeriodRewardFromToday($uid, $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->addScanVideoPeriodRewardFromToday($uid, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'addScanVideoPeriodRewardFromToday', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取最后有效用户视频push推送任务点击奖励.
     *
     * @param integer $uid      用户ID.
     * @param string  $platform 客户端操作系统平台.
     * @param string  $clientV  客户端版本号.
     *
     * @return array
     * @throws \Exception
     */
    public function getPushVideoFinal($uid, $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->getPushVideoFinal($uid, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getPushVideoFinal', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 用户视频push推送任务点击奖励(标记但不领取).
     *
     * @param integer $uid        用户uid.
     * @param string  $pushTaskId 推送push的任务标识.
     * @param string  $platform   客户端操作系统平台.
     * @param string  $clientV    客户端版本号.
     *
     * @return array
     * @throws \Exception.
     */
    public function clickRewardFromPushVideo($uid, $pushTaskId, $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->clickRewardFromPushVideo($uid, $pushTaskId, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            // 因为此接口调用频率高,所以记录有效日志.
            if (!in_array($ex->getCode(), array(670009, 670012, 670013, 670014))) {
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'clickRewardFromPushVideo', func_get_args(), $ex->getMessage());
            }
            throw $ex;
        }
    }

    /**
     * 用户视频push推送任务点击奖励(领取).
     *
     * @param integer $uid      用户uid.
     * @param string  $platform 客户端操作系统平台.
     * @param string  $clientV  客户端版本号.
     *
     * @return array
     * @throws \Exception.
     */
    public function receiveClickRewardFromPushVideo($uid, $platform = '', $clientV = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->receiveClickRewardFromPushVideo($uid, $platform, $clientV);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'receiveClickRewardFromPushVideo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 打开常驻消息栏领取元宝.
     *
     * @param integer $uid          Uid.
     * @param integer $taskRecordId 任务ID.
     * @param array   $deviceInfo   设备信心.
     *
     * @return array
     *
     * @throws \RpcBusinessException
     * 异常.
     */
    public function openNotification($uid, $taskRecordId, $deviceInfo)
    {
        try {
            $result = $this->phpClient('Task\Task')->openNotification($uid, $taskRecordId, $deviceInfo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'openNotification', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取打开常驻消息栏任务详情.
     *
     * @param string $uid        Uid.
     * @param string $deviceInfo 设备信心.
     *
     * @return array
     * @throws \RpcBusinessException 业务异常.
     */
    public function getNotificationTask($uid, $deviceInfo)
    {
        try {
            $result = $this->phpClient('Task\Task')->getNotificationTask($uid, $deviceInfo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\Task', 'getNotificationTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

}
