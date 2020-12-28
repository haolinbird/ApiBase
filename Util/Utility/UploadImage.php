<?php
/**
 * File: CUploadImageUtility.php
 *
 * @author meijuanl <meijuanl@jumei.com>
 * @date 2019-2-12
 */

namespace Util\Utility;

/**
 * Class CUploadImageUtility .
 */

class UploadImageCDNResult {
    public $code = "10001";
    public $error = "";
    public $cdn_file_full_url = "";
    public $user_filename = "";
}

class UploadImage {
    const ALLOWED_FILENAME_EXTENSIONS = 'jpg,jpeg,gif,png,bmp';
    const ZOOM_TYPE_KEEP_RATIO = "keep_ratio";
    const ZOOM_TYPE_STRETCH = "stretch";
    const ZOOM_TYPE_CLIP = "clip";

    static public function get_file_field($name, $idx = null) {
        $fields = null;
        if(is_null($idx)){
            if(isset($_FILES[$name]['tmp_name']) && isset($_FILES[$name]['name'])) {
                $fields = array(
                    'tmp_name'=>$_FILES[$name]['tmp_name'],
                    'name'=>$_FILES[$name]['name'],
                );
            }
        }else {
            if(isset($_FILES[$name]['tmp_name'][$idx]) && isset($_FILES[$name]['name'][$idx])) {
                $fields = array(
                    'tmp_name'=>$_FILES[$name]['tmp_name'][$idx],
                    'name'=>$_FILES[$name]['name'][$idx],
                );
            }
        }
        return $fields;
    }


