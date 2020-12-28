<?php
/**
 * Class ShuaBaoShowIndexService.
 *
 * @author jianyoun<jianyoun@jumei.com>
 */

namespace Service;

/**
 * Class ShuaBaoShowIndexService.
 */
class ShuaBaoShowIndexService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuaBaoShowIndexService';

    /**
     * Get Instance.
     *
     * @param boolean $sington 是否单例.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 视频列表.
     *
     * @param array $params 参数数组.
     *
     * @return array
     * @throws \Exception 异常.
     */
    public function recommendList($params)
    {
        if (empty($params['user_id'])) {
            $params['user_id'] = '';
        }
        $response = $this->thriftClient()->recommendList(json_encode($params));
        return $this->checkThriftResult($response, 'recommendList', func_get_args());
    }

}
