<?php

class Hapyfish2_Ipanda_Event_Bll_Christmas 
{
	const EndTime = 1325433599;
	const RateMin = 1;
	const RateMax = 3;
	const Hit = 3;
	const HitInterval = 5;
	const Bcid = 2145;
	const Max = 400;
	const Type = 'Christmas';
	const Level = 3;
	public static function getInit($uid)
	{
		$num = self::getUserBell($uid);
		$status = Hapyfish2_Ipanda_Event_Cache_Christmas::getUserstatus($uid);
		$date = date('Ymd');
		$list = array();
		if($status['date'] == $date){
			$list = $status['list'];
		}else{
			$list[] = array('id'=>1, 'status'=> 0);
			$list[] = array('id'=>2, 'status'=> 0);
			$list[] = array('id'=>3, 'status'=> 0);
			$list[] = array('id'=>4, 'status'=> 0);
			$list[] = array('id'=>5, 'status'=> 0);
			$data['date'] = $date;
			$data['list'] = $list;
			Hapyfish2_Ipanda_Event_Cache_Christmas::updateUserstatus($uid, $data);
		}
		
		return array('num'=>$num, 'list'=> $list);
	}
	//玩家铃铛数
	public static function getUserBell($uid)
	{
		$cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
		$bell = 0;
		if($cards){
			foreach($cards as $cid => $v){
				if($cid == 2145){
					$bell = $v['count'];
					break;
				}
			}
		}
		return $bell;
	}
	//统计  1为掉落， 2 为赠送
	public static function bell($uid, $time)
	{
		$config = Hapyfish2_Ipanda_Event_Cache_Christmas::getConfig();
		if($time > self::EndTime){
			return null;
		}
//		$userVo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
		$userBell = Hapyfish2_Ipanda_Event_Cache_Christmas::getUserBell($uid);
		if($userBell['max'] >= $config['Max']){
			return null;
		}
		
//		if($userVo['level'] < self::Level){
//			return null;
//		}
		
		$userBellHit = $userBell['hit'];
		$diff = $time - $userBellHit['hitTime'];
		
		if($diff <= $config['HitInterval'] || $userBellHit['num'] == 0){
			$userBellHit['num'] +=1;
		}else{
			$userBellHit['num'] = 1;
		}
		$userBellHit['hitTime'] = $time;
		Hapyfish2_Ipanda_Event_Cache_Christmas::UpdatebellHit($uid, $userBellHit);
		if($userBellHit['num'] >= $config['Hit']){
			$rate = rand(1, $config['RateMax']);
			if($rate <= $config['RateMin']){
				$userBell['max'] += 1;
				Hapyfish2_Ipanda_Event_Cache_Christmas::UpdateMax($uid, $userBell['max'], $time);
				$ok = Hapyfish2_Ipanda_HFC_Card::addUserCard($uid, self::Bcid, 1);
				if($ok){
					$logger = Hapyfish2_Util_Log::getInstance();
					$info = array($uid, 1, 1);
					$logger->report('ChristmasGet', $info);
					return $ok;
				}
			}
		}
		return null;
	}
	
	public static function exchange($uid, $id)
	{
		$status = Hapyfish2_Ipanda_Event_Cache_Christmas::getUserstatus($uid);
		$date = date('Ymd');
		if($date == $status['date']){
			foreach($status['list'] as $k => $v){
				if($v['id'] == $id){
					$status['list'][$k]['status'] +=1;
					if($status['list'][$k]['status'] > 1 ){
						return false;
					}
					break;
				}
			}
		}else{
			$status['date'] = $date;
			$list[] = array('id'=>1, 'status'=> 0);
			$list[] = array('id'=>2, 'status'=> 0);
			$list[] = array('id'=>3, 'status'=> 0);
			$list[] = array('id'=>4, 'status'=> 0);
			$list[] = array('id'=>5, 'status'=> 0);
			$status['list'] = $list;
		}
		$type = self::Type;
		$ok = Hapyfish2_Ipanda_Event_Bll_Reward::get($uid, $id, $type);
		if($ok){
			$logger = Hapyfish2_Util_Log::getInstance();
			$info = array($uid, $id);
			$logger->report('ChristmasExchange', $info);
			Hapyfish2_Ipanda_Event_Cache_Christmas::updateUserstatus($uid, $status);
			return $ok;
		}
	}
}