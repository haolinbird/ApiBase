<?php

namespace Service;



class UploadService extends \Service\ServiceBase
{
    /**
     * @return UploadService
     */
    public static function Instance($sington = true)
    {
        return parent::Instance();
    }

    /**
     * 上传处理功能
     */
    public function imgUpload($image, $cloudImagePath, $code_type = '')
    {
        //上传文件类型列表
        $img_types = array('jpg', 'jpeg', 'png', 'gif', 'pjpeg', 'bmp', 'x-png');
        if ($code_type == 'base64') {
            preg_match('/^(data:\s*image\/(\w+);base64,)/', $image, $match);
            $suffix = $match[2];
            $newImageName = uniqid() . '.' . $suffix;
            $imagefile = base64_decode(str_replace($match[1], '', $image));
        } else {
            $suffix = substr(strrchr($image['name'], '.'), 1);
            $newImageName = md5($image['name'] . time()) . '.' . $suffix;
            $imagefile = file_get_contents($image['tmp_name']);
        }
        if (in_array($suffix, $img_types) && !empty($imagefile)) {
            $res = $this->imgUploadToCloud($newImageName, $imagefile, $cloudImagePath);
            if (!empty($res) && $res['upload'] == 1) {
                return $res['url'];
            }
        }
        return false;
    }

    /**
     * 上传文件到图片系统
     */
    protected function imgUploadToCloud($newImageName, $imagefile, $cloudImagePath)
    {
        // 配置信息.
        $ENV = \Config\Common::$domainForH5;
        if ($ENV == 'http://h5.rd.shuabaola.cn/') {
            $picUser = array(
                'url' => 'http://picdev.int.jumei.com/upload',
                'user' => 'test123',
                'password' => '123456',
                'path' => '/devtest/' . $cloudImagePath,
            );
        } else {
            $picUser = array(
                'url' => 'http://pic.int.jumei.com/upload',
                'user' => 'zengzhang',
                'password' => 'zz@#filejumei',
                'path' => '/zengzhang/' . $cloudImagePath,
            );
        }
        $configfile = array(
            'user' => $picUser['user'], // 用户名.
            'password' => $picUser['password'], // 密码.
            'path' => $picUser['path'], // 上传路径.
            'fileName' => $newImageName,
            'formats' => array('raw'),
        );
        $data = array(
            'configfile' => json_encode($configfile),
            'imagefile' => $imagefile,
        );
        $url = $picUser['url'];
        // 初始化curl.
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // 运行curl, 结果以json格式返回.
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);
        if ($res['code'] == 1000) {
            $result = array(
                'upload' => 1,
                'url' => $this->createFilePath($res['paths']['raw']),
            );
        } else {
            $result = array(
                'upload' => 0,
                'msg' => $res['info'],
            );
        }
        return $result;
    }

    /**
     * 图片缩放
     * @param $fromPic
     * @param $toPic
     * @param $height
     * @param $width
     * @param bool $keep  是否保持元高宽比
     * @return bool|string
     */
    public function imgCloudZoom($fromPic, $toPic, $height, $width, $keep = true)
    {
        // 配置信息.
        $ENV = \Config\Common::$domainForH5;
        if ($ENV == 'http://h5.rd.shuabaola.cn/') {
            $fromPic = '/devtest/' . $fromPic;
            $toPic = '/devtest/' . $toPic;
            $picUser = array(
                'url' => 'http://192.168.20.69:8000/image_zoom',
                'user' => 'test123',
                'password' => '123456',
            );
        } else {
            $fromPic = '/zengzhang/' . $fromPic;
            $toPic = '/zengzhang/' . $toPic;
            $picUser = array(
                'url' => 'http://pic.int.jumei.com/image_zoom',
                'user' => 'zengzhang',
                'password' => 'zz@#filejumei',
            );
        }
        $configfile = [
            'user' => $picUser['user'], // 用户名.
            'password' => $picUser['password'], // 密码.
            'fromPic' => $fromPic, // 原路径: /路径/文件名.
            'zoom_files' => [
                ['toPic' => $toPic, 'height' => $height, 'width' => $width, 'keep' => $keep]
            ],
        ];
        $data = [
            'configfile' => json_encode($configfile),
        ];
        $url = $picUser['url'];
        // 初始化curl.
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // 运行curl, 结果以json格式返回.
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);
        if ($res['code'] == 1000) {
            foreach ($res as $path=>$val) {
                if ($val == 1) {
                    return $this->createFilePath($path);
                }
            }
        }
        return false;
    }

    /**
     * 生成上传文件的外部访问地址.
     */
    protected function createFilePath($file)
    {
        // 图片显示,前缀域名.
        $ENV = \Config\Common::$domainForH5;
        if ($ENV == 'http://h5.rd.shuabaola.cn/') {
            $domainNames = array(
                'http://p12.dev.jmstatic.com'
            );
        } else {
            $domainNames = array(
                'https://p13.jmstatic.com',
                'https://p14.jmstatic.com',
                'https://p15.jmstatic.com',
            );
        }
        $domainName = $domainNames[array_rand($domainNames)];
        return $domainName . $file;
    }

    /**
     * 图片加水印上传.
     *
     * @param string $imagefile 文件内容.
     * @param string $type      类型.
     *
     * @return  array
     */
    public function uploadWithWatermark($imagefile, $type = 'treasure_star')
    {
        $cfg = \Config\JMFile::$watermarkUpload;
        //上传文件类型列表
        $allowTypes = array('jpg', 'jpeg', 'png', 'bmp');
        $suffix = substr(strrchr($imagefile['name'], '.'), 1);
        $fileName = md5($imagefile['name'] . time()) . '.' . $suffix;
        $configfile = array(
            'user'      => $cfg['user'], // 用户名.
            'password'  => $cfg['password'], // 密码.
            'path'      => $cfg[$type]['path'], // 上传路径.
            'fileName'  => $fileName,
            'text'      => $cfg[$type]['text'],
        );
        if (!in_array($suffix, $allowTypes)) {
            $this->rpcBusinessException('图片类型限jpg,jpeg,png,bmp');
        }
        $imagefile = file_get_contents($imagefile['tmp_name']);
        $data = array(
            'configfile' => json_encode($configfile, JSON_UNESCAPED_UNICODE),
            'imagefile' => $imagefile,
        );
        $url = $cfg['url'];
        // 初始化curl.
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // 运行curl, 结果以json格式返回.
        $res = curl_exec($ch);
        curl_close($ch);
        \Utils\Log\Logger::instance()->log($res);
        $res = json_decode($res, true);
        if ($res['code'] == 1000) {
            $result = array(
                'status' => 1,
                'imageUrl' => $cfg['export_url'] . $res['paths']['raw'],
            );
        } else {
            $result = array(
                'status' => 0,
                'msg' => $res['info'],
            );
        }
        return $result;
    }

    /**
     * 读取水印图片.
     */
    public function exportWatermark($fileUrl)
    {
        $cfg = \Config\JMFile::$watermarkUpload;
        $configfile = array(
            'user'      => $cfg['user'], // 用户名.
            'password'  => $cfg['password'], // 密码.
        );
        $data = array(
            'configfile' => json_encode($configfile),
        );
        $url = $fileUrl;
        // 初始化curl.
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // 运行curl, 结果以json格式返回.
        $res = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($res, true);
        return isset($response['code']) ? '' : $res;
    }

}