    /**
     *
     * @param array $input_file_field
     * @param array $options
     * @return UploadImageCDNResult
     */
    static public function upload_to_cdn($input_file_field, $options) {
        $result = new UploadImageCDNResult();

        if(!isset($options['size']) || !in_array($options['size'], array(64,200,100,640,960,375,750,1280)))
        {
            $result->code = '10006';
            $result->error = '图片尺寸只支持640x640,200x64,64x64,375,750,1280!';
            return $result;
        }

        if(is_string($input_file_field))
            $input_file_field = self::get_file_field($input_file_field);

        $max_upload_size = isset($options['max_upload_size']) ? $options['max_upload_size'] : 2 * 1024 * 1024;
        $zoom_type = isset($options['zoom_type']) ? $options['zoom_type'] : self::ZOOM_TYPE_KEEP_RATIO;

        $allowed_filename_extensions = isset($options['allowed_filename_extensions']) ? $options['allowed_filename_extensions'] : self::ALLOWED_FILENAME_EXTENSIONS;
        if(!is_array($allowed_filename_extensions)) $allowed_filename_extensions = explode(',', $allowed_filename_extensions);

        //兼容HTML5上传
        if(isset($_SERVER['HTTP_CONTENT_DISPOSITION']))
        {
            if(preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'], $info))
            {
                $temp_name = ini_get("upload_tmp_dir") . '/' . date("YmdHis") . mt_rand(100000,999999) . '.tmp';
                file_put_contents($temp_name,file_get_contents("php://input"));
                $input_file_field = array('name'=>$info[2],'tmp_name'=>$temp_name,'error'=>0);
            }
        }

        if(empty($input_file_field))
        {
            $result->code = '10001';
            $result->error = '文件域的name错误或者没选择文件';
            return $result;
        }

        //判断是否遇到上传错误
        if( ! empty($input_file_field['error']))
        {
            $error_messages = array(
                UPLOAD_ERR_INI_SIZE=>'文件大小超过了php.ini定义的upload_max_filesize值',
                UPLOAD_ERR_FORM_SIZE=>'文件大小超过了HTML定义的MAX_FILE_SIZE值',
                UPLOAD_ERR_PARTIAL=>'文件上传不完全',
                UPLOAD_ERR_NO_FILE=>'无文件上传',
                UPLOAD_ERR_NO_TMP_DIR=>'缺少临时文件夹',
                UPLOAD_ERR_CANT_WRITE=>'写文件失败',
                UPLOAD_ERR_EXTENSION=>'上传被其它扩展中断',
            );
            if(isset($error_messages[$input_file_field['error']]))
                $result->error = $error_messages[$input_file_field['error']];
            else
                $result->error = '未知错误:' . $input_file_field['error'];
            return $result;
        }

        if(empty($input_file_field['tmp_name']) || $input_file_field['tmp_name'] == 'none')
        {
            $result->code = '10002';
            $result->error = '无文件上传';
            return $result;
        }

        //判断文件大小是否符合
        $filesize=filesize($input_file_field['tmp_name']);
        if($filesize > $max_upload_size)
        {
            $result->code = '10003';
            $result->error = '文件大小超过'.floor($max_upload_size/1048576).'M';
            return $result;
        }

        //判断文件名是否允许
        $extension = strtolower(pathinfo($input_file_field['name'], PATHINFO_EXTENSION ));
        if( ! in_array($extension, $allowed_filename_extensions) )
        {
            $result->code = '10004';
            $result->error = '上传文件扩展名必需为：' . join(',', $allowed_filename_extensions);
            return $result;
        }

        //准备cdn上的文件名
        if(!isset($options["cdn_filename"]))
            $options["cdn_filename"] = CDN::make_cdn_filename_by_time($input_file_field['name']);

        //建立调整过大小的文件
        $thumb_filename = $input_file_field['tmp_name'] . '-thumb.tmp';
        if( ! static::createThumb($input_file_field['tmp_name'], $thumb_filename, $options['max_width'], $options['max_height'], 80, $zoom_type) ) {

            return false;
        }
        if (\Config\Common::$useOldCdn) {
            //把调整过大小的文件传到CDN
            if(CDN::move_file_to_cdn($thumb_filename, $options["cdn_path"], $options["cdn_filename"], $options['cdn_server_info'], $cdn_file_full_url))
            {
                $result->cdn_file_full_url = $cdn_file_full_url;
                $result->user_filename = $input_file_field['name'];
            }
            else
            {
                $result->code = '10005';
                $result->error = "无法上传到CDN";
                //there is a WARNING to EXCEPTION handler in system ....
                try { @unlink($thumb_filename); }catch(Exception $ex){}
            }

            return $result;
        } else {
            $res = self::doUploadPicToNewCdn($thumb_filename, $options["uid"], $options["cdn_path"], $input_file_field, $options["size"]);
            if ($res == false) {
                $result->code = '10005';
                $result->error = "无法上传到CDN";
                //there is a WARNING to EXCEPTION handler in system ....
                try { @unlink($thumb_filename); }catch(Exception $ex){}
            } else {
                $result->cdn_file_full_url = $res['cdn_file_full_url'];
            }
            return $result;
        }

    }

    static public function doUploadPicToNewCdn($r1, $uid, $cdnPath, $fileField, $size) {

        $config = array();
        if (\Config\Common::$isPub) {
            $newCdnFilePath = '';
            foreach ($cdnPath as $value) {
                $newCdnFilePath .= '/'.$value;
            }
            $newCdnFilePath = $newCdnFilePath.'/';
            $config = array('fileName' => "{$uid}-$size-" . time() . "." . pathinfo($fileField['name'], PATHINFO_EXTENSION), 'path' => $newCdnFilePath);
        } else {
            $config = array('fileName' => "{$uid}-$size-". time() . "." . pathinfo($fileField['name'], PATHINFO_EXTENSION), 'path' => \Config\Common::$imageUpload['avatar']['path']);
        }
        rename($r1, \Config\Common::$tempPath.$config["fileName"]);
        $r1 = \Config\Common::$tempPath.$config["fileName"];
        $upLoadResult  = self::upload($r1,$config);
        if ($upLoadResult['raw']) {
            $result['cdn_file_full_url'] = \Config\Common::$imageUpload['avatar']['base_url'].$upLoadResult['raw'];
            return $result;
        } else {
            return false;
        }
    }

    public static function upload($filePath, $config = array(), $isDelete = true)
    {
        if (empty($config['fileName'])) {
            $pathInfo = pathinfo($filePath);
            $config['fileName'] = $pathInfo['filename'];
        }

        if (empty($config['formats'])) {
            $config["formats"] = array('raw');
        }
        $url = \Config\Common::$imageUpload['upload_url'];
        $config["user"] = \Config\Common::$imageUpload['user'];
        $config["password"] = \Config\Common::$imageUpload['password'];


        $post = array(
            'configfile' => json_encode($config),
            'imagefile' =>  class_exists('CURLFile', false) ? new \CURLFile($filePath) : '@' . $filePath,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 28);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1.5);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        if (isset($result['code']) && $result['code'] == 1000) {
            if ($isDelete && file_exists($filePath)) {
                unlink($filePath);
            }
            return $result['paths'];
        }
        \Util\Log::log("usercenter_uploadAvatar_uploadFailed\t" . json_encode($result), "usercenter_uploadAvatar");
        return false;
    }

