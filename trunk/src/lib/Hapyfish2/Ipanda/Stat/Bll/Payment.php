<?php

class Hapyfish2_Ipanda_Stat_Bll_Payment
{
	public static function cal($day)
	{
		$begin = strtotime($day);
		$end = $begin + 86400;
		$amount = 0;
		$gold = 0;
		$count = 0;
        $costGold = 0;
        $userCount = 0;
        $uidTemp = array();
        $yearmonth = date('Ym', $begin);
        
		try {
			$dalPay = Hapyfish2_Ipanda_Stat_Dal_PaymentLog::getDefaultInstance();
			for ($i = 0; $i < DATABASE_NODE_NUM; $i++) {
				for ($j = 0; $j < 10; $j++) {
					$data = $dalPay->getPaymentLogData($i, $j, $begin, $end);
					if ($data) {
						foreach ($data as $row) {
							$amount += $row['amount'];
							$gold += $row['gold'];
							$count++;
                            if ( !isset($uidTemp[$row['uid']]) ) {
                                $userCount++;
                                $uidTemp[$row['uid']] = 1;
                            }
						}
					}
				
                    //岛钻消费信息
                    $goldData = $dalPay->getGold($i, $j, $yearmonth, $begin, $end);
                    if ( $goldData > 0 ) {
                        $costGold += $goldData;
                    }
				}
			}
			
            return array('amount' => $amount, 'gold' => $gold, 'count' => $count, 'costGold' => $costGold, 'userCount' => $userCount);
		} catch (Exception $e) {
            info_log('[pay]:'.$e->getMessage(), 'pay');
            
            return array('amount' => $amount, 'gold' => $gold, 'count' => $count, 'costGold' => $costGold, 'userCount' => $userCount, 'false' => 1);
		}
	}

	public function setPayList($day)
	{
        $begin = strtotime($day);
        $end = $begin + 86400;
        $amountList = array();
        $firstPayLevelList = array();
        $allPayLevelList = array();
        try {
            $dal = Hapyfish2_Ipanda_Stat_Dal_PaymentLog::getDefaultInstance();
            for ($i = 0; $i < DATABASE_NODE_NUM; $i++) {
                    //所有单笔交易充值额度排行，各充值额度有多少次（全程数据）
                    $amountData = $dal->getPayCountByAmount($i);
                    foreach ( $amountData as $val ) {
                        $amount = $val['amount'];
                        $count = $val['count'];
                        if ( isset($amountList[$amount]) ) {
                            $amountList[$amount] += $count;
                        }
                        else {
                            $amountList[$amount] = $count;
                        }
                    }
                    
                    //每天首次充值的玩家等级分布（每日数据）
                    $firstPayLevelData = $dal->getFirstPayLevel($i, $begin, $end);
                    foreach ( $firstPayLevelData as $fplVal ) {
                    	$level = $fplVal['user_level'];
                    	if ( isset($firstPayLevelList[$level]) ) {
                    		$firstPayLevelList[$level]++;
                    	}
                    	else {
                    		$firstPayLevelList[$level] = 1;
                    	}
                    }
                    
                    //所有等级玩家充值的次数和数量（全程数据）
                    $allPayLevelData = $dal->getAllPayLevel($i);
                    foreach ( $allPayLevelData as $aplVal ) {
                        $level = $aplVal['user_level'];
                        $count = $aplVal['count'];
                        $amount = $aplVal['amount'];
                        if ( isset($allPayLevelList[$level]) ) {
                            $allPayLevelList[$level]['count'] += $count;
                            $allPayLevelList[$level]['amount'] += $amount;
                        }
                        else {
                            $allPayLevelList[$level]['count'] = $count;
                            $allPayLevelList[$level]['amount'] = $amount;
                        }
                    }
                    
            }
            $amountList = json_encode($amountList);
            $firstPayLevelList = json_encode($firstPayLevelList);
            $allPayLevelList = json_encode($allPayLevelList);
        } catch (Exception $e) {
        }
        
        $newInfo = array('log_time' => $day, 
                         'amount_list' => $amountList, 
                         'first_pay_list' => $firstPayLevelList,
                         'all_pay_list' => $allPayLevelList);
        $dal->insertPayCountByAmount($newInfo);
        
        return $newInfo;
	}

    public static function getPayList($day)
    {
        $data = null;
        try {
            $dal = Hapyfish2_Ipanda_Stat_Dal_PaymentLog::getDefaultInstance();
            $data = $dal->getDayPaylist($day); 
        } catch (Exception $e) {
        }
        
        return $data;
    }
}