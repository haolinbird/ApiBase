<?php
namespace Service;

/**
 * 刷宝直播服务转发thrift接口调用.
 *
 * @author Zhongxing Wang<zhongxingw@jumei.com>
 */
class ShuaBaoVideoLiveService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoLiveService';

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
     * 创建直播间接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function createLive($params)
    {
        $response = $this->doThriftClientByMethod('createLive', json_encode($params));
        return $this->checkThriftProxyResult($response, 'createLive', func_get_args());
    }

    /**
     * 开始直播接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function startLive($params)
    {
        $response = $this->doThriftClientByMethod('startLive', json_encode($params));
        return $this->checkThriftProxyResult($response, 'startLive', func_get_args());
    }

    /**
     * 获取直播间信息接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getLiveInfo($params)
    {
        $response = $this->doThriftClientByMethod('getLiveInfo', json_encode($params));
        return $this->checkThriftProxyResult($response, 'getLiveInfo', func_get_args());
    }

    /**
     * 直播间主播端心推送接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function heartBreak($params)
    {
        $response = $this->doThriftClientByMethod('heartBreak', json_encode($params));
        return $this->checkThriftProxyResult($response, 'heartBreak', func_get_args());
    }

    /**
     * 主播退出直播.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function anchorQuit($params)
    {
        $response = $this->doThriftClientByMethod('anchorQuit', json_encode($params));
        return $this->checkThriftProxyResult($response, 'anchorQuit', func_get_args());
    }

    /**
     * 观众进入直播间接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function joinLive($params)
    {
        $response = $this->doThriftClientByMethod('joinLive', json_encode($params));
        return $this->checkThriftProxyResult($response, 'joinLive', func_get_args());
    }

    /**
     * 观众退出直播间接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function viewerQuit($params)
    {
        $response = $this->doThriftClientByMethod('viewerQuit', json_encode($params));
        return $this->checkThriftProxyResult($response, 'viewerQuit', func_get_args());
    }

    /**
     * 获取是否有直播权限等信息接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getLiveConfigInfo($params)
    {
        $response = $this->doThriftClientByMethod('getLiveConfigInfo', json_encode($params));
        return $this->checkThriftProxyResult($response, 'getLiveConfigInfo', func_get_args());
    }

    /**
     * 获取文件上传签名等信息接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getFileUploadInfo($params)
    {
        $response = $this->doThriftClientByMethod('getFileUploadInfo', json_encode($params));
        return $this->checkThriftProxyResult($response, 'getFileUploadInfo', func_get_args());
    }

    /**
     * 获取直播间签名信息接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getLiveSignature($params)
    {
        $response = $this->doThriftClientByMethod('getLiveSignature', json_encode($params));
        return $this->checkThriftProxyResult($response, 'getLiveSignature', func_get_args());
    }

    /**
     * 获取直播间签名信息接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getIMSignature($params)
    {
        $response = $this->doThriftClientByMethod('getIMSignature', json_encode($params));
        return $this->checkThriftProxyResult($response, 'getIMSignature', func_get_args());
    }

    /**
     * 加入直播间成功回调.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function joinLiveSuccessCallback($params)
    {
        $response = $this->doThriftClientByMethod('joinLiveSuccessCallback', json_encode($params));
        return $this->checkThriftProxyResult($response, 'joinLiveSuccessCallback', func_get_args());
    }

    /**
     * 禁言用户.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function speakForbid($params)
    {
        $response = $this->doThriftClientByMethod('speakForbid', json_encode($params));
        return $this->checkThriftProxyResult($response, 'speakForbid', func_get_args(), true);
    }

    /**
     * 禁言用户.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function cancelSpeakForbid($params)
    {
        $response = $this->doThriftClientByMethod('cancelSpeakForbid', json_encode($params));
        return $this->checkThriftProxyResult($response, 'cancelSpeakForbid', func_get_args());
    }

    /**
     * 禁言用户.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getAnchorInfo($params)
    {
        $response = $this->doThriftClientByMethod('getAnchorInfo', json_encode($params));
        return $this->checkThriftProxyResult($response, 'getAnchorInfo', func_get_args());
    }

    /**
     * 发送元宝打赏IM消息.
     *
     * @param array $params 请求参数.
     *
     *      {"imId": "@TGS#37PV7YZFO", "count":10, "uid": "打赏人uid"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function sendYBGratuityMessage($params)
    {
        $response = $this->doThriftClientByMethod('sendYBGratuityMessage', json_encode($params));
        return $this->checkThriftProxyResult($response, 'sendYBGratuityMessage', func_get_args());
    }

    /**
     * 查询是否有直播.
     *
     * @param array $params 请求参数.
     *
     *     {"shuabao_login_uid": "108674518", "attention":true, "platform":"Android","client_v":"1.400"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function hasLive($params)
    {
        $response = $this->doThriftClientByMethod('hasLive', json_encode($params));
        return $this->checkThriftResult($response, 'hasLive', func_get_args());
    }

    /**
     * 查询打赏礼物列表.
     *
     * @param array $params 请求参数.
     *
     *     {"client_v": 1.450, "reward_uid": 108674518, "platform": "ios"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getGifts($params)
    {
        $response = $this->doThriftClientByMethod('getGifts', json_encode($params));
        return $this->checkThriftResult($response, 'getGifts', func_get_args());
    }

    /**
     * 打赏主播.
     *
     * @param array $params 请求参数.
     *
     *     {"client_v": 1.450, "platform": "ios", "reward_uid": 108674518, "payee_uid": 110811729, "room_id": 3293, "gift_id": 1, "gift_count": 10, "yb_count": 100}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function reward($params)
    {
        $response = $this->doThriftClientByMethod('reward', json_encode($params));
        return $this->checkThriftResult($response, 'reward', func_get_args(), true);
    }

    /**
     * 打赏排行榜.
     *
     * @param array $params 请求参数.{"roomId":"房间号","payeeUid":"主播uid","rewardUid":"打赏人uid(当前查看人uid)","type":"0 当前房间榜单,1 周榜单"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function rewardTop($params)
    {
        $response = $this->doThriftClientByMethod('rewardTop', json_encode($params));
        return $this->checkThriftResult($response, 'rewardTop', func_get_args());
    }

    /**
     * 获取直播用户信息.
     *
     * @param array $params 请求参数.{"uid":"用户uid"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveUserInfo($params)
    {
        $response = $this->doThriftClientByMethod('liveUserInfo', json_encode($params));
        return $this->checkThriftResult($response, 'liveUserInfo', func_get_args());
    }

    /**
     * 获取直播时的广告推广余额.
     *
     * @param array $params 请求参数.{"shuabao_login_uid": "108674518"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function advertSurplus($params)
    {
        $response = $this->doThriftClientByMethod('advertSurplus', json_encode($params));
        return $this->checkThriftResult($response, 'advertSurplus', func_get_args());
    }

    /**
     * 获取直播详情.
     *
     * @param array $params 请求参数.{"uid":"用户uid"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getLiveDetail($params)
    {
        $response = $this->doThriftClientByMethod('getLiveDetail', json_encode($params));
        return $this->checkThriftResult($response, 'getLiveDetail', func_get_args());
    }

    /**
     * 直播间关注.
     *
     * @param array $params 请求参数.{"roomId":"1234","uid":"108674518","fanUid":"1234567"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function attentionInLiving($params)
    {
        $response = $this->doThriftClientByMethod('attentionInLiving', json_encode($params));
        return $this->checkThriftResult($response, 'attentionInLiving', func_get_args(), true);
    }

    /**
     * 直播间取消关注.
     *
     * @param array $params 请求参数.{"roomId":,"uid":"108674518","fanUid":"1234567"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function cancelAttentionInLiving($params)
    {
        $response = $this->doThriftClientByMethod('cancelAttentionInLiving', json_encode($params));
        return $this->checkThriftResult($response, 'cancelAttentionInLiving', func_get_args(), true);
    }

    /**
     * 发送付费弹幕.
     *
     * @param array $params 请求参数.{im_id:"imid",anchor:"主播id",room_id:"房间id",sender:"弹幕发送人id",amount:"支付金额",content:"发送内容",client_v:"客户端版本",platform:"平台"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function barrage($params)
    {
        $response = $this->doThriftClientByMethod('barrage', json_encode($params));
        return $this->checkThriftResult($response, 'barrage', func_get_args(), true);
    }

    /**
     * 红包礼物列表.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function redEnvelopeList($params)
    {
        $response = $this->doThriftClientByMethod('redEnvelopeList', json_encode($params));
        return $this->checkThriftResult($response, 'redEnvelopeList', func_get_args());
    }

    /**
     * 抢红包.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","giveUid":"发红包人","redSerialId":"红包序号ID","roomId":"房间ID","anchorUid":"主播ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function grabRedPacket($params)
    {
        $response = $this->doThriftClientByMethod('grabRedPacket', json_encode($params));
        return $this->checkThriftResult($response, 'grabRedPacket', func_get_args());
    }

    /**
     * 发送红包.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","delayTime":3,"roomId":"房间ID","anchorUid":"主播ID","giftAmount":1.22,"giftList":[{"giftId":2, 礼物ID,"giftCount":3, 礼物数量}]}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function giveRedPacket($params)
    {
        $response = $this->doThriftClientByMethod('giveRedPacket', json_encode($params));
        return $this->checkThriftResult($response, 'giveRedPacket', func_get_args(), true);
    }

    /**
     * 背包接口.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","delayTime":3,"roomId":"房间ID","anchorUid":"主播ID","giftAmount":1.22,"giftList":[{"giftId":2, 礼物ID,"giftCount":3, 礼物数量}]}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getRedPacketBatchInfo($params)
    {
        $response = $this->doThriftClientByMethod('getRedPacketBatchInfo', json_encode($params));
        return $this->checkThriftResult($response, 'getRedPacketBatchInfo', func_get_args());
    }

    /**
     * 抢红包详情.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","giveUid":"发红包的uid","roomId":"房间ID","page":"当前页","limit":"每页数量"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getRedPacketConsumedInfo($params)
    {
        $response = $this->doThriftClientByMethod('getRedPacketConsumedInfo', json_encode($params));
        return $this->checkThriftResult($response, 'getRedPacketConsumedInfo', func_get_args());
    }

    /**
     * 点击红包ICON接口.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","giveUid":"发红包的uid","redSerialId":"红包序号ID","roomId":"房间ID","anchorUid":"主播ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function clickRedPacketIcon($params)
    {
        $response = $this->doThriftClientByMethod('clickRedPacketIcon', json_encode($params));
        return $this->checkThriftResult($response, 'clickRedPacketIcon', func_get_args());
    }

    /**
     * 获取红包信息.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","giveUid":"发红包的uid","roomId":"房间ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getRedPacketInfo($params)
    {
        $response = $this->doThriftClientByMethod('getRedPacketInfo', json_encode($params));
        return $this->checkThriftResult($response, 'getRedPacketInfo', func_get_args());
    }

    /**
     * 禁言.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登录人","anchorUid":"主播uid","roomId":"房间ID","bannedUid":"被禁言用户Id","operatorUid":"操作人ID-目前是主播能操作禁言","imId":"房间消息ImId"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function speakBanned($params)
    {
        $response = $this->doThriftClientByMethod('speakBanned', json_encode($params));
        return $this->checkThriftResult($response, 'speakBanned', func_get_args(), true);
    }

    /**
     * 取消禁言.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登录人","anchorUid":"主播uid","roomId":"房间ID","cancelBannedUid":"取消被禁言用户Id","operatorUid":"操作人ID-目前是主播能操作禁言","imId":"房间消息ImId"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function cancelSpeakBanned($params)
    {
        $response = $this->doThriftClientByMethod('cancelSpeakBanned', json_encode($params));
        return $this->checkThriftResult($response, 'cancelSpeakBanned', func_get_args(), true);
    }

    /**
     * 贴纸列表.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","roomId":"房间ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function stickersConfigList($params)
    {
        $response = $this->doThriftClientByMethod('stickersConfigList', json_encode($params));
        return $this->checkThriftResult($response, 'stickersConfigList', func_get_args());
    }

    /**
     * 主播发贴纸.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","roomId":"房间ID","showMsg":"贴纸内容","imId":"imId","stickerId":"贴纸ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function sendSticker($params)
    {
        $response = $this->doThriftClientByMethod('sendSticker', json_encode($params));
        return $this->checkThriftResult($response, 'sendSticker', func_get_args());
    }

    /**
     * 删除贴纸.
     *
     * @param array $params 请求参数.{"client_v":"版本","platform":"平台","shuabao_login_uid":"登录人","roomId":"房间ID","imId":"imId"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function delSticker($params)
    {
        $response = $this->doThriftClientByMethod('delSticker', json_encode($params));
        return $this->checkThriftResult($response, 'delSticker', func_get_args());
    }

    /**
     * 创建推流地址.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登录人","roomId":"房间ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function createRtmpPushLink($params)
    {
        $response = $this->doThriftClientByMethod('createRtmpPushLink', json_encode($params));
        return $this->checkThriftResult($response, 'createRtmpPushLink', func_get_args());
    }

    /**
     * 首冲活动信息.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台",shuabao_login_uid:"登录用户ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveActivity($params)
    {
        $response = $this->doThriftClientByMethod('liveActivity', json_encode($params));
        return $this->checkThriftResult($response, 'liveActivity', func_get_args());
    }

    /**
     * 首冲活动发奖品.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台",shuabao_login_uid:"登录用户ID",serial_id:"发送奖品的唯一流水号",activity_id:"活动id",room_id:"房间ID",anchor_uid:"主播ID"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveActivityAward($params)
    {
        $response = $this->doThriftClientByMethod('liveActivityAward', json_encode($params));
        return $this->checkThriftResult($response, 'liveActivityAward', func_get_args());
    }

    /**
     * 直播广场.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","listType":"列表类型","lastIndex":"最后一页的最后一个的index","shuabaoUserId":"用户ID","userIp":"用户IP","latitude":"经度","longitude":"纬度"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveSquare($params)
    {
        $response = $this->doThriftClientByMethod('liveSquare', json_encode($params));
        return $this->checkThriftResult($response, 'liveSquare', func_get_args(), true);
    }

    /**
     * 直播数量.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid（可无）"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveCount($params)
    {
        $response = $this->doThriftClientByMethod('liveCount', json_encode($params));
        return $this->checkThriftResult($response, 'liveCount', func_get_args());
    }

    /**
     * 观众心跳.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"房间ID","anchorUid":"主播ID","now_time":"心跳时间","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function viewerHeartBreak($params)
    {
        $response = $this->doThriftClientByMethod('viewerHeartBreak', json_encode($params));
        return $this->checkThriftResult($response, 'viewerHeartBreak', func_get_args());
    }

    /**
     * pk可邀请列表（分页）.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"房间ID","anchorUid":"主播ID","lastIndex":"最后一页的最后一个的index","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function pkInviteList($params)
    {
        $response = $this->doThriftClientByMethod('pkInviteList', json_encode($params));
        return $this->checkThriftResult($response, 'pkInviteList', func_get_args());
    }

    /**
     * pk被邀请列表（不分页）.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"房间ID","anchorUid":"主播ID","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function pkBeInvitedList($params)
    {
        $response = $this->doThriftClientByMethod('pkBeInvitedList', json_encode($params));
        return $this->checkThriftResult($response, 'pkBeInvitedList', func_get_args());
    }

    /**
     * pk发起邀请.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"房间ID","anchorUid":"主播ID","otherAnchorUid":"对方主播ID","otherRoomId":"对方房间ID","inviteType":"0-发起邀请，1-取消邀请","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function pkInvite($params)
    {
        $response = $this->doThriftClientByMethod('pkInvite', json_encode($params));
        return $this->checkThriftResult($response, 'pkInvite', func_get_args(), true);
    }

    /**
     * pk接受邀请.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"房间ID","anchorUid":"主播ID","otherAnchorUid":"对方主播ID","otherRoomId":"对方房间ID","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function pkReceive($params)
    {
        $response = $this->doThriftClientByMethod('pkReceive', json_encode($params));
        return $this->checkThriftResult($response, 'pkReceive', func_get_args(), true);
    }

    /**
     * pk观众端合流.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","inviterAnchorUid":"邀请人uid","receiveAnchorUid":"被邀请人uid","inviterAnchorRoomId":"邀请人roomId","receiveAnchorRoomId":"被邀请人roomId","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function pkAudienceMixStream($params)
    {
        $response = $this->doThriftClientByMethod('pkAudienceMixStream', json_encode($params));
        return $this->checkThriftResult($response, 'pkAudienceMixStream', func_get_args(), true);
    }

    /**
     * pk声音控制接口.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","changeRoomId":"房间号","voiceStatus":"1 是否有声音0，是无声音；1是有声音","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function commandMixVoice($params)
    {
        $response = $this->doThriftClientByMethod('commandMixVoice', json_encode($params));
        return $this->checkThriftResult($response, 'commandMixVoice', func_get_args(), true);
    }

    /**
     * 关闭pk相关数据.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","pkId":"201888_291881_1666655555","endType":"0 // 结束类型 0，倒计时结束正常结束；1，主播主动结束；2，异常情况结束（例如某个主播流断了）","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function closePk($params)
    {
        $response = $this->doThriftClientByMethod('closePk', json_encode($params));
        return $this->checkThriftResult($response, 'closePk', func_get_args(), true);
    }

    /**
     * pk结束,进入惩罚时间接口.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","pkId":"201888_291881_1666655555","endType":"0 // 结束类型 0，倒计时结束正常结束；1，主播主动结束；2，异常情况结束（例如某个主播流断了）","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function pkPhaseEnd($params)
    {
        $response = $this->doThriftClientByMethod('pkPhaseEnd', json_encode($params));
        return $this->checkThriftResult($response, 'pkPhaseEnd', func_get_args(), true);
    }

    /**
     * 获取打赏礼物列表(仅返回本地播放动效礼物).
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","reward_uid":"登陆用户uid"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getLocalPlayGifts($params)
    {
        $response = $this->doThriftClientByMethod('getLocalPlayGifts', json_encode($params));
        return $this->checkThriftResult($response, 'getLocalPlayGifts', func_get_args());
    }

    /**
     * 获取礼包下载地址.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function giftVersionLoad($params)
    {
        $response = $this->doThriftClientByMethod('giftVersionLoad', json_encode($params));
        return $this->checkThriftResult($response, 'giftVersionLoad', func_get_args());
    }

    /**
     * 直播导航.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveSquareNavigation($params)
    {
        $response = $this->doThriftClientByMethod('liveSquareNavigation', json_encode($params));
        return $this->checkThriftResult($response, 'liveSquareNavigation', func_get_args(), true);
    }

    /**
     * 直播公告.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveAnnouncement($params)
    {
        $response = $this->doThriftClientByMethod('liveAnnouncement', json_encode($params));
        return $this->checkThriftResult($response, 'liveAnnouncement', func_get_args(), true);
    }

    /**
     * 直播广场榜单列.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function topChart($params)
    {
        $response = $this->doThriftClientByMethod('topChart', json_encode($params));
        return $this->checkThriftResult($response, 'topChart', func_get_args(), true);
    }

    /**
     * 添加管理员.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"直播间ID","anchorUid":"主播ID","managerUid":"管理员ID","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function addManager($params)
    {
        $response = $this->doThriftClientByMethod('addManager', json_encode($params));
        return $this->checkThriftResult($response, 'addManager', func_get_args(), true);
    }

    /**
     * 取消管理员.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"直播间ID","anchorUid":"主播ID","managerUid":"管理员ID","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function cancelManager($params)
    {
        $response = $this->doThriftClientByMethod('cancelManager', json_encode($params));
        return $this->checkThriftResult($response, 'cancelManager', func_get_args(), true);
    }

    /**
     * 管理员列表.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"直播间ID","anchorUid":"主播ID","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function managerList($params)
    {
        $response = $this->doThriftClientByMethod('managerList', json_encode($params));
        return $this->checkThriftResult($response, 'managerList', func_get_args(), true);
    }

    /**
     * 被禁言名单列表.
     *
     * @param array $params 请求参数.{client_v:"客户端版本",platform:"平台","shuabao_login_uid":"登陆用户uid","roomId":"直播间ID","anchorUid":"主播ID","ip":"ip"}
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function forbidUserList($params)
    {
        $response = $this->doThriftClientByMethod('forbidUserList', json_encode($params));
        return $this->checkThriftResult($response, 'forbidUserList', func_get_args(), true);
    }

    /**
     * 广场弹窗领取礼物.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function sendGiftForPopup($params)
    {
        $response = $this->doThriftClientByMethod('sendGiftForPopup', json_encode($params));
        return $this->checkThriftResult($response, 'sendGiftForPopup', func_get_args(), true);
    }

    /**
     * 是否隐藏榜单.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function isRankHide($params)
    {
        $response = $this->doThriftClientByMethod('isRankHide', json_encode($params));
        return $this->checkThriftResult($response, 'isRankHide', func_get_args());
    }

    /**
     * 设置或取消榜单隐藏.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function setRankHide($params)
    {
        $response = $this->doThriftClientByMethod('setRankHide', json_encode($params));
        return $this->checkThriftResult($response, 'setRankHide', func_get_args(), true);
    }

    /**
     * 发送全站喇叭.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function sendGlobalHorn($params)
    {
        $response = $this->doThriftClientByMethod('sendGlobalHorn', json_encode($params));
        return $this->checkThriftResult($response, 'sendGlobalHorn', func_get_args(), true);
    }

    /**
     * 贵族礼物列表.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function gradeGifts($params)
    {
        $response = $this->doThriftClientByMethod('gradeGifts', json_encode($params));
        return $this->checkThriftResult($response, 'gradeGifts', func_get_args(), true);
    }

    /**
     * 轮询查询全站消息.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function queryGlobalHornMsg($params)
    {
        $response = $this->doThriftClientByMethod('queryGlobalHornMsg', json_encode($params));
        return $this->checkThriftResult($response, 'queryGlobalHornMsg', func_get_args());
    }

    /**
     * 直播导航接口V2.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function liveSquareNavigationV2($params)
    {
        $response = $this->doThriftClientByMethod('liveSquareNavigationV2', json_encode($params));
        return $this->checkThriftResult($response, 'liveSquareNavigationV2', func_get_args());
    }

    /**
     * 看直播领礼物.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function watchLiveAward($params)
    {
        $response = $this->doThriftClientByMethod('watchLiveAward', json_encode($params));
        return $this->checkThriftResult($response, 'watchLiveAward', func_get_args(), true);
    }

    /**
     * 删除礼物动效.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function giftClean($params)
    {
        $response = $this->doThriftClientByMethod('giftClean', json_encode($params));
        return $this->checkThriftResult($response, 'giftClean', func_get_args(), true);
    }

    /**
     * 主播端商品列表.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getGoodsListForLive($params)
    {
        $response = $this->doThriftClientByMethod('getGoodsListForLive', json_encode($params));
        return $this->checkThriftResult($response, 'getGoodsListForLive', func_get_args(), true);
    }

    /**
     * 用户端商品列表.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function getGoodsListForUser($params)
    {
        $response = $this->doThriftClientByMethod('getGoodsListForUser', json_encode($params));
        return $this->checkThriftResult($response, 'getGoodsListForUser', func_get_args(), true);
    }

    /**
     * 发送购物IM消息.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function sendShoppingMessage($params)
    {
        $response = $this->doThriftClientByMethod('sendShoppingMessage', json_encode($params));
        return $this->checkThriftResult($response, 'sendShoppingMessage', func_get_args(), true);
    }

    /**
     * 设置或取消低调进场.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function setInconspicuousJoin($params)
    {
        $response = $this->doThriftClientByMethod('setInconspicuousJoin', json_encode($params));
        return $this->checkThriftResult($response, 'setInconspicuousJoin', func_get_args(), true);
    }

    /**
     * 设置或取消打赏隐身.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function setRewardHide($params)
    {
        $response = $this->doThriftClientByMethod('setRewardHide', json_encode($params));
        return $this->checkThriftResult($response, 'setRewardHide', func_get_args(), true);
    }

    /**
     * 是否隐身统一接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function isHideAll($params)
    {
        $response = $this->doThriftClientByMethod('isHideAll', json_encode($params));
        return $this->checkThriftResult($response, 'isHideAll', func_get_args(), true);
    }

    /**
     * 观众操作列表.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function viewerOperatingList($params)
    {
        $response = $this->doThriftClientByMethod('viewerOperatingList', json_encode($params));
        return $this->checkThriftResult($response, 'viewerOperatingList', func_get_args(), true);
    }

    /**
     * 观众点击送主播上热门.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function viewerClickSetHot($params)
    {
        $response = $this->doThriftClientByMethod('viewerClickSetHot', json_encode($params));
        return $this->checkThriftResult($response, 'viewerClickSetHot', func_get_args(), true);
    }

    /**
     * 观众送主播上热门.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function viewerSetHot($params)
    {
        $response = $this->doThriftClientByMethod('viewerSetHot', json_encode($params));
        return $this->checkThriftResult($response, 'viewerSetHot', func_get_args(), true);
    }

    /**
     * VIP用户禁播和解封主播.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function vipForbidAnchorLive($params)
    {
        $response = $this->doThriftClientByMethod('vipForbidAnchorLive', json_encode($params));
        return $this->checkThriftResult($response, 'vipForbidAnchorLive', func_get_args(), true);
    }

    /**
     * VIP用户查看主播禁播状态接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function anchorForbidLiveStatus($params)
    {
        $response = $this->doThriftClientByMethod('anchorForbidLiveStatus', json_encode($params));
        return $this->checkThriftResult($response, 'anchorForbidLiveStatus', func_get_args(), true);
    }

    /**
     * 手机锁屏返回推荐直播.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function lockScreenRecommend($params)
    {
        $response = $this->doThriftClientByMethod('lockScreenRecommend', json_encode($params));
        return $this->checkThriftResult($response, 'lockScreenRecommend', func_get_args());
    }

    /**
     * 主播置顶商品.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function topGoods($params)
    {
        $response = $this->doThriftClientByMethod('topGoods', json_encode($params));
        return $this->checkThriftResult($response, 'topGoods', func_get_args(), true);
    }

    /**
     * 主播取消置顶商品.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function cancelTopGoods($params)
    {
        $response = $this->doThriftClientByMethod('cancelTopGoods', json_encode($params));
        return $this->checkThriftResult($response, 'cancelTopGoods', func_get_args(), true);
    }

    /**
     * 主播推荐商品.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function recommendGoods($params)
    {
        $response = $this->doThriftClientByMethod('recommendGoods', json_encode($params));
        return $this->checkThriftResult($response, 'recommendGoods', func_get_args(), true);
    }

    /**
     * 主播撤销推荐商品.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function cancelRecommendGoods($params)
    {
        $response = $this->doThriftClientByMethod('cancelRecommendGoods', json_encode($params));
        return $this->checkThriftResult($response, 'cancelRecommendGoods', func_get_args(), true);
    }

    /**
     * 宝箱接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function roomGiftChestGet($params)
    {
        $response = $this->doThriftClientByMethod('roomGiftChestGet', json_encode($params));
        return $this->checkThriftResult($response, 'roomGiftChestGet', func_get_args(), true);
    }

    /**
     * 活动榜单页菜单.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function commonTopListMenu($params)
    {
        $response = $this->doThriftClientByMethod('commonTopListMenu', json_encode($params));
        return $this->checkThriftResult($response, 'commonTopListMenu', func_get_args(), true);
    }

    /**
     * 首页气泡配置.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function homePageBubbles($params)
    {
        $response = $this->doThriftClientByMethod('homePageBubbles', json_encode($params));
        return $this->checkThriftResult($response, 'homePageBubbles', func_get_args());
    }

    /**
     * 活动榜单页菜单.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function selectGoods($params)
    {
        $response = $this->doThriftClientByMethod('selectGoods', json_encode($params));
        return $this->checkThriftResult($response, 'selectGoods', func_get_args(), true);
    }

    /**
     * 首页弹窗领取礼物.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function homePopReceiveGift($params)
    {
        $response = $this->doThriftClientByMethod('homePopReceiveGift', json_encode($params));
        return $this->checkThriftResult($response, 'homePopReceiveGift', func_get_args(), true);
    }
}
