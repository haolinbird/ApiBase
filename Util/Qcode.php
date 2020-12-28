<?php
/**
 * 生成二维码功能.
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

class Qcode
{
    /**
     * 根据连接生成二维码.
     * 
     * @param string  $text   生成二位的的信息文本,例如文字描述或者网址信息.
     * @param integer $size   生成二维码的尺寸设置.
     * @param string  $level  可选纠错级别，支持四个等级纠错，用来恢复丢失的、读错的、模糊的数据.
     *        L-默认：可以识别已损失的7%的数据.
     *        M-可以识别已损失15%的数据.
     *        Q-可以识别已损失25%的数据.
     *        H-可以识别已损失30%的数据.
     * @param integer $margin 生成的二维码离图片边框的距离.
     * 
     * @return object
     */

    public static function createpng($text, $size = 6, $level = 'L', $margin = 2)
    {
        $result = false;
        if (!empty($text)) {
            include __DIR__ . '/phpqrcode.php';
            $result = \QRcode::png($text, false, $level, $size, $margin);
        }
        return $result;
    }

    /**
     * 根据连接生成二维码资源.
     *
     * @param string  $text   生成二位的的信息文本,例如文字描述或者网址信息.
     * @param integer $size   生成二维码的尺寸设置.
     * @param string $level   可选纠错级别，支持四个等级纠错，用来恢复丢失的、读错的、模糊的数据.
     *        L-默认：可以识别已损失的7%的数据.
     *        M-可以识别已损失15%的数据.
     *        Q-可以识别已损失25%的数据.
     *        H-可以识别已损失30%的数据.
     * @param integer $margin 生成的二维码离图片边框的距离.
     *
     * @return bool|resource
     */

    public static function createPngResource($text, $size = 3, $level = 'L', $margin = 2)
    {
        $result = false;
        if (!empty($text)) {
            include __DIR__ . '/phpqrcode.php';
            $result = \QRcode::resource($text, false, $level, $size, $margin);
        }
        return $result;
    }

    /**
     * 二维码嵌套外围图片
     *
     * @param mixed   $QR           资源符.
     * @param mixed   $outside      资源符.
     * @param integer $from_width_x 宽度.
     * @param integer $from_width_y 长度.
     *
     * @return
     */
    public static function outsideCode($QR, $outside, $from_width_x, $from_width_y)
    {
        if ($outside !== FALSE) {
            $QR = is_resource($QR) ? $QR : imagecreatefromstring(file_get_contents($QR));
            $outside = is_resource($outside) ? $outside : imagecreatefromstring(file_get_contents($outside));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $outside_width = imagesx($outside);//外围图片宽度
            $outside_height = imagesy($outside);//外围图片高度
            //重新组合图片并调整大小
            imagecopyresampled($outside, $QR, $from_width_x, $from_width_y, 0, 0, $QR_width,
                $QR_height, $QR_width, $QR_height);
        }
        //输出图片
        ob_start();
        ImagePng($outside);
        $ret = ob_get_contents();
        ob_end_clean();
        return $ret;
    }

    /**
     * 二维码内嵌logo
     *
     * @param mixed   $QR           资源符.
     * @param mixed   $logo         logo资源符.
     * @param boolean $choiceResult 是否输出图片.
     *
     * @return mixed
     */
    public static function logoCode($QR, $logo, $choiceResult = true)
    {
        if ($logo !== FALSE) {
            $QR = is_resource($QR) ? $QR : imagecreatefromstring(file_get_contents($QR));
            $logo = is_resource($logo) ? $logo : imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//log图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        if($choiceResult){
            //输出图片
            Header("Content-type: image/png");
            ImagePng($QR);
        }else{
            return $QR;
        }
    }

}