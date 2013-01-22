<?php

class Hapyfish2_Ipanda_Bll_Payment
{

 	public static function createPayOrderId($puid)
    {
        //seconds 10 lens
        $ticks = time();

        //server id, 1 lens 0~9
        if (defined('SERVER_ID')) {
            $serverid = SERVER_ID;
        } else {
            $serverid = '0';
        }

        //max 9 lens
        //$this->user_id
        return $ticks . '_' . $serverid . '_' . $puid;
    }

	public static function getOrder($uid, $orderid)
	{
		try {
			$dalPayOrder = Hapyfish2_Ipanda_Dal_PayOrder::getDefaultInstance();
			return $dalPayOrder->getOrder($uid, $orderid);
		} catch (Exception $e) {
		    info_log('getOrder-Err:'.$e->getMessage(), 'Bll_Payment_Err');
			return null;
		}
	}

    public static function createOrder($orderId, $uid, $amount, $gold, $tradeNo, $createTime)
    {
//        if ($amount < 10) {
//            return false;
//        }

    	if ($amount <= 0) {
    		return false;
    	}

        //add db
		$info = array(
			'orderid' => $orderId,
			'amount' => $amount,
			'gold' => $gold,
			'trade_no' => $tradeNo,
			'order_time' => $createTime,
			'uid' => $uid
		);

		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		$info['user_level'] = (int)$userLevel;
        try {
			$dalPayOrder = Hapyfish2_Ipanda_Dal_PayOrder::getDefaultInstance();
			$dalPayOrder->regOrder($uid, $info);
		} catch (Exception $e) {
			info_log($e->getMessage(), 'create-payorder-err');
			return false;
		}

		return true;
    }

    public static function completeOrder($uid, $order)
    {
        $dalPayOrder = Hapyfish2_Ipanda_Dal_PayOrder::getDefaultInstance();

        $ok = false;
		//发宝石
		try {
		    $userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
		    $orderid = $order['orderid'];
		    $gold = $order['gold'];
			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->incGold($uid, $gold);
			Hapyfish2_Ipanda_HFC_User::reloadUserGold($uid);
			$ok = true;
		} catch (Exception $e) {
			info_log('[' . $uid . ':' . $orderid . ']' . $e->getMessage(), 'payment.err.completeorder.1');
			return 1;
		}

		if ($ok) {
			$time = time();
			
			//更新订单状态
			$updateinfo = array('status' => 1, 'complete_time' => $time);
			try {
				$dalPayOrder->completeOrder($uid, $orderid, $updateinfo);
			} catch (Exception $e) {
				info_log('[' . $uid . ':' . $orderid . ']' . $e->getMessage(), 'payment.err.completeorder.2');
			}
			
			$loginfo = array(
				'uid' => $uid,
				'orderid' => $orderid,
				'pid' => $order['trade_no'],
				'amount' => $order['amount'],
				'gold' => $gold,
				'create_time' => $time,
				'user_level' => $order['user_level'],
				'pay_before_gold' => $userGold,
				'summary' => $order['amount'] . 'RMB购买' . $gold . '金币。'
			);

			$content = self::chargeGift($uid, $order['amount']);

			$loginfo['summary'] = $loginfo['summary'] . $content;
			
			try {
				//加入充值记录
				$dalPaymentLog = Hapyfish2_Ipanda_Dal_PaymentLog::getDefaultInstance();
				$dalPaymentLog->insert($uid, $loginfo);
			} catch (Exception $e) {
				info_log('[' . $uid . ':' . $orderid . ']' . $e->getMessage(), 'payment.err.completeorder.3');
			}

			return 0;
		}

		info_log('[' . $uid . ':' . $orderid . ']' . 'completeOrderFailed', 'payment.err.completeorder.4');
		return 1;
    }
    
    private static function getInclude($uid, $amount)
    {
    	$include = null;
   	    $settingInfo = Hapyfish2_Ipanda_Bll_Paysetting::getInfo($uid);
        if ($settingInfo) {
       		$section = $settingInfo['section'];
       		if (!empty($section)) {
       		    foreach ($section as $item) {
	        		if ($item['amount'] == $amount) {
	        			$include = $item['include'];
	        			break;
	        		}
	        	}
       		}
       	}
       	
       	return $include;
    }

    public static function chargeGift($uid, $amount)
	{
	    $content = '';
	    
       	$include = self::getInclude($uid, $amount);
       	if ($include != null && !empty($include)) {
       		$award = new Hapyfish2_Ipanda_Bll_Award();
       		foreach ($include as $item) {
       			if ($item['cid'] > 0) {
					$award->setSomething($item['cid'], $item['num']);
       			}
       		}
       		$award->sendOne($uid);
       		$content = $award->getContent($uid);
       		if ($content) {
       			$content = '送' . join(',', $content);
       		} else {
       			$content = '';
       		}
       		info_log($uid . ':' . json_encode($include), 'pay-include');
       	}

	    return $content;
	}
}