<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/03
 * Time: 上午16:45
 */
class ShuaBaoService extends \Service\ServiceBase
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

    /**
     * 根据用户uid获取账户信息.
     *
     * @param integer $uid             用户ID.
     * @param boolean $isGetChangeType 是否获取变换类型.
     * @param array   $option          额外信息.
     *
     * @return array.
     */
    public function getAccountInfo($uid, $isGetChangeType = false, array $option = array())
    {
        try {
            $result = $this->phpClient('Sycee\Wallet')->getAccountInfo($uid, $isGetChangeType, $option);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Sycee\Wallet', 'getAccountInfo', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 更新刷宝用户数据(没用数据执行添加).
     *
     * @param integer $uid    用户ID.
     * @param array   $params 待更新数据.
     *
     * @return boolean|integer
     * @throws \Exception
     */
    public function updateUsersByParams($uid, array $params)
    {
        try {
            $result = $this->phpClient('Users')->updateUsersByParams($uid, $params);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Users', 'updateUsersByParams', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取用户信息.
     *
     * @param integer $uid 查询用户id.
     *
     * @return array
     * @throws \Exception
     */
    public function getInfo($uid)
    {
        try {
            $result = $this->phpClient('Users')->getInfo($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Users', 'updateUsersByParams', func_get_args(), $ex->getMessage());
        }
        return $result;

    }

    /**
     * 刷宝用户操作登录注册更新用户数据.
     *
     * @param integer $uid         用户id.
     * @param boolean $isLogin     是否登录.
     * @param string  $lastIp      登录ip.
     * @param string  $loginMethod 登录方式.
     * @param string  $regIp       注册ip.
     * @param string  $regSource   注册来源.
     * @param array   $options     附加更新参数.
     *
     * @return boolean
     * @throws \Exception
     */
    public function updateUsersFromRegisterLogin($uid, $isLogin, $lastIp, $loginMethod, $regIp, $regSource, array $options = array())
    {
        try {
            $result = $this->phpClient('Users')->updateUsersFromRegisterLogin($uid, $isLogin, $lastIp, $loginMethod, $regIp, $regSource, $options);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\RpcBusinessException $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Users',
                'updateUsersFromRegisterLogin',
                func_get_args(),
                'RpcBusinessException:' . $ex->getMessage()
            );
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Users',
                'updateUsersFromRegisterLogin',
                func_get_args(),
                'Exception:' . $ex->getMessage()
            );
            // 新注册用户调用服务时中断,需要消息重试,防止用户数据没有及时入库.
            $params = array(
                'uid'          => $uid,
                'is_login'     => $isLogin,
                'last_ip'      => $lastIp,
                'login_method' => $loginMethod,
                'reg_ip'       => $regIp,
                'reg_source'   => $regSource,
                'options'      => $options,
            );
            try {
                $this->sendEvent('shuabao_api_add_users_again', $params);
            } catch (\Exception $ex) {
                \Util\Log\Users::getInstance()->againAddUserExceptionLog(
                    $uid,
                    $ex->getMessage(),
                    $ex->getCode(),
                    func_get_args(),
                    $params
                );
            }
        }
        return $result;
    }

    /**
     * 获取用户邀请信息列表.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception
     */
    public function getInviteList($uid, $page, $pagesize)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getRebateFriendList($uid, $page, $pagesize);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRebate', 'getRebateFriendList', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取用户邀请信息列表.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception
     */
    public function getInviteListForTask($uid, $page, $pagesize)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getRebateFriendListForTask($uid, $page, $pagesize);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Task\InviteRebate',
                'getInviteListForTask',
                func_get_args(),
                $ex->getMessage()
            );
        }
        return $result;
    }

    public function getFriendMarQuee($num)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getFriendMarQuee($num);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(
                self::$serviceName,
                'Task\InviteRebate',
                'getFriendMarQuee',
                func_get_args(),
                $ex->getMessage()
            );
        }
        return $result;
    }

    /**
     * 根据邀请码获取用户信息.
     *
     * @param string invite_code 邀请码.
     *
     * @return array
     * @throws \Exception
     */
    public function getUserInfoByInviteCode($invite_code)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getNoticeUserInfo($invite_code);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRebate', 'getNoticeUserInfo', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取邀请好友页详情.
     *
     * @param integer $uid 用户ID.
     * @param integer $num 跑马灯条数.
     *
     * @return array
     * @throws \Exception
     */
    public function getRebateAmount($uid, $num = 10)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getRebateAmount($uid, $num);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRebate', 'getRebateAmount', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 元换算成元宝.
     *
     * @param integer $cashYuan 元.
     *
     * @return array
     * @throws \Exception
     */
    public function getCalSyceeByCashYuan($cashYuan, $uid)
    {
        try {
            $result = $this->phpClient('Sycee\Withdrawls')->calSyceeByCashYuan($cashYuan, $uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Sycee\InviteRebate', 'calSyceeByCashYuan', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取分享地址.
     *
     * @param integer $uid  用户ID.
     * @param string  $type 类型.
     * @param array   $task 任务信息.
     *
     * @return string
     * @throws \Exception
     */
    public function getShareInfo($uid, $type, $task)
    {
        try {
            $result = $this->phpClient('TaskShare')->getShareInfo($uid, $type, $task);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'TaskShare', 'getShareInfo', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 雅典娜获取提现单列表.
     *
     * @param array   $cond     查询条件.
     * @param integer $page     第几页.
     * @param integer $pageSize 每页条数.
     *
     * @return array
     * @throws \Exception
     */
    public function getWithdrawListByCond($cond = array(), $page = 1, $pageSize = 50)
    {
        try {
            $result = $this->phpClient('Athena\Withdraw')->getWithdrawListByCond($cond, $page, $pageSize);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Athena\Withdraw', 'getWithdrawListByCond', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 雅典娜重新处理提现单.
     *
     * @param integer $withdrawlId 提现单号.
     * @param integer $uid         用户ID.
     * @param string  $processUser 处理人.
     *
     * @return array
     * @throws \Exception
     */
    public function processWithdraw($withdrawlId, $uid, $processUser)
    {
        try {
            $result = $this->phpClient('Balance')->processWithdraw($withdrawlId, $uid, $processUser);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'processWithdraw', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 雅典娜重新通知提现单状态结果.
     *
     * @param integer $uid         用户ID.
     * @param float   $amount      转账金额，单位：元.
     * @param integer $withdrawlId 提现单号(与支付平台转账单号不能同时为空).
     * @param integer $transferNo  支付平台转账单号(与提现单号不能同时为空).
     *
     * @return array
     * @throws \Exception
     */
    public function notifyWithdraw($uid, $amount, $withdrawlId = 0, $transferNo = 0)
    {
        try {
            $result = $this->phpClient('Athena\Withdraw')->notifyWithdraw($uid, $amount, $withdrawlId, $transferNo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Athena\Withdraw', 'notifyWithdraw', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 通过uid获取账户信息(元宝和余额信息).
     *
     * @param integer $uid    用户ID.
     * @param array   $option 附加参数,默认为空 array('sycee' => 1,'amount' => 0,'platform' => 'ios','client_v' => '1.3', 'add_financial' => false).
     *
     * @return array.
     */
    public function getAccountInfoByUid($uid, array $option = array())
    {
        try {
            $result = $this->phpClient('Api\UserCenter')->getAccountInfoByUid($uid, $option);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getAccountInfoByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取新的accessToken时更新用户ACCESS_TOKEN最后刷新时间(系统异常重试一次).
     *
     * @param integer $uid     用户id.
     * @param array   $extInfo 附加更新参数.
     *
     * @return boolean
     * @throws \Exception 业务异常.
     */
    public function setDayLively($uid, $extInfo)
    {
        try {
            $result = $this->phpClient('Users')->setDayLively($uid, $extInfo);
            // \Util\Log\Debugger::getInstance()->addLog("updateRefreshTimeByUid", 200, "OK", func_get_args(), $result);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\RpcBusinessException $ex) {
            $result = -1;
        } catch (\Exception $ex) {
            try {
                $result = $this->phpClient('Users')->setDayLively($uid, $extInfo);
            } catch (\Exception $ex) {
                $result = -1;
                // 不抛异常,不影响正常流程这里记录下日志.
            }
        }
        return $result;
    }

    /**
     * 获取新的accessToken时更新用户ACCESS_TOKEN最后刷新时间(系统异常重试一次).
     *
     * @param integer $uid         用户id.
     * @param integer $refreshTime 刷新时间.
     * @param string  $token       最新的ACCESS_TOKEN.
     * @param string  $platform    平台,iOS/Android.
     * @param array   $ext         附加更新参数.
     *
     * @return boolean
     * @throws \Exception
     */
    public function updateRefreshTimeByUid($uid, $refreshTime = 0, $token = '', $platform = '', array $ext = array())
    {
        try {
            $result = $this->phpClient('Users')->updateRefreshTimeByUid($uid, $refreshTime, $token, $platform, $ext);
            // \Util\Log\Debugger::getInstance()->addDebugLog("updateRefreshTimeByUid", 200, "OK", func_get_args(), $result);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\RpcBusinessException $ex) {
            $result = 0;
        } catch (\Exception $ex) {
            try {
                $result = $this->phpClient('Users')->updateRefreshTimeByUid($uid, $refreshTime, $token, $platform, $ext);
                // \Util\Log\Debugger::getInstance()->addDebugLog("updateRefreshTimeByUid", 201, "OK", func_get_args(), $result);
            } catch (\Exception $ex) {
                $result = 0;
                // 不抛异常,不影响正常流程这里记录下日志.
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Users', 'updateRefreshTimeByUid', func_get_args(), $ex->getMessage());
            }
        }
        return $result;
    }

    /**
     * 根据uid获取刷宝用户信息.
     *
     * @param integer $uid 用户id.
     *
     * @return boolean
     * @throws \Exception
     */
    public function getShuaBaoUserInfoByUid($uid)
    {
        try {
            $result = $this->phpClient('Users')->getShuaBaoUserInfoByUid($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = 0;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Users', 'getShuaBaoUserInfoByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 通过uid获取元宝兑换的余额记录.
     *
     * @param integer $uid      用户ID.
     * @param integer $page     页码.
     * @param integer $pageSize 每页条数.
     * @param array   $option   附加参数,默认为空.
     *
     * @return array
     * @throws \Exception.
     */
    public function getBalanceTransByUid($uid, $page = 1, $pageSize = 10, array $option = array())
    {
        try {
            $result = $this->phpClient('Api\Balance')->getBalanceTransByUid($uid, $page, $pageSize, $option);;
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = 0;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'getBalanceTransByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 查询用户余额流水.
     *
     * @param integer    $uid          用户ID.
     * @param integer    $page         页码.
     * @param integer    $pagesize     每页条数.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getUserBalanceInfo($uid, $page = 1, $pagesize = 10)
    {
        try{
            $columns = 'txn_id, uid, type, sub_type, amount, comments, create_time, outer_ref_id';
            $result = $this->phpClient('Balance')->getUserBalanceInfo($uid, $columns, $page, $pagesize);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'getUserBalanceInfo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 查询个人余额详细.
     *
     * @param integer $uid   用户ID.
     * @param integer $txnId 流水单号.
     *
     * @return mixed
     * @throws \Exception 异常信息.
     */
    public function getOneUserBalanceInfo($uid, $txnId)
    {
        try{
            $result = $this->phpClient('Balance')->getOneUserBalanceInfo($uid, $txnId);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'getOneUserBalanceInfo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 调用SERVICE查询元宝记录.
     *
     * @param $sycee_records_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSyceeRecord($sycee_records_id, $uid)
    {
        try{
            $result = $this->phpClient('Balance')->getSyceeByRecordId($sycee_records_id, $uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'getSyceeByRecordId', func_get_args(), $ex->getMessage());
            throw $ex;
        }
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

    /**
     * 根据用户手机号获取用户信息.
     *
     * @param array $mobiles 手机号.
     *
     * @return array.
     */
    public function getUserInfoByMobile($mobiles)
    {
        try {
            $result = $this->phpClient('Api\UserCenter')->getUserInfoByMobile($mobiles);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getUserInfoByMobile', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 用户刷宝小程序提现的逻辑
     * @param $uid
     * @param $cashYuan
     * @param $recordId
     * @param $channel
     * @param $delay
     * @param $ext
     * @return mixed
     * @throws \Exception
     */
    public function exchangeCash( $uid, $cashYuan, $recordId, $channel, $delay, $ext )
    {
        try {
            $result = $this->phpClient('Balance')->syceeChargeAndWithdrawl( $uid, $cashYuan, $recordId, $channel, $delay, $ext );
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'processWithdraw', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 提醒好友.
     *
     * @param string $inviteUid 被邀请人ID.
     *
     * @return array
     * @throws \Exception
     */
    public function remindFriend($inviteUid, $uid)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->remindFriend($inviteUid, $uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRebate', 'getNoticeUserInfo', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 雅典娜强制重新处理提现单.
     *
     * @param integer $uid         用户ID.
     * @param float   $amount      转账金额，单位：元.
     * @param string  $processUser 处理人.
     * @param integer $withdrawlId 提现单号(与支付平台转账单号不能同时为空).
     * @param integer $transferNo  支付平台转账单号(与提现单号不能同时为空).
     *
     * @return array
     * @throws \Exception
     */
    public function forceDealWithdraw($uid, $amount, $processUser, $withdrawlId = 0, $transferNo = 0)
    {
        try {
            $result = $this->phpClient('Athena\Withdraw')->forceDealWithdraw($uid, $amount, $processUser, $withdrawlId, $transferNo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Athena\Withdraw', 'forceDealWithdraw', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 雅典娜强制将提现单处理成失败.
     *
     * @param integer $uid         用户ID.
     * @param float   $amount      转账金额，单位：元.
     * @param string  $processUser 处理人.
     * @param integer $withdrawlId 提现单号(与支付平台转账单号不能同时为空).
     * @param integer $transferNo  支付平台转账单号(与提现单号不能同时为空).
     *
     * @return string
     * @throws \Exception
     */
    public function forceDealWithdrawFail($uid, $amount, $processUser, $withdrawlId = 0, $transferNo = 0)
    {
        try {
            $result = $this->phpClient('Athena\Withdraw')->forceDealWithdrawFail($uid, $amount, $processUser, $withdrawlId, $transferNo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Athena\Withdraw', 'forceDealWithdrawFail', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 雅典娜查询支付系统提现单的处理进度.
     *
     * @param integer $uid         用户ID.
     * @param integer $withdrawlId 提现单号(与支付平台转账单号不能同时为空).
     * @param integer $transferNo  支付平台转账单号(与提现单号不能同时为空).
     *
     * @return array
     * @throws \Exception
     */
    public function queryPaymentWithdrawResult($uid, $withdrawlId = 0, $transferNo = 0)
    {
        try {
            $result = $this->phpClient('Athena\Withdraw')->queryPaymentWithdrawResult($uid, $withdrawlId, $transferNo);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Athena\Withdraw', 'queryPaymentWithdrawResult', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 根据withdrawlId获取用户提现单详情.
     *
     * @param integer $withdrawlId 提现ID.
     * @param integer $uid         用户ID.
     *
     * @return array.
     * @throws \Exception
     */
    public function getWithdrawByWithdrawlId($withdrawlId, $uid)
    {
        try {
            $result = $this->phpClient('Api\Withdraw')->getWithdrawByWithdrawlId($withdrawlId, $uid);
            if (isset($result['code']) && \PHPClient\Text::hasErrors($result)) {
                // 因为user_withdrawl里直接返回了与异常相同的字段error,所以需要先判断是否有code.
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Withdraw', 'getWithdrawByWithdrawlId', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 新版获取邀请好友页详情.
     *
     * @param integer $uid 用户ID.
     * @param integer $num 跑马灯条数.
     *
     * @return array
     * @throws \Exception
     */
    public function getRebateAmountNew($uid, $num = 10)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getRebateAmountNew($uid, $num);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRebate', 'getRebateAmountNew', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 新版获取用户邀请信息列表.
     *
     * @param integer $uid      用户ID.
     * @param integer $page     页码.
     * @param integer $pageSize 每页条数.
     *
     * @return array
     * @throws \Exception
     */
    public function getInviteListNew($uid, $page = 1, $pageSize = 10)
    {
        try {
            $result = $this->phpClient('Task\InviteRebate')->getRebateFriendListNew($uid, $page, $pageSize);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRebate', 'getRebateFriendList', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 根据uid批量获取用户基础信息.
     *
     * @param array  $uids    用户ID数组.
     * @param string $columns 查询字段.
     *
     * @return array.
     * @throws \Exception
     */
    public function getUserInfoByUids(array $uids, $columns = 'uid, register_time, hp, nickname, avatar_small, avatar_large, gender, birthday, province, city')
    {
        try {
            $result = $this->phpClient('Api\UserCenter')->getUserInfoByUids($uids, $columns);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getUserInfoByUids', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取用户等级.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception
     */
    public function getUserGrade($uid)
    {
        try {
            $result = $this->phpClient('Api\UserCenter')->getUserGrade($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getUserGrade', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * h5页面获取用户等级.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception
     */
    public function getUserGradePage($uid)
    {
        try {
            $result = $this->phpClient('Api\UserCenter')->getUserGradePage($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getUserGradePage', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 战队榜.
     *
     * @param integer $uid   用户ID.
     * @param boolean $isMy  是否返回个榜.
     * @param boolean $isAll 是否返回总榜.
     * @param integer $num   排行榜多少名.
     *
     * @return array.
     * @throws \Exception 异常信息.
     */
    public function getRankingList($uid, $isMy = false, $isAll = false, $num = 10)
    {
        try {
            $result = $this->phpClient('Api\Team')->getRankingList($uid, $isMy, $isAll, $num);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Team', 'getRankingList', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 奖金领取.
     *
     * @param integer $uid   用户ID.
     *
     * @return float 领取的奖金金额.
     * @throws \Exception 异常信息.
     */
    public function cashingTeamPrize($uid)
    {
        try {
            $result = $this->phpClient('Api\Team')->cashingTeamPrize($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            // $result = 0.00;
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Team', 'cashingTeamPrize', func_get_args(), $ex->getMessage());
            throw $ex;
        }
        return $result;
    }

    /**
     * 批量获取用户等级信息.
     *
     * @param array $uids 用户uid数组.
     *
     * @return array
     * @throws \Exception
     */
    public function getUserGradeBatch($uids)
    {
        try {
            $result = $this->phpClient('Api\UserCenter')->getUserGradeBatch($uids);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $e) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getUserGradeBatch', func_get_args(), $e->getMessage());
        }
        return $result;
    }

    /**
     * 获取用户邀请过的通讯录好友信息.
     *
     * @param integer $uid 用户ID.
     *
     * @return array
     * @throws \Exception
     */
    public function getUserInvited($uid)
    {
        $result = array();
        try {
            $result = $this->phpClient('Api\UserCenter')->getUserGradePage($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'getUserGradePage', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 显示金融入口.
     *
     * @param integer $uid    用户ID.
     * @param array   $device 设备信息.
     *
     * @return boolean
     * @throws \Exception
     */
    public function showFinanceEntrance($uid, array $device)
    {
        $result = false;
        try {
            $result = $this->phpClient('Api\UserCenter')->showFinanceEntrance($uid, $device);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\UserCenter', 'showFinnanceEntrance', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取可以补签每日签到的日期(7天之内).
     *
     * @param integer $uid    用户ID.
     * @param string  $source 签到来源,刷宝APP：app，刷宝小程序：applet.
     *
     * @return array
     * @throws \Exception.
     */
    public function getRetroactiveSignInDays($uid, $source = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->getRetroactiveSignInDays($uid, $source);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'getRetroactiveSignInDays', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 补签每日签到(7天之内).
     *
     * @param integer $uid             用户ID.
     * @param integer $retroactiveTime 补签日期(时间戳).
     * @param string  $source          签到来源,刷宝APP：app，刷宝小程序：applet.
     * @param string  $operator        处理人.
     *
     * @return boolean
     * @throws \Exception.
     */
    public function retroactiveSignIn($uid, $retroactiveTime, $source = '', $operator = '')
    {
        try {
            $result = $this->phpClient('Api\Tasks')->retroactiveSignIn($uid, $retroactiveTime, $source);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'retroactiveSignIn', func_get_args(), $result);
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Api\Tasks', 'retroactiveSignIn', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 通过uid判断是否被邀请来的用户.
     *
     * @param integer $uid 用户ID.
     *
     * @return integer 被邀请人UID的映射ID，0则表示非被邀请来的用户.
     * @throws \Exception 异常信息
     */
    public function getInviteUserByUid($uid)
    {
        try {
            $result = $this->phpClient('Task\InviteRecord')->getInviteUserByUid($uid);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
        } catch (\Exception $ex) {
            $result = 0;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Task\InviteRecord', 'getInviteUserByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 根据指定条件获取用户一条余额记录流水.
     *
     * @param integer $uid     用户ID.
     * @param integer $bdId    余额记录流水号bd_id.
     * @param string  $type    流水类型.
     * @param string  $subType 流水子类型.
     *
     * @return array
     * @throws \Exception 异常信息.
     */
    public function getUserBizDealInfo($uid, $bdId, $type, $subType)
    {
        try {
            $result = $this->phpClient('Balance')->getUserBizDealInfo($uid, $bdId, $type, $subType);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'getUserBizDealInfo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 检查提现账户id是否超过风控次数限制
     *
     * @param integer $uid       提现的uid.
     * @param string  $accountId 微信的openid或者支付宝的用户id.
     *
     * @return array 返回status = 1是没有风险, status = 0 是有风险.
     * @throws \Exception
     */
    public function checkAccountIdWithdrawlRisk($uid, $accountId)
    {
        try {
            $result = $this->phpClient('Balance')->checkAccountIdWithdrawlRisk($uid, $accountId);
            if (\PHPClient\Text::hasErrors($result)) {
                $this->RpcBusinessException($result['message'], $result['code']);
            }
            return $result;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Balance', 'checkAccountIdWithdrawlRisk', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

}
