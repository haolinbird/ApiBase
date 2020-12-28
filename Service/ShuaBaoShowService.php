<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class ShuaBaoShowService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoShowService';

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
        // $response = $this->thriftClient()->recommendList(json_encode($params));
        $response = $this->doThriftClientByMethod('recommendList', json_encode($params));
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
        // $response = $this->thriftClient()->querySingleDetailByShowId(json_encode($params));
        $response = $this->doThriftClientByMethod('querySingleDetailByShowId', json_encode($params));
        return $this->checkThriftResult($response, 'querySingleDetailByShowId', func_get_args());
    }

    /**
     * 点赞.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function praise($params)
    {
        // $response = $this->thriftClient()->praise(json_encode($params));
        $response = $this->doThriftClientByMethod('praise', json_encode($params));
        return $this->checkThriftResult($response, 'praise', func_get_args(), true);
    }

    /**
     * 取消点赞.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function cancelPraise($params)
    {
        // $response = $this->thriftClient()->cancelPraise(json_encode($params));
        $response = $this->doThriftClientByMethod('cancelPraise', json_encode($params));
        return $this->checkThriftResult($response, 'cancelPraise', func_get_args(), true);
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
        // $response = $this->thriftClient()->exposeFeedback(json_encode($params));
        $response = $this->doThriftClientByMethod('exposeFeedback', json_encode($params));
        return $this->checkThriftResult($response, 'exposeFeedback', func_get_args());
    }

    /**
     * 标签聚合页接口.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function labelGartherList($params)
    {
        // $response = $this->thriftClient()->labelGartherList(json_encode($params));
        $response = $this->doThriftClientByMethod('labelGartherList', json_encode($params));
        return $this->checkThriftResult($response, 'labelGartherList', func_get_args());
    }

    /**
     * 地址聚合页接口.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function locationGartherList($params)
    {
        // $response = $this->thriftClient()->locationGartherList(json_encode($params));
        $response = $this->doThriftClientByMethod('locationGartherList', json_encode($params));
        return $this->checkThriftResult($response, 'locationGartherList', func_get_args());
    }

    /**
     * 音频聚合页接口.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function musicGartherList($params)
    {
        // $response = $this->thriftClient()->musicGartherList(json_encode($params));
        $response = $this->doThriftClientByMethod('musicGartherList', json_encode($params));
        return $this->checkThriftResult($response, 'musicGartherList', func_get_args());
    }

    /**
     * 分享成功接口.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function sharePostCallBack($params)
    {
        // $response = $this->thriftClient()->sharePostCallBack(json_encode($params));
        $response = $this->doThriftClientByMethod('sharePostCallBack', json_encode($params));
        // #169091 提现门槛调整加入任务元素ab测试 start.
        $uid = \Module\Account::instance()->getUid();
        $redis = \Redis\RedisStorage::getInstance('default');
        if($redis->HEXISTS('withdraw_169091_abtest', $uid)) {
            // 是否分享过.
            $time = time();
            $day = date('Ymd', $time);
            $key = "withdrawShareCnt".$day."_".$uid;
            // 可提现次数.
            $withdrawCnt = "withdrawCnt".$day."_".$uid;
            if(!$redis->exists($key)) {
                $expire = strtotime('tomorrow') - $time;
                $redis->setex($key, $expire, 1);
                if(!$redis->exists($withdrawCnt)) {
                    $redis->setex($withdrawCnt, $expire, 1);
                } else {
                    $redis->incr($withdrawCnt);
                }
            }
        }
        // #169091 提现门槛调整加入任务元素ab测试 end.
        return $this->checkThriftResult($response, 'sharePostCallBack', func_get_args());
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
        // $response = $this->thriftClient()->recommendInterestUserList(json_encode($param));
        $response = $this->doThriftClientByMethod('recommendInterestUserList', json_encode($param));
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
        // $response = $this->thriftClient()->moreRecommendInterestUserList(json_encode($param));
        $response = $this->doThriftClientByMethod('moreRecommendInterestUserList', json_encode($param));
        return $this->checkThriftResult($response, 'moreRecommendInterestUserList', func_get_args());
    }

    /**
     * 关闭推荐用户.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function dislikeAttention($param)
    {
        // $response = $this->thriftClient()->dislikeAttention(json_encode($param));
        $response = $this->doThriftClientByMethod('dislikeAttention', json_encode($param));
        return $this->checkThriftResult($response, 'dislikeAttention', func_get_args());
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
        // $response = $this->thriftClient()->attentionList(json_encode($param));
        $response = $this->doThriftClientByMethod('attentionList', json_encode($param));
        return $this->checkThriftResult($response, 'attentionList', func_get_args());
    }

    /**
     * 举报类型.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function getReportTypes($param)
    {
        // $response = $this->thriftClient()->getReportTypes(json_encode($param));
        $response = $this->doThriftClientByMethod('getReportTypes', json_encode($param));
        return $this->checkThriftResult($response, 'getReportTypes', func_get_args());
    }

    /**
     * 举报接口.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function report($param)
    {
        // $response = $this->thriftClient()->report(json_encode($param));
        $response = $this->doThriftClientByMethod('report', json_encode($param));
        return $this->checkThriftResult($response, 'report', func_get_args(), true);
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
        // $response = $this->thriftClient()->queryHotSmallVideo(json_encode($param));
        $response = $this->doThriftClientByMethod('queryHotSmallVideo', json_encode($param));
        return $this->checkThriftResult($response, 'queryHotSmallVideo', func_get_args());
    }

    /**
     * 删除视频帖子.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function hideShow($param)
    {
        // $response = $this->thriftClient()->hideShow(json_encode($param));
        $response = $this->doThriftClientByMethod('hideShow', json_encode($param));
        return $this->checkThriftResult($response, 'hideShow', func_get_args(), true);
    }

    /**
     * 视频话题列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function labelList($param)
    {
        // $response = $this->thriftClient()->labelList(json_encode($param));
        $response = $this->doThriftClientByMethod('labelList', json_encode($param));
        return $this->checkThriftResult($response, 'labelList', func_get_args());
    }

    /**
     * 验证标签是否是黑名单标签.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function validateLabel($param)
    {
        // $response = $this->thriftClient()->validateLabel(json_encode($param));
        $response = $this->doThriftClientByMethod('validateLabel', json_encode($param));
        return $this->checkThriftResult($response, 'validateLabel', func_get_args());
    }

    /**
     * 添加视频.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function addSmallVideo($param)
    {
        // $response = $this->thriftClient()->addSmallVideo(json_encode($param));
        $response = $this->doThriftClientByMethod('addSmallVideo', json_encode($param));
        \Utils\Log\Logger::instance()->logNew($response);
        return $this->checkThriftResult($response, 'addSmallVideo', func_get_args(), true);
    }

    /**
     * 查询音乐分类.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryMusicTypes($param)
    {
        // $response = $this->thriftClient()->queryMusicTypes(json_encode($param));
        $response = $this->doThriftClientByMethod('queryMusicTypes', json_encode($param));
        return $this->checkThriftResult($response, 'queryMusicTypes', func_get_args());
    }

    /**
     * 根据分类查询音乐.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryMusicsByType($param)
    {
        // $response = $this->thriftClient()->queryMusics(json_encode($param));
        $response = $this->doThriftClientByMethod('queryMusics', json_encode($param));
        return $this->checkThriftResult($response, 'queryMusics', func_get_args());
    }

    /**
     * 收藏音乐.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function collectMusic($param)
    {
        // $response = $this->thriftClient()->collectMusic(json_encode($param));
        $response = $this->doThriftClientByMethod('collectMusic', json_encode($param));
        return $this->checkThriftResult($response, 'collectMusic', func_get_args(), true);
    }

    /**
     * 取消收藏音乐.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function cancelCollectMusic($param)
    {
        // $response = $this->thriftClient()->cancelCollectMusic(json_encode($param));
        $response = $this->doThriftClientByMethod('cancelCollectMusic', json_encode($param));
        return $this->checkThriftResult($response, 'cancelCollectMusic', func_get_args(), true);
    }

    /**
     * 查询收藏音乐列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryCollectMusics($param)
    {
        // $response = $this->thriftClient()->queryCollectMusics(json_encode($param));
        $response = $this->doThriftClientByMethod('queryCollectMusics', json_encode($param));
        return $this->checkThriftResult($response, 'queryCollectMusics', func_get_args());
    }

    /**
     * 音乐列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryMusics($param)
    {
        // $response = $this->thriftClient()->queryMusics(json_encode($param));
        $response = $this->doThriftClientByMethod('queryMusics', json_encode($param));
        return $this->checkThriftResult($response, 'queryMusics', func_get_args());
    }

    /**
     * 腾讯万象优图图片上传的签名信息接口.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function picSign($param)
    {
        // $response = $this->thriftClient()->picSign(json_encode($param));
        $response = $this->doThriftClientByMethod('picSign', json_encode($param));
        return $this->checkThriftResult($response, 'picSign', func_get_args());
    }

    /**
     * 种草列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function seedingList($param)
    {
        // $response = $this->thriftClient()->seedingList(json_encode($param));
        $response = $this->doThriftClientByMethod('seedingList', json_encode($param));
        return $this->checkThriftResult($response, 'seedingList', func_get_args());
    }

    /**
     * 感兴趣图文贴列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function interestPicturePostList($param)
    {
        // $response = $this->thriftClient()->interestPicturePostList(json_encode($param));
        $response = $this->doThriftClientByMethod('interestPicturePostList', json_encode($param));
        return $this->checkThriftResult($response, 'interestPicturePostList', func_get_args());
    }

    /**
     * 发布图文贴（汪汪使用）.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function addPicturePost($param)
    {
        // $response = $this->thriftClient()->addPicturePost(json_encode($param));
        $response = $this->doThriftClientByMethod('addPicturePost', json_encode($param));
        return $this->checkThriftResult($response, 'addPicturePost', func_get_args(), true);
    }

    /**
     * 查询图文贴列表以及猜你喜欢列表（汪汪使用）.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryPicturePostList($param)
    {
        // $response = $this->thriftClient()->queryPicturePostList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryPicturePostList', json_encode($param));
        return $this->checkThriftResult($response, 'queryPicturePostList', func_get_args());
    }

    /**
     * 查询喜欢的图文贴列表（汪汪使用）.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryLikePicturePostList($param)
    {
        // $response = $this->thriftClient()->queryLikePicturePostList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryLikePicturePostList', json_encode($param));
        return $this->checkThriftResult($response, 'queryLikePicturePostList', func_get_args());
    }

    /**
     * 查询我的图文贴列表（汪汪使用）.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryMyPicturePostList($param)
    {
        // $response = $this->thriftClient()->queryMyPicturePostList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryMyPicturePostList', json_encode($param));
        return $this->checkThriftResult($response, 'queryMyPicturePostList', func_get_args());
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
        // $response = $this->thriftClient()->queryVideoUrlByShowIds(json_encode($param));
        $response = $this->doThriftClientByMethod('queryVideoUrlByShowIds', json_encode($param));
        return $this->checkThriftResult($response, 'queryVideoUrlByShowIds', func_get_args());
    }


    /**
     * 在视频上绑定商品
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function bindGoodsOnVideo($param)
    {
        // $response = $this->thriftClient()->bindGoodsOnVideo(json_encode($param));
        $response = $this->doThriftClientByMethod('bindGoodsOnVideo', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 解除视频的商品绑定
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function unbindGoodsOnVideo($param)
    {
        // $response = $this->thriftClient()->unbindGoodsOnVideo(json_encode($param));
        $response = $this->doThriftClientByMethod('unbindGoodsOnVideo', json_encode($param));
        return json_decode($response,true);
    }

   /**
     * 通过帖子id批量查询帖子详情.
     *
     * @param array $param 参数数组.
     *
     * @return array
     * @throws \Exception
     */
    public function queryShowDetailByShowIds($param)
    {
        $response = $this->thriftClient()->queryShowDetailByShowIds(json_encode($param));
        return $this->checkThriftResult($response, 'queryShowDetailByShowIds', func_get_args());
    }

    /**
     * 通过标签id批量查询标签详情.
     *
     * @param array $param 参数数组.
     *
     * @return array
     * @throws \Exception
     */
    public function queryLabelDetailBylabelIds($param)
    {
        $response = $this->thriftClient()->queryLabelDetailBylabelIds(json_encode($param));
        return $this->checkThriftResult($response, 'queryLabelDetailBylabelIds', func_get_args());
    }

    /**
     * 添加私信.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function addPrivateLetter($param)
    {
        $response = $this->doThriftClientByMethod('addPrivateLetter', json_encode($param));
        return json_decode($response,true);
    }

}
