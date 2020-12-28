<?php
namespace Service;
/**
 * 刷宝打赏回复.
 * Create at 2019年5月20日 by qiangd <qiangd@jumei.com>.
 */
class ShuaBaoRewardService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoRewardService';

    /**
     *
     * 保存打赏记录
     *@param param  {"bountyType":0,"clientIp":"192.168.1.2","clientV":"1.3.3","commentId":123456,"deviceId":"设备ID","leaveMessage":"想说的话","platform":"android","receiveUid":12345,"rewardType":1,"showId":"视频ID","totalAmount":100,"uid":456}
     * bountyType:赏金类型（0:元宝）
     * rewardType:打赏类型(0:打赏视频,1:打赏评论)
     * uid:打赏人uid
     * receiveUid:被打赏人ID
     */
    public function saveReward($param)
    {
        $response = $this->thriftClient()->saveReward(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 查看打赏回复详情
     *
     * @param param {"showId":"xxx"，"rewardId":"xxx"}
     * rewardId 打赏记录ID
     * @return {"code":0,"message":"success","data":{"rewardId":"xxx","uid":"123456","receiveUid":"234567","commentId":123,"comment":"评论内容","bountyType":0,"rewardType":1,"leaveMessage":"打赏留言","createTime":1234567890,"totalAmount":100,"poundageAmount":20,"userAmount":80,"videoUrl":"http//xxxx","videoUid":123456,"majorPicUrl":{},"hasThanks":false,"attentionStatus":1}}
     * attentionStatus:关注状态（1-相互关注，2-打赏人关注了被打赏人，3-被打赏人关注了打赏人，4-互相没关注）
     */
    public function queryRewardDetail($param)
    {
        $response = $this->thriftClient()->queryRewardDetail(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 打赏回复
     *
     * @param param {"showId":"xxx"，"rewardId":"xxx","replyMessage":"谢谢"}
     * @return {"code":0,"message":"success","data":{}}
     */
    public function saveReply($param)
    {
        $response = $this->thriftClient()->saveReply(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 查看打赏回复详情
     *
     * @param param {"showId":"xxx"，"replyId":"xxx"}
     * @return {"code":0,"message":"success","data":{"rewardId":"xxx","uid":"123456","receiveUid":"234567","commentId":123,"comment":"评论内容","bountyType":0,"rewardType":1,"leaveMessage":"打赏留言","createTime":1234567890,"totalAmount":100,"poundageAmount":20,"userAmount":80,"videoUrl":"http//xxxx","videoUid":123456,"majorPicUrl":{},"replyMessage":"打赏回复","replyTime":2345678910,"attentionStatus":1}}
     * attentionStatus:关注状态（1-相互关注，2-打赏人关注了被打赏人，3-被打赏人关注了打赏人，4-互相没关注）
     */
    public function queryReplyDetail($param)
    {
        $response = $this->thriftClient()->queryReplyDetail(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 查看打赏和被打赏记录列表
     * @param param {"uid":1111,"minCreateTime":1558582241 最早时间,rows:10,rewardLastId:10 打赏最后ID第一次查询为空,receiveLastId:12 被打赏最后ID第一次查询为空}
     * @return
     */
    public function queryRewardAllListByUid($param)
    {
        $response = $this->thriftClient()->queryRewardAllListByUid(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 查看打赏记录列表(自己作为打赏人)
     * @param param {"uid":1111,"minCreateTime":1558582241 最早时间,rows:10,lastId: 10 第一次查询为空}
     * @return
     */
    public function queryRewardListByUid($param)
    {
        $response = $this->thriftClient()->queryRewardListByUid(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }

    /**
     * 查看被打赏记录列表（自己作为被打赏人）
     * @param param {"uid":1111,"minCreateTime":1558582241 最早时间,rows:10,lastId:12 第一次查询为空}
     * @return
     */
    public function queryReceiveListByUid($param)
    {
        $response = $this->thriftClient()->queryReceiveListByUid(json_encode($param));
        $result = json_decode($response, true);
        return $result;
    }
}