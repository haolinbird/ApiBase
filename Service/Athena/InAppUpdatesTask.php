<?php
/**
 * App版本管理.
 */

namespace Service\Athena;

/**
 * App版本管理.
 */
class InAppUpdatesTask extends \Service\ServiceAthena
{
    protected static $className = 'Athena\InAppUpdatesTask';

    /**
     * 初始化任务.
     *
     * @param array $task 任务信息.
     *
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function initTask(array $task)
    {
        $response = $this->phpClient(self::$className)->initTask($task);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 根据ID获取升级版本信息.
     *
     * @param integer $id 版本ID.
     *
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function getById($id)
    {
        $response = $this->phpClient(self::$className)->getTaskById($id);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 获取升级版本分页数据.
     *
     * @param integer $page     页数.
     * @param integer $pageSize 每页显示数量.
     * @param array   $conds    查询条件.
     *
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function getList($page = 1, $pageSize = 10, $conds = array())
    {
        $response = $this->phpClient(self::$className)->getList($page, $pageSize, $conds);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 保存版本信息.
     *
     * @param array $task 任务信息.
     *
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function save(array $task)
    {
        $response = $this->phpClient(self::$className)->updateTask($task);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

}