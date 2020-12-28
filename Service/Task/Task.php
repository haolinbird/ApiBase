<?php
namespace Service\Task;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/03
 * Time: 上午16:45
 */
class Task extends \Service\ServiceBase
{
    public static $className = 'Task\Task';

    /**
     * 是否收到新人礼包.
     *
     * @param integer $uid 用户id.
     *
     * @return integer
     */
    public function isGotNewUserGift($uid)
    {
        $result = array();
        try {
            $result = $this->phpClient(self::$className)->isReceiveNewUserGift($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'isReceiveNewUserGift', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

}