    /*
      for user returns
    */
    static public function upload_to_cdn_for_return($options , $inputname , $uid) {
        header('Content-Type: text/html; charset=UTF-8');
        $maxattachsize=1*1024*1024;
        $upext='jpg,jpeg,gif,png';//上传扩展名
        $index = 'picture';
        $err = "";
        if(!isset($_FILES[$inputname])){
            return array('err'=>'文件域的name错误或者没选择文件','status' => '0');
        }
        $status = 0;
        $upfile=$_FILES[$inputname];
        if(!empty($upfile['error'][$index]))
        {
            switch($upfile['error'][$index])
            {
                case '1':
                    $err = '文件大小超过了php.ini定义的upload_max_filesize值';
                    break;
                case '2':
                    $err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
                    break;
                case '3':
                    $err = '文件上传不完全';
                    break;
                case '4':
                    //    $err = '无文件上传';
                    break;
                case '6':
                    $err = '缺少临时文件夹';
                    break;
                case '7':
                    $err = '写文件失败';
                    break;
                case '8':
                    $err = '上传被其它扩展中断';
                    break;
                case '999':
                default:
                    $err = '无有效错误代码';
            }
        }
        else if(empty($upfile['tmp_name']) || $upfile['tmp_name'][$index] == '')
        {
            //   $err = '无文件上传';
        }
        else
        {
            $temppath=$upfile['tmp_name'][$index];
            $fileinfo=pathinfo($upfile['name'][$index]);
            $extension=strtolower($fileinfo['extension']);
            $filesize=$upfile['size'][$index];
            if($filesize > $maxattachsize){
                $err='文件大小超过'.$maxattachsize.'字节';
            }
            else
            {
                if(preg_match('/'.str_replace(',','|',$upext).'/i',$extension))
                {
                    self::createThumb($upfile['tmp_name'][$index], $upfile['tmp_name'][$index], $options['max_width'], $options['max_height'], 80, true);
                    $last_name = CDN::make_cdn_filename_by_time($upfile['name'][$index]);
                    if(CDN::move_file_to_cdn($upfile['tmp_name'][$index], $options["cdn_path"], $last_name, $options['cdn_server_info'], $sCDNFileFullUrl))
                    {
                        $msg=array('url'=>$sCDNFileFullUrl,'localname'=>$upfile['name'][$index],'id'=>'1');//id参数固定不变，仅供演示，实际项目中可以是数据库ID
                        $status = 1;
                    }
                    else
                    {
                        $err = "无法上传到CDN";
                    }
                }
                else
                {
                    $err='上传文件扩展名必需为：'.$upext;
                }
            }
            //there is a WARNING to EXCEPTION handler in system ....
            try {
                @unlink($temppath);
            }catch(Exception $ex){}
        }
        return array('err'=>$err,'status'=>$status,'last_name'=>$last_name);
    }
    /**
     * Create thumbnail
     *
     * @param string $sourceFile
     * @param string $targetFile
     * @param int $maxWidth
     * @param int $maxHeight
     * @param boolean $preserverAspectRatio
     * @return boolean
     * @static
     * @access public
     */
    static public function createThumb($sourceFile, $targetFile, $maxWidth, $maxHeight, $quality, $zoomType)
    {
        $bmpSupported = true;
        $sourceImageAttr = @getimagesize($sourceFile);
        if($sourceImageAttr === false) {
            return false;
        }
        $sourceImageWidth = isset($sourceImageAttr[0]) ? $sourceImageAttr[0] : 0;
        $sourceImageHeight = isset($sourceImageAttr[1]) ? $sourceImageAttr[1] : 0;
        $sourceImageMime = isset($sourceImageAttr["mime"]) ? $sourceImageAttr["mime"] : "";
        $sourceImageBits = isset($sourceImageAttr["bits"]) ? $sourceImageAttr["bits"] : 8;
        $sourceImageChannels = isset($sourceImageAttr["channels"]) ? $sourceImageAttr["channels"] : 3;

        if(!$sourceImageWidth || !$sourceImageHeight || !$sourceImageMime) {
            fb("createThumb: invalid params");
            return false;
        }

        $iFinalWidth = $maxWidth == 0 ? $sourceImageWidth : $maxWidth;
        $iFinalHeight = $maxHeight == 0 ? $sourceImageHeight : $maxHeight;

        if($sourceImageWidth <= $iFinalWidth && $sourceImageHeight <= $iFinalHeight) {
            if($sourceFile != $targetFile) {
                copy($sourceFile, $targetFile);
            }
            return true;
        }

        self::setMemoryForImage($sourceImageWidth, $sourceImageHeight, $sourceImageBits, $sourceImageChannels);

        switch($sourceImageAttr['mime'])
        {
            case 'image/gif':
            {
                if(@imagetypes() & IMG_GIF) {
                    $oImage = @imagecreatefromgif($sourceFile);
                } else {
                    $ermsg = 'GIF images are not supported';
                }
            }
                break;
            case 'image/jpeg':
            {
                if(@imagetypes() & IMG_JPG) {
                    $oImage = @imagecreatefromjpeg($sourceFile) ;
                } else {
                    $ermsg = 'JPEG images are not supported';
                }
            }
                break;
            case 'image/png':
            {
                if(@imagetypes() & IMG_PNG) {
                    $oImage = @imagecreatefrompng($sourceFile) ;
                } else {
                    $ermsg = 'PNG images are not supported';
                }
            }
                break;
            case 'image/wbmp':
            {
                if(@imagetypes() & IMG_WBMP) {
                    $oImage = @imagecreatefromwbmp($sourceFile);
                } else {
                    $ermsg = 'WBMP images are not supported';
                }
            }
                break;
            case 'image/bmp':
            {
                /*
                * This is sad that PHP doesn't support bitmaps.
                * Anyway, we will use our custom function at least to display thumbnails.
                * We'll not resize images this way(if $sourceFile === $targetFile),
                * because user defined imagecreatefrombmp and imagecreatebmp are horribly slow
                */
                if($bmpSupported &&(@imagetypes() & IMG_JPG) && $sourceFile != $targetFile) {
                    $oImage = self::imageCreateFromBmp($sourceFile);
                } else {
                    $ermsg = 'BMP/JPG images are not supported';
                }
            }
                break;
            default:
                $ermsg = $sourceImageAttr['mime'].' images are not supported';
                break;
        }

        if(isset($ermsg) || false === $oImage) {
            fb("createThumb: meet error: $ermsg");
            return false;
        }

        if($zoomType == self::ZOOM_TYPE_KEEP_RATIO) {
            $oSize = self::GetAspectRatioSize($iFinalWidth, $iFinalHeight, $sourceImageWidth, $sourceImageHeight );
            $oThumbImage = imagecreatetruecolor($oSize["Width"], $oSize["Height"]);
            $color_white = imagecolorallocate($oThumbImage, 255, 255, 255);
            imagefill($oThumbImage, 0, 0, $color_white);
            self::fastImageCopyResampled($oThumbImage, $oImage, 0, 0, 0, 0, $oSize["Width"], $oSize["Height"], $sourceImageWidth, $sourceImageHeight,(int)max(floor($quality/20), 1));
        }
        else if($zoomType == self::ZOOM_TYPE_STRETCH) {
            $oThumbImage = imagecreatetruecolor($iFinalWidth, $iFinalHeight);
            $color_white = imagecolorallocate($oThumbImage, 255, 255, 255);
            imagefill($oThumbImage, 0, 0, $color_white);
            self::fastImageCopyResampled($oThumbImage, $oImage, 0, 0, 0, 0, $iFinalWidth, $iFinalHeight, $sourceImageWidth, $sourceImageHeight,(int)max(floor($quality/20), 1));
        }
        else if($zoomType == self::ZOOM_TYPE_CLIP) {
            $oThumbImage = imagecreatetruecolor($iFinalWidth, $iFinalHeight);
            $color_white = imagecolorallocate($oThumbImage, 255, 255, 255);
            imagefill($oThumbImage, 0, 0, $color_white);

            $sourceX = 0;
            $sourceY = 0;
            $sourceWidth = $sourceImageWidth;
            $sourceHeight = $sourceImageHeight;
            if($sourceWidth > $sourceHeight) {
                $sourceX =($sourceWidth - $sourceHeight) / 2;
                $sourceWidth = $sourceHeight;
            }else{
                $sourceY =($sourceHeight - $sourceWidth) / 2;
                $sourceHeight = $sourceWidth;
            }
            self::fastImageCopyResampled($oThumbImage, $oImage, 0, 0, $sourceX, $sourceY, $iFinalWidth, $iFinalHeight, $sourceWidth, $sourceHeight,(int)max(floor($quality/20), 1));
        }
        else {
            throw new Exception("unknown zoom type '$zoomType'");
        }
        switch($sourceImageAttr['mime'])
        {
            case 'image/gif':
                imagegif($oThumbImage, $targetFile);
                break;
            case 'image/jpeg':
            case 'image/bmp':
                imagejpeg($oThumbImage, $targetFile, $quality);
                break;
            case 'image/png':
                imagepng($oThumbImage, $targetFile);
                break;
            case 'image/wbmp':
                imagewbmp($oThumbImage, $targetFile);
                break;
        }

        imageDestroy($oImage);
        imageDestroy($oThumbImage);

        return true;
    }



