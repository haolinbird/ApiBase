<?php
namespace Service\Api;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/03
 * Time: 上午16:45
 */
class VideoLive extends \Service\ServiceBase
{
    public static $className = 'Api\VideoLive';


    // 判断是否参与XX充值活动.
    public function checkJoinRechargeAct($uid, $act)
    {
        $showFirstAct = 0;
        try {
            $showFirstAct = $this->phpClient('Api\VideoLive')->checkJoinRechargeAct($uid, $act);
        } catch (\Exception $e) {
            // 异常不处理.
        }
        return $showFirstAct;
    }

    // 判断是否参与首充活动.
    public function checkJoinFirstRechargeAct($uid)
    {
        return $this->checkJoinRechargeAct($uid, 'video_live_reward_first');
    }

    /**
     * 获取充值活动ID.
     *
     * @param integer $uid        用户ID.
     * @param integer $rechargeId 充值ID.
     * @param array   $device     设备信息.
     *
     * @return string
     */
    public function getRechargeActId($uid, $rechargeId = 0, $device = array())
    {
        $activityId = '';
        $platform = strtolower($device['platform']);
        $switch = \Config\VideoLive::$rechargeAct['switch'];
        if ($this->phpClient(self::$className)->checkFirstRecharge($uid, $rechargeId)) {
            $activityId = $switch[$platform]['first'] == 1 ? 'recharge_first' : '';
        } elseif ($switch[$platform]['common'] == 1) {
            $activityId = 'recharge';
        }
        return $activityId;
    }

}
