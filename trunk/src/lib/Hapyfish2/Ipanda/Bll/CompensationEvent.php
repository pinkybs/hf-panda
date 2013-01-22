<?php

class Hapyfish2_Ipanda_Bll_CompensationEvent
{
	public static function gain($uid, $id)
	{
		$award = new Hapyfish2_Ipanda_Bll_Award();
		$award->setDecorate(5221, 1);
		$ok = $award->sendOne($uid);
		if ($ok) {
			$time = time();
			$date = date('Ymd', $time);
			$info = array(
				'id' => $id,
				'uid' => $uid,
				'create_time' => $date
			);
			try {
				$dalCompensationLog = Hapyfish2_Ipanda_Dal_CompensationLog::getDefaultInstance();
				$dalCompensationLog->insert($uid, $info);
				
			} catch (Exception $e) {
				info_log($uid . ':' . $id, 'CompensationEvent_Gain');
			}
//			$feed = array(
//					'uid' => $uid,
//					'template_id' => 0,
//					'actor' => GM_UID_LELE,
//					'target' => $uid,
//					'type' => 9,
//					'title' => array('title' => '2012年 “金蛋”天天送活动,送你金蛋一颗'),
//					'create_time' => $time
//				);
//				Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($feed);
		}
	}

	public static function isGained($uid, $id, $date = 0)
	{
		$result = true;
		
		try {
			$dalCompensationLog = Hapyfish2_Ipanda_Dal_CompensationLog::getDefaultInstance();
			$data = $dalCompensationLog->getOne($uid, $id, $date);
			if ($data) {
				$result = true;
			} else {
				$result = false;
			}
		} catch (Exception $e) {

		}

		return $result;
	}
}