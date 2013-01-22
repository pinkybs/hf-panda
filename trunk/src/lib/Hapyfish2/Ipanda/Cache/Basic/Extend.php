<?php

class Hapyfish2_Ipanda_Cache_Basic_Extend
{
	const NAMESPACE = 'ipanda:extend:';
	
	public static function getKey($word)
	{
		return self::NAMESPACE . $word;
	}
	
	public static function getBasicMC()
	{
		$key = 'mc_0';
		return Hapyfish2_Cache_Factory::getBasicMC($key);
	}
	
	public static function getBasicDB()
	{
		return Hapyfish2_Ipanda_Dal_Basic::getDefaultInstance();
	}
	
	public static function getPandaQuestionList()
	{
		$key = self::getKey('PandaQuestionList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadPandaQuestionList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getPandaQuestionInfo($id)
	{
		$list = self::getPandaQuestionList();
		if (isset($list[$id])) {
			return $list[$id];
		}

		return null;
	}

	public static function loadPandaQuestionList()
	{
		$db = self::getBasicDB();
		$list = $db->getPandaQuestionList();
		if ($list) {
			$key = self::getKey('PandaQuestionList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
	public static function getPandaQuestionSimpleList()
	{
		$key = self::getKey('PandaQuestionSimpleList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadPandaQuestionSimpleList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getPandaQuestionSimpleInfo($id)
	{
		$list = self::getPandaQuestionSimpleList();
		if (isset($list[$id])) {
			return $list[$id];
		}

		return null;
	}

	public static function loadPandaQuestionSimpleList()
	{
		$db = self::getBasicDB();
		$list = $db->getPandaQuestionSimpleList();
		if ($list) {
			$key = self::getKey('PandaQuestionSimpleList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
	
	public static function getDailyAwardList()
	{
		$key = self::getKey('DailyAwardList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadDailyAwardList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getDailyAwardInfo($id)
	{
		$list = self::getDailyAwardList();
		if (isset($list[$id])) {
			return $list[$id];
		}

		return null;
	}

	public static function loadDailyAwardList()
	{
		$db = self::getBasicDB();
		$list = $db->getDailyAwardList();
		if ($list) {
			foreach ($list as &$v) {
				$v['base_award'] = json_decode($v['base_award'], true);
				$v['fans_award'] = json_decode($v['fans_award'], true);
			}
			$key = self::getKey('DailyAwardList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
	public static function getPaySettingList()
	{
		$key = self::getKey('PaySettingList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadPaySettingList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getPaySettingInfo($id)
	{
		$list = self::getPaySettingList();
		if (isset($list[$id])) {
			return $list[$id];
		}

		return null;
	}
	
	public static function updatePaySettingInfo($id, $info)
	{
		$db = self::getBasicDB();
		try {
			$db->updatePaySetting($id, $info);
		} catch(Exception $e) {
			return false;
		}
		
		self::loadPaySettingList();
		return true;
	}
	
	public static function resetPaySettingLocalCache()
	{
		$key = self::getKey('PaySettingList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$cache = self::getBasicMC();
		$list = $cache->get($key);
		if (!$list) {
			$list = self::loadPaySettingList();
		}
		$localcache->set($key, $list);
	}

	public static function loadPaySettingList()
	{
		$db = self::getBasicDB();
		$list = $db->getPaySettingList();
		if ($list) {
			foreach ($list as &$v) {
				if (!empty($v['section'])) {
					$v['section'] = json_decode($v['section'], true);
				}
			}
			$key = self::getKey('PaySettingList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
	public static function getCacheVersion($namespace = 'default')
	{
		$key = self::getKey('CacheVersion' . ':' . $namespace);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$data = $localcache->get($key);
		if (!$data) {
			$cache = self::getBasicMC();
			$data = $cache->get($key);
			if (!$data) {
				$data = self::loadCacheVersion($namespace);
			}
			if ($data) {
				$localcache->set($key, $data, false, 900);
			}
		}

		return $data;
	}
	
	public static function loadCacheVersion($namespace = 'default')
	{
		$db = self::getBasicDB();
		$data = $db->getCacheVersion($namespace);
		if ($data) {
			$data = json_decode($data, true);
			$key = self::getKey('CacheVersion' . ':' . $namespace);
			$cache = self::getBasicMC();
			$cache->set($key, $data);
		}

		return $data;
	}
	
	public static function resetCacheVersion($namespace = 'default')
	{
		$key = self::getKey('CacheVersion' . ':' . $namespace);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$cache = self::getBasicMC();
		$data = $cache->get($key);
		if (!$data) {
			$data = self::loadCacheVersion($namespace);
		}
		$localcache->set($key, $data, false, 900);
		return $data;
	}
	
	public static function updateCacheVersion($vaule, $namespace = 'default')
	{
		$db = self::getBasicDB();
		try {
			$db->updateCacheVersion($namespace, $vaule);
		} catch(Exception $e) {
			return false;
		}
		
		self::loadCacheVersion($namespace);
		return true;
	}
}