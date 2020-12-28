<?php
/**
 * 环境配置.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Config;

/**
 * 环境配置.
 */
class Env
{
    public static $env = "#{ServiceBase.env}";

    /**
     * 环境对应的名字.
     *
     * @var string
     */
    public static $envNames = "#{ServiceBase.env.name}";

}
