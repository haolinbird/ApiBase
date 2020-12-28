<?php
/**
 * Created by PhpStorm.
 * User: Dr_cokiy
 * Date: 2019/3/20
 * Time: 8:33 PM
 */

namespace Service;

class ActivityQualification extends ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabao';

    /**
     * Get Instance.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    public function reduceSycee($uid, $count, $describe)
    {
        $response = $this->phpClient('ActivityQualification')->reduceSycee($uid, $count, $describe);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function reduceBanlance($uid, $subType, $amount, $outerRefId)
    {
        $response = $this->phpClient('Balance')->consumeCommon($uid, $subType, $amount, $outerRefId);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }
}