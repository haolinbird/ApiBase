<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class ShuaBaoShowCoreService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoShowCoreService';

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
     * 视频列表.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function recommendList($params)
    {
        if (empty($params['user_id'])) {
            $params['user_id'] = '';
        }
        $response = $this->thriftClient()->recommendList(json_encode($params));
        return $this->checkThriftResult($response, 'recommendList', func_get_args());
    }

    /**
     * 视频详情.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function querySingleDetailByShowId($params)
    {
        $response = $this->thriftClient()->querySingleDetailByShowId(json_encode($params));
        return $this->checkThriftResult($response, 'querySingleDetailByShowId', func_get_args());
    }

    /**
     * 曝光.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function exposeFeedback($params)
    {
        $response = $this->thriftClient()->exposeFeedback(json_encode($params));
        return $this->checkThriftResult($response, 'exposeFeedback', func_get_args());
    }

    /**
     * 你可能感兴趣的人.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function recommendInterestUserList($param)
    {
        $response = $this->thriftClient()->recommendInterestUserList(json_encode($param));
        return $this->checkThriftResult($response, 'recommendInterestUserList', func_get_args());
    }

    /**
     * 推荐用户列表(查看更多感兴趣的人).
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function moreRecommendInterestUserList($param)
    {
        $response = $this->thriftClient()->moreRecommendInterestUserList(json_encode($param));
        return $this->checkThriftResult($response, 'moreRecommendInterestUserList', func_get_args());
    }

    /**
     * 关注短视频列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function attentionList($param)
    {
        $response = $this->thriftClient()->attentionList(json_encode($param));
        return $this->checkThriftResult($response, 'attentionList', func_get_args());
    }

    /**
     * 热门视频推荐.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryHotSmallVideo($param)
    {
        $response = $this->thriftClient()->queryHotSmallVideo(json_encode($param));
        return $this->checkThriftResult($response, 'queryHotSmallVideo', func_get_args());
    }

    /**
     * 根据视频ID获取视频实时播放地址.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryVideoUrlByShowIds($param)
    {
        $response = $this->thriftClient()->queryVideoUrlByShowIds(json_encode($param));
        return $this->checkThriftResult($response, 'queryVideoUrlByShowIds', func_get_args());
    }
}