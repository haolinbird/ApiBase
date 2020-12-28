<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class ShuaBaoUserCoreService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoUserCoreService';

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

}
