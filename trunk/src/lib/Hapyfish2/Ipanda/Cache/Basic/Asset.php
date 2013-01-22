<?php

class Hapyfish2_Ipanda_Cache_Basic_Asset
{
	const NAMESPACE = 'ipanda:asset:';
	
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
	
	public static function getBuildingList()
	{
		$key = self::getKey('BuildingList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadBuildingList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getBuildingInfo($cid)
	{
		$list = self::getBuildingList();
		if (isset($list[$cid])) {
			return $list[$cid];
		}
		
		return null;
	}
	
	public static function loadBuildingList()
	{
		$db = self::getBasicDB();
		$list = $db->getBuildingList();
		if ($list) {
			$animalList = self::getPhytotronAnimalList();
			foreach ($list as &$v) {
				$v['attribute'] 		= json_decode($v['attribute'], true);
				$v['need_material'] 	= json_decode($v['need_material'], true);
				$v['need_fix'] 			= json_decode($v['need_fix'], true);
				$v['checkout_time'] 	= json_decode($v['checkout_time'], true);

				$animalInfo = $animalList[$v['animal_cid']];
				$material = json_decode(self::getMaterial($v, $animalInfo), true);
				$v['checkout_material'] = array();
				foreach ($material as $m) {
					if ($m['num'] > 0 ) {
						$v['checkout_material'][] = $m;
					}
				}
			}
			
			$key = self::getKey('BuildingList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getMaterial($buildingInfo, $animalInfo)
	{
		$data = self::getMaterialGroupList();
		
		$preData = array();
		foreach ($data as $v) {
			if($v['building_level'] == $buildingInfo['level'] && $v['nature_type'] == $animalInfo['nature_type']
				&& $v['material_group_id'] == $buildingInfo['material_group_id']) 
			{	
				$attribute = $buildingInfo['attribute'];
				$theAttr = json_decode($v['attr_condtion'], true);
				$flag = 1;
				for($i = 0, $num = count($attribute); $i < $num ; $i++ ) {
					if ($attribute[$i] < $theAttr[$i]) {
						$flag = 0;
						break;
					}	
				}
				
				if ($flag == 0) {
					break;
				}
				
				$preData = $v;
			}
		}
		
		return $preData['material'];
	}
	
    public static function getPhytotronAnimalList()
	{
		$key = self::getKey('PhytotronAnimalList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadPhytotronAnimalList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getPhytotronAnimalInfo($cid)
	{
		$list = self::getPhytotronAnimalList();
		if (isset($list[$cid])) {
			return $list[$cid];
		}
		
		return null;
	}
	
	public static function loadPhytotronAnimalList()
	{
		$db = self::getBasicDB();
		$list = $db->getPhytotronAnimalList();
		if ($list) {
			foreach ($list as &$v) {
				$v['unlock_condition'] 	= json_decode($v['unlock_condition'], true);
				$v['product_time'] 		= json_decode($v['product_time'], true);
				$v['consume_building'] 	= json_decode($v['consume_building'], true);
			}
			
			$key = self::getKey('PhytotronAnimalList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getPhytotronUnlockList()
	{
		$key = self::getKey('PhytotronUnlockList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadPhytotronUnlockList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getPhytotronUnlockInfo($id)
	{
		$list = self::getPhytotronUnlockList();
		if (isset($list[$id])) {
			return $list[$id];
		}
		
		return null;
	}
	
	public static function loadPhytotronUnlockList()
	{
		$db = self::getBasicDB();
		$list = $db->getPhytotronUnlockList();
		if ($list) {
			foreach ($list as &$v) {
				$v['need_material'] = json_decode($v['need_material'], true);
			}
			
			$key = self::getKey('PhytotronUnlockList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getDecorateList()
	{
		$key = self::getKey('DecorateList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadDecorateList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getDecorateInfo($id)
	{
		$list = self::getDecorateList();
		if (isset($list[$id])) {
			return $list[$id];
		}
		
		return null;
	}
		
	public static function loadDecorateList()
	{
		$db = self::getBasicDB();
		$list = $db->getDecorateList();
		if ($list) {
			foreach ($list as &$v) {
				$v['attribute_change'] 	= json_decode($v['attribute_change'], true);
			}			
			$key = self::getKey('DecorateList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getAnimalLevelList()
	{
		$key = self::getKey('AnimalLevelList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadAnimalLevelList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getAnimalLevelInfo($level)
	{
		$list = self::getAnimalLevelList();

		if (isset($list[$level])) {
			return $list[$level];
		}
		
		return null;
	}
	
	public static function loadAnimalLevelList()
	{
		$db = self::getBasicDB();
		$list = $db->getAnimalLevelList();
		if ($list) {
			$key = self::getKey('AnimalLevelList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getEntendForestList()
	{
		$key = self::getKey('EntendForestList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadEntendForestList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getEntendForestInfo($num, $forest_no = 0)
	{
		$list = self::getEntendForestList();
		
		foreach ($list as $k => $v ) {
			if (($v['forest_no'] == $forest_no) && ($v['land_num'] == $num)) {
				return $v;
			}
		}
		
		return null;
	}
	
	public static function loadEntendForestList()
	{
		$db = self::getBasicDB();
		$list = $db->getEntendForestList();
		if ($list) {
			$key = self::getKey('EntendForestList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
		
	public static function getMaterialGroupList()
	{
		$key = self::getKey('MaterialGroupList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadMaterialGroupList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function loadMaterialGroupList()
	{
		$db = self::getBasicDB();
		$list = $db->getMaterialGroup();
		if ($list) {
			$key = self::getKey('MaterialGroupList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getMaterialList()
	{
		$key = self::getKey('MaterialList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadMaterialList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function loadMaterialList()
	{
		$db = self::getBasicDB();
		$list = $db->getMaterialList();
		if ($list) {
			$key = self::getKey('MaterialList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getMaterialInfo($cid)
	{
		$list = self::getMaterialList();
		if (isset($list[$cid])) {
			return $list[$cid];
		}
		
		return null;
	}
	
	public static function getCardList()
	{
		$key = self::getKey('CardList');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadCardList();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function getCardInfoByEffectCid($cid)
	{
		$data = self::getCardList();
		
		$result = array();
		foreach ($data as $v) {
			if ($v['effect_cid'] == $cid) {
				$result[] = $v;
			}
		}
		
		return $result;
	}
	
	public static function loadCardList()
	{
		$db = self::getBasicDB();
		$list = $db->getCardList();
		if ($list) {
			$key = self::getKey('CardList');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		
		return $list;
	}
	
	public static function getCardInfo($cid)
	{
		$list = self::getCardList();
		if (isset($list[$cid])) {
			return $list[$cid];
		}
		
		return null;
	}
	
	public static function getAnimalDialogue()
	{
		$key = self::getKey('AnimalDialogue');
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		
		$list = $localcache->get($key);
		if (!$list) {
			$cache = self::getBasicMC();
			$list = $cache->get($key);
			if (!$list) {
				$list = self::loadAnimalDialogue();
			}
			if ($list) {
				$localcache->set($key, $list);
			}
		}
		
		return $list;
	}
	
	public static function loadAnimalDialogue()
	{
		$db = self::getBasicDB();
		$list = $db->getAnimalDialogue();
		if ($list) {
			$key = self::getKey('AnimalDialogue');
			$cache = self::getBasicMC();
			$cache->set($key, $list);
		}
		return $list;
	}
}