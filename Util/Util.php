<?php
/**
 * @file \Util\Util.php
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

/**
 * class \Util\Util.
 */
class Util{

    /**
     * 提现A方案标识.
     */
    const GROUP_A = 'GROUP_A';
    /**
     * 提现B方案标识.
     */
    const GROUP_B = 'GROUP_B';

    /**
     * IsAjax.
     *
     * @return boolean
     */
    public static function isAjax()
    {
        $ajaxCommand = \JMSystem::GetRequest(JM_AJAX_REQUEST_VAR_NAME);
        if ($ajaxCommand || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
            return true;
        }
        return false;
    }

    /**
     * 发送请求.
     *
     * @param string  $url     请求地址.
     * @param string  $params  请求参数.
     * @param string  $type    请求类型.
     * @param integer $count   最多请求次数.
     * @param array   $headers 请求头信息.
     *
     * @return string
     */
    public static function curlRequest($url, $params, $type = 'get', $count = 1, $headers = array())
    {
        $count = $count < 1 ? 1 : $count;
        $count = $count > 3 ? 3 : $count;
        for ($i = 0; $i < $count; $i++) {
            $ch = curl_init();
            if ($type == 'get' && !empty($params)) {
                if (stripos($url, '?') !== false) {
                    $url .= "&{$params}";
                } else {
                    $url .= "?{$params}";
                }
            } elseif ($type == "post" && !empty ($params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
            if ($headers) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
            // curl_setopt($ch, CURLOPT_SSLVERSION, 3);
            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!empty($result) && $status == '200') {
                break;
            }
        }
        return $result;
    }

    /**
     *下载xls文件.
     *
     * @param array  $data      下载的数据内容.
     * @param array  $tableHead 表头.
     * @param string $fileName  文件名.
     * @param string $desc      文件内容描述.
     */
    public static function downXls(array $data, $tableHead, $fileName='filename', $desc = "") {
        ob_clean();
        header("content-Type:text/html; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="'.$fileName.'.xls"');
        $xls = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">'."\r\n".'<meta http-equiv=content-type content=\"text/html; charset=UTF-8\">'."\r\n".'<body>'."\r\n".'<table border="1">'."\r\n";
        if (!empty($desc)) {
            $count = count($tableHead);
            $desc = mb_convert_encoding($desc, 'UTF-8','UTF-8');
            $xls .= "<tr><td colspan='{$count}'>{$desc}</td></tr>";
        }
        $head = '';
        foreach ($tableHead as $v) {
            $head .= "<td>$v</td>";
        }
        $xls .= "<tr>{$head}</tr>\r\n";
        $i = 0;
        foreach ($data as $values) {
            $line = '<tr>';
            foreach ($tableHead as $k => $name) {
                $line .= isset($values[$k]) ? '<td x:str>'.$values[$k].'</td>' : '<td></td>';
            }
            $line .= "</tr>\r\n";
            $xls .= $line;
            if ($i % 200 == 0) {
                echo mb_convert_encoding($xls, 'UTF-8','UTF-8');
                $xls = '';
                ob_flush();
            }
        }
        $xls .= '</table>'."\r\n".'</body>'."\r\n".'</html>';
        die(mb_convert_encoding($xls, 'UTF-8','UTF-8'));
    }

    /**
     * 生成随机字符串.
     *
     * @param integer $length 长度.
     *
     * @return string
     */
    public static function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * Get $_SERVER header value.
     *
     * @param string $name    Name.
     * @param string $default 默认值.
     *
     * @return string
     */
    public static function getServerHeaderByName($name, $default = '')
    {
        if (!is_scalar($name) || !$name) {
            return $default;
        }
        $headerName = "HTTP_".strtoupper($name);
        if (!empty($_SERVER[$headerName])) {
            return $_SERVER[$headerName];
        }
        return $default;
    }

    /**
     * excel里面复制出来的数据处理成数组,主要用于dealman里面批量高级功能里面.
     *
     * @param string $string 提交过来的原始数据.
     *
     * @return array.
     */

    /**
     *格式化那种一次批量数据的文本框数据.
     *
     * @param string  $string 提交过来的数据.
     *
     * @param boolean $row    是否在拆分每行的数据.
     *
     * @return array.
     */
    public static function textareaDataToArray($string, $row = true)
    {
        $data = array();
        if (trim($string) === '') {
            return $data;
        } else {
            $string = explode("\n", $string);
        }
        foreach ($string as $k => $v) {
            if (trim($v) === '') {
                // 空白行过滤掉.
                continue;
            }
            if (!$row) {
                $data[$k + 1] = trim($v);
                continue;
            }
            $v = str_replace(array("\r", "\t", '，'), array('', ',', ','), rtrim($v));
            $v = explode(",", $v);
            $data[$k + 1] = array_map('trim', $v);
        }
        return $data; // 去除空白行
    }

    /**
     * 手机号加星.
     *
     * @param string $mobile 手机号.
     *
     * @return string
     */
    public static function addAsteriskForMobile($mobile = '')
    {
        if (is_scalar($mobile) && $mobile) {
            return substr($mobile, 0, 3) . "****" . substr($mobile, -4, 4);
        }
        return '';
    }

    /**
     * 姓名加星.
     *
     * @param string $name 姓名加星.
     *
     * @return string
     */
    public static function addAsteriskForName($name)
    {
        if (empty($name)) {
            return '匿名';
        }
        $count = mb_strlen($name, 'utf8');
        if ($count == 2) {
            return '*' . mb_substr($name, $count - 1, $count, 'utf8');
        } else {
            return mb_substr($name, 0, 1, 'utf8').'***' . mb_substr($name, $count - 1, $count, 'utf8');
        }
    }

    /**
     *生成一个文件名字.
     *
     * @param string $mime   文件MIME.
     * @param string $prefix 文件前缀.
     * @param string $suffix 后缀
     *
     * @return string.
     */
    public static function getFileSavePath($mime, $prefix = '', $suffix = '')
    {
        $mime = explode('/', $mime);
        $exp = end($mime);
        return $prefix.substr((string)time(), -4).str_pad(rand(0, 9999), 4, '0').$suffix.'.'.$exp;
    }

    /**
     * 用户真实姓名加星.
     *
     * @param string $name 姓名.
     *
     * @return string
     */
    public static function addAsteriskForRealName($name)
    {
        if (empty($name)) {
            return $name;
        }
        return mb_substr($name, 0, 1, 'utf8') ."*";
    }

    /**
     * 银行卡号加星.
     *
     * @param string $bankNo 银行卡号.
     *
     * @return string
     */
    public static function addAsteriskForBankNo($bankNo)
    {
        if (empty($bankNo)) {
            return $bankNo;
        }
        return substr_replace($bankNo, str_repeat("*", strlen($bankNo) - 4 >= 0 ? strlen($bankNo) - 4 : 0), 0, -4);
    }

    /**
     * 支付宝账号/微信账号(openid)加星.
     *
     * @param string $accountId 支付宝账号/微信账号(openid).
     *
     * @return string
     */
    public static function addAsteriskForAccountId($accountId)
    {
        if (empty($accountId)) {
            return '匿名账号';
        }
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $accountId)) {
            $count = mb_strlen($accountId, 'utf8');
            if ($count == 2) {
                return '*' . mb_substr($accountId, $count - 1, $count, 'utf8');
            } else {
                return mb_substr($accountId, 0, 1, 'utf8').'***' . mb_substr($accountId, $count - 1, $count, 'utf8');
            }
        } else {
            return substr_replace($accountId, str_repeat("*", strlen($accountId) - 4 >= 0 ? strlen($accountId) - 4 : 0), 0, -4);
        }
    }

