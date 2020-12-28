<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2019/05/16
 * Time: PM17:18
 */
class ShuaBaoUserSearchService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoUserSearchService';

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
     * 查询用户列表.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function searchUser($params)
    {
        // $response = $this->thriftClient()->searchUser(json_encode($params));
        $response = $this->doThriftClientByMethod('searchUser', json_encode($params));
        if (empty($response)) {
            return array();
        }
        $response = json_decode($response, true);
        if ($response['status'] != "0000") {
            \Util\Log\Service::getInstance()->thriftLog(static::$serviceName, 'searchUser', func_get_args(), $response);
            return array();
        }
        return isset($response['data']) ? $response['data'] : array();
    }

    /**
     * 查询热门用户.
     *
     * @param array $param 参数数组.
     *
     * @return array
     * @throws \Exception
     */
    public function queryHotUser($param)
    {
        $response = $this->thriftClient()->queryHotUser(json_encode($param));
        if (empty($response)) {
            return array();
        }
        $response = json_decode($response, true);
        if ($response['status'] != 0000) {
            \Util\Log\Service::getInstance()->thriftLog(self::$serviceName, 'queryHotUser', func_get_args(), $response);
            return array();
        }

        return isset($response['data']) ? $response['data'] : array();
    }

    /**
     * 查询联想列表.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function getSuggestionKeywords($params)
    {
        // $response = $this->thriftClient()->getSuggestionKeywords(json_encode($params));
        $response = $this->doThriftClientByMethod('getSuggestionKeywords', json_encode($params));
        if (empty($response)) {
            return array();
        }
        $response = json_decode($response, true);
        if ($response['status'] != "0000") {
            \Util\Log\Service::getInstance()->thriftLog(static::$serviceName, 'getSuggestionKeywords', func_get_args(), $response);
            return array();
        }
        return isset($response['data']) ? $response['data'] : array();
    }

    /**
     * 根据手机号查询uid.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryUserByPhoneNumber($params)
    {
        // $response = $this->thriftClient()->queryUserByPhoneNumber(json_encode($params));
        $response = $this->doThriftClientByMethod('queryUserByPhoneNumber', json_encode($params));
        if (empty($response)) {
            return array();
        }
        $response = json_decode($response, true);
        if ($response['status'] != "0000") {
            \Util\Log\Service::getInstance()->thriftLog(static::$serviceName, 'queryUidByPhoneNumber', func_get_args(), $response);
            return array();
        }
        return isset($response['data']) ? $response['data'] : array();
    }

}
