<?php
namespace Service;
/**
 * @author jianyoun<jianyoun@jumei.com>
 */
class ShuaBaoPay extends \Service\ServiceBase
{

    public static $serviceName = 'ShuaBaoPay';

    /**
     * 获取文件上传签名等信息接口.
     *
     * @param array $params 请求参数.
     *
     * @return array
     *
     * @throws \Exception 系统业务异常.
     */
    public function request(array $params)
    {
        return $this->doJMPaymentClientByMethod('request', $params);
    }

}
