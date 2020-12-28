<?php
/**
 * 常驻通知栏消息任务管理.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Service\Athena;

/**
 * 常驻通知栏消息任务管理.
 */
class PermanentNotificationBarTask extends \Service\ServiceAthena
{
    /**
     * 类标识.
     *
     * @var string
     */
    public static $className = 'Athena\PermanentNotificationBarTask';

    /**
     * Get Instance.
     *
     * @param boolean $sington 是否单例.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 获取常驻通知栏消息任务字段文案信息.
     *
     * @return array.
     * @throws \Exception 异常.
     */
    public function getPnbTextInfo()
    {
        try {
            $result = $this->phpClient(self::$className)->getPnbTextInfo();
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getPnbTextInfo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取常驻通知栏消息任务满足条件的分页数据.
     *
     * @param integer $page     页数.
     * @param integer $pageSize 每页显示数量.
     * @param array   $conds    查询条件.
     *
     * @return array
     * @throws \Exception 异常.
     */
    public function getPnbList($page = 1, $pageSize = 10, $conds = array())
    {
        try {
            $result = $this->phpClient(self::$className)->getPnbList($page, $pageSize, $conds);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getPnbList', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 根据ID获取常驻通知栏消息任务信息.
     *
     * @param integer $id 任务ID.
     *
     * @return array 任务信息.
     * @throws \Exception 异常.
     */
    public function getPnbTaskById($id)
    {
        try {
            $result = $this->phpClient(self::$className)->getPnbTaskById($id);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'getPnbTaskById', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 新增常驻通知栏消息任务.
     *
     * @param array $task 任务信息.
     *
     * @return boolean 任务新增结果，true/false.
     * @throws \Exception 异常.
     */
    public function addPnbTask(array $task)
    {
        try {
            $result = $this->phpClient(self::$className)->addPnbTask($task);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'addPnbTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 更新常驻通知栏消息任务.
     *
     * @param integer $id   任务ID.
     * @param array   $task 任务信息.
     *
     * @return boolean 任务更新结果，true/false.
     * @throws \Exception 异常.
     */
    public function updatePnbTask($id, array $task)
    {
        try {
            $result = $this->phpClient(self::$className)->updatePnbTask($id, $task);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'updatePnbTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 删除常驻通知栏消息任务.
     *
     * @param integer $id          任务ID.
     * @param string  $processUser 处理者.
     *
     * @return boolean 任务删除结果，true/false.
     * @throws \Exception 异常.
     */
    public function deletePnbTask($id, $processUser)
    {
        try {
            $result = $this->phpClient(self::$className)->deletePnbTask($id, $processUser);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'deletePnbTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 恢复已删除常驻通知栏消息任务(回收站).
     *
     * @param integer $id          任务ID.
     * @param string  $processUser 处理者.
     *
     * @return boolean 恢复删除任务结果，true/false.
     * @throws \Exception 异常.
     */
    public function recoverPnbTask($id, $processUser)
    {
        try {
            $result = $this->phpClient(self::$className)->recoverPnbTask($id, $processUser);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, self::$className, 'recoverPnbTask', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

}
