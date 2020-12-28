<?php
/**
 * 统一日志.
 *
 * @author shangyuh<shangyuh@jumei.com>
 */

namespace Util\Log;

/**
 * 统一日志.
 */
abstract class Base
{
    protected static $instances;
    // 通用eventLog日志.
    protected $loggerCfg = 'eventLog';
    // 外部接口eventLog日志.
    protected $loggerServiceCfg = 'eventServiceLog';
    // 调试全文本日志.
    protected $loggerFullTextCfg = 'eventFullTextLog';
    // 通用eventDebugLog日志.
    protected $loggerDebugCfg = 'eventDebugLog';
    // 数据统计eventStatisticsLog日志.
    protected $loggerStatisticsCfg = 'eventStatisticsLog';

    /**
     * 初始化对应的日志对象.
     *
     * @return $this
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className;
        }
        return self::$instances[$className];
    }

    /**
     * 记录通用日志.
     *
     * @param array $logData 日志数据.
     *
     * @return mixed
     */
    protected function setLogData($logData = array())
    {
        $log = array();
        $log['event'] = !empty($logData['event']) ? $logData['event'] : str_replace(__NAMESPACE__ . '\\', '', get_called_class());
        $log['uid'] = isset($logData['uid']) ? $logData['uid'] : 0;
        $log['params'] = isset($logData['params']) ? $logData['params'] : '';
        $log['code'] = isset($logData['code']) ? $logData['code'] : 0;
        $log['message'] = isset($logData['message']) ? $logData['message'] : '';
        $log['content'] = isset($logData['content']) ? $logData['content'] : '';
        $log['log_time'] = date('Y-m-d H:i:s', time());
        $log['project'] = 'api'; // 标记是api的日志
        $closedLogConf = isset(\Config\Log::$closedLogConf) ? \Config\Log::$closedLogConf : array();
        if (!empty($closedLogConf['closedEventLogByEventName']) && in_array($log['event'], $closedLogConf['closedEventLogByEventName'])) {
            return true;
        }
        return null === \Util\Log::logNew($this->loggerCfg, $log);
    }

    /**
     * 记录外部服务日志.
     *
     * @param array $logData 日志数据.
     *
     * @return mixed
     */
    protected function setServiceLogData($logData = array())
    {
        $log = array();
        $log['event'] = !empty($logData['event']) ? $logData['event'] : str_replace(__NAMESPACE__ . '\\', '', get_called_class());
        $log['params'] = isset($logData['params']) ? $logData['params'] : '';
        $log['message'] = isset($logData['message']) ? $logData['message'] : '';
        $log['content'] = isset($logData['content']) ? $logData['content'] : '';
        $log['log_time'] = date('Y-m-d H:i:s', time());
        $log['project'] = 'api'; // 标记是api的日志
        $closedLogConf = isset(\Config\Log::$closedLogConf) ? \Config\Log::$closedLogConf : array();
        if (!empty($closedLogConf['closedEventLogByEventName']) && in_array($log['event'], $closedLogConf['closedEventLogByEventName'])) {
            return true;
        }
        return null === \Util\Log::logNew($this->loggerServiceCfg, $log);
    }

    /**
     * 以普通传入格式写入日志文本.
     *
     * @param string $event 日志事件标记.
     *
     * @return boolean
     */
    protected function logFullText($event)
    {
        $closedLogConf = isset(\Config\Log::$closedLogConf) ? \Config\Log::$closedLogConf : array();
        if (!empty($closedLogConf['closedFullTextEventLogByEventName']) && in_array($event, $closedLogConf['closedFullTextEventLogByEventName'])) {
            return true;
        }
        $args = func_get_args();
        unset($args[0]);
        $content = $args;
        return null === \Util\Log::logNew($this->loggerFullTextCfg, print_r([$event => $content], true) . '::End At ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
    }

    /**
     * 统一错误日志记录.
     *
     * @param string  $eventName 事件名称.
     * @param integer $uid       用户ID.
     * @param string  $message   错误信息.
     * @param integer $code      错误code.
     * @param array   $params    请求参数.
     * @param array   $content   日志信息.
     *
     * @return mixed
     */
    protected function addLogByEventName($eventName, $uid, $message, $code = 0, $params = array(), $content = array())
    {
        $logData = array(
            'event'   => $eventName,
            'uid'     => $uid,
            'message' => $message,
            'code'    => $code,
            'params'  => $params,
            'content' => $content,
        );
        return $this->setLogData($logData);
    }

    /**
     * 记录Debug日志.
     *
     * @param array $logData 日志数据.
     *
     * @return mixed
     */
    protected function setDebugLogData($logData = array())
    {
        $log = array();
        $log['event'] = !empty($logData['event']) ? $logData['event'] : str_replace(__NAMESPACE__ . '\\', '', get_called_class());
        $log['params'] = isset($logData['params']) ? $logData['params'] : '';
        $log['message'] = isset($logData['message']) ? $logData['message'] : '';
        $log['content'] = isset($logData['content']) ? $logData['content'] : '';
        $log['log_time'] = date('Y-m-d H:i:s', time());
        $closedLogConf = isset(\Config\Log::$closedLogConf) ? \Config\Log::$closedLogConf : array();
        if (!empty($closedLogConf['closedEventLogByEventName']) && in_array($log['event'], $closedLogConf['closedEventLogByEventName'])) {
            return true;
        }
        return null === \Util\Log::logNew($this->loggerDebugCfg, $log);
    }

    /**
     * 记录数据统计日志.
     *
     * @param array $statisticsLog 统计日志数据.
     *
     * @return mixed
     */
    protected function setStatisticsLog($statisticsLog = array())
    {
        $log = array();
        $log['event'] = !empty($statisticsLog['event']) ? $statisticsLog['event'] : str_replace(__NAMESPACE__ . '\\', '', get_called_class());
        $log['uid'] = isset($statisticsLog['uid']) ? $statisticsLog['uid'] : 0;
        $log['platform'] = isset($statisticsLog['platform']) ? $statisticsLog['platform'] : '';
        $log['client_v'] = isset($statisticsLog['client_v']) ? $statisticsLog['client_v'] : '';
        $log['utm_source'] = isset($statisticsLog['utm_source']) ? $statisticsLog['utm_source'] : '';
        $log['device_id'] = isset($statisticsLog['device_id']) ? $statisticsLog['device_id'] : '';
        $log['scene'] = isset($statisticsLog['scene']) ? $statisticsLog['scene'] : '';
        $log['foreground'] = isset($statisticsLog['foreground']) ? $statisticsLog['foreground'] : -1;
        $log['is_active_user'] = isset($statisticsLog['is_active_user']) ? $statisticsLog['is_active_user'] : -1;
        $log['message'] = isset($statisticsLog['message']) ? $statisticsLog['message'] : '';
        $log['code'] = isset($statisticsLog['code']) ? $statisticsLog['code'] : 0;
        $log['params'] = isset($statisticsLog['params']) ? $statisticsLog['params'] : array();
        $log['content'] = isset($statisticsLog['content']) ? $statisticsLog['content'] : array();
        $log['log_time'] = date('Y-m-d H:i:s', time());
        $log['project'] = 'api'; // 标记是api的日志.
        $closedLogConf = isset(\Config\Log::$closedLogConf) ? \Config\Log::$closedLogConf : array();
        if (!empty($closedLogConf['closedEventLogByEventName']) && in_array($log['event'], $closedLogConf['closedEventLogByEventName'])) {
            return true;
        }
        return null === \Util\Log::logNew($this->loggerStatisticsCfg, $log);
    }

}
