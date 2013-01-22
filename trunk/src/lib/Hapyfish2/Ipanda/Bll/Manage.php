<?php

class Hapyfish2_Ipanda_Bll_Manage
{
	public static function clearUser($uid)
	{
		$step = 0;
		try {
			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->clear($uid);
			$dalUser->init($uid);
			$step++;
			
			//$dalUserSequence = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
			//$dalUserSequence->clear($uid);
			//$dalUserSequence->init($uid);
			//$step++;
			
			//增加一个新森林，一个新动物
			$dalForest = Hapyfish2_Ipanda_Dal_Forest::getDefaultInstance();
			$dalForest->clear($uid);
			$dalForest->init($uid);
			$step++;
			
			$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
			$dalPhytotronAnimal->clear($uid);
			$dalPhytotronAnimal->init($uid);
			$step++;
			
			//初始化建筑
			$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
			$dalBuilding->clear($uid);
			$dalBuilding->init($uid);
			$step++;
			
			//初始化培育屋
			$dalPhytotron = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
			$dalPhytotron->clear($uid);
			$dalPhytotron->init($uid);
			$step++;
			
			//初始化装饰物
			$dalDecorate = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
			$dalDecorate->clear($uid);
			$dalDecorate->init($uid);
			$step++;
			
			//初始化用户道具
			$dalCard = Hapyfish2_Ipanda_Dal_Card::getDefaultInstance();
			$dalCard->clear($uid);
			$dalCard->init($uid);
			$step++;
			
			//初始化用户材料
			$dalMaterial = Hapyfish2_Ipanda_Dal_Material::getDefaultInstance();
			$dalMaterial->clear($uid);
			$dalMaterial->init($uid);
			$step++;
			
			//初始化成就
			$dalAchievement = Hapyfish2_Ipanda_Dal_Achievement::getDefaultInstance();
			$dalAchievement->clear($uid);
			$dalAchievement->init($uid);
			$step++;
			
			//清除任务完成记录
			$dalTask = Hapyfish2_Ipanda_Dal_Task::getDefaultInstance();
			$dalTask->clear($uid);
			$step++;
			
			//初始化任务数据
			$dalTaskOpen = Hapyfish2_Ipanda_Dal_TaskOpen::getDefaultInstance();
			$dalTaskOpen->clear($uid);
			$dalTaskOpen->init($uid);
			$step++;

			//初始化一个管理员
			$dalPhytotronAdmin = Hapyfish2_Ipanda_Dal_PhytotronAdmin::getDefaultInstance();
			$dalPhytotronAdmin->clear($uid);
			$dalPhytotronAdmin->init($uid);
			$step++;
			
			//初始化道具卡状态
			$dalCardStatus = Hapyfish2_Ipanda_Dal_CardStatus::getDefaultInstance();
			$dalCardStatus->clear($uid);
			$dalCardStatus->init($uid);
			$step++;
			
			//清除升级记录
			$dalLevelUpLog = Hapyfish2_Ipanda_Dal_LevelUpLog::getDefaultInstance();
			$dalLevelUpLog->clear($uid);
			$step++;
			
			//清除建筑等级解锁记录
			$dalBuildingLevelUnlock = Hapyfish2_Ipanda_Dal_BuildingLevelUnlock::getDefaultInstance();
			$dalBuildingLevelUnlock->clear($uid);
			$step++;
			
			//清除建筑解锁记录
			$dalBuildingUnlock = Hapyfish2_Ipanda_Dal_BuildingUnlock::getDefaultInstance();
			$dalBuildingUnlock->clear($uid);
			$step++;
			
			//清除管理员日志记录
			$dalPhytotronAdminMylog = Hapyfish2_Ipanda_Dal_PhytotronAdminMylog::getDefaultInstance();
			$dalPhytotronAdminMylog->clear($uid);
			$step++;
		}
		catch (Exception $e) {
			info_log('[' . $step . ']' . $e->getMessage(), 'manage.inituser');
            return false;
		}
		
		//clear cache
		$hfc = Hapyfish2_Cache_Factory::getHFC($uid);
		$mc = Hapyfish2_Cache_Factory::getMC($uid);
		
		//清除用户各个缓存
		$keys = array(
			'i:u:exp:' . $uid,
			'i:u:love:' . $uid,
			'i:u:gold:' . $uid,
			'i:u:level:' . $uid,
			'i:u:energyinfo:' . $uid,
			'i:u:admin_num:' . $uid,
			'i:u:title:' . $uid,
			'i:u:login:' . $uid,
			'i:u:card:' . $uid,
			'i:u:material:' . $uid,
			'i:u:ach:' . $uid,
			'i:u:taskopen:' . $uid,
			'i:u:phyanimal:' . $uid,
		);
		foreach ($keys as $key) {
			$hfc->delete($key);
		}
		
		//清除建筑缓存
		$builingIds = Hapyfish2_Ipanda_Cache_Building::getAllIds($uid);
		if ($builingIds) {
	        foreach ($builingIds as $id) {
	        	$key = 'i:u:bld:' . $uid . ':' . $id;
	        	$hfc->delete($key);
        		$key2 = 'i:u:bldids:takelovelog:' . $uid . ':' . $id;
        		$mc->delete($key2);
	        }
		}
		
		$keys = array(
			'i:u:bldids:all:' . $uid,
			'i:u:bldids:onforest:' . $uid,
			'i:u:bldids:forestanimalnum:' . $uid,
			'i:u:buildinglevelunlocklist:' . $uid,
			'i:u:buildingunlocklist:'. $uid,
			'i:u:combohit:' . $uid,
			'i:u:decoratelist:' . $uid,
			'i:u:feed:' . $uid,
			'i:u:forestlist:' . $uid,
			'i:u:mapgrid:' . $uid,
			'i:u:pandaquestion:' . $uid,
			'i:u:phytotronlist:' . $uid,
			'i:u:phytotronadminlist:' . $uid,
			'i:u:phytotronadminmyloglist:' . $uid,
			'i:u:phyanimids:' . $uid,
			'i:u:alltask:' . $uid,
			'i:u:taskstatus:' . $uid,
			'i:u:dlyvisit:' . $uid,
		);
		foreach ($keys as $key) {
			$mc->delete($key);
		}
        
        return true;
	}

}