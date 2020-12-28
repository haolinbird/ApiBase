<?php
/**
 * Created by PhpStorm.
 * User: shijian
 * Date: 2019/2/14
 * Time: 2:55 PM
 */
namespace Service;

use Log\Exception;

class Game extends ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'shuabao';
    public static $className = 'Game';

    const costNoExchange = 0; // 未兑换
    const succExchange = 1; // 兑换成功
    const exchanging = 2; // 兑换中
    const failExchange = 3; // 兑换失败

    /**
     * Get Instance.
     *
     * @return $this
     */
    public static function instance($sington = true)
    {
        return parent::instance($sington);
    }

    public function gameStart($uid, $gameType)
    {
        $response = $this->phpClient('Game')->gameStart($uid, ip2long(\JMSystem::GetClientIp()), $gameType);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function nextStage($gameHash, $uid, $stage, $timeConsume, $gameType)
    {
        $response = $this->phpClient('Game')->nextStage($gameHash, $uid, $stage, $timeConsume, $gameType);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function gameOver($gameHash, $uid, $gameType)
    {
        $response = $this->phpClient('Game')->gameOver($gameHash, $uid, $gameType);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    public function passList($uid, $gameType)
    {
        $redis = $this->redis();
        $cacheKey = 'Shuabao_Game_Pass_List' . $gameType;
        $passListJson = $redis->get($cacheKey);
        if (empty($passListJson)) {
            // 优化缓存, 为了防止大量并发搞数据库, 故加一个逻辑, 请求中先设置一个假数据缓存, 然后请求回来以后再进行刷新数据.
            $cacheArr = [
                ['nickname' => '无痕110','sycee_profit' => 5000],
                ['nickname' => '方圆几里','sycee_profit' => 5000],
                ['nickname' => 'JM138KCEA603Tf','sycee_profit' => 5000],
                ['nickname' => '天空之城','sycee_profit' => 5000],
                ['nickname' => 'h1wQBwJXGWp0','sycee_profit' => 5000],
            ];
            $redis->SETEX($cacheKey, 300, json_encode($cacheArr));

            // 从接口获取数据进行缓存.
            $response = $this->phpClient('Game')->passList($uid, $gameType);
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

    public function syceeSendTotal($gameType)
    {
        $redis = \Redis\RedisStorage::getInstance('default');
        $cacheKey = 'Shuabao_Game_Sycee_Send_Total';
        $syceeSendTotal = $redis->HGET($cacheKey, $gameType);

        return $syceeSendTotal ?: 0;
    }

    public function gameRemain($uid, $gameType)
    {
        $response = $this->phpClient('Game')->gameRemain($uid, $gameType);
        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 检查是否提现的测试用户.
     *
     * @param $uid
     *
     * @return int
     *
     * @throws \Exception
     */
    public function WithdrawABForGame($uid)
    {
        $isAb = 0;
        if (\Redis\RedisStorage::getInstance('default')->HEXISTS('user_withdraw_abtest', $uid)) {
            $isAb = 1;
        }
        return $isAb;
    }

    /**
     * @param $gameHash
     * @param $uid
     * @return mixed
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function gameReturnProfit($gameHash, $uid)
    {
        $response = $this->phpClient('Game')->gameReturnProfit($gameHash, $uid);

        if (\PHPClient\Text::hasErrors($response)) {
            $this->RpcBusinessException($response['message'], $response['code']);
        }

        return $response;
    }

    /**
     * 根据配置与通关率 获取当前用户口红机难度
     * @param $gameType
     * @param $uid
     * @return int
     * @throws \Exception
     * @throws \RpcBusinessException
     */
    public function getGameGradeByTameType($gameType, $uid)
    {
        $redis = $this->redisSharding('default', $uid);
        $cacheKey = 'Shuabao_Game_Get_Grade_' . date("Ymd") . '_' . $gameType;
        $grade = $redis->HGET($cacheKey, $uid);
        if ($grade === false){
            $grade = $this->phpClient('Game')->getGameGradeByTameType($gameType, $uid);
            if (\PHPClient\Text::hasErrors($grade)) {
                $this->RpcBusinessException($grade['message'], $grade['code']);
            }
            $redis->HSET($cacheKey, $uid, $grade);//拿到返回值 覆盖默认值
            $liveTime = $redis->TTl($cacheKey);
            if($liveTime <= 0){
                //设置当天有效
                $redis->expire($cacheKey, 86400);
            }
        }
        return $grade !== false ? $grade : 1;
    }

    public function getGameHash($gameType, $uid, $day)
    {
        $redis = $this->redisSharding('default', $uid);
        if($day) {
            $cacheKey = 'Shuabao_Game_Get_Grade_' . date("Ymd", strtotime("-$day days")) . '_' . $gameType;
        }else{
            $cacheKey = 'Shuabao_Game_Get_Grade_' . date("Ymd") . '_' . $gameType;
        }
        $liveTime = $redis->TTl($cacheKey);

        $grade = $redis->HGETALL($cacheKey);
        return [$liveTime,$grade];
    }

    public function exchange($uid, $orderInfo, $addressID, $ID)
    {

        file_put_contents('/home/www/logs/Test4.log',var_export(['新开始' => '--------' . $uid . '---------'],true), FILE_APPEND);
        file_put_contents('/home/www/logs/Test4.log',var_export(['开始' => '--------' . $uid . '---------'],true), FILE_APPEND);
        file_put_contents('/home/www/logs/Test4.log',var_export( ['id'=>$ID],true), FILE_APPEND);

        //查询用户最后一条shuabao_balance_sycee_goods记录
        $getNowDeductionInfo = $this->phpClient('Mall')->getInfoByID($ID, $uid);

        $id = $getNowDeductionInfo['id'];
        $hash = $getNowDeductionInfo['hash_id'];
        $sku = $getNowDeductionInfo['sku'];
        $jumeiPrice = $getNowDeductionInfo['product_price'];
        file_put_contents('/home/www/logs/Test4.log',var_export( ['记录'=>$getNowDeductionInfo],true), FILE_APPEND);

        /**
         *开始
         */
//        try{
//
//            $updateFailExchangeRes = $this->phpClient('Mall')->updateExchangeInfoByID($id, $uid, $hash, $sku, 123456, ['status'=> self::failExchange, 'warehouse'=> 'CD06']);
//        }catch (\Exception $e){
//            file_put_contents('/home/www/logs/Test4.log',var_export( ['结果'=>$e->getMessage()],true), FILE_APPEND);
//        }
//        file_put_contents('/home/www/logs/Test4.log',var_export( ['修改失败状态参数'=>[$id, $uid, $hash, $sku, 123456, self::failExchange, 'CD06'], '结果'=>$updateFailExchangeRes],true), FILE_APPEND);
//        return $updateFailExchangeRes;
        /**
         *结束
         */

        if ($getNowDeductionInfo['status'] != 0 || $getNowDeductionInfo['uid'] != $uid || $id != $ID || !empty($getNowDeductionInfo['warehouse'])) {
            file_put_contents('/home/www/logs/Test4.log',var_export( ['数据对比'=>[$uid, $orderInfo, $addressID, $ID,$getNowDeductionInfo]],true), FILE_APPEND);
            return [
                'error' => true,
                'msg' => '没有参与资格'
            ];
        }
        file_put_contents('/home/www/logs/Test4.log',var_export( ['获取配置'],true), FILE_APPEND);
        //判断当前售卖数量
        $mallProductItems = \Config\Common::$mallProductItems;
        file_put_contents('/home/www/logs/Test4.log',var_export( ['配置'=>$mallProductItems],true), FILE_APPEND);

        foreach ($mallProductItems['item'] as $data){
            if ($data['item_id'] == $hash) {
                $number = $data['number'];
            }
        }
        $goodsMountRedisNumber = $this->phpClient('Mall')->getMallProductNumber($hash);//现在兑换数量
        file_put_contents('/home/www/logs/Test4.log',var_export( ['当前售卖数量'=>$goodsMountRedisNumber, '上限'=>$number],true), FILE_APPEND);
        if($number <= $goodsMountRedisNumber){
            return [
                'error' => true,
                'msg' => '不能再执行兑换'
            ];
        }
        file_put_contents('/home/www/logs/Test4.log',var_export( ['个人最后一条获取'=>$getNowDeductionInfo, '当前售卖数量'=>$goodsMountRedisNumber, '上限'=>$number],true), FILE_APPEND);

        //判断是否已经进行过兑换操作 创建零元单
        $joinStatusByIDUid = $this->getExchangeLimitByIdUID($ID, $uid);
        if($joinStatusByIDUid){
            return [
                'error' => true,
                'msg' => '已经参与！'
            ];
        }

        // 创建聚美订单
        $orderInfo['items'] = [
            [
                'sku_no' => $sku, // 商品的sku_no
                'deal_hash_id' => $hash, // 商品的deal_hash_id
                'item_price' => $jumeiPrice, // 商品的单价
                'settlement_price' => 0, // 商品的结算价
                'quantity' => '1', // 商品的数量
                'from' => 'side_bar', // 商品来源于页面的位置标识
            ]
        ];
        $orderRes = \PHPClient\Text::inst('JMOrderSystem')->setClass('Api\Growth')->createZeroOrder($orderInfo);
        file_put_contents('/home/www/logs/Test4.log',var_export( ['创建聚美订单参数'=>$orderInfo, '结果'=>$orderRes],true), FILE_APPEND);

        if (\PHPClient\Text::hasErrors($orderRes)) {
            \Util\Log\Service::getInstance()->phpClientLog('JMOrderSystem', 'Api\Growth', 'createZeroOrder', func_get_args(), $orderRes);
            return [
                'error' => true,
                'msg' => '创建订单失败'
            ];
        }

        //参与加值
        $this->setExchangeLimitByIdUID($ID, $uid);

        // 将聚美订单号更新到shuabao_balance_sycee_goods表
        $updateOrderIDRes = $this->phpClient('Mall')->updateExchangeInfoByID($id, $uid, $hash, $sku, $orderRes['order_id'], ['status' => self::exchanging]);
        file_put_contents('/home/www/logs/Test4.log',var_export( ['聚美订单号更新到参数'=>[$id, $uid, $hash, $sku, $orderRes['order_id'], self::exchanging], '结果'=>$updateOrderIDRes],true), FILE_APPEND);

        if (\PHPClient\Text::hasErrors($updateOrderIDRes)) {
            \Util\Log\Service::getInstance()->phpClientLog('shuabao', 'Mall', 'updateExchangeInfoByID', func_get_args(), $updateOrderIDRes);
            return [
                'error' => true,
                'msg' => '更新订单失败'
            ];
        }
        // 支付回调
        $paymenInfo = [
            'payment_method' => $orderInfo['payment_method'], // 0元单写死
            'batch_trade_number' => $orderRes['batch_trade_number'], // 创建订单成功返回的批次号
            'sub_type' => 'topup', // 写死
            'amount' => '0.00', // 0元单当然是0咯
            'outer_ref_id' => $orderRes['order_id'],  // 这个用交易单号
            'order_id' => '0',
            'gateway_notify_text' => '',
            'channel_id' => 21,
            'payment_time' => time(),
            'buyer_sign' => '',
            'buyer_payment_method' => $orderInfo['payment_method'],  // 0元单写死
            'user_id' => $orderInfo['uid'],  // UID
            'order_ids' => [
                0 => $orderRes['order_id'], // 交易单号
            ],
            'batch_paymethod' => $orderInfo['payment_method'],  // 0元单写死
            'filter_flags' => [],
            'gateway_buyer' => [],
        ];
        $callbackRes = \PHPClient\Text::inst('JMOrderSystem')->setClass('Payment')->callback($paymenInfo);
        file_put_contents('/home/www/logs/Test4.log',var_export( ['支付回调参数'=>$paymenInfo, '结果'=>$callbackRes],true), FILE_APPEND);

        if ($callbackRes !== true) {
            $updateFailExchangeRes = $this->phpClient('Mall')->updateExchangeInfoByID($id, $uid, $hash, $sku, $orderRes['order_id'], ['status'=> self::failExchange, 'warehouse'=> $updateOrderIDRes['warehouse']]);
            file_put_contents('/home/www/logs/Test4.log',var_export( ['修改失败状态参数'=>[$id, $uid, $hash, $sku, $orderRes['order_id'], self::failExchange, $updateOrderIDRes['warehouse']], '结果'=>$updateFailExchangeRes],true), FILE_APPEND);


            if (\PHPClient\Text::hasErrors($updateFailExchangeRes)) {
                \Util\Log\Service::getInstance()->phpClientLog('shuabao', 'Mall', 'updateExchangeInfoByID', func_get_args(), $updateFailExchangeRes);
            }
            \Util\Log\Service::getInstance()->phpClientLog('JMOrderSystem', 'Payment', 'callback', func_get_args(), $callbackRes);

            return [
                'error' => true,
                'msg' => '支付回调失败'
            ];
        }
        $updateSuccExchangeRes = $this->phpClient('Mall')->updateExchangeInfoByID($id, $uid, $hash, $sku,  $orderRes['order_id'], ['status'=> self::succExchange]);
        file_put_contents('/home/www/logs/Test4.log',var_export( ['修改成功状态'=>[$id, $uid, $hash, $sku,  $orderRes['order_id'], self::succExchange], '结果'=>$updateSuccExchangeRes],true), FILE_APPEND);

        if (\PHPClient\Text::hasErrors($updateSuccExchangeRes)) {
            \Util\Log\Service::getInstance()->phpClientLog('shuabao', 'Mall', 'updateExchangeInfoByID', func_get_args(), $updateSuccExchangeRes);
        }

        return [
            'jm_order_id' => $orderRes['order_id'],
        ];
    }

}