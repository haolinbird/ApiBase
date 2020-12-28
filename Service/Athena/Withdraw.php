<?php
namespace Service\Athena;

/**
 * 提现相关接口
 */
class Withdraw extends \Service\ServiceAthena
{
    protected static $className = 'Athena\Withdraw';

    /**
     * 获取用户最近提现信息.
     *
     * @param integer $uid UID.
     *
     * @return array 提现信息.
     */
    public function getLatestWithdrawInfo($uid)
    {
        $data = $this->phpClient('Athena\Withdraw')->getLatestWithdrawInfo($uid);
        // 因为提现单中含有error字段，因此这里不能直接使用hasErrors方法进行检测
        if (!empty($data) && !empty($data['withdrawl_id'])) {
            return $data;
        }
        return array();
    }
}
