<?php
namespace Service;
/**
 * Created by PhpStorm.
 * User: shangyuh
 * Date: 2018/11/04
 * Time: 上午17:00
 */
class PromoCard extends \Service\ServiceBase
{
    /**
     * 服务标识.
     *
     * @var string
     */
    protected static $serviceName = 'PromoCard';

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
     * 注册送券.
     *
     * @param integer $uid 用户uid.
     *
     * @return boolean
     */
    public function registeredPromoCard($uid)
    {
        try {
            if (empty(\Config\Common::$isRegisteredPromoCard)) {
                return false;
            }
            $this->phpClient("PromoCardSharding")->newUserCoupon($uid);
        } catch (\Exception $e) {
            // 暂不处理.
            \Util\Log\Service::getInstance()->phpClientLog(self::$serviceName, 'PromoCardSharding', 'newUserCoupon', func_get_args(), $e->getMessage());
        }
        return true;
    }

    /**
     * 根据用户uid和站点获取用户绑定信息.
     *
     * @param integer $uid      用户uid.
     * @param string  $siteName 三方站点.
     *
     * @return mixed
     */
    public function getUserExtConnectInfo($uid, $siteName)
    {
        return $this->phpClient('ExtConnect')->getUserConnectedSisteInfo($uid, $siteName);
    }

    /**
     * 根据兑换码获取兑换码详细信息
     * 文档:http://wiki.int.jumei.com/index.php?title=RedeemCode_service#getCardInfoByCardNo
     * @param $cardNo
     * @return mixed
     * @throws \RpcBusinessException
     */
    public function getCardInfoByCardNo($cardNo)
    {
        return $this->phpClient('RedeemCode')->getCardInfoByCardNo($cardNo);
    }

    /**
     * 发放现金券
     * @param int $plan_id 方案ID
     * @param int $uid 用户ID
     * @param int $retrieval_source_parent_id 父订单ID
     * @param array $retrieval_source_ref_id 子订单ID
     * @return array
     */
    public function sendPromoCard($plan_id, $uid, $retrieval_source_parent_id = 0, Array $retrieval_source_ref_id = array())
    {
        $promo_result = [
            'errcode' => 10001,
            'errmsg' => 'uid or plan_id is empty!'
        ];
        if (!empty($uid) && !empty($plan_id)) {
            $card_infos = [
                'uid' => $uid,
                'retrieval_source_parent_id' => $retrieval_source_parent_id,
                'retrieval_source_ref_id' => $retrieval_source_ref_id,
            ];
            //发放现金券
            $promo_result = $this->createCardsToDbByPlan($plan_id, $card_infos);
        }
        return $promo_result;
    }

    /**
     * 批量发放现金券
     * @param array $plans 现金券方案
     * @param int $uid 用户ID
     * @return array
     */
    public function batchSendPromoCard(Array $plans, $uid)
    {
        $promo_result = [];
        if (!empty($uid) && !empty($plans) && is_array($plans)) {
            foreach ($plans as $plan) {
                $card_infos = [
                    'uid' => $uid,
                    'retrieval_source_parent_id' => isset($plan['retrieval_source_parent_id'])
                        ? $plan['retrieval_source_parent_id'] : 0,
                    'retrieval_source_ref_id' => isset($plan['retrieval_source_ref_id'])
                        ? $plan['retrieval_source_ref_id'] : array(),
                ];
                //发放现金券
                $promo_result[] = $this->createCardsToDbByPlan($plan['plan_id'], $card_infos);
            }
        } else {
            $promo_result = [
                [
                    'errcode' => 10001,
                    'errmsg' => 'plans or uid is error!'
                ]
            ];
        }
        return $promo_result;
    }

    /**
     * @param int $plan_id 方案ID
     * @param array $card_infos 现金券信息数组
     * @return mixed
     */
    protected function createCardsToDbByPlan($plan_id, $card_infos)
    {
        $config = \Config\Common::$promoCardConfig;
        if( !isset( $config ) )
        {
            return [];
        }

        $token = md5($config['signname'] . $plan_id . json_encode($card_infos) . $config['secret']);
        try {

            $result = call_user_func(
                [$this->phpClient("PromoCardSharding"), 'createCardsToDbByPlan'],
                $config['signname'],
                $plan_id,
                $card_infos,
                $token
            );
        } catch (\Exception $exception) {
            return [];
        }
        return $result;
    }

    /**
     * 判断用户是否有特定批次号的券
     * @param $uid
     * @param $batch_no
     * @return bool
     */
    public function checkHasBatchNoCard($uid, $batch_no)
    {
        $result = call_user_func(
            [$this->phpClient("PromoCardSharding"), 'checkHasBatchNoCard'],
            $uid,
            $batch_no
        );
        return $result['errcode'] == 0 ? $result['data'] : true;
    }

    /**
     * 根据planId获取对应详情
     * @param $plan_id
     * @return array
    array (
    'id' => '901040',
    'depart_sign' => 'growth',
    'plan_name' => '增长业务测试2',
    'status' => 'pass',
    'create_explain' => 'test',
    'verify_explain' => 'test',
    'update_explain' => '',
    'create_user' => 'test',
    'update_user' => '',
    'verify_user' => 'test',
    'infos' =>
    array (
    0 =>
    array (
    'promocard_type' => 'normal',
    'effect_params' => '1.00',
    'minimal_order_amount' => '199.00',
    'effect_method' => 'rmb_off',
    'batch_no' => 'growth_test_20170207',
    'scope_id' => '8137',
    'enable_time' => '1486448249',
    'expire_time' => '1486742399',
    'usage_limit' => '1',
    'description' => 'test',
    'retrieval_source' => 'test',
    'number' => '1',
    'date_type' => 'stamp',
    'prefix' => '',
    'batch_category_id' => '10',
    ),
    1 =>
    array (
    'promocard_type' => 'normal',
    'effect_params' => '1.00',
    'minimal_order_amount' => '100.00',
    'effect_method' => 'rmb_off',
    'batch_no' => 'growth_duobaotest_20170207',
    'scope_id' => '11073',
    'enable_time' => '1486448328',
    'expire_time' => '1486742399',
    'usage_limit' => '1',
    'description' => 'test',
    'retrieval_source' => 'test',
    'number' => '1',
    'date_type' => 'stamp',
    'prefix' => '',
    'batch_category_id' => '5',
    ),
    ),
    )
     */
    public function getPromoCardPlanById($plan_id)
    {
        try {
            $res = call_user_func(
                [$this->phpClient("CardSystem"), 'getPromocardPlanById'],
                $plan_id
            );

            if ($res['errcode'] == 0) {
                return $res['data'];
            } else {
                return [];
            }
        } catch (\Exception $exception) {
            return [];
        }
    }

}