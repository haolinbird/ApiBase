<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/12/12
 * Time: 上午16:18
 */
class AntiFraud extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'AntiFraud';

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
     * 获取用户风控值.
     *
     * @param integer $uid         用户ID.
     * @param string  $identifying 活动标识.
     * @param array   $params      设备指纹参数.
     * $params = array (
     *     'tokenId' => '41113557', // 用户ID(必须).
     *     'eventName' => 'app_mobile_login', // 登录来源(必须).
     *     'deviceId' => 'asdfasdfafxcxwe2r34234', // 数美设备指纹(必须).
     *     'ip' => '182.138.102.82', // 客户端ip(必须).
     *     'timestamp' => 1536106689, // 时间戳ms.
     *     'phone' => 13688361763, // 手机号.
     *     'valid' => 1, // 是否登录成功 (1: 成功, 0: 失败).
     *     'userExist' => 1, // 用户是否存在 (1: 存在, 0: 不存在).
     *     'captchaValid' => 1 // 验证码是否通过(1: 通过, 0:不通过).
     * );
     * @param boolean $isThrift    是否thrift调用.
     *
     * @return array.
     * @throws \Exception
     */
    public function getScoreV2($uid, $identifying, array $params = array(), $isThrift = false)
    {
        try {
            $params = json_encode($params);
            $response = $this->phpClient('RiskScore')->getScoreV2($uid, $identifying, $params, $isThrift);
            if (\PHPClient\Text::hasErrors($response)) {
                $this->RpcBusinessException($response['message'], $response['code']);
            }
            return $response;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'RiskScore', 'getScoreV2', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取风险分数(新).
     *
     * @param integer $uid        用户ID.
     * @param string  $appName    应用名.
     * @param string  $ruleName   规则名.
     * @param string  $fparms     基础请求参数.
     * @param bool    $needDetail 是否需要分数详情.
     * @param bool    $isThrift   是否是thrift接口.
     *
     * @return array
     * @throws \RpcBusinessException 系统异常
     */
    public function scoreByRule($uid, $appName, $ruleName, $fparms, $needDetail = false, $isThrift = false)
    {
        $result = 0;
        $response = \PHPClient\Text::inst('AntiFraud')->setClass('RiskScore')->scoreByRule($uid, $appName, $ruleName, $fparms, $needDetail, $isThrift);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['data']['risk_score'];
        } else {
            $this->rpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

}