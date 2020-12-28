<?php
/**
 * 图片处理引擎.使用imagick库操作.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;
/**
 * 图片引擎类.
 */
class ImageLib
{

    /**
     * 裁剪图片. 当前只支持jpg图片格式.
     * 
     * @param string $from    源路径.
     * @param string $to      目标路径. 
     * @param string $width   宽度. 
     * @param string $height  高度. 
     * @param string $point_x 范围X坐标. 
     * @param string $point_y 范围Y坐标. 
     * 
     * @return bool 裁剪成功与否.
     */
    public static function cropImage($from, $to, $width, $height, $point_x, $point_y)
    {
        $image = new \Imagick($from);
        $image->stripImage(); // 去掉数码头信息，例如ISO之类的相片信息.
        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
        $dpi = $image->getImageResolution();
        $image->setImageCompressionQuality(95); // 图片质量度.
        $image->setImageResolution($dpi['x'], $dpi['y']); // DPI值.s
        $image->cropImage($width, $height, $point_x, $point_y); // 裁切图片
        if ($image->writeImage($to)) {
            $image->clear();
            $image->destroy();
            return true;
        }
        return false;
    }

    /**
     * 创建缩略图.
     * 
     * @param string $from    源路径.
     * @param string $to      目标路径. 
     * @param string $width   宽度. 
     * @param string $height  高度. 
     * 
     * @return bool 成功与否.
     */
    public static function copyImageMagick($from, $to, $width, $height){
        $state = false;
        try {
            $thumb = new \Imagick();
            $thumb->readImage($from);
            $thumb->setImageFormat( "jpg" );
            $thumb->setImageCompression(imagick::COMPRESSION_JPEG);
            $thumb->setImageCompressionQuality(100);
            $thumb->stripImage();
            $thumb->cropThumbnailImage($width,$height);
            $state = $thumb->writeImage($to);
            $thumb->destroy();
        } catch (\Exception $ex) {
        }
        return $state;
    }

    /**
     * 复制图片到本地.
     * 
     * @param string $from 源.
     * @param string $to 本地.
     * 
     * @return boolean.
     */
    public static function copyImageToLocal($from, $to)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $from);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        return $content && (file_put_contents($to, $content) == true);
    }

    /**
     * 切图.
     * 
     * @param string  $from    图片源.
     * @param string  $to      存放地址.
     * @param string  $width   Width.
     * @param string  $height  Height.
     * @param boolean $bestfit Bestfit.
     * 
     * @return bool 裁剪成功与否.
     */
    public static function thumbnailImage($from, $to, $width, $height, $bestfit = true)
    {
        $state = false;
        try {
            $image = new \Imagick($from);
            $image->stripImage(); // 去掉数码头信息，例如ISO之类的相片信息.
            $image->setImageCompression(\Imagick::COMPRESSION_JPEG);
            $dpi = $image->getImageResolution();
            $image->setImageCompressionQuality(95); // 图片质量度.
            $image->setImageResolution($dpi['x'], $dpi['y']); // DPI值.s
            $image->thumbnailImage($width, $height, $bestfit);
            $state = $image->writeImage($to);
            $image->clear();
            $image->destroy();
        } catch (\Exception $ex) {
        }
        return $state;
    }

}
