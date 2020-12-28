<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class SqShuaBaoLiveService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'SqShuaBaoLiveService';

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

}
