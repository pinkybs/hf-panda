<?php

class Hapyfish2_Ipanda_Event_Cache_Christmas
{
	public static function bellHit($uid)
	{
		$key = 'ipanda:c:e:h:b'.$uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
		if($data === false){
			$time =time();
			$data['num'] = 0;
			$data['hitTime'] = $time;
		}
		return $data;
	}
	
	public static function UpdatebellHit($uid, $userBellHit)
	{
		$key = 'ipanda:c:e:h:b'.$uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$cache->set($key, $userBellHit);
	}
	
	public static function UpdateMax($uid, $max, $time)
	{
		$key = 'ipanda:c:e:max'.$uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data['max'] = $max;
		$data['date'] = date('Ymd', $time);
		$cache->set($key, $data);
	}
	
	public static function getUerNum($uid)
	{
		$key = 'ipanda:c:e:max'.$uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
		if($data === false){
			return null;
		}
		return $data;
	}
	public static function getUserBell($uid)
	{
		$keys = array('ipanda:c:e:h:b'.$uid,'ipanda:c:e:max'.$uid );
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->getMulti($keys);
		if ($data === false) {
			return null;
		}
		$hit = $data[$keys[0]];
		if($hit === null){
			$time =time();
			$hit['num'] = 0;
			$hit['hitTime'] = $time;
		}
		$date = date('Ymd');
		$maxList = $data[$keys[1]];
		$max = $maxList['max'];
		if($maxList === null || $maxList['date'] != $date){
			$max = 0;
		}
		return array('hit'=>$hit, 'max'=>$max);
	}
	
	public static function getUserstatus($uid)
	{
		$key = 'ipanda:e:c:is:get'.$uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
		if($data === false){
			$date = date('Ymd');
			$list[] = array('id'=>1, 'status'=> 0);
			$list[] = array('id'=>2, 'status'=> 0);
			$list[] = array('id'=>3, 'status'=> 0);
			$list[] = array('id'=>4, 'status'=> 0);
			$list[] = array('id'=>5, 'status'=> 0);
			$data['date'] = $date;
			$data['list'] = $list;
			$cache->set($key, $data);
		}
		return $data;
	}
	
	public static function updateUserstatus($uid, $data)
	{
		$key = 'ipanda:e:c:is:get'.$uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$cache->set($key, $data);
	}
	
	public static function getConfig()
	{
		$key = 'ipanda:e:c:config';
		$cache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
		$data = $cache->get($key);
		if($data === false){
			$data['RateMin'] = 1;
			$data['RateMax'] = 3;
			$data['Hit'] = 2;
			$data['HitInterval'] = 5;
			$data['Max'] = 400;
		}
		return $data;
	}
}