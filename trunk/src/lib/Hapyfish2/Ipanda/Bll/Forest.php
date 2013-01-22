<?php

class Hapyfish2_Ipanda_Bll_Forest
{
	public static function getForestList($uid)
	{
       $list = Hapyfish2_Ipanda_Cache_Forest::getForestList($uid);
       return $list;
	}
	
	public  static function getForestByNo($uid,$no)
	{
		$list = Hapyfish2_Ipanda_Cache_Forest::getForestList($uid);
		
		if(count($list) == 1)
		{
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getEntendForestInfo($list[0]['extend_land']);
			if(empty($basicInfo))
			{
				$list[0]['animal_top'] = 30;
			}
			else 
			{
				$list[0]['animal_top'] = $basicInfo['animal_top'];
			}
			return $list[0];
		}
		foreach ($list as $v)
		{
			if($no == $v['forest_no'])
			{
				$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getEntendForestInfo($v['extend_land']);
				if(empty($basicInfo))
				{
					$v['animal_top'] = 30;
				}
				else 
				{
					$v['animal_top'] = $basicInfo['animal_top'];
				}
				
				return $v;
			}
		}
		return $list;
	}
	
	public static function extend_land($uid,$forest_no)
	{
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Forest::getDefaultInstance();
			$dal->extend_land($uid, $forest_no);
		} catch (Exception $e) {

			info_log("$uid-$forest_no", 'forest-extend_land');
			info_log($e->getMessage(), 'forest-extend_land');
			return false;
		}
		//更新到缓存
		Hapyfish2_Ipanda_Cache_Forest::updatetocache($uid,$forest_no);
		return true;
	}
	
	public static function getAnimalTop($uid,$forest_no)
	{
		$data = self::getForestByNo($uid, $forest_no);
		return $data['animal_top'];
		$land_num = $data['extend_land'];
		$basic = Hapyfish2_Ipanda_Bll_BasicInfo::getEntendForestInfo($forest_no,$land_num);
		if($basic)
		{
			return $basic['animal_top'];
		}
		return 30;
	}
	
	public static function getCurrentAnimalNum($uid,$forest_no)
	{
		$data = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$animal_num = Hapyfish2_Ipanda_Cache_Building::getForestAnimalNum($uid);
		return $animal_num;
	}
}