<?php

class Hapyfish2_Ipanda_Bll_BuildingLevelUnlock
{
	public static function getId($uid)
	{
		$dal = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
		return $dal->getId($uid,"buildingLevelUnlock");
	}
	
	public static function checkByBuildingid($uid,$building_id,$level)
	{
		$list = Hapyfish2_Ipanda_Cache_BuildingLevelUnlock::getList($uid);
		if (empty($list)) {
			return false;
		}
		
		foreach ($list as $v)
		{
			if($v['ipanda_user_building_id'] ==  $building_id && $v['level'] == $level)
			{
				return true;
			}
		}
		return false;
	}
	
	public static function addlog($uid,$info)
	{
		$id = self::getId($uid);
		$info['id'] = $id;
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_BuildingLevelUnlock::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'BuildingLevelUnlock-add-err');
			info_log($e->getMessage(), 'BuildingLevelUnlock-add-err');
			return false;
		}
		//添加到缓存
		$data = Hapyfish2_Ipanda_Cache_BuildingLevelUnlock::addtocache($uid, $info);
		return true;
	}
	
	
}