<?php
/**
 * File Demo.php
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Module;

/**
 * Class \Module\Demo.
 */
class Demo extends \Module\ModuleBase
{
    /**
     * Get Instance.
     *
     * @param boolean $sington 是否强制获取新单例对象
     *
     * @return \Module\Demo
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 测试请求 Service.
     *
     * @param string $testStr 请求参数.
     *
     * @return mixed
     */
    public function testRequestService($testStr)
    {
        try {
            return \Service\Demo\Test::instance()->
            return \PHPClient\Text::inst('ServiceBase')->setClass('Test')->test($testStr);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
