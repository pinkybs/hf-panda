<?php

class Hapyfish2_Ipanda_Bll_ConsumeLog
{
	public static function love($uid, $cost, $summary, $time)
	{
		$info = array(
			'uid' => $uid,
			'cost' => $cost,
			'summary' => $summary,
			'create_time' => $time
		);
		
		$ok = false;
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_ConsumeLog::getDefaultInstance();
			$dalLog->insertLove($uid, $info);
			$ok = true;
		} catch (Exception $e) {
			
		}
		
		return $ok;
	}
	
	public static function getLove($uid, $year, $month, $limit = 50)
	{
		try {
			if ($month < 10) {
				$month = '0' . $month;
			}
			$yearmonth = $year . $month;
			$dalLog = Hapyfish2_Ipanda_Dal_ConsumeLog::getDefaultInstance();
			return $dalLog->getLove($uid, $yearmonth, $limit);
		} catch (Exception $e) {
			
		}
		
		return null;
	}
	
	public static function getGold($uid, $year, $month, $limit = 50)
	{
		try {
			if ($month < 10) {
				$month = '0' . $month;
			}
			$yearmonth = $year . $month;
			$dalLog = Hapyfish2_Ipanda_Dal_ConsumeLog::getDefaultInstance();
			return $dalLog->getGold($uid, $yearmonth, $limit);
		} catch (Exception $e) {
			
		}
		
		return null;
	}
}