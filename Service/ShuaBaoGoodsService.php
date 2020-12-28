<?php
/**
 * Created by PhpStorm.
 * User: liuzhonghao
 * Date: 2020/2/5
 * Time: 上午10:43
 */

namespace Service;


class ShuaBaoGoodsService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoGoodsService';

    /**
     * Get Instance.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 添加商品.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function addGoods($param)
    {
        $response = $this->doThriftClientByMethod('addGoods', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 商品列表.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function goodsList($param)
    {
        $response = $this->doThriftClientByMethod('goodsList', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 更新商品售卖状态（上架/下架）.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function updateSellStatus($param)
    {
        $response = $this->doThriftClientByMethod('updateSellStatus', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 绑定已存在的商品到视频上.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function bindGoodsOnVideo($param)
    {
        $response = $this->doThriftClientByMethod('bindGoodsOnVideo', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 删除商品.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function delGoods($param)
    {
        $response = $this->doThriftClientByMethod('delGoods', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 带货视频详情.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryGoodsVideoDetail($param)
    {
        $response = $this->doThriftClientByMethod('queryGoodsVideoDetail', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 查询更多带货视频.
     *
     * @param array $param 参数数组.
     *
     * @return array.
     * @throws \Exception
     */
    public function queryMoreGoodsVideo($param)
    {
        $response = $this->doThriftClientByMethod('queryMoreGoodsVideo', json_encode($param));
        return json_decode($response,true);
    }

    /**
     * 获取淘宝商品信息
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    public function queryTaoBaoGoods($param)
    {
        $response = $this->doThriftClientByMethod('queryTaoBaoGoods', json_encode($param));
        return json_decode($response,true);
    }

}