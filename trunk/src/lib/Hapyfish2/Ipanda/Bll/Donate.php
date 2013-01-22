<?php

class Hapyfish2_Ipanda_Bll_Donate
{

 	public static function createDonateId($puid)
    {
        //seconds 10 lens
        $ticks = str_replace('.', '', getmicrotime());

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

	public static function getDonate($uid, $donateid)
	{
		try {
			$dalDonate = Hapyfish2_Ipanda_Dal_Donate::getDefaultInstance();
			return $dalDonate->getDonate($uid, $donateid);
		} catch (Exception $e) {
		    info_log('getDonate-Err:'.$e->getMessage(), 'Bll_Donate_Err');
			return null;
		}
	}

    public static function createDonate($donateid, $donateitem, $uid, $amount, $gold, $tradeNo, $createTime)
    {

    	if ($amount <= 0) {
    		return false;
    	}

        //add db
		$info = array(
			'donateid' => $donateid,
			'donate_item' => $donateitem,
			'amount' => $amount,
			'gold' => $gold,
			'trade_no' => $tradeNo,
			'create_time' => $createTime,
			'uid' => $uid
		);

		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		$info['user_level'] = (int)$userLevel;
        try {
			$dalDonate = Hapyfish2_Ipanda_Dal_Donate::getDefaultInstance();
			$dalDonate->regDonate($uid, $info);
		} catch (Exception $e) {
			info_log('createDonate-Err:'.$e->getMessage(), 'Bll_Donate_Err');
			return false;
		}

		return true;
    }

    /**
     * complete donate order 完成订单
     *
     * @param  int $uid
     * @param  string $donateid
     * @return int [0-success 1-has already completed 2-not found 3-failed]
     */
    public static function completeDonate($uid, $donateid)
    {
        $donate = self::getDonate($uid, $donateid);

        if (empty($donate)) {
            return 2;
        }
        if ($donate['status'] != 0) {
            return 1;
        }

        $ok = false;
		//发宝石
		try {
		    $userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
		    $gold = $donate['gold'];
			/*$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->incGold($uid, $gold);
			Hapyfish2_Ipanda_HFC_User::reloadUserGold($uid);*/
			$ok = true;
		} catch (Exception $e) {
			info_log('[' . $uid . ':' . $donateid . ']' . $e->getMessage(), 'donate.err.confirm.1');
			return 3;
		}

		if ($ok) {
			$time = time();
			//更新订单状态
			$updateinfo = array('status' => 1, 'complete_time' => $time);

			$loginfo = array(
				'uid' => $uid, 'donateid' => $donateid,
				'amount' => $donate['amount'], 'gold' => $donate['gold'],
				'create_time' => $time, 'user_level' => $donate['user_level'],
				'pay_before_gold' => $userGold
			);
			try {
			    $dalDonate = Hapyfish2_Ipanda_Dal_Donate::getDefaultInstance();
				$dalDonate->completeDonate($uid, $donateid, $updateinfo);
				//更新捐赠记录
				$dalLog = Hapyfish2_Ipanda_Dal_DonateLog::getDefaultInstance();
				$dalLog->insert($uid, $loginfo);
			} catch (Exception $e) {
				info_log('[' . $uid . ':' . $donateid . ']' . $e->getMessage(), 'donate.err.confirm.2');
				return 3;
			}

			return 0;
		}

		info_log('[' . $uid . ':' . $donateid . ']' . 'completeDonateFailed', 'donate.err.confirm.3');
		return 1;
    }


    public static function listDonate($uid, $limit = 50)
	{
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_DonateLog::getDefaultInstance();
			return $dalLog->listDonate($uid, $limit);
		} catch (Exception $e) {

		}

		return null;
	}
}