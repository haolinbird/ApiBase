<?php
namespace Service;

class ShuaBaoLabelSearchService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoLabelSearchService';

    /**
     * Get Instance.
     *
     * @param bool $sington
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington); // TODO: Change the autogenerated stub
    }

    /**
     * 获取热门话题.
     *
     * @param array $param
     *
     * @return array
     * @throws \Exception
     */
    public function queryHotLabel($param)
    {
        $response = $this->thriftClient()->queryHotLabel(json_encode($param));
        if (empty($response)) {
            return [];
        }

        $response = json_decode($response, true);
        if ($response['status'] != '00000') {
            \Util\Log\Service::getInstance()->thriftLog(self::$serviceName, 'queryHotLabel', func_get_args(), $response);
            return [];
        }

        return isset($response['data']) ? $response['data'] : [];
    }

    /**
     * 联想搜索.
     *
     * @param array $param 参数.
     *
     * @return array
     * @throws \Exception
     */
    public function getSuggestionKeywords($param)
    {
        $response = $this->thriftClient()->getSuggestionKeywords(json_encode($param));
        if (empty($response)) {
            return [];
        }

        $response = json_decode($response, true);
        if ($response['status'] != '00000') {
            \Util\Log\Service::getInstance()->thriftLog(self::$serviceName, 'getSuggestionKeywords', func_get_args(), $response);
            return [];
        }

        return isset($response['data']) ? $response['data'] : [];
    }
}