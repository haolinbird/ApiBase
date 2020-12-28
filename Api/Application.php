<?php
namespace Api;

/**
 * app 版本调度列表
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
class Application {
    /**
     * 配置
     * @var array
     */
    protected $siteConfig = array();
    /**
     *
     * @var \JMSiteEngine
     */
    protected $siteEngine;
    
    protected $clientVPre = '';
    
    protected $routePath = '';

    /**
     *
     * @param array $siteConfig
     * @return \Api\Application
     */
    protected function __construct($siteConfig)
    {
        $this->siteConfig = $siteConfig;
        return $this;
    }
    
    /**
     * 实例化入口
     * @param array $siteConfig
     * @return \Api\Application
     */
    public static function instance($siteConfig)
    {
        return new self($siteConfig);
    }
    
    /**
     * 获取站点对象
     * @return \JMSiteEngine
     */
    protected function getSiteEngine()
    {
        if (!$this->siteEngine || !($this->siteEngine instanceof \JMSiteEngine)){
            $this->siteEngine = new \JMSiteEngine();
        }
        
        return  $this->siteEngine;
    }
    
    /**
     * 重新调度app版本
     */
    protected function dispatch()
    {
        $this->routePath = ! empty($_GET[JM_ROUTE_PATH_VAR_NAME]) ? $_GET[JM_ROUTE_PATH_VAR_NAME] : "";
        // 先将默认的跌幅格式化一遍
        $this->routePath = $this->formatRoutePath($this->routePath);
        // 解析路由格式
        $routePathFields = $this->getRoutePathFields($this->routePath);
        // 对于路径大于3段不处理
        if (count($routePathFields) <= 2){
            // 默认等于Base 版本
            $versionPath = $this->getVersionPath();
            // 加上版本号
            $versionPath = $versionPath.$this->routePath;
            // 去掉两个斜杠的情况
            $versionPath = str_replace('//', '/', $versionPath);
            // 重新赋值
            $this->routePath = $versionPath;
        }
        
        // 重新赋值路由
        $_GET[JM_ROUTE_PATH_VAR_NAME] = $this->routePath;
    }
    
    /**
     * 格式化路由
     * @param array $routePath
     * @return string
     */
    protected function formatRoutePath($routePath)
    {
        $routePathField = explode('/', $routePath);
        if (is_array($routePathField)){
            foreach ($routePathField as &$v){
                // 处理下划线转驼峰
                $tmpV = explode('_', $v);
                if ($tmpV && is_array($tmpV)){
                    $v = '';
                    foreach ($tmpV as $str){
                        $v .= ucfirst($str);
                    }
                }
                $v = ucfirst($v);
            }
            unset($v);
        }
        $path = implode('/', $routePathField);
        return $path;
    }
    
    /**
     * 检测版本路径
     * @param unknown $version
     * @return boolean
     */
    public function checkVersionPath($version)
    {
        $path =  'Controller'.DIRECTORY_SEPARATOR.$version.DIRECTORY_SEPARATOR. $this->routePath;
        $pathFields = $this->getRoutePathFields($path);
        array_pop($pathFields);
        $verPath = JM_PROJECT_ROOT.join('/', $pathFields).'.php';
        return is_file($verPath);
    }
    /**
     * 转化成路由字段
     * @param unknown $path
     */
    protected function getRoutePathFields($path)
    {
        $siteEngine = $this->getSiteEngine();
        $siteEngine->setRoutePath($path);
        $routePathFields = $siteEngine->getRoutePathFields();
        // 过滤掉空操作
        $routePathFields = array_filter($routePathFields);
        // 如果只有一个则把黑夜路由名称加上
        if (count($routePathFields) == 1){
            $routePathFields[] = $siteEngine->getDefaultRoutePathBaseName();
        }
        return $routePathFields;
    }
    
    /**
     * 得到版本path
     */
    protected function getVersionPath()
    {
        if (empty($this->clientVPre)){
            return ;
        }
        // 默认等于Base 版本
        $version = 'Base';
        // 得到版本
        $clientV = \Util\Util::getHeaderByName($this->clientVPre);
        // 如果没有版本字段,则直接返回原生路径
        if ($clientV === ""){
            return ;
        }
        // 大版本
        $firstV = '';
        // 小版本
        $secondV = '';
        if (!empty($clientV)){
            $clientVMap = explode('.', $clientV);
            if ($clientVMap){
                $firstV = $clientVMap[0];
                
                if (isset($clientVMap[1]) && !empty($clientVMap[1])){
                    $secondV = $clientVMap[1];
                }
            }
            
        }
        $versionMap = \Config\Common::$versionMap;
        $routeFields = $this->getRoutePathFields($this->routePath);
        $routePathKey = join('_', $routeFields);
        // 获取默认版本
        $defaultVersion = $versionMap['default'];
        if (!empty($defaultVersion) && $this->checkVersionPath($defaultVersion)){
            $version = $defaultVersion;
        }
        if ($firstV !== "" && isset($versionMap[$firstV]) && !empty($versionMap[$firstV])){
            // 第一层解析大版本
            $firstVMap = $versionMap[$firstV];
            $secondVMap = array();
            $pathMap = '';
            if (is_scalar($firstVMap) && $this->checkVersionPath($firstVMap)){
                $version = $firstVMap;
            }elseif (is_array($firstVMap)){
                // 小版本取默认版本
                if ($firstVMap[0]){
                    $secondVMap = $firstVMap[0];
                }
                if ($secondV !== "" && isset($firstVMap[$secondV]) && !empty($firstVMap[$secondV])){
                    $secondVMap = $firstVMap[$secondV];
                }
                // 如果有针对单接口的路由版本则用
                if (isset($firstVMap[$routePathKey]) && !empty($firstVMap[$routePathKey])  && is_scalar($firstVMap[$routePathKey])){
                    $pathMap = $firstVMap[$routePathKey];
                }
            }
            if (is_scalar($secondVMap) && $this->checkVersionPath($secondVMap)){
                $version = $secondVMap;
            }else if (is_array($secondVMap)){
                if (isset($secondVMap[$routePathKey]) && !empty($secondVMap[$routePathKey]) && is_scalar($secondVMap[$routePathKey])){
                    $pathMap = $secondVMap[$routePathKey];
                }
            }
            if ($pathMap !== "" && $this->checkVersionPath($pathMap)){
                $version = $pathMap;
            }
        }
        return $version.'/';
    }
    
    /**
     * app 应用初始化
     * @return \Api\Application
     */
    public function App($clientVPre = '', $routes = array())
    {
        \JMRegistry::set('SiteInfo', $this->siteConfig);
        $this->clientVPre = $clientVPre;
        
        // Url >> controller 映射器
        if (!is_array($routes)){
            $routes = array();
        }
        
        // 启动session
        // session_start();
        $siteEngine = $this->getSiteEngine();
        $siteEngine->setRoutePathMap($routes);
        $siteEngine->setSiteName(SITE_NAME);
        $siteEngine->ensureMainSiteAndLearnSubDomain();
        
        return $this;
    }
    
    /**
     * 运行入口
     */
    public function run()
    {
        // 重新调用app版本号
        $this->dispatch();
        $this->getSiteEngine()->run();
    }
}
