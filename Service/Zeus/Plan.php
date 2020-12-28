<?php
namespace Service\Zeus;

use Service\ServiceBase;
/**
 * 广告计划.
 * @user kuid<kuid@jumei.com
 * @date 2018年11月21日
 */
class Plan extends ServiceBase
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
     * 更新广告计划.
     *
     * @param array  $params    更新数据.
     * @param array  $condition 条件.
     * @param string $msg       错误提示.
     *
     * @return integer.
     */
    public function updatePlan($params, $condition, &$msg)
    {
        try {
            $status = $this->phpClient('Zeus\Plan')->updatePlan($params, $condition);
            if (!$status) {
                $status = 0;
                $msg = isset($result['message']) && !empty($result['message']) ? $result['message'] : '更新广告计划失败!';
            }
        } catch (Exception $e) {
            $status = 0;
            $msg = '更新广告计划异常!';
        }

        return $status;
    }

    /**
     * 新增广告计划.
     *
     * @param array $data 广告计划信息.
     * @param string $msg 错误提示.
     *
     * @return mixed
     */
    public function addPlan($data, &$msg)
    {
        try {
            $status = $this->phpClient('Zeus\Plan')->addPlan($data);
            if (!$status) {
                $status = 0;
                $msg = isset($result['message']) && !empty($result['message']) ? $result['message'] : '新增广告计划失败!';
            }
        } catch (Exception $e) {
            $status = 0;
            $msg = '新增广告计划出现异常!';
        }

        return $status;
    }

    /**
     * 广告计划列表-分页.
     *
     * @param array   $condition 条件.
     * @param string  $fields    字段.
     * @param integer $page      页码.
     * @param integer $pageSize  每页条数.
     *
     * @return array.
     */
    public function getPlanList($condition, $fields = '*', $page = 1, $pageSize = 10)
    {
        return $this->phpClient('Zeus\Plan')->getPlanList($condition, $fields, $page, $pageSize);
    }

    /**
     * 广告计划详细信息.
     *
     * @param array $condition 条件.
     *
     * @return array.
     */
    public function getPlan($condition)
    {
        return $this->phpClient('Zeus\Plan')->getPlan($condition);
    }

    /**
     * 广告计划列表.
     *
     * @param array  $condition 条件.
     * @param string $fields    字段.
     *
     * @return array.
     */
    public function getPlans($condition, $fields = '*')
    {
        return $this->phpClient('Zeus\Plan')->getPlans($condition, $fields);
    }

    /**
     * 广告素材上传.
     *
     * @param string $uploadFilePath 上传文件的具体路径.
     * @param string $ext            上传文件的类型.
     *
     * @return  string
     */
    public function upload($uploadFilePath, $ext)
    {
        if (!is_file($uploadFilePath)) {
            $result = json_encode(array("code" => "1005", "info" => "can not find file, error path!"));
            return $result;
        }

        $materialConfig = \Config\Advert::$material;
        if ($ext === 'mp4') {
            $url = $materialConfig['uploadUrl'] . 'uploadVideo';
        } else {
            $url = $materialConfig['uploadUrl'] . 'uploadRadioFile';
        }

        // 上传文件需要在前面加＠
        $post = array('file' => class_exists('CURLFile', false) ? new \CURLFile($uploadFilePath) : '@' . $uploadFilePath);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 0
        );
        $result = $this->curlExec($options);
        @unlink($uploadFilePath);

        return json_decode($result, true);
    }

    /**
     * 调用curl
     *
     * @param array $options curl 参数数组
     * @return string json
     */
    private function curlExec($options)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        // 返回json string
        $result = curl_exec($ch);
        // 检查是否有错误发生
        if (curl_errno($ch)) {
            $error = "curl exec error! " . curl_error($ch);
            $result = json_encode(array("code" => "1005", "info" => $error));
        }
        curl_close($ch);
        return $result;
    }

    /**
     * 广告信息同步短视频系统.
     *
     * @param array $plan 广告信息.
     *
     * @return  string
     */
    public function sync($plan)
    {
        $materialConfig = \Config\Advert::$material;

        $post = array(
            'Video_url' => $plan['material']['video_url'],
            'Description' => $plan['ad_name'],
            'activity_text' => $plan['ad_name'],
            'activity_subtitle' => $plan['ad_name'],
            'activity_url' => $plan['material']['jump_link'],
            'Major_pic_id' => $plan['material']['pic_url'],
        );

        if (isset($plan['material']['out_no']) && $plan['material']['out_no']) {
            $post['showId'] = $plan['material']['out_no'];
            $url = $materialConfig['uploadUrl'] . 'updateVideoShowDescriptionAndMajorPicNew';
        } else {
            $url = $materialConfig['uploadUrl'] . 'addRadioShow';
        }
        $post = json_encode($post);

        $header = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post)
        );
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 0
        );
        $result = $this->curlExec($options);

        return json_decode($result, true);
    }

    /**
     * 发布消息到其他系统.
     *
     * @param array $param 同步数据.
     *
     * @return mixed.
     */
    public function syncData($param)
    {
        return $this->phpClient('Zeus\Plan')->syncData($param);
    }

}