<?php
/**
 * api 基类
 * @author Lin Hao<lin.hao@xiaonianyu.com>
 * @date 2020-12-28 14:23:38
 */
class Controller_Base_Base extends JMViewController_WebManagementBase
{

    public $api_version = 'base';

    public $device = array(); // 设备信息

    protected $responseHeaders = array();

    protected $uid = '';

    /**
     * 认证token
     * @var string
     */
    protected $accessToken = "";

    /**
     * 平台版本
     * @var string
     */
    protected $clientV = "";

    /**
     * 接口是否需要登录
     */
    public $needLogin = false;

    /**
     * 需要排出验证的方法列表, @SEE: $needLogin.
     *
     * (如果: needLogin=true, 该变量就排除登录, 如果: needLogin=false, 该变量就需要登录).
     *
     * @var array
     */
    protected $excludeActions = array();

    /**
     * 接口是否为app前台请求默认初始值 true 为前台请求 false为后台请求
     *
     * @var boolean
     */
    protected $defaultFnd = true;

    /**
     * 需要做取反处理的默认方法列表, @SEE: $defaultFnd.
     *
     * @var array
     */
    protected $excludeFndActions = array();

    public static $instances;
    /**
     * 是否自动处理action中抛出的异常.
     *
     * @var boolean
     */
    protected $autoHandleException = true;

    /**
     * 用户验证权限判断.
     */
    public function validateAuthAction()
    {
        $routePathFields = $this->getSiteEngine()->getRoutePathFields();
        $action = end($routePathFields);
        if (!empty($action)) {
            if (in_array($action, $this->excludeActions)) {
                $this->needLogin = !$this->needLogin;
            }
            // 处理默认fnd的值.
            if (in_array($action, $this->excludeFndActions)) {
                $this->defaultFnd = !$this->defaultFnd;
            }
        }
    }

    /**
     * 初始化.
     *
     * @return mixed
     */
    public function initialize()
    {
        // 检查服务器状态
        parent::initialize();

        // 检查公共参数和初始化设备信息
        $this->initParametersAndDevice();
    }

    /**
     * 初始化参数和设备信息.
     *
     * @return void
     */
    protected function initParametersAndDevice()
    {
    }

    /**
     * 输出响应信息.
     *
     * @param string  $type       消息.
     * @param array   $result     数据.
     * @param mixed   $message    返回码.
     * @param string  $action     Action.
     * @param array   $popWindows 提示信息类型.
     *
     * @return void
     */
    public function response($type, $result = array(), $message = false, $action = 'toast', $popWindows = array())
    {
        // 输出返回的header 信息
        $this->responceHeader();
        \Util\AppResponse::responseExit($type, $result, $message, $action, $popWindows);
    }

    /**
     * 输出成功json.
     *
     * @param array  $result  Data.
     * @param string $message 消息.
     * @param string $action  Action, 如toast,jump.
     *
     * @return void
     */
    public function responseSuccess($result = array(), $message = '', $action = 'toast', $popWindows = array())
    {
        $this->response("SUCCESS", $result, $message, $action, $popWindows);
    }

    /**
     * 输出失败json.
     *
     * @param mixed  $exception 异常信息.
     * @param string $action    Action, 如toast,jump.
     */
    public function responseFail($exception = '', $action = 'toast')
    {
        if ($exception instanceof \RpcBusinessException) {
            // 业务异常信息可以输出.
            $message = $exception->getMessage();
        } else {
            if ($exception instanceof \Exception) {
                \MNLogger\EXLogger::instance()->log($exception);
                if (\Config\Env::$env != 4) {
                    $message = $exception->getMessage();
                } else {
                    $message = '网络开小差啦';
                }
                // \Util\Log::log($exception->getMessage(), "shuabao_api_exception");
                \Util\Log\Exception::getInstance()->addLog(
                    'shuabao_api_exception',
                    $exception->getCode(),
                    $exception->getMessage()
                );
            } else {
                $message = $exception;
            }
        }
        $this->response('FAILED', array(), $message, $action);
    }

    /**
     * 接口返回header 信息
     */
    protected function responceHeader()
    {
        if (!isset($this->responseHeaders['access_token'])){
            $this->responseHeaders['access_token'] = $this->accessToken;
        }
        if (!empty($this->responseHeaders) && is_array($this->responseHeaders)){
            foreach ($this->responseHeaders as $key => $v){
                header($key. ' : '. $v);
            }
        }
    }
}
