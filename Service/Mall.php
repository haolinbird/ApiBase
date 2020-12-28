<?php
/**
 * Created by PhpStorm.
 * User: liuzhonghao
 * Date: 2019/5/13
 * Time: 下午4:58
 */

namespace Service;


class Mall extends ServiceBase
{
    const costNoExchange = 0; // 未兑换
    const succExchange = 1; // 兑换成功
    const exchanging = 2; // 兑换中
    const failExchange = 3; // 兑换失败
    const returnSycee = 4;//退还元宝


    /**
     * @param $uid
     * @param $orderInfo
     * @param $addressID
     * @param $ID
     * @return array
     * @throws \RpcBusinessException
     */
    public function exchange($uid, $orderInfo, $addressID, $ID)
    {
        file_put_contents('/home/www/logs/Test5.log',var_export(['新开始' => '--------' . $uid . '---------' . 'mtime :' . microtime(true )],true), FILE_APPEND);
        file_put_contents('/home/www/logs/Test5.log',var_export(['开始' => '--------' . $uid . '---------'],true), FILE_APPEND);
        file_put_contents('/home/www/logs/Test5.log',var_export( ['id'=>$ID],true), FILE_APPEND);

        //根据ID查询shuabao_balance_sycee_goods记录
        $getNowDeductionInfo = $this->phpClient('Mall')->getInfoByID($ID, $uid);

        $id = $getNowDeductionInfo['id'];
        $hash = $getNowDeductionInfo['hash_id'];
        $sku = $getNowDeductionInfo['sku'];
        $jumeiPrice = $getNowDeductionInfo['product_price'];
        file_put_contents('/home/www/logs/Test5.log',var_export( ['记录'=>$getNowDeductionInfo],true), FILE_APPEND);


        if ($getNowDeductionInfo['status'] != 0 || $getNowDeductionInfo['uid'] != $uid || $id != $ID || !empty($getNowDeductionInfo['warehouse']) || !empty($getNowDeductionInfo['order_id'])) {
            file_put_contents('/home/www/logs/Test5.log',var_export( ['数据对比'=>[$uid, $orderInfo, $addressID, $ID,$getNowDeductionInfo]],true), FILE_APPEND);
            return [
                'error' => true,
                'msg' => '没有参与资格'
            ];
        }
        file_put_contents('/home/www/logs/Test5.log',var_export( ['获取配置'],true), FILE_APPEND);
        //判断当前售卖数量
        $mallProductItems = \Config\Common::$mallProductItems;
        file_put_contents('/home/www/logs/Test5.log',var_export( ['配置'=>$mallProductItems],true), FILE_APPEND);

        foreach ($mallProductItems['item'] as $data){
            if ($data['item_id'] == $hash) {
                $number = $data['number'];
            }
        }
        $goodsMountRedisNumber = $this->phpClient('Mall')->getMallProductNumber($hash);//现在兑换数量
        file_put_contents('/home/www/logs/Test5.log',var_export( ['当前售卖数量'=>$goodsMountRedisNumber, '上限'=>$number],true), FILE_APPEND);
        if($number <= $goodsMountRedisNumber){
            //退还用户元宝
            if (!in_array($getNowDeductionInfo['status'], [self::succExchange, self::returnSycee])) {
                $this->phpClient('Mall')->fixExchangeByUidID($id, $uid);
            }
            return [
                'error' => true,
                'msg' => '商品库存不足，已退还元宝啦！',
                'desc' => '商品库存不足，已退还元宝啦！',
            ];
        }
        file_put_contents('/home/www/logs/Test5.log',var_export( ['个人最后一条获取'=>$getNowDeductionInfo, '当前售卖数量'=>$goodsMountRedisNumber, '上限'=>$number],true), FILE_APPEND);

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
        file_put_contents('/home/www/logs/Test5.log',var_export( ['创建聚美订单参数'=>$orderInfo, '结果'=>$orderRes, 'mtime :' => microtime(true )],true), FILE_APPEND);

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
        file_put_contents('/home/www/logs/Test5.log',var_export( ['聚美订单号更新到参数'=>[$id, $uid, $hash, $sku, $orderRes['order_id'], self::exchanging], '结果'=>$updateOrderIDRes, 'mtime :' => microtime(true )],true), FILE_APPEND);

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
        try{

            $callbackRes = \PHPClient\Text::inst('JMOrderSystem')->setClass('Payment')->callback($paymenInfo);
            file_put_contents('/home/www/logs/Test5.log',var_export( ['支付回调参数'=>$paymenInfo, '结果'=>$callbackRes, 'mtime :' => microtime(true )],true), FILE_APPEND);
        }catch (\Exception $e) {
            file_put_contents('/home/www/logs/Test5.log',var_export( ['支付回调参数'=>$paymenInfo, '结果'=> $e->getMessage(), 'mtime :' => microtime(true )],true), FILE_APPEND);
            return [];
        }

        //file_put_contents('/home/www/logs/Test5.log',var_export( ['直接返回结果' => $orderRes['batch_trade_number']],true), FILE_APPEND);
        //return $orderRes['batch_trade_number'];

        if ($callbackRes !== true) {
            $updateFailExchangeRes = $this->phpClient('Mall')->updateExchangeInfoByID($id, $uid, $hash, $sku, $orderRes['order_id'], ['status'=> self::failExchange, 'warehouse'=> $updateOrderIDRes['warehouse']]);
            file_put_contents('/home/www/logs/Test5.log',var_export( ['修改失败状态参数'=>[$id, $uid, $hash, $sku, $orderRes['order_id'], self::failExchange, $updateOrderIDRes['warehouse']], '结果'=>$updateFailExchangeRes, 'mtime :' => microtime(true )],true), FILE_APPEND);


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
        file_put_contents('/home/www/logs/Test5.log',var_export( ['修改成功状态'=>[$id, $uid, $hash, $sku,  $orderRes['order_id'], self::succExchange], '结果'=>$updateSuccExchangeRes, 'mtime :' => microtime(true )],true), FILE_APPEND);

        if (\PHPClient\Text::hasErrors($updateSuccExchangeRes)) {
            \Util\Log\Service::getInstance()->phpClientLog('shuabao', 'Mall', 'updateExchangeInfoByID', func_get_args(), $updateSuccExchangeRes);
        }

        return [
            'jm_order_id' => $orderRes['order_id'],
        ];
    }

    /**
     * @param $gameType
     * @param $uid
     * @return int
     * @throws \RpcBusinessException
     */
    public function setExchangeLimitByIdUID($id, $uid)
    {
        $redis = $this->redisSharding('default', $uid);
        $cacheKey = 'Shuabao_Exchange_Balance_goods_' . date("Ymd");
        $sonKey = $id . '_' . $uid;
        $redis->HSET($cacheKey, $sonKey, 1);//拿到返回值 覆盖默认值
        $liveTime = $redis->TTl($cacheKey);
        if($liveTime <= 0){
            //设置当天有效
            $redis->expire($cacheKey, 86400);
        }
        return 1;
    }

    /**
     * 根据ID获取用户当前兑换操作流程
     * @param $id
     * @param $uid
     * @return mixed
     */
    public function getExchangeLimitByIdUID($id, $uid)
    {
        $redis = $this->redisSharding('default', $uid);
        $cacheKey = 'Shuabao_Exchange_Balance_goods_' . date("Ymd");
        $sonKey = $id . '_' . $uid;

        $grade = $redis->HGET($cacheKey, $sonKey);
        return $grade;
    }

    /**
     * @param $uid
     * @throws \RpcBusinessException
     */
    public function getExchangeListByUid($uid)
    {
        $TEXT_DESC = \Module\Mall::$TEXT_DESC;//文字描述
        $mallProductItems = \Config\Common::$mallProductItems;//商品配置信息

        $getExchangeListByUid = $this->phpClient('Mall')->getExchangeListByUid($uid);
        $info = [];
        if ($getExchangeListByUid) {
            foreach ($getExchangeListByUid as $key => $val) {
                $info[$key]['id'] = $val['id'];
                if ($val['status'] == self::costNoExchange) {
                    $info[$key]['text_desc'] = $TEXT_DESC[$val['status']];
                    $info[$key]['is_style'] = 1;//前端展示样式
                    $info[$key]['jump_url'] = \Config\Common::$domainForH5 . 'mall_confirm_address?id=' . $val['id'];
                }
                if ($val['status'] == self::returnSycee) {
                    $info[$key]['text_desc'] = $TEXT_DESC[$val['status']];
                    $info[$key]['jump_url'] = '';
                }
                if (in_array($val['status'],  [self::succExchange, self::exchanging, self::failExchange])) {
                    $info[$key]['text_desc'] = $TEXT_DESC[$val['status']];
                    $info[$key]['jump_url'] = 'jumeimall://page/account/order/type?category=all';
                    $info[$key]['order_info'] = '兑换单号： ' . $val['order_id'];
                }

                foreach ($mallProductItems['item'] as $data){
                    if ($data['item_id'] == $val['hash_id']) {
                        $info[$key]['title'] = $data['title'];
                        $info[$key]['image_url'] = $data['image_url'];
                        $info[$key]['price'] = $data['original_price'];
                        $info[$key]['sycee_price'] = $data['sycee_price'];
                    }
                }
            }

        }
        return $info ? : [];
    }

    /**
     * @param $id
     * @param $uid
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function fixExchangeByUidID($id, $uid)
    {
        $fixExchangeResult = $this->phpClient('Mall')->fixExchangeByUidID($id, $uid);
        if (\PHPClient\Text::hasErrors($fixExchangeResult)) {
            $this->RpcBusinessException($fixExchangeResult['message'], $fixExchangeResult['code']);
        }
        return $fixExchangeResult;
    }
}