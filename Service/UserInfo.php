<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: njy
 * Date: 2018/10/31
 * Time: 上午11:01
 */
class UserInfo extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'UserInfo';

    /**
     * Get Instance.
     *
     * @return \Service\UserInfo
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 根据用户uid获取单个用户头像.
     *
     * @param integer $uid 用户ID.
     *
     * @return mixed.
     */
    public function getAvatarByUid($uid)
    {
        $response = $this->phpClient('UserInfo')->getAvatarByUid($uid);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 根据uid批量获取头像和昵称.
     *
     * @param array $uids 用户ID数组.
     *
     * @return mixed.
     */
    public function getNickNameAndAvatarByUids($uids)
    {
        $response = $this->phpClient('UserInfo')->getNickNameAndAvatarByUids($uids);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['data'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 获取用户附加表信息.
     *
     * @param integer $uid 用户ID.
     *
     * @return mixed.
     */
    public function getUserInfo($uid)
    {
        $response = $this->phpClient('UserInfo')->getUserInfo($uid);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 获取验证码.
     *
     * @param integer $uid           用户ID.
     * @param integer $mobileId      Mobile, 加密id.
     * @param string  $useType       Use Type, 手机绑定为mobile_bind.
     * @param integer $ttl           验证码有效时间.
     * @param integer $maxTimes      该手机+usetype的验证码今天最多获取多少次.
     * @param integer $secondSendOne 多少秒可以获取一条.
     * @param boolean $forBind       是否为手机绑定验证.
     * @param boolean $isLong        是否是长验证码（6位).
     *
     * @return boolean
     */
    public function getConfirmCode($uid, $mobileId, $useType, $ttl, $maxTimes, $secondSendOne, $forBind, $isLong)
    {
        return $this->phpClient('MobileBind')->getConfirmCode($uid, $mobileId, $useType, $ttl, $maxTimes, $secondSendOne, $forBind, $isLong);
    }

    /**
     * 验证短信验证码.
     *
     * @param integer $uid
     * @param integer $mobileId
     * @param string  $useType
     * @param integer $confirmCode
     * @param boolean $forBind
     * @param integer $mobile 手机号码明文(方便日志查看).
     */
    public function verifyConfirmCode($uid, $mobileId, $useType, $confirmCode, $forBind, $mobile = '')
    {
        try {
            $result = $this->phpClient('MobileBind')->verifyConfirmCode($uid, $mobileId, $useType, $confirmCode, $forBind);
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MobileBind', 'verifyConfirmCode', func_get_args(), $result);
            return $result;
        } catch (\Exception $e) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'MobileBind', 'verifyConfirmCode', func_get_args(), $e->getMessage());
            throw $e;
        }
    }

    /**
     * 清除验证码code.
     *
     * @param integer $uid
     * @param integer $mobileId
     * @param string  $useType
     *
     * @return boolean
     */
    public function clearConfirmCodeData($uid, $mobileId, $useType)
    {
        return $this->phpClient('MobileBind')->clearConfirmCodeData($uid, $mobileId, $useType);
    }

    /**
     * 验证手机号码是否存在.
     *
     * @param integer $account
     *
     */
    public function isExistMobile($account)
    {
        $response = $this->phpClient('UserInfo')->isExistMobile($account);
        \Utils\Log\Logger::instance()->log(array('params' => func_get_args(), 'response' => $response));
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 验证绑定手机号.
     *
     * @param $account
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function mobileExists($account)
    {
        $response = $this->phpClient('MobileBind')->mobileExists($account);
        \Utils\Log\Logger::instance()->log(array('params' => func_get_args(), 'response' => $response));
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }


    /**
     * 验证邮箱.
     *
     * @param $account
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function isExistEmail($account)
    {
        $response = $this->phpClient('UserInfo')->isExistEmail($account);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 验证用户名.
     *
     * @param $account
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function isExistNickname($account)
    {
        $response = $this->phpClient('UserInfo')->isExistNickname($account);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }
    /**
     * 增加用户数据.
     *
     * @param mixed $email     邮箱.
     * @param mixed $nickName  昵称.
     * @param mixed $password  密码.
     * @param mixed $password2 还是密码.
     * @param mixed $subscribe 是否订阅.
     * @param mixed $refererId 来源id.
     * @param mixed $phoneNum  手机号.
     * @param mixed $lastReg   上次注册时间.
     * @param mixed $referSite 来源站点.
     * @param mixed $site      分站标识.
     * @param mixed $regIp     注册的ip.
     * @param mixed $reportUid 口碑报告的用户id.
     * @param mixed $isJumei   是否聚美.
     *
     * @return mixed
     * @throws \Exception
     */
    public function register($email, $nickName, $password, $password2, $subscribe, $refererId = 0, $phoneNum = '', $lastReg = '', $referSite = '', $site = 'bj', $regIp = '', $reportUid = 0, $isJumei = 0)
    {
        $refererId = $refererId ? $refererId : 0;
        try {
            $res = $this->phpClient('UserInfo')->register($email, $nickName, $password, $password2, $subscribe, $refererId, $phoneNum, $lastReg, $referSite, $site, $regIp, $reportUid, $isJumei);
            if ($res['error'] != 0 ) {
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'UserInfo', 'register', func_get_args(), $res);
            }
            return $res;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'UserInfo', 'register', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 获取用户ID.
     *
     * @param $mobileId 手机密文
     */
    public function getUidAndMobileByMobileForApp($mobileId)
    {
        $response = $this->phpClient('UserInfo')->getUidAndMobileByMobileForApp($mobileId);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['msg'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 计数器增加.
     *
     * @param string $prefix Prefix.
     * @param string $key    Key.
     *
     * @return mixed
     */
    public function incrementByKey($prefix, $key)
    {
        $res = $this->phpClient('UserInfo')->incrementByKey($prefix, $key);
        return ($res['error'] == 0) ? $res['msg'] : false;
    }

    /**
     * 获取用户信息.
     *
     * @param integer $uid   用户ID.
     * @param boolean $flush 是否刷新.
     *
     * @return mixed
     */
    public function getUserByUid($uid, $flush = false)
    {
        $res = $this->phpClient('UserInfo')->getUserByUid($uid);
        return ($res['error'] == 0) ? $res['msg'] : false;
    }

    /**
     * 判断用户是不是高危用户.
     *
     * @param integer $uid 用户ID.
     *
     * @return boolean
     */
    public function checkWeaknessUserByUid($uid)
    {
        $tag = $this->phpClient('Tag')->isRelatedTag($uid, 'uid', 'weakness_account_user');
        return (!empty($tag) && $tag['data'] == true) ? true : false;
    }


    /**
     * 获取用户手机绑定信息.
     *
     * @param integer $uid    用户ID.
     * @param boolean $master 是否读取主库.
     *
     * @return mixed
     */
    public function getMobileBindInfoByUid($uid, $master = false)
    {
        try {
            $mobileInfo = $this->phpClient('MobileBind')->getMobileByUid($uid, $master);
            if ($mobileInfo['error'] == 0) {
                $mobileInfo = $mobileInfo['msg'];
                if (!empty($mobileInfo['phone_number_trusteeship_id'])) {
                    $data = $mobileInfo['phone_number_trusteeship_id'];
                    $mobileInfo['phone_number'] = \Service\TrusteeshipData::instance()->getDecryptData($data);
                }
            }
        } catch (\Exception $e) {
            $mobileInfo['phone_number'] = '';
        }
        return $mobileInfo;
    }

    /**
     * 用户账号密码登录.
     *
     * @param string $username 用户名.
     * @param string $password 密码.
     *
     * @return array
     * @throws \Exception
     */
    public function authLogin($username, $password)
    {
        $password = md5($password);
        // 符合11位手机号规则的先走手机登录流程
        if (\Util\Validator::isMobile($username)) {
            $res = $this->phpClient('UserInfo')->authLoginMobile($username, $password);
            if ($res['error'] != 0) {
                $res = $this->phpClient('UserInfo')->authLogin($username, $password);
            }
        } else {
            $res = $this->phpClient('UserInfo')->authLogin($username, $password);
        }
        return $res;
    }

    /**
     * 根据用户uid和站点获取用户绑定信息.
     *
     * @param integer $uid      用户uid.
     * @param string  $siteName 三方站点.
     *
     * @return mixed
     */
    public function getUserExtConnectInfo($uid, $siteName)
    {
        return $this->phpClient('ExtConnect')->getUserConnectedSisteInfo($uid, $siteName);
    }

    /**
     * 检查用户昵称.
     *
     * @param string  $nickname 昵称.
     * @param integer $uid      用户Id.
     *
     * @return mixed true表示用户名可用,false表示用户名不可用,字符串表示是系统添加后缀生成用户名
     */
    public function checkUserNickname($nickname, $uid = 0)
    {
        $res = $this->phpClient('Validation')->filterUserNickName($nickname, $uid);
        if ($res['error'] == 0) {
            return $res['data'];
        }
        return false;
    }

    /**
     * 绑定聚美账号与微信账号openid\unionid.
     *
     * @param integer $uid       聚美Id.
     * @param string  $extUserId OpenId.
     * @param string  $unionId   UnionId.
     *
     * @return boolean
     * @throws \Exception
     */
    public function bindWeiXinUserInfo($uid, $extUserId, $unionId)
    {
        $reference = 'mobile';
        try {
            $res = $this->phpClient('ExtConnect')->bindedWeixinUserInfo($uid, $extUserId, $unionId, $reference);
            if ($res['error'] != 0) {
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'ExtConnect', 'bindedWeixinUserInfo', func_get_args(), $res);
                return $res['error'];
            } else {
                return true;
            }
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'ExtConnect', 'bindedWeixinUserInfo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 绑定聚美账号与第三方账号.
     *
     * @param string $uid        聚美uid.
     * @param string $extUserId  第三方站点Id.
     * @param string $siteParams 三方站点信息.
     * @param string $siteName   站点名字.
     *
     * @return mixed
     * @throws \Exception
     */
    public function setUserConnectedSiteInfo($uid, $extUserId, $siteParams, $siteName)
    {
        $optionFollowUs = 1;
        $optionUpdateOnLogin = 1;
        $optionUpdateOnPurchase = 1;
        $optionUpdateOnReceipt = 1;
        $connectTime = time();
        $status = 0;
        try {
            $res = $this->phpClient("ExtConnect")->setUserConnectedSiteInfo($uid, $siteName, $extUserId, $siteParams, $optionFollowUs, $optionUpdateOnLogin, $optionUpdateOnPurchase, $optionUpdateOnReceipt, $connectTime, $status);
            if (empty($res)) {
                \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'ExtConnect', 'setUserConnectedSiteInfo', func_get_args(), $res);
            }
            return $res;
        } catch (\Exception $ex) {
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'ExtConnect', 'setUserConnectedSiteInfo', func_get_args(), $ex->getMessage());
            throw $ex;
        }
    }

    /**
     * 用户常用设备增加.
     *
     * @param integer $uid        用户ID.
     * @param string  $deviceId   设备ID.
     * @param string  $deviceName 设备名称.
     *
     * @return boolean
     */
    public function addCommonDevice($uid, $deviceId, $deviceName = '')
    {
        try {
            $res = $this->phpClient('UserDevice')->addDevice($uid, $deviceId, $deviceName);
            if (isset($res['error']) && ($res['error'] == 0 || $res['error'] == 40003)) {
                // 常用设备添加成功或者之前已经添加.
                return true;
            }
        } catch (\Exception $e) {
            $data = array(
                'platform' => \Util\Util::getHeaderByName('platform'),
                'client_v' => \Util\Util::getHeaderByName('client_v'),
                'site'     => \Util\Util::getHeaderByName('site'),
                'params'   => func_get_args()
            );
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'UserDevice', 'addDevice', $data, $e->getMessage());
        }

        return false;
    }

    /**
     * 收集设备信息.
     *
     * @param integer $uid  用户ID.
     * @param string  $type 类型.
     *
     * @return boolean
     */
    public function collectDeviceInfo($uid, $type = 'login')
    {
        try {
            $uuid = \Util\Util::getHeaderByName('uuid');
            if (empty($uuid)) {
                $uuid = \Util\Util::getHeaderByName('device_id');
            }
            if ($type == 'login') {
                //  验证是否是同一设备, 如果不是有一个发送报警短信.到用户手机
                $this->phpClient("Device")->checkAndAlarm($uid, $uuid);
            } elseif ($type == "register") {
                // 手机设备信息
                $res = $this->phpClient("Device")->add($uid, $uuid);
            }
        } catch (\Exception $e) {
            $data = array(
                'uuid'      => \Util\Util::getHeaderByName('uuid'),
                'device_id' => \Util\Util::getHeaderByName('device_id'),
                'params'    => func_get_args()
            );
            $method = $type == 'login' ? 'checkAndAlarm' : 'add';
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'Device', $method, $data, $e->getMessage());
        }
        return true;
    }

    /**
     * 获取用户其他扩展信息.
     *
     * @param array $uid 用户ID.
     *
     * @return mixed
     */
    public function getNickNameGenderAvatarCommentByUids($uids)
    {
        $response = $this->phpClient('UserInfo')->getNickNameGenderAvatarCommentByUids($uids);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['data'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 判断unionid是否存在.
     *
     * @param string $unionId 用户unionid.
     *
     * @return mixed
     */
    public function getUserInfoByUnionid($unionId)
    {
        $response = $this->phpClient('ExtConnect')->getUserInfoByUnionid($unionId);
        if (isset($response['error']) && $response['error'] == 0) {
            $result = $response['data'];
        } else {
            $this->RpcBusinessException($response['msg'], $response['error']);
        }
        return $result;
    }

    /**
     * 是否绑定手机号.
     *
     * @param integer $uid 用户Id.
     *
     * @return array
     * @throws \Exception 系统异常.
     */
    public function hasBindMobile($uid)
    {
        $result = $this->phpClient('MobileBind')->hasBindMobile($uid);
        if (isset($result['error']) && $result['error'] == 0) {
            $result = $result['data'];
        } else {
            $this->RpcBusinessException($result['msg'], $result['error']);
        }
        return $result;
    }

    /**
     * 强制绑定手机号码.
     *
     * @param integer $uid          用户Id.
     * @param integer $mobile       加密手机号.
     * @param integer $willSendHour 订阅发送时间.
     * @param string  $site         加密手机号.
     *
     * @return array
     * @throws \Exception 系统异常.
     */
    public function forceBind($uid, $mobile, $willSendHour = 0, $site = 'cd')
    {
        $result = $this->phpClient('MobileBind')->forceBind($uid, $mobile, $willSendHour, $site);
        // 强制绑定记录日志.
        $this->log('forceBindMobileLog', array('params' => func_get_args(), 'result' => $result));
        if (isset($result['error']) && $result['error'] == 0) {
            $result = $result['data'];
        } else {
            $this->RpcBusinessException($result['msg'], $result['error']);
        }
        return $result;
    }

    /**
     * 更新用户信息
     *
     * @param array $uids 用户ID.
     * @param object $userInfo 用户信息.
     *
     * @return array
     * @throws \Exception 系统异常.
     */
    public function updateSettingAndInfoV2( $uid, $userInfo )
    {
        $result = array();
        if (!empty($uid)) {
            $json = $this->phpClient('UserInfo')->updateSettingAndInfoV2( $uid, $userInfo );
            if (empty($json)) {
                $this->RpcBusinessException('updateSettingAndInfoV2 response empty');
            }
            $response = json_decode($json, true);
            if (isset($response['error']) && $response['error'] == 0) {
                $result = $response['data'];
            } else {
                $this->RpcBusinessException($response['msg'], $response['error']);
            }
        }
        return $result;
    }

    /**
     * Update avatar.
     *
     * @param integer $uid         User  Id.
     * @param string  $avatarLarge Large Avatar.
     * @param string  $avatarSmall Small Avatar.
     *
     * @return boolean
     * @throws \Exception 系统异常.
     */
    public function updateAvatar($uid, $avatarLarge, $avatarSmall)
    {
        if (!empty($uid)) {
            $response = $this->phpClient('UserInfo')->updateAvatar($uid, $avatarLarge, $avatarSmall);
            if (isset($response['error']) && $response['error'] == 0) {
                return true;
            } else {
                $this->RpcBusinessException($response['msg'], $response['error']);
            }
        }
        return false;
    }

    /**
     * 获取地址列表.
     *
     * @param integer $parentAreaCode 地区代号.
     *
     * @throws \Exception 抛异常.
     *
     * @return mixed.
     */
    public function getChildAreas($parentAreaCode)
    {
        try {
            $res = $this->phpClient('UserAddress')->getChildAreaInfos($parentAreaCode);
            if ($res['error'] == 0) {
                return $res['data'];
            }
        } catch (\Exception $e) {
            $this->RpcBusinessException("UserInfo:UserAddress->getChildAreaInfos", $e->getMessage());
        }
    }

}