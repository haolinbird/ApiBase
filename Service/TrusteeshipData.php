<?php
/**
 * TrusteeshipData服务信息接口.
 *
 * @author jianyoun<jianyoun@jumei.com>
 */

namespace Service;

/**
 *  加解密服务信息接口.
 */
class TrusteeshipData extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'TrusteeshipData';

    private static $appId;
    private static $appKey;
    private static $time;

    /**
     * 获取实例.
     *
     * @return \Service\TrusteeshipData
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 构造函数.
     */
    public function __construct()
    {
        self::$appId = \Config\Common::$trusteeshipData['appid'];
        self::$appKey = \Config\Common::$trusteeshipData['appkey'];
        self::$time = time();
    }

    /**
     * 获取加解密服务的token.
     *
     * @param integer|array $decryptData 要解密的id, 数组或数字.
     *
     * @return string
     */
    private function getTrusteeshipToken($decryptData)
    {
        $datastring = is_array($decryptData) ? implode(',', $decryptData) : $decryptData;
        return md5(self::$appId . self::$appKey . $datastring . self::$time);
    }

    /**
     * 通过一个加密id获取解密数据.
     *
     * @param integer $dataId 加密id.
     *
     * @return boolean|string
     * @throws \Exception 系统异常.
     */
    public function getDecryptData($dataId)
    {
        $result = '';
        if (!empty($dataId)) {
            $token = $this->getTrusteeshipToken($dataId);
            $response = $this->phpClient('TrusteeshipData')->getDecryptData($dataId, self::$appId, self::$time, $token);
            if (isset($response['error']) && $response['error'] == 0) {
                $result = $response['msg'];
            } else {
                $this->RpcBusinessException($response['msg'], $response['error']);
            }
        }
        return $result;
    }

    /**
     * 批量解密数据接口.
     *
     * @param array $decryptData 一组需要解密的数据.
     *
     * @return array
     */
    public function getDecryptDataBatch(array $decryptData)
    {
        $result = array();
        $decryptData = array_filter($decryptData);
        if (!empty($decryptData)) {
            $token = $this->getTrusteeshipToken($decryptData);
            $response = $this->phpClient('TrusteeshipData')->getDecryptDataBatch($decryptData, self::$appId, self::$time, $token);
            if (isset($response['error']) && $response['error'] == 0) {
                $result = $response['msg'];
            } else {
                $this->RpcBusinessException($response['msg'], $response['error']);
            }
        }
        return $result;
    }

    /**
     * 加密数据.
     *
     * @param string $data 待加密字符串.
     *
     * @return integer|boolean 返回加密串ID.
     */
    public function encryptData($data)
    {
        $result = false;
        if (!empty($data)) {
            try {
                $response = $this->phpClient('TrusteeshipData')->encryptData($data);
                if (isset($response['error']) && $response['error'] == 0) {
                    $result = $response['msg'];
                } else {
                    $this->RpcBusinessException($response['msg'], $response['error']);
                }
            } catch (\Exception $e) {
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'TrusteeshipData', 'encryptData', func_get_args(), $e->getMessage());
                throw $e;
            }
        }
        return $result;
    }

    /**
     * 批量加密数据.
     *
     * @param array $dataArr 待加密字符串数组.
     *
     * @return array|boolean 返回ID数组.
     */
    public function encryptDataBatch(array $dataArr)
    {
        $result = false;
        if (!empty($dataArr)) {
            $response = $this->phpClient('TrusteeshipData')->encryptDataBatch($dataArr);
            if (isset($response['error']) && $response['error'] == 0) {
                $result = $response['msg'];
            } else {
                $this->RpcBusinessException($response['msg'], $response['error']);
            }
        }
        return $result;
    }

}