    /**
     * Return aspect ratio size, returns associative array:
     * <pre>
     * Array
     *(
     *      [Width] => 80
     *      [Heigth] => 120
     * )
     * </pre>
     *
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $actualWidth
     * @param int $actualHeight
     * @return array
     * @static
     * @access public
     */
    static function getAspectRatioSize($maxWidth, $maxHeight, $actualWidth, $actualHeight)
    {
        $oSize = array("Width"=>$maxWidth, "Height"=>$maxHeight);

        // Calculates the X and Y resize factors
        $iFactorX =(float)$maxWidth /(float)$actualWidth;
        $iFactorY =(float)$maxHeight /(float)$actualHeight;

        // If some dimension have to be scaled
        if($iFactorX != 1 || $iFactorY != 1)
        {
            // Uses the lower Factor to scale the oposite size
            if($iFactorX < $iFactorY) {
                $oSize["Height"] =(int)round($actualHeight * $iFactorX);
            }
            else if($iFactorX > $iFactorY) {
                $oSize["Width"] =(int)round($actualWidth * $iFactorY);
            }
        }

        if($oSize["Height"] <= 0) {
            $oSize["Height"] = 1;
        }
        if($oSize["Width"] <= 0) {
            $oSize["Width"] = 1;
        }

        // Returns the Size
        return $oSize;
    }



