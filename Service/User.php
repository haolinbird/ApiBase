<?php
namespace Service;

/**
 * 用户相关接口
 */
class User extends \Service\ShuaBaoService
{
    protected static $className = 'Users';

    public function getShuaBaoUserInfoByUid($uid)
    {
        $response = $this->phpClient('Users')->getShuaBaoUserInfoByUid($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 检测是否填写过活动信息
     *
     * @param $uid
     * @param $goods_type
     * @return mixed
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function CheckGoodsDown($uid, $goods_type)
    {
        $response = $this->phpClient('Users')->CheckGoodsDown($uid, $goods_type);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 填写奖励领取信息
     *
     * @param $params
     * @return mixed
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function InsertGoodsDown($params)
    {
        $response = $this->phpClient('Users')->InsertGoodsDown($params);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 通过uid检查刷宝账号是否可用.
     *
     * @param integer $uid 用户ID.
     *
     * @return boolean 返回true=可用,false=不可用.
     */
    public function isAccountAvailable($uid)
    {
        $response = $this->phpClient()->isAccountAvailable($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 绑定手机.
     *
     * @param string $uid    用户id.
     * @param string $mobile 手机号码.
     *
     * @return mixed
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function bindMobile($uid, $mobile)
    {
        $response = $this->phpClient('Users')->bindMobile($uid, $mobile);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

}
