<?php
namespace Service;

class ShuaBaoPostSearchService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoPostSearchService';

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
     * 查询附件的人.
     *
     * @param array $params 参数数组.
     *
     * @return array
     * @throws \Exception
     */
    public function searchNearbyUser($params)
    {
        $response = $this->thriftClient()->searchNearbyUser(json_encode($params));
        if (empty($response)) {
            return array();
        }

        $response = json_decode($response, true);
        if ($response['status'] != '00000') {
            \Util\Log\Service::getInstance()->thriftLog(self::$serviceName, 'searchNearbyUser', func_get_args(), $response);
            return array();
        }

        return isset($response['data']) ? $response['data'] : array();
    }

    /**
     * 根据话题id查询视频列表.
     *
     * @param array $params 参数数组.
     *
     * @return array
     * @throws \Exception
     */
    public function searchPostByLabelId($params)
    {
        $response = $this->thriftClient()->searchPostByLabelId(json_encode($params));
        if (empty($response))
            return array();

        $response = json_decode($response, true);
        if ($response['status'] != '00000') {
            \Util\Log\Service::getInstance()->thriftLog(self::$serviceName, 'searchPostByLabelId', func_get_args(), $response);
            return array();
        }

        return isset($response['data']) ? $response['data'] : array();
    }

}
