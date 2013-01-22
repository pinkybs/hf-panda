<?php

class Hapyfish2_Ipanda_Cache_Gift
{
	public static function getBasicMC()
	{
		$key = 'mc_0';
		return Hapyfish2_Cache_Factory::getBasicMC($key);
	}

    public static function loadBasicGiftList()
	{
		$db = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
		$list = $db->getBasicGiftList();
		if ($list) {
			$key = 'ipanda:giftlist';
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}

	public static function getBasicGiftList()
	{
		$key = 'ipanda:giftlist';
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadBasicGiftList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getBasicGiftInfo($id)
	{
		$list = self::getBasicGiftList();
		if (isset($list[$id])) {
			return $list[$id];
		}

		return null;
	}


}