<?php
/**
 * 默认页面
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
class Controller_Index extends Controller_Base_Base
{
    /**
     * app入口文件
     */
    public function action_Index()
    {
       $url =  $this->getWebSiteUrl();
    }

    /**
     * 找不到页面的报错
     */
    public function action_PageNotFound()
    {
        $this->responseFail('NOT_FOUND');
    }

}
