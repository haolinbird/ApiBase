<?php
/**
 * 静态资源辅助类
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
namespace Util;

class StaticRes
{
    static protected $inst;
    protected $config;
    protected $mapJson;
    protected $cacheDir = '/home/logs/wangwang/map-json/';

    /**
     * 静态资源配置.
     *
     * @param string $configName 配置名称.
     *
     * @return $this
     */
    static public function instance($configName)
    {
        if (empty(self::$inst[$configName])) {
            self::$inst[$configName] = new self($configName);
        }
        return self::$inst[$configName];
    }

    /**
     * VueStaticResNew constructor.
     * @param $configName
     */
    public function __construct($configName)
    {
        $this->config = \Config\Common::$vueStaticConf[$configName];
        if (defined('\Config\Common::VUE_CACHE_DIR')) {
            $this->cacheDir = \Config\Common::VUE_CACHE_DIR . $configName . DIRECTORY_SEPARATOR . 'map-json' . DIRECTORY_SEPARATOR;
        }
        $this->cacheDir = $this->cacheDir . $configName . DIRECTORY_SEPARATOR;
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    /**
     * 获取静态资源.
     *
     * @param string $url
     * @param boolean $https
     *
     * @return null|string
     *
     * @throws \Exception
     */
    public function getAutoStaticSetting($url = '', $https = false)
    {
        $static_url = $this->config['auto_static'];
        if (empty($this->mapJson[$static_url])) {
            // 读取配置.
            $this->mapJson[$static_url] = $this->getAutoStaticByFile($static_url);
        }
        $result = '';
        if (!empty($this->mapJson[$static_url])) {
            if (preg_match('/\.css$/', $url)) {
                $result = $this->mapJson[$static_url]['css'][$url];
            } else {
                $result = $this->mapJson[$static_url]['js']['min'][$url];
            }
        }
        // 试过很多方法，暂时没办法判断请求是http还是https, 故如果页面支持https则使用//进行自适应匹配.
        if (!empty($result) && $https == true) {
            $result = str_replace('http://', '//', $result);
        }
        return $result;
    }

    /**
     * 从文件中获取静态.
     *
     * @param string $static_url 版本地址.
     *
     * @return mixed
     */
    protected function getAutoStaticByFile($static_url)
    {
        $filePath = $this->cacheDir . md5($static_url) . '.json';
        if (file_exists($filePath)) {
            $mapJson = file_get_contents($filePath);
            $mapJson = json_decode($mapJson, true);
        } else {
            $mapJson = $this->getAutoStatic($static_url);
            if (is_array($mapJson)) {
                file_put_contents($filePath, json_encode($mapJson));
            }
        }
        return $mapJson;
    }

    /**
     * 获得静态URL数据.
     *
     * @param string $static_url 静态远程地址.
     *
     * @return mixed|null
     */
    protected function getAutoStatic($static_url)
    {
        $mapJson = file_get_contents($static_url);
        $mapJson = json_decode($mapJson, true);
        // 删除掉无用的配置.
        unset($mapJson['js']['debug']);
        return $mapJson;
    }
}