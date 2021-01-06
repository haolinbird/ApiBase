<?php
/**
 * Demo控制层
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

class Controller_Base_Demo extends Controller_Base_Base
{

    public $needLogin = false;

    public function initialize()
    {
    }

    /**
     * 测试数据
     */
    public function action_Test()
    {
        $params = JMGetGet('test_params') ?: 'hello test';

        $result = \Module\Demo::instance()->testRequestService($params);

        return $this->responseSuccess(['type' => 'success']);
    }
}

