<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class ShuaBaoUserService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoUserService';

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
     * 获取单个用户信息.
     *
     * @param array  $param          参数数组.
     * @param string $sourceFunction 调用此方法的来源方法名.
     *
     * @return array.
     * @throws \Exception
     */
    public function querySingelUserInfo($param, $sourceFunction = '')
    {
        if (empty($param['cur_uid'])) {
            $param['cur_uid'] = '';
        }
        if (\Util\Util::isQueryRpc('querySingelUserInfo', $sourceFunction)) {
            // $response = $this->thriftClient()->querySingelUserInfo(json_encode($param));
            $response = $this->doThriftClientByMethod('querySingelUserInfo', json_encode($param));
        } else {
            $response = array (
                'birthday' => '0000-00-00',
                'gender' => '0',
                'signature' => '',
                'city' => '',
                'vip_logo' => new \stdClass(),
                'is_attention' => '0',
                'weibo_link' => '',
                'weibo_link_desc' => '',
                'gold' => '0',
                'uid' => empty($param['uid']) ? '0' : $param['uid'],
                'forbid_live' => '0',
                'constellation' => '',
                'province' => '',
                'weixin_account' => '',
                'nickname' => '',
                'copper' => '0',
                'vip' => '0',
                'weixin_account_desc' => '',
                'attention_label_count' => '0',
                'like_count' => '0',
                'age_desc' => '',
                'attention_count' => '0',
                'avatar_small' => new \stdClass(),
                'avatar_large' => new \stdClass(),
                'fans_count' => '0',
                'grade' => '0',
                'praise_count' => 0,
                'label_count' => '0',
                'age' => 0,
                'short_video_count' => '0',
            );
            return $response;
        }
        return $this->checkThriftResult($response, 'querySingelUserInfo', func_get_args());
    }

    /**
     * 查询用户短视频列表(个人短视频列表).
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryUserSmallVideoList($param)
    {
        if (empty($param['cur_uid'])) {
            $param['cur_uid'] = '';
        }
        // $response = $this->thriftClient()->queryUserSmallVideoList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryUserSmallVideoList', json_encode($param));
        return $this->checkThriftResult($response, 'queryUserSmallVideoList', func_get_args());
    }

    /**
     * 获取用户关注人列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryAttentionUserList($param)
    {
        // $response = $this->thriftClient()->queryAttentionUserList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryAttentionUserList', json_encode($param));
        return $this->checkThriftResult($response, 'queryAttentionUserList', func_get_args());
    }

    /**
     * 获取用户粉丝列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryFansUserList($param)
    {
        // $response = $this->thriftClient()->queryFansUserList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryFansUserList', json_encode($param));
        return $this->checkThriftResult($response, 'queryFansUserList', func_get_args());
    }

    /**
     * 查询用户喜欢短视频列表(个人点了赞列表).
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryUserLikePostList($param)
    {
        if (empty($param['cur_uid'])) {
            $param['cur_uid'] = '';
        }
        // $response = $this->thriftClient()->queryUserLikePostList(json_encode($param));
        $response = $this->doThriftClientByMethod('queryUserLikePostList', json_encode($param));
        return $this->checkThriftResult($response, 'queryUserLikePostList', func_get_args());
    }

    /**
     * 关注用户.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function attentionUser($param)
    {
        // $response = $this->thriftClient()->attentionUser(json_encode($param));
        $response = $this->doThriftClientByMethod('attentionUser', json_encode($param));
        return $this->checkThriftResult($response, 'attentionUser', func_get_args(), true);
    }

    /**
     * 取消关注用户.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function cancleAttentionUser($param)
    {
        // $response = $this->thriftClient()->cancleAttentionUser(json_encode($param));
        $response = $this->doThriftClientByMethod('cancleAttentionUser', json_encode($param));
        return $this->checkThriftResult($response, 'cancleAttentionUser', func_get_args(), true);
    }

    /**
     * 添加黑名单.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function addBlacklist($param)
    {
        // $response = $this->thriftClient()->addBlacklist(json_encode($param));
        $response = $this->doThriftClientByMethod('addBlacklist', json_encode($param));
        return $this->checkThriftResult($response, 'addBlacklist', func_get_args(), true);
    }

    /**
     * 取消黑名单.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function cancelBlacklist($param)
    {
        // $response = $this->thriftClient()->cancelBlacklist(json_encode($param));
        $response = $this->doThriftClientByMethod('cancelBlacklist', json_encode($param));
        return $this->checkThriftResult($response, 'cancelBlacklist', func_get_args(), true);
    }

    /**
     * 查询黑名单列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryBlacklist($param)
    {
        // $response = $this->thriftClient()->queryBlacklist(json_encode($param));
        $response = $this->doThriftClientByMethod('queryBlacklist', json_encode($param));
        return $this->checkThriftResult($response, 'queryBlacklist', func_get_args());
    }

    /**
     * 批量查询用户简要信息.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryUserInfoBatch($param)
    {
        // $response = $this->thriftClient()->queryUserInfoBatch(json_encode($param));
        $response = $this->doThriftClientByMethod('queryUserInfoBatch', json_encode($param));
        return $this->checkThriftResult($response, 'queryUserInfoBatch', func_get_args());
    }

    /**
     * 查询视频发布.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryUserAuthInfo($param)
    {
        $response = $this->thriftClient()->queryUserAuthInfo(json_encode($param));
        return $this->checkThriftResult($response, 'queryUserAuthInfo', func_get_args());
    }

    /**
     * 通知用户信息修改.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function noticeUserChange($param)
    {
        // $response = $this->thriftClient()->noticeUserChange(json_encode($param));
        $response = $this->doThriftClientByMethod('noticeUserChange', json_encode($param));
        return $this->checkThriftResult($response, 'noticeUserChange', func_get_args());
    }

    /**
     * 查询用户短视频列表(审核通过且可见，仅给主播创建直播时用).
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryUserVideosForLive($param)
    {
        // $response = $this->thriftClient()->queryUserVideosForLive(json_encode($param));
        $response = $this->doThriftClientByMethod('queryUserVideosForLive', json_encode($param));
        return $this->checkThriftResult($response, 'queryUserVideosForLive', func_get_args());
    }

    /**
     * 查询某个用户是否在另一个用户的黑名单列表中.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function inBlacklist($param)
    {
        // $response = $this->thriftClient()->inBlacklist(json_encode($param));
        $response = $this->doThriftClientByMethod('inBlacklist', json_encode($param));
        return $this->checkThriftResult($response, 'inBlacklist', func_get_args());
}

    /**
     * 发私信消息前回掉
     *
     * @param string $param 参数数组.
     *
     * @return string eg:{"ActionStatus":"OK","ErrorInfo":"","ErrorCode":0}
     * @throws \Exception
     */
    public function callbackBeforeSendMsg($param)
    {
        // $response = $this->thriftClient()->callbackBeforeSendMsg(json_encode($param));
        return $this->doThriftClientByMethod('callbackBeforeSendMsg', json_encode($param));
    }

}
