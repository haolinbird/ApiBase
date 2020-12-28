<?php
/**
 * Sms.
 *
 * @author dengjing <jingd3@jumei.com>
 */

namespace Service;

/**
 * Sms.
 */
class Sms extends \Service\ServiceBase
{

    /**
     * Get instance of the derived class.
     *
     * @return \Service\Sms
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    /**
     * 发送短信.
     *
     * @param integer $mobile 手机号.
     * @param string  $content 短信内容.
     *
     * @return boolean
     * @throws \Exception
     */
    public function sendByConf($mobile, $content, $conf = 'common')
    {
        if (!isset(\Config\Sms::$$conf)) {
            $this->rpcBusinessException('短信配置不存在');
        }
        $smsConfig = \Config\Sms::$$conf;
        $ch = curl_init($smsConfig['host']);
        $post = array(
            'num' => $mobile,
            'content' => $content,
            'task' => $smsConfig['task'],
            'channel' => $smsConfig['channel'],
            'key' => $smsConfig['key'],
            'global' => 0,
            'encrypt' => 0,
        );
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200 || curl_error($ch) != '') {
            $result = false;
        } elseif ($response == 'ok') {
            $result = true;
        } else {
            $result = false;
        }
        curl_close($ch);
        $ip = \JMSystem::GetClientIp();
        try {
            $device = \JMRegistry::get('device');
        } catch (\Exception $e) {
            $device = array();
        }
        $deviceId = !empty($device['device_id']) ? $device['device_id'] : '';
        $this->log('sms_send_log', array('datetime' => date('Y-m-d H:i:s'), 'mobile' => $mobile, 'type' => 0, 'conf' => $conf, 'task' => $smsConfig['task'], 'channel' => $smsConfig['channel'], 'content' => $content, 'response' => $response, 'ip' => $ip, 'device_id' => $deviceId));
        return $result;
    }

    /**
     * 通过邀请通道发送短信.
     *
     * @param integer $mobile  明文手机号.
     * @param string  $content 内容.
     *
     * @return boolean 成功返回true,失败返回false.
     */
    public function sendInvitePromo($mobile, $content)
    {
        return $this->sendByConf($mobile, $content, 'invitePromoConfig');
    }

    /**
     * 通过营销通道发送短信.
     *
     * @param integer $mobile  明文手机号.
     * @param string  $content 内容.
     *
     * @return boolean 成功返回true,失败返回false.
     */
    public function sendPromo($mobile, $content)
    {
        return $this->sendByConf($mobile, $content, 'promoConfig');
    }

    /**
     * 调用短信网关.
     *
     * @param string  $num     手机号.
     * @param string  $content 发送内容.
     * @param string  $task    发送任务.
     * @param integer $encrypt 是否使用加密手机号传输.
     * @param integer $case    语音还是短信,默认0表示短信.
     *
     * @return array.
     */
    public function sendMsgByMobile($num, $content, $task, $encrypt, $case = 0)
    {
        $smsConfig = \Config\Sms::$$task;
        $global = 0; // 是否由网关根据比例分配通道.
        $ch = curl_init($smsConfig['host']);
        $post = array(
            'num' => $num,
            'content' => $content,
            'task' => $smsConfig['task'],
            'channel' => $smsConfig['channel'],
            'key' => $smsConfig['key'],
            'global' => $global,
            'encrypt' => $encrypt,
        );
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] != 200 || curl_error($ch) != '') {
            $result = false;
        } elseif ($response == 'ok') {
            $result = true;
        } else {
            $result = false;
        }
        curl_close($ch);
        $ip = \JMSystem::GetClientIp();
        try {
            $device = \JMRegistry::get('device');
        } catch (\Exception $e) {
            $device = array();
        }
        $deviceId = !empty($device['device_id']) ? $device['device_id'] : '';
        $this->log('sms_send_log', array('datetime' => date('Y-m-d H:i:s'), 'mobile' => $num, 'type' => $case, 'conf' => $task, 'task' => $smsConfig['task'], 'content' => $content, 'response' => $response, 'ip' => $ip, 'device_id' => $deviceId));
        return $result;
    }

}
