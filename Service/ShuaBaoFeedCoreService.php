<?php
/**
 * 社区服务信息接口.
 *
 * @author Zhongxing Wang<zhongxingw@jumei.com>
 */

namespace Service;

/**
 * Created by PhpStorm.
 */
class ShuaBaoFeedCoreService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoFeedCoreService';

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
     * 获取关注流信息接口.
     *
     * @param array $params 请求参数. {"uid": 108674518, "platform": "iOS", "client_v": 1.550}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function queryAttentionFeedInfo($params)
    {
        $response = $this->doThriftClientByMethod('queryAttentionFeedInfo', json_encode($params));
        return $this->checkThriftResult($response, 'queryAttentionFeedInfo', func_get_args());
    }

    /**
     * 获取关注流列表接口.
     *
     * @param array $params 请求参数. {"uid": 108674518, "offset": 0, "page_size": 10, "platform": "iOS", "client_v": 1.550}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function queryAttentionFeedList($params)
    {
        $response = $this->doThriftClientByMethod('queryAttentionFeedList', json_encode($params));
        return $this->checkThriftResult($response, 'queryAttentionFeedList', func_get_args());
    }

    /**
     * 曝光关注接口.
     *
     * @param array $params 请求参数. {"uid": 108674518, "show_id":"SMALL_VIDEO_3792880",  "platform": "iOS", "client_v": 1.550}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function exposeAttentionFeed($params)
    {
        $response = $this->doThriftClientByMethod('exposeAttentionFeed', json_encode($params));
        return $this->checkThriftResult($response, 'exposeAttentionFeed', func_get_args());
    }

    /**
     * 关注流小红点接口.
     *
     * @param array $params 请求参数. {"uid": 108674518, "platform": "iOS", "client_v": 1.550}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function redPoint($params)
    {
        $response = $this->doThriftClientByMethod('redPoint', json_encode($params));
        return $this->checkThriftResult($response, 'redPoint', func_get_args());
    }

    /**
     * 直播列表接口.
     *
     * @param array $params 请求参数. {"uid": 108674518, "offset": 0, "platform": "iOS", "client_v": 1.550}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveList($params)
    {
        $response = $this->doThriftClientByMethod('liveList', json_encode($params));
        return $this->checkThriftResult($response, 'liveList', func_get_args());
    }

    /**
     * 视频列表接口.
     *
     * @param array $params 请求参数. {"uid": 108674518, "offset": "0", "page_size": 10, "platform": "iOS", "client_v": 1.550}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function showList($params)
    {
        $response = $this->doThriftClientByMethod('showList', json_encode($params));
        return $this->checkThriftResult($response, 'showList', func_get_args());
    }

}
