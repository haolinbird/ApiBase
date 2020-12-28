<?php
namespace Config;

class Log {
    // 文件日志的根目录.请确认php进程对此目录可写
    public $FILE_LOG_ROOT = "#{ServiceBase.Log.Dir}";

    /**
     * 刷宝worker的请求和返回数据日志.
     *
     * @var array
     */
    public $shuabao_worker = ['logger' => 'jsonfile', 'rotateFormat' => 'Y-m-d'];
    /**
     * 短信发送日志.
     *
     * @var array
     */
    public $sms_send_log = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 第三方登录绑定日志.
     *
     * @var array
     */
    public $ext_log = array(
        'logger' => 'file',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 配置关闭日志文件的配置.
     *
     * array(
     *   'closedLogConfigNames' => array(
     *       "sms_send_log"
     *   ),
     *   'closedEventLogByEventName' => array(
     *       "ShuaBaoService"
     *   ),
     * )
     *
     * @var array
     */
    public static $closedLogConf = "#{ServiceBase.api.closedLogConf}";

    /**
     * 通用日志.
     *
     * @var array
     */
    public $eventLog = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 外部服务日志.
     *
     * @var array
     */
    public $eventServiceLog = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 强制绑定手机号日志.
     *
     * @var array
     */
    public $forceBindMobileLog = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * url验签异常日志.
     *
     * @var array
     */
    public $url_sign = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 手机号码登录日志.
     *
     * @var array
     */
    public $login_log = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 普通全文本格式文件日志.
     *
     * @var array
     */
    public $eventFullTextLog = array(
        'logger' => 'file',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 通用Debug日志.
     *
     * @var array
     */
    public $eventDebugLog = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

    /**
     * 数据统计日志.
     *
     * @var array
     */
    public $eventStatisticsLog = array(
        'logger' => 'jsonfile',
        'rotateFormat' => 'Y-m-d',
    );

}
