<?php
/**
 * Created by PhpStorm.
 * User: shijian
 * Date: 2019/1/11
 * Time: 9:02 PM
 */
namespace Service;

/**
 * 兑换service
 * Class Exchange
 * @package Service
 */
class Exchange extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'Growth';

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
     * 兑换
     * @param $uid
     * @param $redeem_code
     * @param $address_id
     * @return bool
     */
    public function exchange($uid, $redeem_code, $address_id)
    {
        $ext_info = array(
            'address_id' => $address_id,
            'ip' => \JMSystem::GetClientIp(),
        );
        try {
            $response = $this->phpClient('Shuabao\Exchange')->exchange($uid, $redeem_code, $ext_info);
            if (\PHPClient\Text::hasErrors($response)) {
                \Util\Log\Service::getInstance()->phpClientLog(
                    self::$serviceName,
                    'Shuabao\Exchange',
                    'exchange',
                    func_get_args(),
                    $response
                );
                return false;
            }
        } catch (\Exception $exception) {
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Shuabao\Exchange',
                'exchange',
                func_get_args(),
                $exception->getMessage()
            );
        }
        return true;
    }
}