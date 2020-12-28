<?php
/**
 * @file JumeiAuth
 *
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */

namespace Util;

class JumeiAuth extends \Util\CInstance
{
    //session名
    const SESSION_KEY_AUTH_INFO = 'JMAuthAthena_auth_info';
    const INIT_PATH = 'index.menu';
    //auth配置信息
    private $_config = '';

    //不需要登录权限的path
    private $_logRuleOutPath = array(
        'callback.login',
        'auth.islogin'
    );
    // 不需要验证权限的公共接口.
    private $_notCheckAuthPath = array();


    //Session会话信息
    private $_session = array();
    /**
     * 类构造函数
     */
    public function __construct() {
        $this->_notCheckAuthPath = \Config\AuthSystem::$notCheckAuthPath;
        $this->_config = \Config\AuthSystem::$jumeiAuth;
        $this->_session = empty($_SESSION[self::SESSION_KEY_AUTH_INFO]) ? '' : $_SESSION[self::SESSION_KEY_AUTH_INFO];
        if (!is_array($this->_session) && !empty($this->_session)) {
            $this->_session = json_decode($this->_session, true);
        }
    }
    /**
     * 设置Auth相关的session信息
     *
     * 将auth系统中返回的用户信息、用户的权限数据进行处理后存储到session中
     *
     * @param array $userInfo 用户账号信息
     * @param array $authInfo 用户权限信息
     */
    public function setSession($userInfo, $authInfo) {
        $arr = array(
            'username' => $userInfo['username'],
            'fullname' => isset($userInfo['fullname']) ? $userInfo['fullname'] : '',
            'email' => isset($userInfo['mail']) ? $userInfo['mail'] : '',
            'paths' => array(),
            'groups'=> isset($authInfo['groups']) && is_array($authInfo['groups']) ? $authInfo['groups'] : array(),
        );
        if (empty($arr['email'])) {
            $arr['email'] = $arr['username'].'kefu@jumei.com';
        }
        if (!empty($authInfo['paths'])) {
            $paths = array();
            foreach ($authInfo['paths'] as $k => $v) {
                $paths[strtolower($v['path'])] = array_map('strtolower', $v['roles']);
            }
            $arr['paths'] = $paths;
        }
        $this->_session = $arr;
        $this->getUserInfo();
        $_SESSION[self::SESSION_KEY_AUTH_INFO] = json_encode($arr);
    }

    /**
     *获取用户的权限组.
     *
     * @return array.
     */
    public function getGroups()
    {
        return $this->_session['groups'];
    }

    /**
     *获取一个path下面的所有角色.
     *
     * @param string $path
     * @return array.
     */
    public function getRolesByPath($path)
    {
        return isset($this->_session['paths'][$path]) ? $this->_session['paths'][$path] : array();
    }

    public function isLogin()
    {
        $log = !empty($this->_session['username']) && !empty($this->_session['email']);
        if (!$log) {
            $_SESSION[self::SESSION_KEY_AUTH_INFO] = '';
        }
        return $log;
    }

    /**
     * 判断是否已经登录.
     *
     * @return boolean
     */
    public function setLoginSession() {
        if (isset($_GET['token']) && isset($_GET['username'])) {
            $userInfo = $this->getUserInfoFromAuth($_GET['token'], $_GET['username']);
            if ($userInfo && empty($userInfo['errorid'])) {
                $authInfo = $this->getUserAuth($_GET['username']);
                $this->setSession($userInfo, $authInfo);
            }
        }
    }
    /**
     * 获取用户的完整权限信息
     *
     * 通过auth系统返回的用户名，调用auth系统接口获取用户的完整权限信息
     *
     * @param string $username 用户名
     * @return array
     */
    public function getUserAuth($username) {
        $num = 0;
        while ($num < 2 && ($ret = $this->_send('api/grouprole', array('uid' => $username, 'app_key' => $this->_config['appKey'], 'app_name' => $this->_config['appName']))) === false) {
            $num++;
        }
        return $ret;
    }

    /**
     * 获取用户的完整权限信息.
     *
     * 通过auth系统返回的用户名，调用auth系统接口获取用户的完整账户信息
     *
     * @param string $username 用户名
     *
     * @return array
     */
    public function getUserInfoByAuth($username) {
        $num = 0;
        while ($num < 2 && ($ret = $this->_send('api/member', array('uid' => $username, 'app_key' => $this->_config['appKey'], 'app_name' => $this->_config['appName']))) === false) {
            $num++;
        }
        return $ret;
    }

    /**
     * 获取用户完整信息
     *
     * 通过auth系统返回的token和username生成session_id，
     * 再请求auth系统的接口，获取用户的完整信息。
     *
     * @param string $token auth系统返回的token
     * @param string $username 用户名
     * @return array
     */
    protected function getUserInfoFromAuth($token, $username) {
        return $this->_send('api/info', array('session_id'=>sha1($token.$this->_config['appKey'].$username)));
    }

    /**
     *获取用户信息.
     *
     * @return boolean.
     */
    public function getUserInfo()
    {
        return $this->_session;
    }

    /**
     * 对外发启请求
     *
     * 通过GET的方式向外发送请求，请求的超时时间为20秒
     *
     * @param string $path 地址
     * @param array $data 请求的参数
     * @return array
     */
    private function _send($path, $data = array()) {
        $url = $this->_config['serverUrl'] . $path . (!empty($data) ? '?' . http_build_query($data) : '');
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n",
                'timeout' => 60
            )
        );
        $context = stream_context_create($opts);
        $rs = file_get_contents($url, false, $context);
        if (!empty($rs)) {
            return json_decode($rs, true);
        }
        return false;
    }

    /**
     *登录.
     */
    public function login()
    {
        $this->setLoginSession();
    }

    /**
     *退出登录.
     */
    public function logout()
    {
        $_SESSION[self::SESSION_KEY_AUTH_INFO] = '';
    }

    /**
     *权限验证.
     *
     * @param string  $path   权限path.
     * @param boolean $admin  是否admin就表示有改权限.
     * @param boolean $default 是否有控制器的default的角色就表示有这个权限.
     *
     * @return boolean.
     */
    public function checkAuth($path, $admin = true, $default = true)
    {
        $path = str_replace('/', '.', $path);
        $path = strtolower($path);
        $controller = $action = '';
        if (!empty($path)) {
            list($controller, $action) = explode('.', $path);
        }
        if (!empty($path) && !in_array($path, $this->_logRuleOutPath, true)) {
            if (!$this->isLogin()) {
                $this->jumpLogin();
            }
            if ($admin && $this->isAdmin()) {
                return true;
            }
            return in_array($path, $this->_notCheckAuthPath) || $this->getRolesByPath($path);
        }
        return true;
    }

    public function isAdmin()
    {
        return in_array(\Config\AuthSystem::$authAdminGroup, $this->getGroups()) || in_array($this->_session['username'], \Config\AuthSystem::$authAdminUserName);
    }

    /**
     *跳转到auth的登录页面.
     */
    public function jumpLogin()
    {
        $authUrl = $this->getAuthUrl();
        \Util\AthenaResponse::jump($authUrl);
    }

    // auth登录地址.
    public function getAuthUrl()
    {
        return $this->_config['serverUrl'].'api/login/?camefrom='.  urlencode($this->_config['camefrom']);
    }

    /**
     *设置不需要登录的页面.
     *
     * @param type $path
     * @return $this.
     */
    public function setlogRuleOutPath($path)
    {
        $this->_logRuleOutPath = $path;
        return $this;
    }
}