    /**
     * 获取头部信息.
     *
     * @param string $name 头部名称.
     * @param string $priv 头部前缀(主要用于自定义HTTP).
     *
     * @return mixed
     */
    public static function getHeaderByName($name, $priv = 'HTTP_')
    {
        $name = $priv . strtoupper(str_replace('-', '_', $name));
        return isset($_SERVER[$name]) ? $_SERVER[$name] : '';
    }

    /**
     * 获取渠道包名
     * 先取header，取header时候需要大写并且加前缀，取不到再尝试从cookie取
     * utm_source: 首次安装渠道名,   source: 当前安装渠道名
     *
     * @param string $is_now 是否获取当前渠道包名，true获取当前，false获取第一次安装
     * @return string   不为空返回对应值，为空返回空字符串
     */
    public static function getQuDaoBaoMing($is_now = false)
    {
        if ($is_now) {
            $key = ['header' => 'HTTP_SOURCE', 'cookie' => 'channel'];
        } else {
            $key = ['header' => 'HTTP_UTM_SOURCE', 'cookie' => 'channel'];   // 目前utm_source还没有加进cookie，后续要把channel修改成utm_source
        }
        if (isset($_SERVER[$key['header']])) {
            return $_SERVER[$key['header']];
        } elseif (isset($_COOKIE[$key['cookie']])) {
            return $_COOKIE[$key['cookie']];
        }
        return '';
    }

    /**
     * 解析Node值.
     *
     * @param string $qrCode 二维码值.
     *
     * @return mixed
     */
    public static function getShopIdFromQrCode($qrCode)
    {
        if (!$qrCode) {
            return false;
        }
        $parseUrl = parse_url($qrCode);
        if (empty($parseUrl['query'])) {
            return false;
        }
        parse_str($parseUrl['query'], $result);
        if (!$result) {
            return false;
        }
        return !empty($result['shop_id']) ? $result['shop_id'] : false;
    }