    /**
     * Source: http://pl.php.net/imagecreate
     *(optimized for speed and memory usage, but yet not very efficient)
     *
     * @static
     * @access public
     * @param string $filename
     * @return resource
     */
    function imageCreateFromBmp($filename)
    {
        //20 seconds seems to be a reasonable value to not kill a server and process images up to 1680x1050
        @set_time_limit(20);

        if(false ===($f1 = fopen($filename, "rb"))) {
            return false;
        }

        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
        if($FILE['file_type'] != 19778) {
            return false;
        }

        $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
            '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
            '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));

        $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);

        if($BMP['size_bitmap'] == 0) {
            $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
        }

        $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
        $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
        $BMP['decal'] =($BMP['width']*$BMP['bytes_per_pixel']/4);
        $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
        $BMP['decal'] = 4-(4*$BMP['decal']);

        if($BMP['decal'] == 4) {
            $BMP['decal'] = 0;
        }

        $PALETTE = array();
        if($BMP['colors'] < 16777216) {
            $PALETTE = unpack('V'.$BMP['colors'], fread($f1, $BMP['colors']*4));
        }

        //2048x1536px@24bit don't even try to process larger files as it will probably fail
        if($BMP['size_bitmap'] > 3 * 2048 * 1536) {
            return false;
        }

