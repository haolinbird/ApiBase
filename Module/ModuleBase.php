<?php
namespace Module;
/**
 * module 基类
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
class ModuleBase {
    protected static $_instance;

    const SESSION_ID_LOCK_KEY = 'apibase_session_id_lock_key';

    /**
     * 实例化入口
     * @param boolean $sington
     * @return $this|\Module\ModuleBase
     */
    public static function instance($sington = true)
    {
        $className = get_called_class();
        if (!$sington || !isset(self::$_instance[$className])) {
            self::$_instance[$className] = new $className;
        }
        return self::$_instance[$className];
    }

    /**
     * 业务异常
     * @param string  $msg
     * @param integer $code
     * @throws \RpcBusinessException
     */
    protected function rpcBusinessException($msg, $code = 1)
    {
        throw new \RpcBusinessException($msg, $code);
    }

    /**
     * 记录日志.
     *
     * @param string $endpoint 日志配置.
     * @param array  $content  日志内容.
     * @param array  $options  日志选项.
     *
     * @return mixed
     */
    public function log($endpoint = 'default', $content = array(), $options = array())
    {
        //return \Log\Handler::instance($endpoint)->log($content, $options);
        return \Util\Log::logNew($endpoint, $content, $options);
    }

    /**
     * 获取存储redis.
     *
     * @param string $endpoint 获取的redis名字.
     *
     * @return \Redis\RedisStorage|\Redis
     */
    public function redis($endpoint = 'default')
    {
        return \Redis\RedisMultiStorage::getInstance($endpoint);
    }

    /**
     * redis根据uid分区.
     *
     * @param string $endpoint
     * @param int $uid
     *
     * @return mixed
     */
    public function redisSharding($endpoint = 'default', $uid = 0)
    {
        if (is_numeric($uid) && ($uid > 0)) {
            return \Redis\RedisMultiCache::getInstance($endpoint)->partitionByUID($uid);
        } else {
            return \Redis\RedisMultiStorage::getInstance($endpoint);
        }
    }

    /**
     * 获取缓存redis.
     *
     * @param string $endpoint 获取的redis名字.
     *
     * @return \Redis\RedisCache
     */
    public function redisCache($endpoint = 'default')
    {
        return \Redis\RedisMultiCache::getInstance($endpoint);
    }

    /**
     * 获取缓存redis(非sharding).
     *
     * @return \Redis\RedisCache
     * @throws \Exception 异常信息.
     */
    public function redisDefaultShuabaoCache()
    {
        return \Redis\RedisMultiCache::getInstance('shuabaoCache');
    }

    /**
     * 获取缓存redis(sharding).
     *
     * @param integer $uid 用户ID.
     *
     * @return \Redis\RedisCache
     * @throws \Exception 异常信息.
     */
    public function redisShuabaoCache($uid)
    {
        if (is_numeric($uid) && ($uid > 0)) {
            return \Redis\RedisMultiCache::getInstance('shuabaoCache')->partitionByUID($uid);
        } else {
            $this->rpcBusinessException('用户ID有误！！！');
        }
    }

    /**
     * 获取用户属性redis缓存集群的实例.
     *
     * @param integer $uid 用户ID.
     *
     * @return \Redis\RedisMultiStorage
     */
    public function redisUserProperty($uid = 0)
    {
        if (is_numeric($uid) && ($uid > 0)) {
            return \Redis\RedisMultiStorage::getInstance('userProperty')->partitionByUID($uid);
        } else {
            return \Redis\RedisMultiStorage::getInstance('userProperty');
        }
    }

    /**
     * 模拟获取随机sessionid.
     *
     * @return string
     */
    public function getSessionId()
    {
        $sessionId = '';
        try {
            $sessionId = \JMRegistry::get('sb_session_id');
        } catch (\Exception $e) {

        }
        if ($sessionId) {
            return $sessionId;
        }
        $sessionId = \JMGetCookie('sb_session_id');
        if ($sessionId) {
            return $sessionId;
        } else {
            $deviceId = \Util\Util::getHeaderByName('device_id');
            $sessionId = uniqid() . rand(1, 10000);
            if (empty($deviceId)) {
                \Utils\Cookie\Cookie::setCookiesWholeDomain(['sb_session_id' => $sessionId, 'unique_device_id' => ''], time() + 12 * 3600);
                return $sessionId;
            }

            $result = $this->redis()->SETNX(self::SESSION_ID_LOCK_KEY . $deviceId, $sessionId);
            $this->redis()->expire(self::SESSION_ID_LOCK_KEY . $deviceId, 12 * 3600);

            if (!$result) {
                $sessionId = $this->redis()->get(self::SESSION_ID_LOCK_KEY . $deviceId);
            }
            \Utils\Cookie\Cookie::setCookiesWholeDomain(['sb_session_id' => $sessionId, 'unique_device_id' => ''], time() + 12 * 3600);
            return $sessionId;
        }
    }

    /**
     * http get请求.
     *
     * @param string $url
     * @param array  $get
     *
     * @return bool|mixed Result.
     */
    public function get($url, $get = array())
    {
        return $this->httpRequest($url, $get);
    }

    /**
     * http post 请求.
     *
     * @param string $url
     * @param string  $post
     *
     * @return bool|mixed Result.
     */
    public function post($url, $post)
    {
        return $this->httpRequest($url, array(), $post);
    }

    /**
     * 执行http/https请求.
     *
     * @param string $url
     * @param array  $get
     * @param array  $post
     *
     * @return bool|mixed Result.
     */
    public function httpRequest($url, $get = array(), $post = array())
    {
        if (!empty($get)) {
            $getString = http_build_query($get);
            $url .= '?' . $getString;
        }

        $ch = curl_init($url);
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200 || curl_error($ch) != '') {
            $result = false;
        } else {
            $result = $response;
        }
        curl_close($ch);

        return $result;
    }

    /**
     * 三方绑定日志.
     *
     * @param string $category 分类.
     * @param string $action   操作.
     * @param string $site     三方站点.
     * @param string $mobile   手机号.
     * @param array  $extInfo  其他信息.
     * @param string $redisKey RedisKey.
     * @param string $redisVal RedisVal.
     * @param string $result   结果.
     *
     * @return boolean
     */
    public function extLog($category, $action, $site, $mobile, $extInfo, $redisKey = '', $redisVal = '', $result = 'false')
    {
        $userIp   = \JMSystem::GetClientIp();
        $date     = '[' . date('Y-m-d H:i:s') . ']';
        $cate     = "[{$category}_{$action}|site:$site|result:$result|mobile:$mobile]";
        $platform = \Util\Util::getHeaderByName('platform');
        $clientV  = \Util\Util::getHeaderByName('client_v');
        $deviceId = \Util\Util::getHeaderByName('device_id');
        $device   = "[platform:{$platform}|client_v:{$clientV}|device_id:{$deviceId}]";
        $extStr   = is_array($extInfo) ? json_encode($extInfo) : $extInfo;
        $extInfo  = "[redisKey:$redisKey|redisVal:$redisVal|extInfo:$extStr]";
        $data     = "{$date}-{$cate}-{device:$device}-[{$extInfo}]-[{user_id: $userIp}]\n";
        \Util\Log::log($data, $category);
        //return $this->log('ext_log', $data);
    }

    /**
     * 访问频率限制.
     *
     * @param string  $limitKey 限制key.
     * @param integer $second   时间间隔.
     * @param integer $number   次数.
     *
     * @return boolean
     */
    public function checkVisitLimiter($limitKey, $second = 1, $number = 1)
    {
        $second = intval($second);
        $number = $number > 1 ? intval($number) : 1;
        $tmp = explode('_', $limitKey);
        $uid = end($tmp);
        $redis = $this->redisSharding('default', $uid);
        $count = $redis->incr($limitKey);
        if ($count == 1) {
            $redis->expire($limitKey, $second);
            return true;
        } elseif ($count <= $number) {
            return true;
        }
        return false;
    }

    /**
     * Send a event/message to Jumei EventCenter.
     *
     * @param string $eventClass Event/Message Class name that defined in {@link http://meman.int.jumei.com/ EventCenter}.
     * @param mixed  $content    Event/message content which can be any PHP data type that can be serialized.
     * @param array  $options    Available options are "delay", "priority".  "delay" is in senconds, "priority" are integers which are from 0 (most urgent) to 0xFFFFFFFF (least urgent).
     * @param string $endpoint   EventServer configuration name.
     *
     * @return boolean
     * @throws \Exception
     */
    public function sendEvent($eventClass, $content, $options = array('priority' => null, 'delay' => null), $endpoint = 'default')
    {
        try {
            $result = \EventClient\Gateway::send($eventClass, $content, $options, $endpoint);
            return $result;
        } catch (\Exception $ex) {
            \Utils\Log\Logger::instance()->log($ex);
            if (\Config\Common::$sendEventExceptionSwitch) {
                throw $ex;
            } else {
                return false;
            }
        }
    }

}
