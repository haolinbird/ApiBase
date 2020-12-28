<?php
namespace Util;

class CInstance
{

    protected static $_instance = array();

    /**
     * 获取单例
     * 
     * 相对于createInstance的另一种选择
     * 
     * @return static
     */
    public static function getInstance() {
        $className = get_called_class();
        if (!isset(self::$_instance[$className])) {
            self::$_instance[$className] = new $className();
        }
        return self::$_instance[$className];
    }
}