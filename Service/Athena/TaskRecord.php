<?php
/**
 * 任务相关服务.
 */

namespace Service\Athena;

/**
 * 任务相关服务.
 */
class TaskRecord extends \Service\ServiceAthena
{

    protected static $className = 'Athena\TaskRecord';

    /**
     * 获取最近一段时间用户签到的时间列表.
     *
     * @param integer $uid          用户ID.
     * @param integer $registerTime 注册时间.
     * @param integer $dayNum       时间范围.
     *
     * @return array
     */
    public function getRecentSignInDays($uid, $registerTime, $dayNum)
    {
        // 结束时间为当前
        $endTime = time();
        // 计算开始时间
        $startTime = strtotime("-{$dayNum}days");
        $startTime = strtotime(date('Y-m-d', $startTime));
        if ($startTime < $registerTime) {
            $startTime = $registerTime;
        }
        // 获取签到日期列表
        $taskTypes = array(
            'sign_in_app'
        );
        $signInDays = $this->phpClient('Athena\TaskRecord')->getJoinTaskDays($uid, $taskTypes, $startTime, $endTime);
        if (\PHPClient\Text::hasErrors($signInDays)) {
            $this->RpcBusinessException($signInDays['message'], $signInDays['code']);
        }
        return $signInDays;
    }

}
