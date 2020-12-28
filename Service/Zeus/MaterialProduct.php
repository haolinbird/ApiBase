<?php
namespace Service\Zeus;

use Service\ServiceBase;
/**
 * 广告素材商品.
 * @user kuid<kuid@jumei.com
 * @date 2018年12月5日
 */
class MaterialProduct extends ServiceBase
{
    /**
     * Get Instance.
     *
     * @return \Service\UserInfo
     */
    public static function instance($sington = false)
    {
        return parent::instance($sington);
    }

    /**
     * 更新广告素材商品.
     *
     * @param array  $params    更新数据.
     * @param array  $condition 条件.
     *
     * @return integer.
     */
    public function updateMaterialProduct($params, $condition)
    {
        return $this->phpClient('Zeus\MaterialProduct')->updateMaterialProduct($params, $condition);
    }

    /**
     * 新增广告素材商品.
     *
     * @param array $data 新增数据.
     *
     * @return mixed
     */
    public function addMaterialProduct($data)
    {
        return $this->phpClient('Zeus\MaterialProduct')->addMaterialProduct($data);
    }

    /**
     * 广告素材商品列表.
     *
     * @param array   $condition 条件.
     * @param string  $fields    字段.
     * @param integer $page      页码.
     * @param integer $pageSize  每页条数.
     *
     * @return array.
     */
    public function getMaterialProductList($condition, $fields = '*', $page = 1, $pageSize = 10)
    {
        return $this->phpClient('Zeus\MaterialProduct')->getMaterialProductList($condition, $fields, $page, $pageSize);
    }

    /**
     * 广告素材商品详细信息.
     *
     * @param array   $condition 条件.
     *
     * @return array.
     */
    public function getMaterialProduct($condition)
    {
        return $this->phpClient('Zeus\MaterialProduct')->getMaterialProduct($condition);
    }

    /**
     * 广告素材商品详细列表.
     *
     * @param array   $condition 条件.
     *
     * @return array.
     */
    public function getMaterialProductAll($condition)
    {
        return $this->phpClient('Zeus\MaterialProduct')->getMaterialProductAll($condition);
    }

}