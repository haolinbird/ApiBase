<?php
/**
 * Created by PhpStorm.
 * User: Dr_cokiy
 * Date: 2019/2/17
 * Time: 下午12:54
 */

namespace Service;

class BoxReward extends ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabao';

    public static $className = 'BoxReward';

    const BOXES_KEY = "Shuabao_Boxs_List_NEWPUB12";

    /**
     * Get Instance.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    public function sendSycee($uid, $count, $describe)
    {
        $response = $this->phpClient('BoxReward')->sendSycee($uid, $count, $describe);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function addJoinInfo($data)
    {
        $response = $this->phpClient('BoxReward')->addJoinInfo($data);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function getJoinInfo($cond, $columns = '*', $mulity = 'row')
    {
        $response = $this->phpClient('BoxReward')->getJoinInfo($cond, $columns, $mulity);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function updateRewardInfo($data, $cond)
    {
        $response = $this->phpClient('BoxReward')->updateRewardInfo($data, $cond);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function addGoodsInfo($data)
    {
        $response = $this->phpClient('BoxReward')->addGoodsInfo($data);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    // 获取已发放实物奖品数量
    public function getGoodsCount($cond)
    {
        $response = $this->phpClient('BoxReward')->getGoodsCount($cond);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function getGoodsInfo($cond, $columns = '*', $mulity = 'row')
    {
        $response = $this->phpClient('BoxReward')->getGoodsInfo($cond, $columns, $mulity);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function updateGoodsInfo($data, $cond)
    {
        $response = $this->phpClient('BoxReward')->updateGoodsInfo($data, $cond);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function passList()
    {
        // 不知道还用不用, 先停止掉, 如果有问题再打开.
        return [];
        $redis = $this->redis();
        $cacheKey = 'Shuabao_Game_Pass_List';
        $passListJson = $redis->get($cacheKey);
        if (empty($passListJson)) {
            $response = $this->phpClient('Game')->passList();
            if (\PHPClient\Text::hasErrors($response)) {
                $this->RpcBusinessException($response['message'], $response['code']);
            }
            if ($response['rows']) {
                $redis->set($cacheKey, json_encode($response['rows']));
                $redis->expire($cacheKey, 300);
                return $response['rows'];
            }
        }
        return json_decode($passListJson, true);
    }

    public function del($t, $id)
    {
        $response = $this->phpClient('BoxReward')->del($t, $id);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function update($t, $id, $data)
    {
        $response = $this->phpClient('BoxReward')->update($t, $id, $data);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }
    /**
     * 查询出宝箱的数据
     * @param $where
     * @param string $columns
     * @param string $mulity
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function  getDataFromBoxes( $where, $columns = '*', $mulity = 'row' )
    {
        $redis = $this->redis();

        if ($mulity == 'all') {
            $cacheKey = 'Shuabao_Boxs_List';
            $ttl = 120;
        } else {
            $cacheKey = 'Shuabao_Boxs_ROW_' . json_encode($where);
            $ttl = 120;
        }
        $passListJson = $redis->get($cacheKey);

        if (empty($passListJson)) {
            $response = $this->phpClient('Boxes')->getData($where, $columns, $mulity);
            if (\PHPClient\Text::hasErrors($response)) {
                return [];
            }
            if ($response) {
                $redis->setex($cacheKey, $ttl, json_encode($response));
                return $response;
            }
        }
        return json_decode($passListJson, true);
    }

    /**
     * 根据视频id获取redis中的数据
     * @param $video_ids
     * @return array
     * @throws \Exception
     */
    public function getDataFromRedisByVideoIds( $video_ids )
    {
        if( !$video_ids )
        {
            return [];
        }
        $redis = $this->redis();
        $redis_key = self::BOXES_KEY.mt_rand(0,10);
        if( !$redis->EXISTS( $redis_key ) )
        {
            return $this->getDataFromBoxesNew( [ 'enabled' => 1, 'from_type' => 'shuabao','start_time <=' => time(),'end_time >=' => time() ], $redis_key );
        }
        return $redis->HMGET( $redis_key,$video_ids );

    }


    public function getDataFromBoxesNew( $where, $redis_key = '' )
    {
        //存入缓存  这里保证数据库里面一定有一个数据存在
        $redis = $this->redis();
        if( !$redis_key )
        {
            $redis_key = self::BOXES_KEY.mt_rand(0,10);
        }
        $ret = $redis->HGETALL( $redis_key );
        if( $ret )
        {
            return $ret;
        }
        $response = $this->phpClient('Boxes')->getData($where, 'box_key,video_id', 'all' );
        if (\PHPClient\Text::hasErrors($response)) {
            return [];
        }

        //这里要放一个假数据 防止 缓存穿透
        $new_data  = [ 'test_video' => 1 ];
        //重新拼接数组
        if( $response )
        {
            foreach( $response as $val )
            {
                $new_data[ $val['video_id'] ] = $val['box_key'] ;
            }
        }
        $redis->HMSET( $redis_key, $new_data );
        $redis->EXPIRE( $redis_key, 300 );

        return $new_data;
    }

}