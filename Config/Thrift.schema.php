<?php
namespace Config;

define('CONFIG_THRIFT_PROVIDER_DIR', __DIR__ . DIRECTORY_SEPARATOR  . '..' . DIRECTORY_SEPARATOR . 'Provider');
/**
 * Class Thrift.
 */
class Thrift
{
    // 日志配置目录.
    const THIRFT_LOG_DIR = '/home/www/logs/';

    /**
     * 社区服务(用户信息相关).
     *
     * @var array
     */
    public $default = array(
        'nodes' => "#{ServiceBase.common.services.ip}",
        'provider' => CONFIG_THRIFT_PROVIDER_DIR,
        'protocol' => 'binary',
        'timeout' => 10,
        'use_lark' => "",
        'service' => 'ServiceBase',
        'dove_key' => 'ServiceBase.common.services.ip',
    );
}
