<?php

class Hapyfish2_Ipanda_Bll_BuildingUnlock
{
	public static function checkByCid($uid,$cid)
	{
		$list = Hapyfish2_Ipanda_Cache_BuildingUnlock::getList($uid);
		if (empty($list)) {
			return false;
		}
		
		foreach ($list as $v) {
			if($v['cid'] == $cid) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function getlist($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_BuildingUnlock::getList($uid);
		if (empty($list)) {
			return $data;
		}
		
		foreach ($list as $v) {
			$data[] = (int)$v['cid'];
		}
		
		return $data;
	}
	
	public static function addlog($uid,$info)
	{
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_BuildingUnlock::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'BuildingUnlock-add-err');
			info_log($e->getMessage(), 'BuildingUnlock-add-err');
			return false;
		}
		//添加到缓存
		$data = Hapyfish2_Ipanda_Cache_BuildingUnlock::addtocache($uid, $info);
		return true;
	}	
}