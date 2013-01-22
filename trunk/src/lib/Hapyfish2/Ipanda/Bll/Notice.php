<?php

class Hapyfish2_Ipanda_Bll_Notice
{	
	public static function getNoticeList()
	{
		return Hapyfish2_Ipanda_Cache_BasicInfo::getNoticeList();
	}
	
	public static function add($info)
	{
		try {
			$db = Hapyfish2_Ipanda_Dal_BasicInfo::getDefaultInstance();
			$db->addNotice($info);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	public static function update($id, $info)
	{
		try {
			$db = Hapyfish2_Ipanda_Dal_BasicInfo::getDefaultInstance();
			$db->updateNotice($id, $info);
			Hapyfish2_Ipanda_Cache_BasicInfo::loadNoticeList();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	public static function loadToMemcached()
	{
		return Hapyfish2_Ipanda_Cache_BasicInfo::loadNoticeList();
	}
	
	public static function loadToAPC()
	{
		$key = 'ipanda:pubnoticelist';
		$cache = Hapyfish2_Ipanda_Cache_BasicInfo::getBasicMC();
		$list = $cache->get($key);
		
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$localcache->set($key, $list, false, 900);
		
		return $list;
	}
	
	public static function getFromMemcached()
	{
		$key = 'ipanda:pubnoticelist';
		$cache = Hapyfish2_Ipanda_Cache_BasicInfo::getBasicMC();
		return $cache->get($key);
	}
	
	public static function getFromAPC()
	{
		$key = 'ipanda:pubnoticelist';
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		return $localcache->get($key);
	}

}