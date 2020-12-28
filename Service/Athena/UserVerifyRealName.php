<?php
/**
 * 雅典娜后台实名认证.
 *
 * @author qiangd <qiangd@jumei.com>
 */

namespace Service\Athena;

/**
 * Create at 2019年8月16日 by qiangd <qiangd@jumei.com>.
 */
class UserVerifyRealName extends \Service\ServiceAthena
{

    protected static $className = 'Athena\UserVerifyRealName';

    /**
     * 获取最近一段时间用户签到的时间列表.
     *
     * @param integer $uid      用户uid.
     * @param string  $idCard   查询条件.
     * @param integer $page     第几页.
     * @param integer $pageSize 每页条数.
     *
     * @return mixed
     */
    public function getList($uid, $idCard, $page, $pageSize)
    {
        $idCard = trim($idCard);
        if (! empty($uid)) {
            $cond = array ();
            if (! empty($idCard)) {
                $cond ['id_card'] = $idCard;
            }
            $res = $this->phpClient()
                ->getListByCond($uid, $cond, $page, $pageSize);
            if (\PHPClient\Text::hasErrors($res)) {
                $this->RpcBusinessException($res ['message'], $res ['code']);
            }
            return $res;
        }
        $res = $this->phpClient()->getIdCardVerifyInfo($idCard, $page, $pageSize);
        if (\PHPClient\Text::hasErrors($res)) {
            $this->RpcBusinessException($res ['message'], $res ['code']);
        }
        return $res;
    }

    /**
     * 删除一条认证记录.
     *
     * @param integer $verifyId 记录ID.
     * @param integer $uid      用户ID.
     *
     * @return mixed
     */
    public function deleteVerify($verifyId, $uid)
    {
        $verifyId = trim($verifyId);
        $res = $this->phpClient()->deleteVerify($verifyId, $uid);
        if (\PHPClient\Text::hasErrors($res)) {
            $this->RpcBusinessException($res ['message'], $res ['code']);
        }
        return $res;
    }

}
