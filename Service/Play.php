<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/03
 * Time: 上午16:45
 */
class Play extends \Service\ServiceBase
{
    public static $className = 'Play';


    public function interestTreasureBox($sign, $level, $uuid, $data, $uid, $smDeviceId, $ip, $clientV, $platform, $deviceId)
    {
        $response = $this->phpClient('Play')
            ->interestTreasureBox($sign, $level, $uuid, $data, $uid, $smDeviceId, $ip, $clientV, $platform, $deviceId);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        // 补全广告地址
        if ($response['type'] == 'sycee_advert' && $response['url']) {
            $parseUrl = explode('?', $response['url']);
            parse_str($parseUrl[1], $params);
            $showIdInfo = \Service\ShuaBaoShowCoreService::instance()->querySingleDetailByShowId(
                array('show_id' => $params['video_id'])
            );
            if (empty($showIdInfo)) {
                return $response;
            }
            $params['video_url'] = $showIdInfo['video_url'];
            $response['cover_pic'] = $showIdInfo['cover_pic'];
            $response['url'] = $parseUrl[0] . '?' . http_build_query($params);
        }
        return $response;
    }

    public function interestTreasureBoxNew($uid, $ext_info)
    {
        $response = $this->phpClient('Play')
            ->interestTreasureBoxNew($uid, $ext_info);

        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }
        //前面用array_merge 所以这里要返回空数组
        if( !$response )
        {
            return [];
        }
        // 补全广告地址
        if ($response['type'] == 'sycee_advert' && $response['url']) {
            $parseUrl = explode('?', $response['url']);
            parse_str($parseUrl[1], $params);
            $showIdInfo = \Service\ShuaBaoShowService::instance()->querySingleDetailByShowId(
                array('show_id' => $params['video_id'])
            );
            if (empty($showIdInfo)) {
                return $response;
            }
            $params['video_url'] = $showIdInfo['video_url'];
            $response['cover_pic'] = $showIdInfo['cover_pic'];
            $response['url'] = $parseUrl[0] . '?' . http_build_query($params);
        }
        return $response;
    }
}