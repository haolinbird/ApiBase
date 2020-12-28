<?php
namespace Service;

class UserService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'UserService';

    /**
     * Get Instance.
     *
     * @param boolean $sington 是否单例.
     *
     * @return \Service\UserService
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 获取用户标签.
     *
     * @param integer $uid  用户uid.
     * @param string $label 标签名字, 如finance.
     *
     * @return array
     */
    public function getUserLabel($uid, $label)
    {
        $res = $this->phpClient('UserLabel')->getUserLabel($uid, $label);
        if ($res['error'] != 0) {
            $this->rpcBusinessException($res['msg']);
        }
        return $res['data'];
    }

}