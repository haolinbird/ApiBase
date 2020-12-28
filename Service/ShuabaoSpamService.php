<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/01
 * Time: PM15:25
 */
class ShuabaoSpamService extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'ShuabaoSpamService';

    /**
     * Get Instance.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
	 * 敏感词识别
	 * @param {"type":"nickname/avatar_large/signature"}
	 * @return  {"level":0/1/2,"words":["a","b"]}
     *
     * @throws \Exception
	 */
    public function sensRecognition($params)
    {
        // $response = $this->thriftClient()->sensRecognition(json_encode($params));
        $response = $this->doThriftClientByMethod('sensRecognition', json_encode($params));
        $res = json_decode($response, true);
        return $res;
    }

    /**
     * 垃圾评论识别.
     *
     * @param string $comment
     * @return array ['spam' => true|false]
     * @throws \Exception
     */
    public function spamRecognition($comment)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        // $result = $this->thriftClient()->spamRecognition($comment);
        $result = $this->doThriftClientByMethod('spamRecognition', $comment);
        // \Util\Log\Service::getInstance()->thriftLog(static::$serviceName, 'spamRecognition', func_get_args(), $result);
        return json_decode($result, true);
    }
}
