<?php
namespace Service\Zeus;

use Service\ServiceBase;
/**
 * 广告素材.
 * @user kuid<kuid@jumei.com
 * @date 2018年12月1日
 */
class Material extends ServiceBase
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
     * 更新广告素材.
     *
     * @param array  $params    更新数据.
     * @param array  $condition 条件.
     *
     * @return integer.
     */
    public function updateMaterial($params, $condition)
    {
        return $this->phpClient('Zeus\Material')->updateMaterial($params, $condition);
    }

    /**
     * 新增广告素材.
     *
     * @param array $data 新增数据.
     *
     * @return mixed
     */
    public function addMaterial($data)
    {
        return $this->phpClient('Zeus\Material')->addMaterial($data);
    }

    /**
     * 广告素材列表-分页.
     *
     * @param array   $condition 条件.
     * @param string  $fields    字段.
     * @param integer $page      页码.
     * @param integer $pageSize  每页条数.
     *
     * @return array.
     */
    public function getMaterialList($condition, $fields = '*', $page = 1, $pageSize = 10)
    {
        return $this->phpClient('Zeus\Material')->getMaterialList($condition, $fields, $page, $pageSize);
    }

    /**
     * 广告素材详细信息.
     *
     * @param array $condition 条件.
     *
     * @return array.
     */
    public function getMaterial($condition)
    {
        return $this->phpClient('Zeus\Material')->getMaterial($condition);
    }

    /**
     * 广告素材详细信息.
     *
     * @param array  $condition 条件.
     * @param string $fields    字段.
     *
     * @return array.
     */
    public function getMaterials($condition, $fields = '*')
    {
        return $this->phpClient('Zeus\Material')->getMaterials($condition, $fields);
    }

    /**
     * 素材上传.
     *
     * @param string $uploadFilePath 上传文件的具体路径.
     * @param string $mine           文件MIME.
     *
     * @return  string
     */
    public function upload($uploadFilePath, $mine)
    {
        if (!is_file($uploadFilePath)) {
            $result = json_encode(array("code" => "1005", "info" => "can not find file, error path!"));
            return $result;
        }

        $jmConfig = new \Config\JMFile();
        $fileConfig = $jmConfig->default;
        $path = $fileConfig['path']['zeus_material'] . '/' . date("Ymd/H");
        $fileName = \Util\Util::getFileSavePath($mine);
        $upResult = \JMFile\JMFile::instance()->upload($uploadFilePath, $path, $fileName);

        @unlink($uploadFilePath);
        $result = json_decode($upResult, true);
        if (isset($result['paths']['raw']) && !empty($result['paths']['raw'])) {
            $result['paths']['raw'] = $fileConfig['cdnPath'] . $result['paths']['raw'];
        }

        return $result;
    }

}