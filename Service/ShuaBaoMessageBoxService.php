<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2019/04/15
 * Time: PM16:54
 */
class ShuaBaoMessageBoxService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoMessageBoxService';

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
     * 查询合作消息设置.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function queryCooperationMessageSetting($params)
    {
        $response = $this->thriftClient()->queryCooperationMessageSetting(json_encode($params));
        return $this->checkThriftResult($response, 'queryCooperationMessageSetting', func_get_args());
    }

    /**
     * 合作消息设置.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function setCooperationMessageSetting($params)
    {
        $response = $this->thriftClient()->setCooperationMessageSetting(json_encode($params));
        return $this->checkThriftResult($response, 'setCooperationMessageSetting', func_get_args(), true);
    }

    /**
     * 添加合作消息.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function addCooperationMessage($params)
    {
        $response = $this->thriftClient()->addCooperationMessage(json_encode($params));
        return $this->checkThriftResult($response, 'addCooperationMessage', func_get_args(), true);
    }

    /**
     * 处理合作消息状态.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function updateCooperationMessageStatus($params)
    {
        $response = $this->thriftClient()->updateCooperationMessageStatus(json_encode($params));
        return $this->checkThriftResult($response, 'updateCooperationMessageStatus', func_get_args(), true);
    }

    /**
     * 查询合作消息详情.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function queryCooperationMessageDetail($params)
    {
        $response = $this->thriftClient()->queryCooperationMessageDetail(json_encode($params));
        return $this->checkThriftResult($response, 'queryCooperationMessageDetail', func_get_args());
    }

    /**
     * 互动push设置查询.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function queryInteractPushSetting($params)
    {
        $response = $this->thriftClient()->queryInteractPushSetting(json_encode($params));
        return $this->checkThriftResult($response, 'queryInteractPushSetting', func_get_args());
    }

    /**
     * 互动push设置.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function setInteractPushSetting($params)
    {
        $response = $this->thriftClient()->setInteractPushSetting(json_encode($params));
        return $this->checkThriftResult($response, 'setInteractPushSetting', func_get_args(), true);
    }

}