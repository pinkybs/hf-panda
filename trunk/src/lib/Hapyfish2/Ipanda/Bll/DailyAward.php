<?php

class Hapyfish2_Ipanda_Bll_DailyAward
{

	public static $_mcKeyPrex = 'i:u:dlyaward:';
	public static $_mcGainAwardAll = 'ipanda:dlyaward:cntall';

	public static function getDailyAwardsVoData()
    {
        $list = Hapyfish2_Ipanda_Cache_Basic_Extend::getDailyAwardList();
        $rstVo = array();
        foreach ($list as $data) {
            $rstVo[] = array('day' => $data['id'], 'awards' => $data['base_award'], 'fansaward' => $data['fans_award']);
        }

        $result = array('signAwardClass' => $rstVo);
        return json_encode($result);
    }

    /**
     * check user task info
     *
     * @param integer $uid
     * @param integer $activeDays
     * @return array
     */
    public static function getAwards($uid, $activeDays)
    {
    	$result = array();
    	$result['signDay'] = -1;//[int] -1 表示领完了 0 是无奖励  1是连续一天登陆
    	$result['isfans'] = false;
    	$today = date('Ymd');
    	
        //has gained today's awards
    	$mckey = self::$_mcKeyPrex . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $dailyAward = $cache->get($mckey);//$dailyAward['date'], $dailyAward['award'], $dailyAward['gained']
		if ($dailyAward && $dailyAward['award'] && $dailyAward['dt'] == $today && $dailyAward['gained']) {
			return $result;
		}

    	//is app fans
    	$context = Hapyfish2_Util_Context::getDefaultInstance();
		$puid = $context->get('puid');
		$session_key = $context->get('session_key');
		$rest = Taobao_Rest::getInstance();
		$rest->setUser($puid, $session_key);
		$isFan = $rest->isFan();
		//$isFan = true;
		$result['isfans'] = $isFan ? true : false;

    	//today gained user count
    	$basCache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
    	$rowGainAll = $basCache->get(self::$_mcGainAwardAll);
    	if (!$rowGainAll) {
    	    $rowGainAll = array('dt' => $today, 'cnt' => 0);
            $basCache->set(self::$_mcGainAwardAll, $rowGainAll);
    	}
    	if ($rowGainAll['dt'] != $today) {
    	    //info_log($rowGainAll['dt'].'|'.$rowGainAll['cnt'], 'dailyAwardGainCount');
    	    $rowGainAll = array('dt' => $today, 'cnt' => 0);
    	    $basCache->set(self::$_mcGainAwardAll, $rowGainAll, 86400);
    	}
    	$result['signAwardNumber'] = $rowGainAll['cnt'];

		if ($dailyAward && $dailyAward['award'] && $dailyAward['dt'] == $today) {
            $result['signDay'] = $activeDays;
            if (0 == $activeDays) {
                $result['signDay'] = -1;
                $dailyAward['gained'] = 1;
                $cache->set($mckey, $dailyAward, 86400);
            }
		    return $result;
		}

		//generate today's award items
		$aryAward = array();
		if ($activeDays > 0) {
		    $awardId = $activeDays > 5 ? 5 : $activeDays;
		    $awardInfo = Hapyfish2_Ipanda_Cache_Basic_Extend::getDailyAwardInfo($awardId);
		    $aryAward = array('base_award' => $awardInfo['base_award']);
		    if ($result['isfans']) {
		        $aryAward['fans_award'] = $awardInfo['fans_award'];
		    }
		}

		$dailyAward = array('dt' => $today, 'award' => $aryAward, 'gained' => 0);
        //0 $activeDays user has viewed the page
        if (0 == $activeDays) {
            $result['signDay'] = -1;
            $dailyAward['gained'] = 1;
        }
        else {
            $result['signDay'] = $activeDays;
        }
        $cache->set($mckey, $dailyAward, 86400);

    	return $result;
    }

	/**
     * gain awards
     *
     * @param integer $uid
     * @return array
     */
    public static function gainAwards($uid)
    {
    	$resultVo = array();

		$today = date('Ymd');
    	$mckey = self::$_mcKeyPrex . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $dailyAward = $cache->get($mckey);//$dailyAward['date'], $dailyAward['award'], $dailyAward['gained']
        if (empty($dailyAward)) {
        	return self::_retErrCode('-1001');
        }
        if ($dailyAward && $dailyAward['award'] && $dailyAward['dt'] != $today) {
			return self::_retErrCode('-1002');
		}
        if ($dailyAward && $dailyAward['award'] && $dailyAward['dt'] == $today && $dailyAward['gained']) {
			return self::_retErrCode('-1003');
		}

		$isSend = false;
		$love = $gold = 0;
		$cardList = array();
		$materialList = array();
		$decorateList = array();
		if (isset($dailyAward['award']['base_award'])) {
            $baseAward = $dailyAward['award']['base_award'];
			foreach ($baseAward as $item) {
				if ($item['type'] == 1) {
					//爱心
					$love += $item['num'];
				} else if ($item['type'] == 2) {
					//金币
					$gold += $item['num'];
				} else if ($item['type'] == 3) {
					//道具
					$cardList[] = array($item['cid'], $item['num']);
				} else if ($item['type'] == 4) {
					//材料
					$materialList[] = array($item['cid'], $item['num']);
				} else if ($item['type'] == 5) {
					//装饰
					$decorateList = array($item['cid'], $item['num']);
				}
			}
		}
		
        if (isset($dailyAward['award']['fans_award'])) {
            $fansAward = $dailyAward['award']['fans_award'];
        	foreach ($fansAward as $item) {
				if ($item['type'] == 1) {
					//爱心
					$love += $item['num'];
				} else if ($item['type'] == 2) {
					//金币
					$gold += $item['num'];
				} else if ($item['type'] == 3) {
					//道具
					$cardList[] = array($item['cid'], $item['num']);
				} else if ($item['type'] == 4) {
					//材料
					$materialList[] = array($item['cid'], $item['num']);
				} else if ($item['type'] == 5) {
					//装饰
					$decorateList = array($item['cid'], $item['num']);
				}
			}
		}

        $sendAward = new Hapyfish2_Ipanda_Bll_Award();
        if ($love > 0) {
            $sendAward->setLove($love);
            $isSend = true;
        }
        if ($gold) {
            $sendAward->setGold($gold, 2);
            $isSend = true;
        }
        if (count($cardList) > 0) {
            $sendAward->setCardList($cardList);
            $isSend = true;
        }
        if (count($materialList) > 0) {
            $sendAward->setMaterialList($materialList);
            $isSend = true;
        }
        if (count($decorateList) > 0) {
            $sendAward->setDecorateList($materialList);
            $isSend = true;
        }
        if ($isSend) {
            $rst = $sendAward->sendOne($uid);
        }

        $dailyAward['gained'] = 1;
        $cache->set($mckey, $dailyAward, 86400);

        //today gained user count ++
    	$basCache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
    	$rowGainAll = $basCache->get(self::$_mcGainAwardAll);
    	if (!$rowGainAll || $rowGainAll['dt'] != $today) {
    	    $rowGainAll = array('dt' => $today, 'cnt' => 0);
    	}
    	$rowGainAll['cnt'] += 1;
    	$basCache->set(self::$_mcGainAwardAll, $rowGainAll);

        return self::_retResult();
    }

	private static function _retErrCode($code)
	{
	    return array('status' => -1, 'errCode' => $code);
	}

    private static function _retResult($data = null)
	{
	    if ($data) {
	        return array('status' => 1, 'data' => $data);
	    }
	    else {
	        return array('status' => 1);
	    }
	}

}