    /**
     * 订单时间美化.
     *
     * @param string $time 时间戳.
     *
     * @return string
     */
    public static function makeOrderCreateTimeFriendly($time)
    {
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }
        $startPoint = strtotime(date("Y-m-d"));
        if ($time > $startPoint) {
            return date("H:i", $time);
        }
        if (date("Y") == date("Y", $time)) {
            return date("m月d日 H:i", $time);
        }
        return date("y年m月d日 H:i", $time);
    }

    /**
     * 为url添加参数.
     *
     * @param string $url    Url.
     * @param array  $params 参数数组.
     *
     * @return string
     */
    public static function addParamForUrl($url, array $params) {
        if (!is_scalar($url) || empty($url)) {
            return $url;
        }
        $_params = http_build_query($params);
        if (strpos($url, "?") !== false) {
            $url .= "&".$_params;
        } else {
            $url .= "?".$_params;
        }
        return $url;
    }
    
    public static function uploadImage($option = array())
    {
        $rootpath = 'mdimage';
        $maxsize = 1024000;
        if (isset($option['path'])) {
            $rootpath = $option['path'];
        }
        if (isset($option['max_size'])) {
            $maxsize = $option['max_size'];
        }
        if (empty($_FILES['file']['tmp_name'])) {
            return array('msg' => '请选择文件', 'code' => 1000);
        }
        if ($_FILES['file']['size'] >= $maxsize) {
            return array('msg' => '文件过大', 'code' => 1000);
        }
        if (strpos($_FILES['file']['type'], 'image/') === false) {
            return array('msg' => '文件格式不正确', 'code' => 1000);
        }
        $imageInfo = getimagesize($_FILES['file']['tmp_name']);
        if (empty($imageInfo)) {
            return array('msg' => '文件格式不正确', 'code' => 1000);
        }
        if (isset($option['max_width']) && $imageInfo['0'] > $option['max_width']) {
            $h = $option['max_width'] * $imageInfo['1'] / $imageInfo['0'];
            $s = \Util\ImageLib::thumbnailImage($_FILES['file']['tmp_name'], $_FILES['file']['tmp_name'], $option['max_width'], intval($h));
        }
        $path = \Config\Common::$activityPicInfo['path'].'/'.$rootpath.'/'. date("Ymd/H");
        $fileNmae = \Util\Util::getFileSavePath($_FILES['file']['type']);
        $upResult = \JMFile\JMFile::instance()->upload($_FILES['file']['tmp_name'], $path, $fileNmae);;
        @unlink($_FILES['file']['tmp_name']);
        $upResult = json_decode($upResult, true);
        if (!(is_array($upResult) && $upResult['code'] == 1000)) {
            return array('msg' => "图片上传失败:".$upResult['info'], 'code' => 1000);
        }
        return array('code' => 200, 'path' => $upResult['paths']['raw'], 'url' => end(\Config\Common::$activityPicInfo['domain']).$upResult['paths']['raw']);
    }

    /**
     * 为h5链接加上参数
     *
     * @param string $url   链接.
     * @param array $params 参数数组.
     *
     * @return string
     */
    public static function addParamForH5Url($url, array $params)
    {
        if (!is_scalar($url) || empty($url)) {
            return $url;
        }
        $_params = http_build_query($params);
        if (strpos($url, "meidianwc://page/webview") !== false) {
            $parsedUrl = parse_url($url);
            if (!empty($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $uris);
                if (!empty($uris['web_url'])) {
                    $uris = array_map('urldecode', $uris);
                    $webUrl = \Util\Util::addParamForUrl($uris['web_url'], $params);
                    $uris['web_url'] = $webUrl;
                    $uriStr = http_build_query($uris);
                    return "meidianwc://page/webview?".$uriStr;
                }
            }
            return $url;
        } elseif (strpos($url, 'http') === 0) {
            if (strpos($url, "?") !== false) {
                $url .= "&" . $_params;
            } else {
                $url .= "?" . $_params;
            }
            return $url;
        } else {
            return $url;
        }
    }

    /**
     * 司机名字Mask.
     *
     * @param string $name    司机姓名.
     *
     * @param string $tailStr 后缀名.
     *
     * @return string
     */
    public static function maskDriverName($name, $tailStr = "师傅")
    {
        if (!is_scalar($name)) {
            return '';
        }
        // 不考虑复姓
        return mb_substr($name, 0, 1, "utf-8") . $tailStr;
    }

    /**
     * 生成指定长度的随机字符串.
     *
     * @param integer $length 长度.
     *
     * @return string
     */
    public static function randomStr($length)
    {
        $str = '';
        $pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwzyz';
        for ($i = 0; $i < $length; $i++) {
            $str .= $pattern{mt_rand(0, 20000) % 52};
        }
        return $str;

    }

    /**
     * 根据手机号生成用户昵称.
     *
     * @param string $mobile 手机号.
     *
     * @return string
     */
    public static function getNickNameFromMobile($mobile)
    {
        $r = \Util\Validator::isMobile($mobile);
        if (!$r) {
            return false;
        }
        $pre = empty(\Config\Common::$nickNamePreFromMobile) ? '' : \Config\Common::$nickNamePreFromMobile;
        if (empty($pre) || is_numeric(substr($pre,0,1))) {
            // $pre为空或者开头为数字,需要随机放一个字母到开头.
            $pre = self::randomStr(1) . $pre;
        }
        /**
        $preNum = substr($mobile, 0, 1);
        $sufNum = substr($mobile, -1, 1);
        $replaceStr = self::randomStr(9);

        $nick = $pre . $preNum . $replaceStr . $sufNum;
         *
         */
        $nick = $pre . substr(hexdec(uniqid()), -10);
        $nickname = $nick;
        $count = 0;
        while (\Module\Account::instance()->isExistsUsername($nickname) !== true) {
            $nickname = $nick . self::randomStr(3);
            if ($count++ > 10) {
                return false;
            }
        }
        return $nickname;
    }

    /**
     * 当前URL地址
     */
    public static function getCurrentSelfUrl()
    {
        return self::getProtocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * 判断是当前请求协议是否https.
     *
     * @return boolean 返回true表示是, false表示不是.
     */
    public static function isHttps()
    {
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
            return true;
        } elseif (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            return true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return true;
        } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
            return true;
        }
        return false;
    }

    /**
     * 获取请求的协议.
     *
     * @return string 返回https://获取http://.
     */
    public static function getProtocol()
    {
        return self::isHttps() ? 'https://' : 'http://';
    }

    /*
     * 获取UA来源.
     */
    public static function getClientUa()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match("/alipay|aliapp/i", $ua)) {
            return 'alipay';
        } elseif (preg_match("/MicroMessenger/i", $ua)) {
            return 'weixin';
        }
        return 'unkown';
    }

    /**
     * 获取当前页面所在平台
     * @return string
     */
    public static function getPlatform()
    {
        $platform = 'unkown';

        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/android/i', $ua)) {
            $platform = 'android';
        }
        if (preg_match('/iphone/i', $ua)) {
            $platform = 'iphone';
        }
        return $platform;
    }

    /**
     * 获取当前页面所在操作系统,返回iOS或Android或空.
     *
     * @return string
     */
    public static function getOSByUA()
    {
        $platform = '';
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/android/i', $ua)) {
            $platform = 'Android';
        }
        if (preg_match('/iphone/i', $ua)) {
            $platform = 'iOS';
        }
        return $platform;
    }

    /**
     * 检验第三方登录站点是否启用.
     *
     * @param string $siteName 第三方站点.
     *
     * @return bool.
     */
    public static function checkExtConnect($siteName)
    {
        $config = \Config\Common::$extSiteName;
        if (!isset($config[$siteName]) || $config[$siteName]['status'] != 'enable') {
            return false;
        }
        return true;
    }

    /**
     * 发送HttpPost 请求.
     *
     * @param string  $url         URL.
     * @param array   $params      参数K-V数组.
     * @param integer $connTimeOut CURLOPT_CONNECTTIMEOUT.
     * @param integer $timeOut     CURLOPT_TIMEOUT.
     *
     * @return string
     */
    public static function httpPost($url, $params, $connTimeOut = 2, $timeOut = 5)
    {
        $ch = curl_init();
        /*JAVA 需要这样才能收到 Start*/
        $url .= '?'. http_build_query($params);
        /*JAVA 需要这样才能收到  End */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        // 设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connTimeOut);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HEADER, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Curl请求代理包装.
     *
     * @param integer &$ch Curl Handler.
     *
     * @return void
     * @throws \Exception
     */
    public static function curlProxyWrapper(&$ch)
    {
        $siteInfo = \JMRegistry::get("SiteInfo");
        if (isset($siteInfo['curlProxyCfg'])
            && $siteInfo['enableCurlProxy'] === true
            && is_resource($ch)
            && is_array($siteInfo['curlProxyCfg'])
        ) {
            if (is_numeric($siteInfo['curlProxyCfg']['proxyType'])) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, $siteInfo['curlProxyCfg']['proxyType']);
            }
            if ($siteInfo['curlProxyCfg']['basicAuthStr']) {
                curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $siteInfo['curlProxyCfg']['basicAuthStr']);
            }
            if ($siteInfo['curlProxyCfg']['proxyUri']) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_PROXY, $siteInfo['curlProxyCfg']['proxyUri']);
            }
        }
    }

    /**
     * 获取默认头像地址.
     *
     * @param integer              $uid    用户id.
     * @param string|object|array  $avatar 用户头像.
     *
     * @return object
     * @throws \Exception
     */
    public static function getDefaultAvatar($uid, $avatar)
    {
        if (empty($uid)) {
            $uid = rand(100, 999);
        } else {
            $uid = trim($uid);
            if (!ctype_digit((string)$uid)) {
                $uid = rand(100, 999);
            }
        }
        $uid = substr($uid, -2, 1);
        if (!empty($avatar) && (is_string($avatar) || is_array($avatar) || is_object($avatar))) {
            if (is_string($avatar)) {
                return [
                    '640' => $avatar,
                    '720' => $avatar,
                    '1080' => $avatar,
                    '1280' => $avatar
                ];
            }
            if (is_object($avatar)) {
                $avatar = (array)$avatar;
            }
            $avatar640 = '';
            $avatar720 = '';
            $avatar1080 = '';
            $avatar1280 = '';
            $isRes = true;
            foreach ($avatar as $key => $value) {
                if (!empty($value)) {
                    if ($key == 640) {
                        $avatar640 = $value;
                    } elseif ($key == 720) {
                        $avatar720 = $value;
                    }  elseif ($key == 1080) {
                        $avatar1080 = $value;
                    }  elseif ($key == 1280) {
                        $avatar1280 = $value;
                    } else {
                        $isRes = false;
                        break;
                    }
                } else {
                    $isRes = false;
                    break;
                }
            }
            if ($isRes) {
                return [
                    '640' => $avatar640,
                    '720' => $avatar720,
                    '1080' => $avatar1080,
                    '1280' => $avatar1280
                ];
            } else {
                if (isset(\Config\Common::$defaultAvatarUrl) && is_array(\Config\Common::$defaultAvatarUrl)) {
                    $uid = $uid % 4;
                    $defaultAvatarUrl = \Config\Common::$defaultAvatarUrl[$uid];
                    return [
                        '640' => $defaultAvatarUrl,
                        '720' => $defaultAvatarUrl,
                        '1080' => $defaultAvatarUrl,
                        '1280' => $defaultAvatarUrl
                    ];
                }
            }
        } else {
            if (isset(\Config\Common::$defaultAvatarUrl) && is_array(\Config\Common::$defaultAvatarUrl)) {
                $uid = $uid % 4;
                $defaultAvatarUrl = \Config\Common::$defaultAvatarUrl[$uid];
                return [
                    '640' => $defaultAvatarUrl,
                    '720' => $defaultAvatarUrl,
                    '1080' => $defaultAvatarUrl,
                    '1280' => $defaultAvatarUrl
                ];
            }
        }
        throw new \RpcBusinessException('获取头像失败！！！');
    }

    /**
     * 通过应用版本号或设备控制是否显示某信息.
     *
     * @param string $platform 设备.
     * @param string $clientV  应用版本号.
     * @param array  $cfgInfo  某信息配置信息.
     * <pre>
     * $cfgInfo = array(
     *    // 设备(0不显示,1显示;设备名称请使用小写).
     *    'platform' => array(
     *        'ios'     => 1,
     *        'android' => 1,
     *    ),
     *    // 应用版本号(符号‘=’统一用‘equal_to’替换;像类似此类符号‘<equal_to’不要中间留空格,否则无法识别;版本号不支持带字母).
     *    'client_v' => array(
     *        'ios'     => array(
     *            // 第一个判断条件.
     *            array(
     *                '>'  => '0.9',
     *                '<equal_to' => '1.000'
     *            ),
     *            // 第二个判断条件.(相互独立，不与第一个判断条件冲突)
     *            array(
     *                '>'  => '0.7',
     *                '<equal_to' => '0.8'
     *            ),
     *        ),
     *        'android' => array(
     *            // 第一个判断条件.
     *            array(
     *                'equal_to' => '1.000'
     *            ),
     *        ),
     *    )
     * )
     * </pre>
     *
     * @return integer 1显示,0不显示.
     */
    public static function getIsShowInfoByPlatformAndClientV($platform, $clientV, array $cfgInfo = array())
    {
        if (!empty($platform) && !empty($cfgInfo)) {
            $platform = strtolower($platform);
            if (!empty($cfgInfo['platform']) && isset($cfgInfo['platform'][$platform]) && $cfgInfo['platform'][$platform] == 0) {
                return 0;
            } else {
                if (!empty($clientV)) {
                    if (!empty($cfgInfo['client_v']) && !empty($cfgInfo['client_v'][$platform])) {
                        $clientVCompareCardinalNumber = empty(\Config\Common::$clientVCompareCardinalNumber) ? '1000' : \Config\Common::$clientVCompareCardinalNumber;
                        $clientVTemp = $clientV * $clientVCompareCardinalNumber;
                        foreach ($cfgInfo['client_v'][$platform] as $items) {
                            // 用于每一个item的条件是否全部满足,有一个不满足此item的条件就不成立.
                            $isMeetConditionsFromItem = true;
                            foreach ($items as $operator => $compareClientV) {
                                // 转义等号.
                                $operator = str_ireplace('equal_to', '=', $operator);
                                $compareClientVTemp = $compareClientV * $clientVCompareCardinalNumber;
                                if ($operator == '>') {
                                    if (!($clientVTemp > $compareClientVTemp)) {
                                        $isMeetConditionsFromItem = false;
                                        break;
                                    }
                                } elseif($operator == '<') {
                                    if (!($clientVTemp < $compareClientVTemp)) {
                                        $isMeetConditionsFromItem = false;
                                        break;
                                    }
                                } elseif($operator == '=') {
                                    if (!($clientVTemp == $compareClientVTemp)) {
                                        $isMeetConditionsFromItem = false;
                                        break;
                                    }
                                } elseif($operator == '>=') {
                                    if (!($clientVTemp >= $compareClientVTemp)) {
                                        $isMeetConditionsFromItem = false;
                                        break;
                                    }
                                } elseif($operator == '<=') {
                                    if (!($clientVTemp <= $compareClientVTemp)) {
                                        $isMeetConditionsFromItem = false;
                                        break;
                                    }
                                } elseif($operator == '!=') {
                                    if (!($clientVTemp != $compareClientVTemp)) {
                                        $isMeetConditionsFromItem = false;
                                        break;
                                    }
                                } else {
                                    $isMeetConditionsFromItem = false;
                                    break;
                                }
                            }
                            if ($isMeetConditionsFromItem) {
                                return 0;
                            }
                        }
                    }
                }
            }
        }
        return 1;
    }

    /**
     * 转换指定图像地址格式.
     *
     * @param string $avatarUrl 图像地址.
     *
     * @return array
     */
    public static function transferFormatAvatar($avatarUrl)
    {
        return is_string($avatarUrl) ? [
            '640' => $avatarUrl,
            '720' => $avatarUrl,
            '1080' => $avatarUrl,
            '1280' => $avatarUrl
        ] : new \stdClass();
    }

    /**
     * 设置头部信息.
     *
     * @param string $name 头部名称.
     * @param string $val  头部值.
     * @param string $priv 头部前缀(主要用于自定义HTTP).
     *
     * @return mixed
     */
    public static function setHeaderByName($name, $val, $priv = 'HTTP_')
    {
        $name = $priv . strtoupper($name);
        return $_SERVER[$name] = $val;
    }

    /**
     * 检测url链接
     *
     * @param string $url 链接
     *
     * @return boolean
     */
    public static function checkUrl($url)
    {
        $preg = '/^(http[s]?:)?\/\/([a-zA-Z0-9_]+)\.(\w+)(.*)$/isU';
        if (!preg_match($preg, $url)){
            return false;
        }
        return true;
    }
    
    /**
     * 自定义多进制，根据设置的多进制字符串获取多进制对应的十进制
     * @param string $str 自定义的多进制字符串
     * @param integer $val 对应的多进制数字
     * @return integer
     */
    public static function strToNum($str, $val)
    {
        $x = strlen($str);
        $arr = str_split($str);
        // 进行key value 互换
        $arr = array_flip($arr);
        if ($val === ""){
            return $arr[0];
        }
        $vArr = str_split($val);
        $vArr = array_reverse($vArr);
        $num = 0;
        foreach ($vArr as $key => $v){
            if (isset($arr[$v]) && is_numeric($arr[$v]) && $arr[$v] > 0){
                $dNum = $arr[$v];
                $pNum = pow($x,$key);
                $num += $dNum * $pNum;
            }
            
        }
        return $num;
    }

    /**
     * 获取AB方案.
     *
     * @param integer $uid  用户ID.
     * @param array   $info 设备array('platform' => 'ios', 'client_v' => '1.3').
     *
     * @return string
     */
    public static function getABByUid($uid, array $info = array())
    {
        $groupSign = '';
        $getABUid = substr($uid, -2, 1);
        if (!empty(\Config\Common::$getABCfg)) {
            $cfg = \Config\Common::$getABCfg;
            if (!empty($cfg['enable'])) {
                // 限制只能使用某个版本.
                if (!empty($cfg['only_use_client_v'])
                    && !empty($info['platform'])
                    && !empty($info['client_v'])) {
                    $platform = strtolower($info['platform']);
                    if (!empty($cfg['only_use_client_v'][$platform])) {
                        $clientVCompareCardinalNumber = empty(\Config\Common::$clientVCompareCardinalNumber) ? '1000' : \Config\Common::$clientVCompareCardinalNumber;
                        if (($info['client_v'] * $clientVCompareCardinalNumber) != ($cfg['only_use_client_v'][$platform] * $clientVCompareCardinalNumber)) {
                            return $groupSign;
                        }
                    }
                }
                // 限制不能低于某个版本.
                if (!empty($cfg['min_enable_client_v'])
                    && !empty($info['platform'])
                    && !empty($info['client_v'])) {
                    $platform = strtolower($info['platform']);
                    if (!empty($cfg['min_enable_client_v'][$platform])) {
                        $clientVCompareCardinalNumber = empty(\Config\Common::$clientVCompareCardinalNumber) ? '1000' : \Config\Common::$clientVCompareCardinalNumber;
                        if (($info['client_v'] * $clientVCompareCardinalNumber) < ($cfg['min_enable_client_v'][$platform] * $clientVCompareCardinalNumber)) {
                            return $groupSign;
                        }
                    }
                }
                if (isset($cfg['project_selection'])) {
                    switch ($cfg['project_selection']) {
                        case 0:
                            $getABCfg = $cfg[0];
                            $getABModulo = $getABUid % 2;
                            if (!empty($getABCfg[$getABModulo])) {
                                $groupSign = $getABCfg[$getABModulo];
                            }
                            break;
                        case 1:
                            $getABCfg = $cfg[1];
                            if (!empty($getABCfg[self::GROUP_A]) && in_array($getABUid, $getABCfg[self::GROUP_A])) {
                                $groupSign = self::GROUP_A;
                            } elseif (!empty($getABCfg[self::GROUP_B]) && in_array($getABUid, $getABCfg[self::GROUP_B])) {
                                $groupSign = self::GROUP_B;
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return $groupSign;
    }
    
    /**
     * 通过版本号获取指定的值
     * @param string $clientV 客户端版本号
     * @param string $platform 平台号
     * @param array $versionInfo
     * <pre>
     *  array (
     *       'ios_GROUP_A' =>   // 如果有平台区分的话,以平台名称为前缀
     *       array (
     *         'ios_2' =>
     *         array (
     *           '>equal_to' => '1.200',
     *         ),
     *         'ios_1' =>
     *        array (
     *           '<' => '1.200',
     *         ),
     *       ),
     *       'ios_GROUP_B' =>
     *       array (
     *         'ios_4' =>
     *         array (
     *           '>equal_to' => '1.200',
     *         ),
     *         'ios_3' =>
     *         array (
     *           '<' => '1.200',
     *         ),
     *       ),
     *       'android_GROUP_A' =>
     *       array (
     *         'android_2' =>
     *         array (
     *           '>equal_to' => '1.200',
     *         ),
     *         'android_1' =>
     *         array (
     *           '<' => '1.200',
     *         ),
     *       ),
     *       'android_GROUP_B' =>
     *       array (
     *         'android_4' =>
     *         array (
     *           '>equal_to' => '1.200',
     *         ),
     *         'android_3' =>
     *         array (
     *           '<' => '1.200',
     *         ),
     *       ),
     *     )
     * )
     * </pre>
     * @param array $values
     * <pre>
     * array (
     *       'ios_GROUP_A' => 0,
     *       'ios_GROUP_B' => 0,
     *       'android_GROUP_A' => 0,
     *       'android_GROUP_B' => 0,
     *    )
     *
     * </pre>
     * @param integer $clientVCompareCardinalNumber 版本比较放大系数
     *
     * @return string | Object |array | false 返回values里边对应key 对应的值匹配不成功返回false
     *
     */
    public static function getValueByVersion($clientV, $platform, $versionInfo, $values, $clientVCompareCardinalNumber = 1000)
    {
        if ((empty($clientV) && empty($platform) )|| empty($versionInfo) || empty($values)) {
            return false;
        }
        $clientV = strtolower($clientV);
        $platform = strtolower($platform)."";
        
        $clientVTemp = $clientV * $clientVCompareCardinalNumber;
        foreach ($versionInfo as $key => $items){
            // 处理操作符比较
            // 用于每一个item的条件是否全部满足,有一个不满足此item的条件就不成立.
            $isMeetConditionsFromItem = false;
            if ($platform !== ""){
                $lkey = strtolower($key);
                // 如果平台匹配的话则直接跳过
                if(strpos($lkey, $platform) !== 0){
                    continue;
                }
            }
            foreach ($items as $item){
                $hasAllCompare = true;
                foreach ($item as $operator => $compareClientV) {
                    // 转义等号.
                    $operator = str_ireplace('equal_to', '=', $operator);
                    $compareClientVTemp = $compareClientV * $clientVCompareCardinalNumber;
                    if ($operator == '>') {
                        if (!($clientVTemp > $compareClientVTemp)) {
                            $hasAllCompare = false;
                            break;
                        }
                    } elseif($operator == '<') {
                        if (!($clientVTemp < $compareClientVTemp)) {
                            $hasAllCompare = false;
                            break;
                        }
                    } elseif($operator == '=') {
                        if (!($clientVTemp == $compareClientVTemp)) {
                            $hasAllCompare = false;
                            break;
                        }
                    } elseif($operator == '>=') {
                        if (!($clientVTemp >= $compareClientVTemp)) {
                            $hasAllCompare = false;
                            break;
                        }
                    } elseif($operator == '<=') {
                        if (!($clientVTemp <= $compareClientVTemp)) {
                            $hasAllCompare = false;
                            break;
                        }
                    } elseif($operator == '!=') {
                        if (!($clientVTemp != $compareClientVTemp)) {
                            $hasAllCompare = false;
                            break;
                        }
                    }
                }
                // 有一条规则匹配上则返回
                if ($hasAllCompare){
                    $isMeetConditionsFromItem = true;
                    break;
                }
            }
            if ($isMeetConditionsFromItem && isset($values[$key])){
                return $values[$key];
            }
        }
        return false;
    }

    /**
     * 根据浏览器HTTP_USER_AGENT中的信息获取非app的platform和client_v.
     *
     * @return array.
     */
    public static function getPlatformAndClientV()
    {
        $httpUserAgent = $_SERVER['HTTP_USER_AGENT'];
        // 优先cookie
        $clientV = JMGetCookie('client_v');
        preg_match('@Shuabao\_(\w+)\sv([\d|\.]+)@', $httpUserAgent, $matches);
        $platform = empty($matches[1]) ? \Util\Util::getHeaderByName('platform') : $matches[1];
        if (empty($clientV)) {
            $clientV = empty($matches[2]) ? \Util\Util::getHeaderByName('client_v') : $matches[2];
        }
        return array(
            'platform' => $platform,
            'client_v' => $clientV
        );
    }

    /**
     * 初始化MNLogger.
     */
    public static function initMNLogger()
    {
        try {
            \MNLogger\TraceLogger::instance('trace')->HTTP_SR();
        } catch (\Exception $e) {

        }
        // 记录无法捕捉的错误
        register_shutdown_function(
            function () {
                $error = error_get_last();
                if (in_array($error['type'], array(E_ERROR, E_USER_ERROR), true) && 0 === strpos($error['message'], 'Uncaught exception')) {
                    \MNLogger\EXLogger::instance()->log(new \Exception($error['message'].' in '.$error['file'].' on line '.$error['line']));
                }
                $responseType = ($error && in_array($error['type'], array(E_ERROR, E_USER_ERROR), true)) ? \MNLogger\Base::T_EXCEPTION : \MNLogger\Base::T_SUCCESS;
                \MNLogger\TraceLogger::instance('trace')->HTTP_SS($responseType, 0);
                // 刷新日志到磁盘（必须在SS之后）
                if (is_callable(array('MNLogger\TraceLogger', 'flush'))) {
                    \MNLogger\TraceLogger::flush();
                }
            }
        );
    }

    /**
     * 是否允许调用rpc接口.
     *
     * @param string $callingMethod  调用rpc方法名.
     * @param string $sourceFunction 调用此方法的来源方法名.
     *
     * @return boolean.
     */
    public static function isQueryRpc($callingMethod, $sourceFunction)
    {
        $res = true;
        if (!empty($callingMethod) && !empty($sourceFunction) && isset(\Config\Common::$queryRpcRestrict)) {
            $queryRpcRestrict = \Config\Common::$queryRpcRestrict;
            if (!empty($queryRpcRestrict[$callingMethod])) {
                if (isset($queryRpcRestrict[$callingMethod][$sourceFunction])) {
                    if (empty($queryRpcRestrict[$callingMethod][$sourceFunction])) {
                        $res = false;
                    }
                }
            }
        }
        return $res;
    }

    /**
     * ab策略
     *
     * @param integer $uid 用户ID
     *
     * @return integer
     */
    public static function abPolicy($uid){
        $returnPolicy = "0";
        if ($uid < 1000){
            return $returnPolicy;
        }
        $idRange = range(0, 9);
        $seedId = array();
        foreach($idRange as $rawId){
            $seedId[] = str_pad(strval($rawId), 3, "0", STR_PAD_LEFT);
        }
        $policyArray = array("1001"=> $seedId);
        //去掉最后一位，uid的后三位
        $uidNum = substr(strval($uid), -4, 3);
        foreach($policyArray as $policyId => $policyRange){
            if (in_array($uidNum, $policyRange)){
                $returnPolicy = $policyId;
                break;
            }
        }
        return $returnPolicy;
    }

    /**
     * 构建邀请文本.
     *
     * @param string  $oldTxt     原版文本.
     * @param string  $inviteCode 邀请码.
     * @param string  $videoId    视频ID.
     * @param integer $type       类型 1分享视频 2分享好友 3提醒他 4分享视频（比1携带的信息更多）.
     *
     * @return string
     */
    public static function buildShareTxt($oldTxt, $inviteCode, $videoId = "", $type = 1, $ext = array())
    {
        if (empty($oldTxt)) {
            return $oldTxt;
        }
        $oldTxt = str_replace('$code', $inviteCode, $oldTxt);
        // 得到需要正则匹配内的内容.
        $preLabel = "#";
        $chkLabel = ":";
        // 正则匹配出要替换的字符串.
        preg_match('/{(.*?)}/', $oldTxt, $matches);
        // 得到正则匹配出来的字符串.
        $pregTxt = ! empty($matches[0]) ? $matches[0] : "";
        $cPreTxt = ! empty($matches[1]) ? $matches[1] : "";
        // 没有匹配到内容直接返回.
        if (empty($cPreTxt)) {
            return $oldTxt;
        }
        $videoId = str_replace('SMALL_VIDEO_', '', $videoId);
        // 得到最终要生成的字符串.
        $chkTxt = str_replace(array('$inviteCode', '$videoId'), array($preLabel . $inviteCode . $type, $preLabel . $videoId), $cPreTxt);
        if ($type == 4) {
            $chkTxt .= $preLabel . $ext['share_type'] . $preLabel . $ext['video_source'] . $preLabel . $ext['platform'] . $preLabel . $ext['uid'];
        }
        // 去掉第一个前缀符号.
        $chkPreTxt = ltrim($chkTxt, $preLabel);
        // 将字符串分隔为数组
        $chkTxtArr = preg_split('/(?<!^)(?!$)/u', $chkPreTxt );
        // 得到交验位数
        $chkNum = 0;
        // 得到计数初始值.
        $i = count($chkTxtArr)%8;
        foreach ($chkTxtArr as $v) {
            $i ++;
            $num = ord($v);
            $chkNum = $num%($chkNum + $i);
        }
        // 得到最终的校验位
        $chkNum = $chkNum%10;
        $chkTxt .= $chkLabel.$chkNum;
        // 还替换最终的文本.
        $oldTxt = str_replace($pregTxt, $chkTxt, $oldTxt);
        return $oldTxt;
    }

    /**
     * 解码分享文本内容.
     *
     * @param string $clipTxt 粘贴版文案.
     *
     * @return array
     * @throws \Exception
     */
    public static function decodeShareTxt($clipTxt)
    {
        $device = \JMRegistry::get('device');
        // 初始化结果集
        $result = array(
            // 邀请码
            'invite_code' => "",
            // 扩展信息
            'ext_info' => "",
            // 是否带刷宝标识
            'is_shuabao' => 0,
            // 效验合法
            'verify_pass' => 0,
            // 类型
            'type' => ''
        );
        if (empty($clipTxt)) {
            return $result;
        }
        $inviteCode = "";
        // 效验是否带刷宝标识.
        $labelArr = \Config\Common::$clipShuabaoLabel;
        // 进行字符串刷宝特征匹配.
        if (is_array($labelArr)) {
            foreach ($labelArr as $label) {
                if (mb_strpos($clipTxt, $label) !== false) {
                    $result['is_shuabao'] = 1;
                    break;
                }
            }
        }
        if ($result['is_shuabao'] == 1) {
            $match = array();
            preg_match('/邀请码【(.*?)】/', $clipTxt, $match);
            // 得到匹配的字符串.
            $inviteCode = isset($match[1]) ? $match[1] : "";
            $result['invite_code'] = $inviteCode;
        }
        // 先匹配出第目标文案.
        $targetTxt = "";
        // 得到需要正则匹配内的内容.
        $preLabel = "#";
        $chkLabel = ":";
        $matches = array();
        preg_match('/'.$preLabel.'(.*?)'.$chkLabel.'(\d)/', $clipTxt, $matches);
        // 如果匹配结果为空直接返回.
        if (empty($matches)) {
            return $result;
        }
        // 得到匹配的字符串.
        $chkTxt = ! empty($matches[1]) ? $matches[1] : "";
        // 没有匹配到内容直接返回
        if ($chkTxt === "") {
            return $result;
        }
        // 证明按格式匹配出了内容.
        if(count($matches) == 3) {
            // 去掉第一个前缀符号.
            $chkPreTxt = ltrim($chkTxt, $preLabel);
            // 将字符串分隔为数组
            $chkTxtArr = preg_split('/(?<!^)(?!$)/u', $chkPreTxt );
            // 得到交验位数
            $chkNum = 0;
            // 得到计数初始值.
            $i = count($chkTxtArr)%8;
            foreach ($chkTxtArr as $v) {
                $i ++;
                $num = ord($v);
                $chkNum = $num%($chkNum + $i);
            }
            // 得到最终的校验位
            $chkNum = $chkNum%10;
            if ($chkNum == $matches[2]) {
                $result['verify_pass'] = 1;
            }
        }
        // 得到字符串码的类型.
        $targetArr = explode($preLabel, $chkTxt);
        if (!in_array(count($targetArr), array(2, 6))) {
            return $result;
        }
        $type = substr($targetArr[0], -1, 1);

        $strLen = mb_strlen($targetArr[0]);
        if ($strLen > 1) {
            $inviteCode = mb_substr($targetArr[0], 0, $strLen - 1);
        }
        $extTxt = $targetArr[1];
        $result['ext_info'] = $extTxt;
        $result['type'] = $type;
        if (! empty($inviteCode)) {
            $result['invite_code'] = $inviteCode;
        }
        if ($type == 4) {
            $result['share_type'] = $targetArr[2];
            $result['video_source'] = $targetArr[3];
            $result['share_platform'] = $targetArr[4];
            $result['share_uid'] = $targetArr[5];

            // type为4是新版本的类型，兼容老版本进行类型转化
            if ($device['client_v'] < \Config\Video::$videoShareConf['share_v2_min_client_v']) {
                $result['type'] = 1;
            }
        }

        return $result;
    }

    /**
     * 替换配置中的通配符*为对应的数字.
     *
     * @param array $eachCase 每组配置.
     * @param array $replace  需要替换的数字数组.
     *
     * @return array
     */
    public static function replaceGlob(&$eachCase, $replace)
    {
        $newVal = [];
        foreach ($eachCase as $key => $value) {
            if (strpos($value, '*') !== false) {
                foreach ($replace as $num) {
                    $newVal[] = str_replace('*', $num, $value); // 把配置的*替换成数字.
                }
                unset($eachCase[$key]); // 替换之后删除带*号的配置
            }
        }
        return $newVal;
    }

    /**
     * 查看某个uid的倒数第5位和第二位是否在实验组配置中.
     *
     * @param integer $uid   用户uid.
     * @param array   $cases 每组配置,如array('11', '2*', '*2', '**').
     *
     * @return boolean
     */
    public static function searchInTestGroup($uid, array $cases)
    {
        $result = false;
        $last2 = substr($uid, -2, 1);
        $last5 = substr($uid, -5, 1);
        $uidDigit = $last5 . $last2;
        foreach ($cases as $oneCase) {
            $pattern = '@^' . str_replace('*', '\d', $oneCase) . '$@';
            if (preg_match($pattern, $uidDigit)) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * 二维数组去掉指定索引item.
     *
     * @param array $oriArray OriArray.
     * @param array $indexes  Indexes.
     *
     * @return mixed
     */
    public static function twoDptArrayUnsetByIndexes($oriArray, $indexes)
    {
        if (!is_array($oriArray) || !is_array($indexes)) {
            return $oriArray;
        }
        foreach ($oriArray as $k => $item) {
            foreach ($indexes as $index) {
                unset($oriArray[$k][$index]);
            }
        }
        return $oriArray;
    }

    /**
     * Check hp.
     *
     * @param integer $hp 手机号.
     *
     * @return boolean
     */
    public static function checkHp($hp)
    {
        return (boolean)preg_match('/^1\d{10}$/', $hp);
    }

    /**
     * 是否包含url.
     *
     * @param string $content 内容.
     *
     * @return boolean
     */
    public static function isContainUrl($content)
    {
        $content = str_replace('．', '.', $content);
        $content = str_replace(' ', '', $content);

        // 过滤中文的形同 http://baidu。com
        if (preg_match_all('/http:\/\/([A-Za-z0-9.。]+)/ism', $content, $result)) {
            $content = '';
            foreach ($result as $res) {
                $content .= $res[0].'|';
            }
            $content = str_replace('。', '.', $content);
        }

        if (preg_match('/(?:[a-zA-Z0-9]+(?:\\-*[a-zA-Z0-9])*[\\.])+[a-zA-Z]{2,6}/ism', $content)) {
            return true;
        }
        return false;
    }

    /**
     * 根据出生日期获取年龄.
     *
     * @param string $birthday 出生年月日(1900-01-01).
     *
     * @return string 年龄
     */
    public static function getAgeDescByBirthday($birthday)
    {
        if (empty($birthday)
            || (strtotime($birthday) < strtotime('1900-01-01'))
            || (strtotime($birthday) >= strtotime(date("Y-m-d")))) {
            return '';
        }
        $year = date('Y');
        $month = date('m');
        if (substr($month, 0, 1)==0) {
            $month = substr($month, 1);
        }
        $day = date('d');
        if (substr($day, 0, 1) == 0) {
            $day = substr($day, 1);
        }
        $arr = explode('-', $birthday);
        $age = $year - $arr[0];
        if ($month < $arr[1]) {
            $age = $age - 1;
        } elseif ($month == $arr[1] && $day < $arr[2]) {
            $age = $age - 1;
        }
        return $age . '岁';
    }

    /**
     * 根据出生日期获取星座.
     *
     * @param string $birthday 出生年月日(1900-01-01).
     *
     * @return string 年龄
     */
    public static function getConstellationByBirthday($birthday)
    {
        if ((empty($birthday) || (strtotime($birthday) < strtotime('1900-01-01')))) {
            return '';
        }
        $arr = explode('-', $birthday);
        $month = $arr[1];
        $day = $arr[2];
        if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $star = '水瓶座';
        } elseif (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $star = '双鱼座';
        } elseif (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
            $star = '白羊座';
        } elseif (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $star = '金牛座';
        } elseif (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
            $star = '双子座';
        } elseif (($month == 6 && $day >= 22) || ($month == 7 && $day <= 22)) {
            $star = '巨蟹座';
        } elseif (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
            $star = '狮子座';
        } elseif (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $star = '处女座';
        } elseif (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
            $star = '天秤座';
        } elseif (($month == 10 && $day >= 24) || ($month == 11 && $day <= 22)) {
            $star = '天蝎座';
        } elseif (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
            $star = '射手座';
        } elseif (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
            $star = '摩羯座';
        } else {
            $star = '';
        }
        return $star;
    }

    /**
     * 根据浏览器HTTP_USER_AGENT中的信息判断是否在app内.
     *
     * @return boolean
     */
    public static function checkUserAgentInShuaBaoApp()
    {
        return false !== stripos($_SERVER['HTTP_USER_AGENT'], 'shuabao');
    }

    /**
     * 简易通用版版本控制.
     *
     * @param string $clientV     客户端版本号.
     * @param string $platform    平台号.
     * @param array  $versionInfo 版本控制信息.
     * <pre>
     *  array (
     *       'ios' =>   // 如果有平台区分的话,以平台名称为前缀
     *       array (
     *         'min_client_v' => '1.200',
     *         'max_client_v' => '6.200',
     *       ),
     *       'android' =>
     *       array (
     *         'min_client_v' => '1.201',
     *       ),
     *     )
     * </pre>
     *
     * @return integer 0:不在版本控制中 1:在版本控制中 2:配置信息不存在，默认值
     */
    public static function simpleVersionControl($clientV, $platform, array $versionInfo)
    {
        $platform = strtolower($platform);
        $isControl = 1;
        if (!empty($versionInfo)
            && !empty($versionInfo[$platform])) {
            $clientVCompareCardinalNumber = empty(\Config\Common::$clientVCompareCardinalNumber) ? '1000' : \Config\Common::$clientVCompareCardinalNumber;
            if (!empty($versionInfo[$platform]['min_client_v'])) {
                if (($clientV * $clientVCompareCardinalNumber) < ($versionInfo[$platform]['min_client_v'] * $clientVCompareCardinalNumber)) {
                    $isControl = 0;
                }
            }
            if (!empty($versionInfo[$platform]['max_client_v'])) {
                if (($clientV * $clientVCompareCardinalNumber) > ($versionInfo[$platform]['max_client_v'] * $clientVCompareCardinalNumber)) {
                    $isControl = 0;
                }
            }
        } else {
            $isControl = 2;
        }
        return $isControl;
    }

    /**
     * 简易通用版uid分组控制.
     *
     * @param integer $uid          用户ID.
     * @param array   $uidGroupInfo Uid分组信息.
     * <pre>
     *  array (
     *       'uid_group_1' => array("0*", "1*", "2*", "3*", "5*", "6*", "7*", "8*", "90"),
     *       'uid_group_2' => array("91", "92", "93", "94", "95", "96", "97", "98", "99"),
     *     )
     * </pre>
     *
     * @return string 返回对应分组的key值
     */
    public static function simpleUidGroupControl($uid, array $uidGroupInfo)
    {
        $uidGroupKey = '';
        if (!empty($uid) && !empty($uidGroupInfo)) {
            // 得到uid的特定匹配位数值.
            $uidLast = substr($uid, -5, 1) . substr($uid, -2, 1);
            foreach ($uidGroupInfo as $key => $item) {
                if (!empty($item) && is_array($item)) {
                    $replace = range(0, 9);
                    $newVal = \Util\Util::replaceGlob($item, $replace);
                    if (!empty($newVal)) {
                        // 不为空说明有删除带*号的配置,需要合并补充上去.
                        $item = array_merge($newVal, $item);
                    }
                    if (in_array($uidLast, $item)) {
                        $uidGroupKey = $key;
                        break;
                    }
                }
            }
        }
        return $uidGroupKey;
    }

    /**
     * 根据redis过期时间获取redis过期时间今日必须过期的正确时间.
     *
     * @param integer $expiredTime 过期时间.
     *
     * @return integer
     */
    public static function getTodayRedisExpiredTimeByExpiredTime($expiredTime)
    {
        $todayRedisExpiredTime = 0;
        if (empty($expiredTime) || !is_numeric($expiredTime)) {
            return $todayRedisExpiredTime;
        }
        $todayRedisExpiredTime = $expiredTime;
        $time = time();
        $tomorrowTime = strtotime('tomorrow');
        if (($tomorrowTime - $time) < $expiredTime) {
            $todayRedisExpiredTime = $tomorrowTime - $time;
        }
        return $todayRedisExpiredTime;
    }

    /**
     * 正则获取url参数值.
     *
     * @param string $url     链接.
     * @param string $argName 参数名称.
     *
     * @return mixed
     */
    public static function getUrlParams($url, $argName)
    {
        $regx = '/.*[&|\?]'. $argName .'=([^&]*)(.*)/';
        preg_match($regx, $url, $match);
        return $match[1];
    }

    /**
     * DeviceId取余.
     *
     * @param string  $deviceId DeviceId.
     * @param integer $y        除数.
     *
     * @return integer
     */

    public static function modDeviceId($deviceId, $y = 100)
    {
        $deviceId .= '';
        $last4 = substr($deviceId, - 4);
        $deviceIdConvert = base_convert($last4, 36, 10);
        return $deviceIdConvert % $y;
    }

    /**
     * 传入运算符对比两个整数.
     *
     * @param   integer     $x  数X
     * @param    integer    $y  数Y
     * @param string $operator  对比运算符 支持 > < equal_to >equal_to <equal_to <> !=
     *
     * @return bool
     *
     */
    public static function intCompare($x , $y ,$operator = '=' ){
        $operator = str_ireplace('equal_to', '=', $operator);
        if ($operator == '>') {
            return ($x > $y);
        } else if ($operator == '<') {
            return ($x < $y);
        } else if ($operator == '=') {
            return ($x == $y);
        } else if ($operator == '>=') {
            return ($x >= $y);
        } else if ($operator == '<=') {
            return ($x <= $y);
        } else if ($operator == '!=') {
            return ($x != $y);
        } else if ($operator == '<>') {
            return ($x != $y);
        }
        return false;
    }

}
