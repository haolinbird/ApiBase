<?php
// 定义项目/应用名称标识。(内部全系统通用标识)
define('JM_APP_NAME', 'ApiBase-api');

// 开发框架所需的site命名
define('SITE_NAME', 'ApiBase-api');

if (!defined('DEBUG')) {
    define('DEBUG', false);
}
// 定义公共类库根目录，请根据实际环境修改。
define('JM_VENDOR_DIR', __DIR__ . '/../Vendor/');

date_default_timezone_set('Asia/Shanghai');
// 定义系统常量
define('__DS__', DIRECTORY_SEPARATOR);
define('JM_APP_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('JM_PROJECT_ROOT', JM_APP_ROOT);
define('JM_WEB_FRAMEWORK_ROOT', JM_VENDOR_DIR.'JMWebFramework/Lib/');
define('JM_PROJECT_LOGS_ROOT', JM_APP_ROOT . '../Logs');
define('JM_EXT', JM_PROJECT_ROOT . '../Ext/');

// 页面模板根目录
define('JM_VIEW', JM_APP_ROOT . 'View/');
define('JM_PHASE_STAGING', 'staging');

// 项目内部公用模块根目录
define('JM_COMMON', JM_PROJECT_ROOT . '../Commons/');


$siteConfig = array();
/*=====================================================
 cookie中分站信息的key，这个值非常重要，请保持和主站一致
 =====================================================*/
$siteConfig['siteVersion'] = 'default_site_25';
$siteConfig['Site']['WWW']['WebDomainName'] = "";
$siteConfig['Site']['Main']['TopLevelDomainName'] = "";
$siteConfig['Site'][SITE_NAME]['FriendlyName'] = '基础服务API';
$siteConfig['Site'][SITE_NAME]['WebDomainName'] = "ApiBase.xiaonianyu.com";
$siteConfig['Site'][SITE_NAME]['WebBaseURL'] = 'http://' . $siteConfig['Site'][SITE_NAME]['WebDomainName'] . '/';

$siteConfig['AccountUserInfo']['DefaultInfo'] = "";

// 当前站点域名.
$siteConfig['Site']['Current'] = $siteConfig['Site'][SITE_NAME];

/**日志记录级别
 $levels = array(
 100 => 'DEBUG',
 200 => 'INFO',
 300 => 'WARNING',
 400 => 'ERROR',
 500 => 'CRITICAL',
 550 => 'ALERT',
 999 => 'DATA'
 );
 */
$siteConfig['Logger']['default']['Level'] = '100';
$siteConfig['Logger']['default']['Handlers'][] = array(
    'class' => 'Mono_Handler_LineFileEveryDay',
    'params' => array('basePath' => JM_PROJECT_LOGS_ROOT)
);
// 上传文件 或css 后需要更新版本号.
$cssConfig = array('path' => $siteConfig['Site'][SITE_NAME]['WebBaseURL'], 'version' => 0);
$imgList   = array('path' => $siteConfig['Site'][SITE_NAME]['WebBaseURL'], 'version' => 0);
$jsList    = array('path' => $siteConfig['Site'][SITE_NAME]['WebBaseURL'], 'version' => 0);

// 将开发环境，与业务相关的配置写到这里


$siteConfig['IsInDevelopment'] = false;

$MNLogger['stats'] = array(
    'on' => true,
    'app' => JM_APP_NAME,
    'logdir' => '/tmp/logs/monitor'
);
$MNLogger['data'] = array(
    'on' => true,
    'app' => JM_APP_NAME,
    'logdir' => '/tmp/logs/monitor'
);
$MNLogger['exception'] = array(
    'on' => true,
    'app' => JM_APP_NAME,
    'logdir' => '/tmp/logs/monitor'
);
class RpcBusinessException extends \Exception {}