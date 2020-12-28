<?php
/**
 * Created by PhpStorm.
 * User: shijian
 * Date: 2019/1/13
 * Time: 11:41 AM
 */
namespace Service;

class OrderSystem extends ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'JMOrderSystem';

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
     * @param $uid
     * @param $page
     * @param string $tab all全部、paid已付款（不包含退款）
     * @param int $page_size
     * @return array
     * @throws \RpcBusinessException
     */
    public function getPageOrdersByUid($uid, $page, $tab = 'paid', $page_size = 20)
    {
        $response = $this->phpClient('Api\ShuaBao')->getPageOrdersByUid($uid, $tab, array(), $page, $page_size);
        if (\PHPClient\Text::hasErrors($response)) {
            return [];
        }
        return $response;
    }

    /**
     * 获取用户最早支付订单
     * @param $uid
     * @return mixed
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function getEarliestPaidOrderByUid($uid)
    {
        $response = $this->phpClient('Api\ShuaBao')->getEarliestPaidOrderByUid($uid);
        if (\PHPClient\Text::hasErrors($response)) {
            return [];
        }
        return $response;
    }
}