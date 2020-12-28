<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class ShuaBaoCommentService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoCommentService';

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
     * 评论列表.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     */
    public function commentList($params)
    {
        $response = $this->thriftClient()->commentList(json_encode($params));
        return $this->checkThriftResult($response, 'commentList', func_get_args());
    }

    /**
     * 新增评论.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     */
    public function addComment($params)
    {
        $response = $this->thriftClient()->addComment(json_encode($params));
        // #169091 提现门槛调整加入任务元素ab测试 start.
        $uid = \Module\Account::instance()->getUid();
        $redis = \Redis\RedisStorage::getInstance('default');
        if($redis->HEXISTS('withdraw_169091_abtest', $uid)) {
            // 是否写过评论.
            $time = time();
            $day = date('Ymd', $time);
            $key = "withdrawConmmentCnt".$day."_".$uid;
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
        // 添加返回原值参数.
        $result = $this->checkThriftResult($response, 'addComment', func_get_args(), true);
        if (isset($result['code']) && $result['code'] != 0) {
            // 如果错误码是1000 则直接用接口返回的message
            if ($result['code'] == 1000) {
                $this->rpcBusinessException($result['message']);
            }
            return array();
        }
        return $result;
    }

    /**
     * 点赞评论.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     */
    public function praiseComment($params)
    {
        $response = $this->thriftClient()->praiseComment(json_encode($params));
        return $this->checkThriftResult($response, 'praiseComment', func_get_args(), true);
    }

    /**
     * 取消点赞评论.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     */
    public function canclePraiseComment($params)
    {
        $response = $this->thriftClient()->canclePraiseComment(json_encode($params));
        return $this->checkThriftResult($response, 'canclePraiseComment', func_get_args(), true);
    }

    /**
     * 删除评论.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     */
    public function deleteComment($params)
    {
        $response = $this->thriftClient()->deleteComment(json_encode($params));
        return $this->checkThriftResult($response, 'deleteComment', func_get_args());
    }

    /**
     * 查询二级评论列表.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function secondCommentList($params)
    {
        $response = $this->thriftClient()->secondCommentList(json_encode($params));
        return $this->checkThriftResult($response, 'secondCommentList', func_get_args());
    }

    /**
     * 检查评论是否存在.
     *
     * @param array $params 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function checkCommentExist($params)
    {
        $response = $this->thriftClient()->checkCommentExist(json_encode($params));
        return $this->checkThriftResult($response, 'checkCommentExist', func_get_args());
    }

    /**
     * 查询视频用户的评论数
     * @param param{"show_id":"SMALL_VIDEO_100267275","user_id":"568800028","startTime":"1550866807","endTime":"1560866807"}
     * @return {"code":0,"data":{"count":0},"message":"success"}
     * @throws org.apache.thrift.TException
     */
    public function queryShowUserCommentCount($params)
    {
        $response = $this->thriftClient()->queryShowUserCommentCount(json_encode($params));
        return $this->checkThriftResult($response, 'queryShowUserCommentCount', func_get_args());
    }

}