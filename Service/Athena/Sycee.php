<?php
/**
 * 元宝相关服务.
 */

namespace Service\Athena;

/**
 * 元宝相关服务.
 */
class Sycee extends \Service\ServiceAthena
{

    protected static $className = 'Sycee\Sycee';

    /**
     * 获取用户最近收入汇总信息(按天).
     *
     * @param integer $uid    UID.
     * @param integer $dayNum 天数.
     *
     * @return array
     */
    public function getRecentIncomeSummaries($uid, $dayNum)
    {
        $response = $this->phpClient('Athena\Sycee')->getRecentIncomeSummaries($uid, $dayNum);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

    /**
     * 雅典娜元宝流水列表.
     *
     * @param array   $cond     查询条件.
     * @param integer $page     第几页.
     * @param integer $pageSize 每页条数.
     *
     * @return array.
     * @throws \Exception
     */
    public function getListByCond($cond = array(), $page = 1, $pageSize = 200)
    {
        try {
            $result = $this->phpClient('Athena\Sycee')->getListByCond($cond, $page, $pageSize);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Athena\Sycee', 'getListByCond', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

}
