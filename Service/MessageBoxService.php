<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2019/04/17
 * Time: 上午15:25
 */
class MessageBoxService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'MessageBoxService';

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
     * 根据UID获取所有分类的未读消息数.
     *
     * @param integer $uid          用户ID.
     * @param integer $registerTime 用户注册时间.
     *
     * @return array.
     */
    public function getAllTypeUnReadCountByUid($uid, $registerTime)
    {
        try {
            $result = $this->phpClient('MessageBox')->getAllTypeUnReadCountByUid($uid, $registerTime);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getAllTypeUnReadCountByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取未读消息数量.
     *
     * @param integer $uid          用户ID.
     * @param array   $typeIds      一组类别ID.
     * @param integer $registerTime 用户注册时间.
     *
     * @return array.
     */
    public function getUserUnreadMessageCount($uid, $typeIds, $registerTime)
    {
        try {
            $result = $this->phpClient('MessageBox')->getUserUnreadMessageCount($uid, $typeIds, $registerTime);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getUserUnreadMessageCount', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 根据UID获取所有分类列表, 并带上分类的最后一条消息.
     *
     * @param integer $uid          用户ID.
     * @param integer $registerTime 用户注册时间.
     *
     * @return array.
     */
    public function getTypeListByUid($uid, $registerTime)
    {
        try {
            $result = $this->phpClient('MessageBox')->getTypeListByUid($uid, $registerTime);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getTypeListByUid', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 根据UID,TYPE获取消息列表(首页).
     *
     * @param integer $uid          用户ID.
     * @param integer $typeId       类别ID.
     * @param integer $registerTime 用户注册时间.
     * @param integer $limit        数量.
     *
     * @return array.
     */
    public function getMessageListByUidTypeId($uid, $typeId, $registerTime, $limit = 10)
    {
        try {
            $result = $this->phpClient('MessageBox')->getMessageListByUidTypeId($uid, $typeId, $registerTime, $limit);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getMessageListByUidTypeId', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 根据UID,TYPE获取消息列表(正序下一页).
     *
     * @param integer $uid          用户ID.
     * @param integer $typeId       类别ID.
     * @param integer $registerTime 用户注册时间.
     * @param integer $timestamp    最新一条消息的unix timestamp.
     * @param integer $messageId    最新一条消息的Id.
     * @param integer $limit        数量.
     *
     * @return array.
     */
    public function getNextPageMessageListByUidTypeId($uid, $typeId, $registerTime, $timestamp, $messageId, $limit = 10)
    {
        try {
            $result = $this->phpClient('MessageBox')->getNextPageMessageListByUidTypeId($uid, $typeId, $registerTime, $timestamp, $messageId, $limit);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getNextPageMessageListByUidTypeId', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 根据UID,TYPE获取消息列表(倒序下一页).
     *
     * @param integer $uid          用户ID.
     * @param integer $typeId       类别ID.
     * @param integer $registerTime 用户注册时间.
     * @param integer $timestamp    最新一条消息的unix timestamp.
     * @param integer $messageId    最新一条消息的Id.
     * @param integer $limit        数量.
     *
     * @return array.
     */
    public function getPrevPageMessageListByUidTypeId($uid, $typeId, $registerTime, $timestamp, $messageId, $limit = 10)
    {
        try {
            $result = $this->phpClient('MessageBox')->getPrevPageMessageListByUidTypeId($uid, $typeId, $registerTime, $timestamp, $messageId, $limit);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getPrevPageMessageListByUidTypeId', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 标记用户有新消息.
     *
     * @param integer $receiverUid 接收者用户ID.
     *
     * @return boolean.
     */
    public function markNewMessage($receiverUid)
    {
        try {
            $result = $this->phpClient('IMMessage')->markNewMessage($receiverUid);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'IMMessage', 'markNewMessage', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 判断给定用户是否有新消息.
     *
     * @param integer $uid 用户ID.
     *
     * @return boolean.
     */
    public function hasNewMessage($uid)
    {
        try {
            $result = $this->phpClient('IMMessage')->hasNewMessage($uid);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'IMMessage', 'hasNewMessage', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 删除用户新消息标记.
     *
     * @param integer $uid 用户ID.
     *
     * @return boolean.
     */
    public function removeNewMessageMark($uid)
    {
        try {
            $result = $this->phpClient('IMMessage')->removeNewMessageMark($uid);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'IMMessage', 'removeNewMessageMark', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 获取所有未读消息数量(包括im消息).
     *
     * @param integer $uid          用户ID.
     * @param array   $typeIds      一组类别ID.
     * @param integer $registerTime 用户注册时间.
     *
     * @return array.
     */
    public function getUserUnreadMessageCountWithIMMessage($uid, $typeIds, $registerTime)
    {
        try {
            $result = $this->phpClient('MessageBox')->getUserUnreadMessageCountWithIMMessage($uid, $typeIds, $registerTime);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? array() : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = array();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'getUserUnreadMessageCountWithIMMessage', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 发送消息到用户.
     *
     * @param string  $account    帐号名称.
     * @param string  $password   帐号密码.
     * @param integer $templateId 模板ID.
     * @param integer $userId     用户ID.
     * @param array   $dataList   消息内容体.
     *
     * @return array|boolean
     */
    public function sendPush($account, $password, $templateId, $userId, array $dataList = array())
    {
        $res = array('code' => 0, 'status' => false, 'msg' => '');
        try {
            $result = $this->phpClient('MessageEntry')->sendPush($account, $password, $templateId, $userId, $dataList);
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageEntry', 'sendPush', func_get_args(), $result);
            if ($result['errorcode'] == 0) {
                $res['status'] = true;
            } else {
                $this->RpcBusinessException($result['errmsg'], $result['errorcode']);
            }
        } catch (\Exception $ex) {
            $res['code'] = $ex->getCode();
            $res['msg'] = $ex->getMessage();
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageEntry', 'sendPush', func_get_args(), $ex->getMessage());
        }
        return $res;
    }

    /**
     * 标记消息已经浏览过(只针对非广播信息).
     *
     * @param integer $userId           用户ID.
     * @param integer $messageId        消息ID.
     * @param integer $returnedViewTime 获取列表时返回的浏览时间，用于判断是否需要标记(更新数据库).
     *
     * @return array|boolean
     */
    public function markMessageViewed($userId, $messageId, $returnedViewTime = 0)
    {
        if (empty($messageId)) {
            return false;
        }
        if (strpos($messageId, 'job_') === 0) {
            $jobId = ltrim($messageId, 'job_');
            if (empty($jobId)) {
                return false;
            }
            try {
                $result = $this->phpClient('MessageBox')->markMessageViewedByJobId($userId, 6, $jobId, 0);
                if ($result['errcode'] == 0) {
                    $result = empty($result['data']) ? array() : $result['data'];
                } else {
                    $this->RpcBusinessException($result['msg'], $result['errcode']);
                }
            } catch (\Exception $ex) {
                $result = array();
                // 不抛异常,不影响正常流程.
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'markMessageViewedByJobId', func_get_args(), $ex->getMessage());
            }
            return $result;
        } else {
            try {
                $result = $this->phpClient('MessageBox')->markMessageViewed($userId, $messageId, $returnedViewTime);
                if ($result['errcode'] == 0) {
                    $result = empty($result['data']) ? array() : $result['data'];
                } else {
                    $this->RpcBusinessException($result['msg'], $result['errcode']);
                }
            } catch (\Exception $ex) {
                $result = array();
                // 不抛异常,不影响正常流程.
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'markMessageViewed', func_get_args(), $ex->getMessage());
            }
            return $result;
        }
        
    }

    /**
     * 标记用户是否在线,用于聊天场景.
     *
     * @param integer $uid 用户ID.
     *
     * @return boolean.
     */
    public function markUserOnline($uid)
    {
        try {
            $result = $this->phpClient('IMMessage')->markUserOnline($uid);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'IMMessage', 'markUserOnline', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 检查目标用户$receiverUid是否在线，其中$uid表示希望获知结果的来源用户.
     * 简单讲就是$uid时发送者UID，$receiverUid是接收者UID.
     * 当指定$uid时，会将$uid的在线标识更新(更新过期时间).
     *
     * @param integer $receiverUid 接收者用户ID.
     * @param integer $uid         用户ID.
     *
     * @return boolean.
     */
    public function isUserOnline($receiverUid, $uid)
    {
        try {
            $result = $this->phpClient('IMMessage')->isUserOnline($receiverUid, $uid);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'IMMessage', 'isUserOnline', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 移除用户在线标识.
     *
     * @param integer $uid 用户ID.
     *
     * @return boolean.
     */
    public function removeUserOnlineMark($uid)
    {
        try {
            $result = $this->phpClient('IMMessage')->removeUserOnlineMark($uid);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'IMMessage', 'removeUserOnlineMark', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

    /**
     * 批量删除消息.
     *
     * @param integer $uid        用户ID.
     * @param integer $typeId     类别ID.
     * @param array   $messageIds 一组消息ID.
     *
     * @return boolean.
     */
    public function deleteMessageByUidMessageIds($uid, $typeId, array $messageIds)
    {
        try {
            $result = $this->phpClient('MessageBox')->deleteMessageByUidMessageIds($uid, $typeId, $messageIds);
            if ($result['errcode'] == 0) {
                $result = empty($result['data']) ? false : $result['data'];
            } else {
                $this->RpcBusinessException($result['msg'], $result['errcode']);
            }
        } catch (\Exception $ex) {
            $result = false;
            // 不抛异常,不影响正常流程.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MessageBox', 'deleteMessageByUidMessageIds', func_get_args(), $ex->getMessage());
        }
        return $result;
    }

}