        $IMG = fread($f1, $BMP['size_bitmap']);
        fclose($f1);
        $VIDE = chr(0);

        $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
        $P = 0;
        $Y = $BMP['height']-1;

        $line_length = $BMP['bytes_per_pixel']*$BMP['width'];

        if($BMP['bits_per_pixel'] == 24) {
            while($Y >= 0)
            {
                $X=0;
                $temp = unpack( "C*", substr($IMG, $P, $line_length));

                while($X < $BMP['width'])
                {
                    $offset = $X*3;
                    imagesetpixel($res, $X++, $Y,($temp[$offset+3] << 16) +($temp[$offset+2] << 8) + $temp[$offset+1]);
                }
                $Y--;
                $P += $line_length + $BMP['decal'];
            }
        }
        elseif($BMP['bits_per_pixel'] == 8)
        {
            while($Y >= 0)
            {
                $X=0;

                $temp = unpack( "C*", substr($IMG, $P, $line_length));

                while($X < $BMP['width'])
                {
                    imagesetpixel($res, $X++, $Y, $PALETTE[$temp[$X] +1]);
                }
                $Y--;
                $P += $line_length + $BMP['decal'];
            }
        }
        elseif($BMP['bits_per_pixel'] == 4)
        {
            while($Y >= 0)
            {
                $X=0;
                $i = 1;
                $low = true;

                $temp = unpack( "C*", substr($IMG, $P, $line_length));

                while($X < $BMP['width'])
                {
                    if($low) {
                        $index = $temp[$i] >> 4;
                    }
                    else {
                        $index = $temp[$i++] & 0x0F;
                    }
                    $low = !$low;

                    imagesetpixel($res, $X++, $Y, $PALETTE[$index +1]);
                }
                $Y--;
                $P += $line_length + $BMP['decal'];
            }
        }
        elseif($BMP['bits_per_pixel'] == 1)
        {
            $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
            if(($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
            elseif(($P*8)%8 == 1) $COLOR[1] =($COLOR[1] & 0x40)>>6;
            elseif(($P*8)%8 == 2) $COLOR[1] =($COLOR[1] & 0x20)>>5;
            elseif(($P*8)%8 == 3) $COLOR[1] =($COLOR[1] & 0x10)>>4;
            elseif(($P*8)%8 == 4) $COLOR[1] =($COLOR[1] & 0x8)>>3;
            elseif(($P*8)%8 == 5) $COLOR[1] =($COLOR[1] & 0x4)>>2;
            elseif(($P*8)%8 == 6) $COLOR[1] =($COLOR[1] & 0x2)>>1;
            elseif(($P*8)%8 == 7) $COLOR[1] =($COLOR[1] & 0x1);
            $COLOR[1] = $PALETTE[$COLOR[1]+1];
        }
        else {
            return false;
        }

        return $res;
    }


    /**
     * @link http://pl.php.net/manual/en/function.imagecopyresampled.php
     * replacement to imagecopyresampled that will deliver results that are almost identical except MUCH faster(very typically 30 times faster)
     *
     * @static
     * @access public
     * @param string $dst_image
     * @param string $src_image
     * @param int $dst_x
     * @param int $dst_y
     * @param int $src_x
     * @param int $src_y
     * @param int $dst_w
     * @param int $dst_h
     * @param int $src_w
     * @param int $src_h
     * @param int $quality
     * @return boolean
     */
    public static function fastImageCopyResampled(&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3)
    {
        if(empty($src_image) || empty($dst_image)) {
            return false;
        }

        if($quality <= 1) {
            $temp = imagecreatetruecolor($dst_w + 1, $dst_h + 1);
            imagecopyresized($temp, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w + 1, $dst_h + 1, $src_w, $src_h);
            imagecopyresized($dst_image, $temp, 0, 0, 0, 0, $dst_w, $dst_h, $dst_w, $dst_h);
            imagedestroy($temp);

        } elseif($quality < 5 &&(($dst_w * $quality) < $src_w ||($dst_h * $quality) < $src_h)) {
            $tmp_w = $dst_w * $quality;
            $tmp_h = $dst_h * $quality;
            $temp = imagecreatetruecolor($tmp_w + 1, $tmp_h + 1);
            imagecopyresized($temp, $src_image, 0, 0, $src_x, $src_y, $tmp_w + 1, $tmp_h + 1, $src_w, $src_h);
            imagecopyresampled($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $tmp_w, $tmp_h);
            imagedestroy($temp);

        } else {
            imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
        }

        return true;
    }

    /**
     * @link http://pl.php.net/manual/pl/function.imagecreatefromjpeg.php
     * function posted by e dot a dot schultz at gmail dot com
     *
     * @static
     * @access public
     * @param string $filename
     * @return boolean
     */
    public static function setMemoryForImage($imageWidth, $imageHeight, $imageBits, $imageChannels)
    {
        $MB = 1048576;  // number of bytes in 1M
        $K64 = 65536;    // number of bytes in 64K
        $TWEAKFACTOR = 2.4;  // Or whatever works for you
        $memoryNeeded = round(( $imageWidth * $imageHeight
                    * $imageBits
                    * $imageChannels / 8
                    + $K64
                ) * $TWEAKFACTOR
            ) + 3*$MB;

        //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
        //Default memory limit is 8MB so well stick with that.
        //To find out what yours is, view your php.ini file.
        $memoryLimit = self::returnBytes(@ini_get('memory_limit'))/$MB;
        if(!$memoryLimit) {
            $memoryLimit = 8;
        }

        $memoryLimitMB = $memoryLimit * $MB;
        if(function_exists('memory_get_usage')) {
            if(memory_get_usage() + $memoryNeeded > $memoryLimitMB) {
                $newLimit = $memoryLimit + ceil(( memory_get_usage()
                            + $memoryNeeded
                            - $memoryLimitMB
                        ) / $MB
                    );
                if(@ini_set( 'memory_limit', $newLimit . 'M' ) === false) {
                    return false;
                }
            }
        } else {
            if($memoryNeeded + 3*$MB > $memoryLimitMB) {
                $newLimit = $memoryLimit + ceil(( 3*$MB
                            + $memoryNeeded
                            - $memoryLimitMB
                        ) / $MB
                    );
                if(false === @ini_set( 'memory_limit', $newLimit . 'M' )) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * convert shorthand php.ini notation into bytes, much like how the PHP source does it
     * @link http://pl.php.net/manual/en/function.ini-get.php
     *
     * @static
     * @access public
     * @param string $val
     * @return int
     */
    public static function returnBytes($val) {
        $val = trim($val);
        if(!$val) {
            return 0;
        }
        $last = strtolower($val[strlen($val)-1]);
        $val = intval(substr($val, 0, -1));
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

}
?>
