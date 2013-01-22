<?php

class Hapyfish2_Ipanda_Cache_Basic_Info
{
	const NAMESPACE = 'ipanda:info:';
	
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
	
	public static function getFeedTemplate()
	{
		$key = self::getKey('FeedTemplate');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$tpl = $localcache->get($key);
		if (!$tpl) {
			$cache = self::getBasicMC();
			$tpl = $cache->get($key);
			if (!$tpl) {
				$tpl = self::loadFeedTemplate();
			}
			if ($tpl) {
				$localcache->set($key, $tpl);
			}
		}
		
		return $tpl;
	}
	
	public static function getFeedTemplateTitle($template_id)
	{
		$tpl = self::getFeedTemplate();
		if ($tpl && isset($tpl[$template_id])) {
			return $tpl[$template_id];
		}
		
		return null;
	}
	
	public static function loadFeedTemplate()
	{
		$db = self::getBasicDB();
		$tpl = $db->getFeedTemplate();
		if ($tpl) {
			$key = self::getKey('FeedTemplate');
			$cache = self::getBasicMC();
			$cache->set($key, $tpl);
		}
		
		return $tpl;
	}
	
	public static function getUserLevelList()
	{
		$key = self::getKey('UserLevelList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadUserLevelList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getUserLevelInfo($level)
	{
		$list = self::getUserLevelList();
		if (isset($list[$level])) {
			return $list[$level];
		}
		
		return null;
	}
	
	public static function loadUserLevelList()
	{
		$db = self::getBasicDB();
		$list = $db->getUserLevelList();
		if ($list) {
			$key = self::getKey('UserLevelList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getAchievementList()
	{
		$key = self::getKey('AchievementList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadachievementList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getAchievementInfo($id)
	{
		$list = self::getAchievementList();
		if (isset($list[$id])) {
			return $list[$id];
		}
		
		return null;
	}
	
	public static function getAchievementInfoByGroup($gid)
	{
		$list = self::getAchievementList();
		$data = array();
		
		foreach ($list as $v) {
			if ($v['group_id'] == $gid){
				$data[] = $v;
			}
		}
		
		return $data;
	}
	
	public static function loadachievementList()
	{
		$db = self::getBasicDB();
		$list = $db->getAchievement();
		if ($list) {
			$key = self::getKey('AchievementList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getTaskTypeList()
	{
		$key = self::getKey('TaskTypeList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadTaskTypeList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getTaskTypeInfo($id)
	{
		$list = self::getTaskTypeList();
		if (isset($list[$id])) {
			return $list[$id];
		}

		return null;
	}

	public static function loadTaskTypeList()
	{
		$db = self::getBasicDB();
		$list = $db->getTaskTypeList();
		if ($list) {
			$key = self::getKey('TaskTypeList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
	public static function getTaskConditionList()
	{
		$key = self::getKey('TaskConditionList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadTaskConditionList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	/**
	 * 任务条件很多，如果取一个都从一个整体列表里去取，内存开销有点大
	 * 细粒度到每个，但重新刷新的时候需要遍历更新
	 *
	 */
	public static function getTaskConditionInfo($id)
	{
		$key = self::getKey('TaskCondition:' . $id);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$info = $localcache->get($key);
		if (!$info) {
			$list = self::getTaskConditionList();
			if (isset($list[$id])) {
				$info = $list[$id];
				$localcache->set($key, $info);
			}
		}

		return $info;
	}
	
	public static function loadAllTaskConditionInfo()
	{
		$list = self::getTaskConditionList();
		if ($list) {
			$localcache = Hapyfish2_Cache_LocalCache::getInstance();
			foreach ($list as $item) {
				$key = self::getKey('TaskCondition:' . $item['id']);
				$localcache->set($key, $item);
			}
			return true;
		}
		
		return false;
	}

	public static function loadTaskConditionList()
	{
		$db = self::getBasicDB();
		$list = $db->getTaskConditionList();
		if ($list) {
			$key = self::getKey('TaskConditionList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
	public static function getTaskList()
	{
		$key = self::getKey('TaskList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();

		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadTaskList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}

		return $list;
	}

	public static function getTaskInfo($id)
	{
		$key = self::getKey('Task:' . $id);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$info = $localcache->get($key);
		if (!$info) {
			$list = self::getTaskList();
			if (isset($list[$id])) {
				$info = $list[$id];
				$localcache->set($key, $info);
			}
		}
		
		return $info;
	}
	
	public static function loadAllTaskInfo()
	{
		$list = self::getTaskList();
		if ($list) {
			$localcache = Hapyfish2_Cache_LocalCache::getInstance();
			foreach ($list as $item) {
				$key = self::getKey('Task:' . $item['id']);
				$localcache->set($key, $item);
			}
			return true;
		}
		
		return false;
	}
	
	public static function getTaskIdsByLevel($level)
	{
		$list = self::getTaskList();
		$ids = array();
		if ($list) {
			foreach ($list as $task) {
				if ($task['need_user_level'] == $level && $task['front_task_id'] == '[]') {
					$ids[] = (int)$task['id'];
				}
			}
		}

		return $ids;
	}

	public static function loadTaskList()
	{
		$db = self::getBasicDB();
		$list = $db->getTaskList();
		if ($list) {
			$key = self::getKey('TaskList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}

		return $list;
	}
	
}