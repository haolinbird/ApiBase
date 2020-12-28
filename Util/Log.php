<?php
/**
 * @file \Util\Log.php
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

class Log
{
    
    /**
     * 写日志.
     *
     * @param string $message  内容.
     * @param string $category 类别.
     *
     * @return void
     */
    public static function log($message, $category = '')
    {
        $log = new \Config\Log();
        $path = rtrim($log->FILE_LOG_ROOT, "/") . "/" . $category;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $message = is_array($message) ? json_encode($message) : $message;
        $filename = date('Ymd');
        $file = $path . "/{$filename}.log";
        $message = date('Y-m-d H:i:s') . "\t $message \n";
        error_log($message, 3, $file);
    }
    
    /**
     * 接口异常日志.
     *
     * @param string $interface 接口.
     * @param string $message   内容.
     *
     * @return void
     */
    public static function interfaceExceptionLog($interface, $message)
    {
        self::log($interface . "|" . $message, "mdInterfaceException");
    }

    /**
     * 公用记录日志.
     *
     * @param string $endpoint 日志配置.
     * @param array  $content  日志内容.
     * @param array  $options  日志选项.
     *
     * @return mixed
     */
    public static function logNew($endpoint = 'default', $content = array(), $options = array())
    {
        $closedLogConf = isset(\Config\Log::$closedLogConf) ? \Config\Log::$closedLogConf : array();
        if (!empty($closedLogConf['closedLogConfigNames']) && in_array($endpoint, $closedLogConf['closedLogConfigNames'])) {
            return true;
        }
        return \Log\Handler::instance($endpoint)->log($content, $options);
    }

    /**
     * 调试用的日志.
     *
     * @param string $logFile 日志位置.
     */
    public static function debug()
    {
        $args = func_get_args();
        foreach ($args as $arg) {
            error_log(var_export($arg, true) . PHP_EOL, 3, '/tmp/debug.log');
        }
    }

}
