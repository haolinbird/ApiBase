<?php
// header申明
header("Content-type: text/html; charset=utf-8");
// 引入配置文件
require_once(__DIR__.'/../Config.inc.php');

// 引入公用(跨项目)类库加载器.
require JM_VENDOR_DIR.'Bootstrap/Autoloader.php';
Bootstrap\Autoloader::instance()->init();
\JmArchiTracker\Tracker::init();
// Web路由/控制器
require_once(JM_WEB_FRAMEWORK_ROOT. 'JMFrameworkWebManagement.php');
// 引入公用(跨项目)类初始化MNLogger加载器.
\Util\Util::initMNLogger();
// 重新定义应用入口
\Api\Application::instance($siteConfig)->App('client_v')->run();
