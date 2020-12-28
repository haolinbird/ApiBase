<?php
namespace Service\Zeus;

use Service\ServiceBase;
/**
 * 广告用户管理.
 * @user kuid<kuid@jumei.com
 * @date 2018年11月20日
 */
class User extends ServiceBase
{
    /**
     * Get Instance.
     *
     * @return \Service\UserInfo
     */
    public static function instance($sington = false)
    {
        return parent::instance($sington);
    }

    /**
     * 用户账号密码登录.
     *
     * @param string $username 用户名.
     * @param string $password 密码.
     *
     * @return mixed
     */
    public function authLogin($username, $password)
    {
        $password = md5($password);
        $res = $this->phpClient('Zeus\User')->authLogin($username, $password);

        return $res;
    }

    /**
     * 获取用户账号信息.
     *
     * @param integer $uid 用户ID.
     *
     * @return mixed
     */
    public function getUserInfo($uid)
    {
        $res = $this->phpClient('Zeus\User')->getUserInfo($uid);

        return $res;
    }

    /**
     * 更新用户账号信息.
     *
     * @param array $params    更新数据.
     * @param array $condition 条件.
     *
     * @return integer.
     */
    public function updateUser($params, $condition)
    {
        $res = $this->phpClient('Zeus\User')->updateUser($params, $condition);

        return $res;
    }

    /**
     * 新增用户账号.
     *
     * @param array $data 新增数据.
     *
     * @return integer.
     */
    public function addUser($data)
    {
        $res = $this->phpClient('Zeus\User')->addUser($data);

        return $res;
    }

}