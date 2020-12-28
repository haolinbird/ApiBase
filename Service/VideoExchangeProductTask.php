<?php
/**
 * Created by PhpStorm.
 * User: liyadong
 * Date: 2019/4/28
 * Time: 7:30 PM
 */

namespace Service;


class VideoExchangeProductTask extends ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabao';

    public static $className = 'VideoExchangeProductTask';

    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    public function getUserTaskInfo($uid)
    {
        return [
            [
                'id' => 1,
                'video_id' =>'video1',
                'uid' => 1,
                'product_flag' => '1',
                'task_status' => 1,
                'product_amount' => 5000,
                'used_sycee' => 2435,
                'used_balance' => 1000,
                'task_acquire_amount' => 0,
                'invite_acquire_amount' => 2,
                'create_time' => 1556121698,
                'update_time' => 1553121598,
            ],
            [
                'id' => 2,
                'video_id' =>'video1',
                'uid' => 1,
                'product_flag' => '2',
                'task_status' => 1,
                'product_amount' => 2000,
                'used_sycee' => 245,
                'used_balance' => 1000,
                'task_acquire_amount' => 0,
                'invite_acquire_amount' => 2,
                'create_time' => 1546101598,
                'update_time' => 1556121598,
            ],

        ];
    }
}