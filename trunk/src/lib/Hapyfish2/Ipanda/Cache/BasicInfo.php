<?php

class Hapyfish2_Ipanda_Cache_BasicInfo
{
	public static function getBasicMC()
	{
		$key = 'mc_0';
		return Hapyfish2_Cache_Factory::getBasicMC($key);
	}
	
	public static function getNoticeList()
	{
		$key = 'ipanda:pubnoticelist';
		$key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = array();
			}
			$localcache->set($key, $list, false, 900);
		}
		
		return $list;
	}
	
	public static function loadNoticeList()
	{
		$db = Hapyfish2_Ipanda_Dal_BasicInfo::getDefaultInstance();
		$list = $db->getNoticeList();
		
		if ($list) {
			$main = array();
			$sub = array();
			$pic = array();
			foreach ($list as $item){
				if ($item['position'] == 1) {
					$main[] = $item;
				} else if($item['position'] == 2){
					$sub[] = $item;
            	} else if($item['position'] == 3){
					$pic[] = $item;
				}
			}
            $info = array('main' => $main, 'sub' => $sub, 'pic' => $pic);
			
			$key = 'ipanda:pubnoticelist';
			$key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
			$cache = self::getBasicMC();
			$cache->set($key, $info);
		} else {
			$info = array();
		}
		return $info;
	}
	
}