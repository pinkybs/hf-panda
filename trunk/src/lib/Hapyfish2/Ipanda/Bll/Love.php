<?php

class Hapyfish2_Ipanda_Bll_Love
{
	public static function get($uid, $isVip = false, $cache = false)
	{
		$love = 0;
		if ($cache) {
			$love = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
		} else {
			try {
		    	$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
            	$love = $dalUser->getLove($uid);
			} catch (Exception $e) {
			}
		}

		if ($isVip) {
			return array('balance' => $love, 'is_vip' => 0);
		} else {
			return $love;
		}
	}

	public static function addConsumeLoveLog($uid, $info)
	{
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_ConsumeLog::getDefaultInstance();
			$dalLog->insertLove($uid, $info);
		} catch (Exception $e) {

		}
	}

	public static function consume($uid, $info)
	{
        $love = $info['cost'];

		try {
			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->declove($uid, $love);

			$ret = Hapyfish2_Ipanda_HFC_User::reloadUserLove($uid);
			if(empty($ret))
			{
				return false;
			}
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'love-consume-err');
			return false;
		}

		$info['create_time'] = time();
        self::addConsumeLoveLog($uid, $info);

        return true;
	}

	public static function add($uid, $info)
	{
		$love = $info['love'];

		if ($love <=0) {
			return false;
		}

		$ok = false;
		try {
			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->incGold($uid, $love);
			Hapyfish2_Ipanda_HFC_User::reloadUserLove($uid);
			$ok = true;
		} catch (Exception $e) {
			info_log($e->getMessage(), 'love-add');
		}

		if ($ok) {
			if (isset($info['type'])) {
				$type = $info['type'];
			} else {
				$type = 0;
			}

			if (isset($info['time'])) {
				$time = $info['time'];
			} else {
				$time = time();
			}

			$data = array('uid' => $uid, 'love' => $love, 'type' => $type, 'create_time' => $time);
			self::addLoveLog($uid, $data);
		}
		return $ok;
	}

	public static function addLoveLog($uid, $info)
	{
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_ConsumeLog::getDefaultInstance();
			$dalLog->insertLove($uid, $info);
		} catch (Exception $e) {

		}
	}
}