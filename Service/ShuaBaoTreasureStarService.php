<?php
/**
 * Class ShuaBaoTreasureStarService.
 *
 * @author jianyoun<jianyoun@jumei.com>
 */

namespace Service;

/**
 * Class ShuaBaoTreasureStarService.
 */
class ShuaBaoTreasureStarService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoTreasureStarService';

    /**
     * Get Instance.
     *
     * @param boolean $sington 是否单例.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 根据用户id查询宝藏新星用户信息.
     *
     * @param array $params 参数数组{"uid": 2000052476, "platform": "iOS", "client_v": 1.880} .
     *
     * @return array
     * @throws \Exception 异常.
     */
    public function queryTreasureStarUserByUid($params)
    {
        $response = $this->thriftClient()->queryTreasureStarUserByUid(json_encode($params));
        return $this->checkThriftResult($response, 'queryTreasureStarUserByUid', func_get_args());
    }

    /**
     * 添加宝藏新新用户.
     *
     * @param array $params 参数数组.
     *
     * @return array
     * @throws \Exception 异常.
     */
    public function addTreasureStarUser($params)
    {
        $response = $this->thriftClient()->addTreasureStarUser(json_encode($params));
        return $this->checkThriftResult($response, 'addTreasureStarUser', func_get_args(), true);
    }

    /**
     * 查询宝藏新星视频列表.
     *
     * @param array $params 参数数组.
     *
     * @return array
     * @throws \Exception 异常.
     */
    public function queryTreasureStarVideoList($params)
    {
        $response = $this->thriftClient()->queryTreasureStarVideoList(json_encode($params));
        return $this->checkThriftResult($response, 'queryTreasureStarVideoList', func_get_args());
    }

}
