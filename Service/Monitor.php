<?php
/**
 * shuabao-api
 *
 * @author Haow1 <haow1@jumei.com>
 */

/**
 * shuabao-api
 *
 * @author Haow1 <haow1@jumei.com>
 */

namespace Service;

/**
 * 封禁监控相关.
 */
class Monitor extends ServiceBase
{

    protected static $className = 'Monitor';

    /**
     * 获取用户信息编辑权限.
     *
     * @param integer $userId UID.
     *
     * @throws \RpcBusinessException A.
     * @return array
     */
    public function getPermitOfUserInfoEdit($userId)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $response = $this->phpClient()->getPermitOfUserInfoEdit($userId);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        return $response;
    }

}
