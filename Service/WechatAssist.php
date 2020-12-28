<?php
/**
 * Created by PhpStorm.
 * User: shijian
 * Date: 2019/1/25
 * Time: 5:40 PM
 */
namespace Service;

class WechatAssist extends ServiceBase
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

    public function getNotExpireShareInfo($shareUid)
    {
        $response = $this->phpClient('WechatAssist')->getNotExpireShareInfo($shareUid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    public function getAssistInfoByOpenidShareId($openid, $shareId)
    {
        $response = $this->phpClient('WechatAssist')->getAssistInfoByOpenidShareId($openid, $shareId);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    public function getTodayAssistNumber($openid)
    {
        $response = $this->phpClient('WechatAssist')->getTodayAssistNumber($openid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    public function recordAssist($openid, $unionid, $shareId, $shareUid)
    {
        $response = $this->phpClient('WechatAssist')->recordAssist($openid, $unionid, $shareId, $shareUid);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 获取用户助力列表.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception
     */
    public function getAssistShareList($uid)
    {
        try {
            $result = $this->phpClient('WechatAssist')->getAssistShareList($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'WechatAssist', 'getAssistShareList', func_get_args(), $ex->getMessage());
        }

        return $result;
    }

    /**
     * 添加分享数据
     * @param $uid
     * @return array
     */
    public function addAssistShare($uid)
    {
        try {
            $result = $this->phpClient('WechatAssist')->addAssistShare($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'WechatAssist', 'addAssistShare', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 判断分享分数是否达标
     */
    public function getAssistShareCount()
    {
        try {
            $result = $this->phpClient('WechatAssist')->getAssistShareCount();
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = 0;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'WechatAssist', 'getAssistShareCount', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    public function getAssistShareInfoByUid($uid)
    {
        try {
            $result = $this->phpClient('WechatAssist')->getAssistShareInfoByUid($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'WechatAssist', 'getAssistShareInfoByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }
}