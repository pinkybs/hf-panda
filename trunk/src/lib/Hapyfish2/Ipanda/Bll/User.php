<?php

class Hapyfish2_Ipanda_Bll_User
{
	public static function getUserInit($uid)
	{
        //owner platform info
        $user = Hapyfish2_Platform_Bll_User::getUser($uid);

		$userVO = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
		
		if($userVO['energy'] == $userVO['energy_top']) {
			$energy_recover_time = 0;
		} else {
			$energy_recover_time = $userVO['energy_recover_time'] + EG_RECOVERY_TIME - time();
			if ($energy_recover_time < 0) {
				$energy_recover_time = 0;
			}
		}
		
		$current_admin_num = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getWorkingCount($uid);
		
		if ($userVO['title'] > 0) {
			$achiInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementInfo($userVO['title']);
			if ($achiInfo) {
				$title = $achiInfo['title'];
			}
		} else {
			$title = '';
		}
		
		return array(
			'uid' => $userVO['uid'],
			'name' => $user['name'],
			'exp' => $userVO['exp'],
			'level' => $userVO['level'],
			'face' => $user['figureurl'],
			'love' => $userVO['love'],
			'gold' => $userVO['gold'],
			'energy' => $userVO['energy'],
			'energy_recover_time' => $energy_recover_time,
			'title' => $title,
			'current_admin_num' => $current_admin_num
		);
	}

	/**
	 * join user
	 *
	 * @param integer $uid
	 * @return boolean
	 */
	public static function joinUser($uid)
	{
		$user = Hapyfish2_Platform_Bll_User::getUser($uid);
		if (empty($user)) {
			return false;
		}

		$step = 0;
		//$today = date('Ymd');
		try {
			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->init($uid);
			$step++;
			
			$dalUserSequence = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
			$dalUserSequence->init($uid);
			$step++;
			
			//增加一个新森林，一个新动物
			$dalForest = Hapyfish2_Ipanda_Dal_Forest::getDefaultInstance();
			$dalForest->init($uid);
			$step++;
			
			$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
			$dalPhytotronAnimal->init($uid);
			$step++;
			
			//初始化建筑
			$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
			$dalBuilding->init($uid);
			$step++;
			
			//初始化培育屋
			$dalPhytotron = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
			$dalPhytotron->init($uid);
			$step++;
			
			//初始化装饰物
			$dalDecorate = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
			$dalDecorate->init($uid);
			$step++;
			
			//初始化用户道具
			$dalCard = Hapyfish2_Ipanda_Dal_Card::getDefaultInstance();
			$dalCard->init($uid);
			$step++;
			
			//初始化用户材料
			$dalMaterial = Hapyfish2_Ipanda_Dal_Material::getDefaultInstance();
			$dalMaterial->init($uid);
			$step++;
			
			//初始化成就
			$dalAchievement = Hapyfish2_Ipanda_Dal_Achievement::getDefaultInstance();
			$dalAchievement->init($uid);
			$step++;
			
			//初始化任务
			$dalTaskOpen = Hapyfish2_Ipanda_Dal_TaskOpen::getDefaultInstance();
			$dalTaskOpen->init($uid);
			$step++;
			
			//初始化一个管理员
			$dalPhytotronAdmin = Hapyfish2_Ipanda_Dal_PhytotronAdmin::getDefaultInstance();
			$dalPhytotronAdmin->init($uid);
			$step++;
			
			//初始化道具卡状态
			$dalCardStatus = Hapyfish2_Ipanda_Dal_CardStatus::getDefaultInstance();
			$dalCardStatus->init($uid);
			$step++;
		}
		catch (Exception $e) {
			info_log('(' . $uid . ')[' . $step . ']' . $e->getMessage(), 'ipanda.user.init');
            return false;
		}
		
		info_log('(' . $uid . ')[' . $step . ']', 'user.init.ok');

		Hapyfish2_Ipanda_Cache_User::setAppUser($uid);

		return true;
	}
	
	/**
	 * update user today info
	 */
	public static function updateUserTodayInfo($uid)
	{
	    $loginInfo = Hapyfish2_Ipanda_HFC_User::getUserLoginInfo($uid);
		if (!$loginInfo) {
			return null;
		}

		$isSaveDb = true;
		$now = time();
		$todayTm = strtotime(date('Ymd', $now));
		$newLoginInfo = array();
		$newLoginInfo['last_login_time'] = $loginInfo['last_login_time'];
		if ($loginInfo['last_login_time'] < $now) {
		    $newLoginInfo['last_login_time'] = $now;
		}
		$newLoginInfo['today_login_count'] = $loginInfo['today_login_count'] + 1;
		$newLoginInfo['all_login_count'] = $loginInfo['all_login_count'] + 1;
		$newLoginInfo['active_login_count'] = $loginInfo['active_login_count'];
		$newLoginInfo['max_active_login_count'] = $loginInfo['max_active_login_count'];

        //info_log(json_encode($loginInfo), 'aa');

		//new day come
		if ($todayTm > $loginInfo['last_login_time']) {
		    $isSaveDb = true;
		    $newLoginInfo['today_login_count'] = 1;
		    if ($todayTm - $loginInfo['last_login_time'] > 86460) {
		        $newLoginInfo['active_login_count'] = 0;
		    }
		    else {
		        $newLoginInfo['active_login_count'] = $loginInfo['active_login_count'] + 1;
		        if ($newLoginInfo['active_login_count'] > $loginInfo['max_active_login_count']) {
                    $newLoginInfo['max_active_login_count'] = $newLoginInfo['active_login_count'];
		        }
		    }
		    
		    //派发任务
		    $event = array('uid' => $uid, 'days' => $newLoginInfo['active_login_count']);
		    Hapyfish2_Ipanda_Bll_Event::loginCounter($event);
		    //add log
			$logger = Hapyfish2_Util_Log::getInstance();
			$userInfo = Hapyfish2_Platform_Bll_User::getUser($uid);
			$joinTime = $userInfo['create_time'];
			$gender = $userInfo['gender'];
			$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
			$logger->report('101', array($uid, $joinTime, $gender, $userLevel, $newLoginInfo['active_login_count'], $newLoginInfo['all_login_count']));
		}
		$time = time();
		if($time <= 1326038399 ){
			$date = date('Ymd', $time);
            $gained = Hapyfish2_Ipanda_Bll_CompensationEvent::isGained($uid, 1, $date);
            if (!$gained) {
            	Hapyfish2_Ipanda_Bll_CompensationEvent::gain($uid, 1);
            }
       }
        Hapyfish2_Ipanda_HFC_User::updateUserLoginInfo($uid, $newLoginInfo, $isSaveDb);
        
        return $newLoginInfo;
	}
	
	/**
	 * 检查用户是否升级
	 *
	 * @param int $uid
	 * @return true|false
	 */
	public static function checkLevelUp($uid)
	{
		$user = Hapyfish2_Ipanda_HFC_User::getUser($uid, array('exp' => 1, 'level' => 1));
		if (!$user) {
			return false;
		}

		$userLevel = $user['level'];
		$nextLevelInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getUserLevelInfo($userLevel + 1);
		if (!$nextLevelInfo) {
			return false;
		}

		if ($user['exp'] < $nextLevelInfo['exp']) {
			return false;
		}

		$user['level'] += 1;

		//
		$ok = Hapyfish2_Ipanda_HFC_User::updateUserLevel($uid, $user['level']);
		if ($ok) {
			$now = time();
			//升级日志记录
			Hapyfish2_Ipanda_Bll_LevelUpLog::add($uid, $userLevel, $user['level']);
			
			//管理员数量变更
			$admin_num = $nextLevelInfo['admin_num'];
			if ($admin_num > 0) {
				Hapyfish2_Ipanda_HFC_User::updateUserAdminNum($uid, $admin_num);
			}

			//最大能量值增加，并且能量值恢复满
			$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
			$userEnergy['energy_top'] = $nextLevelInfo['energy'];
			$userEnergy['energy'] = $userEnergy['energy_top'];
			$userEnergy['energy_recover_time'] = $now;
			Hapyfish2_Ipanda_HFC_User::updateUserEnergy($uid, $userEnergy, true);

			//升级奖励
			$award = array();
			$awardRot = new Hapyfish2_Ipanda_Bll_Award();
			if ($nextLevelInfo['award_gold'] > 0) {
				$gold = (int)$nextLevelInfo['award_gold'];
				$awardRot->setGold($gold, 1);
				$award[] = array('cid' => '2', 'num' => $gold);
			}
			if ($nextLevelInfo['award_material_cid'] > 0 && $nextLevelInfo['award_material_num'] > 0) {
				$award_material_num = (int)$nextLevelInfo['award_material_num'];
				$awardRot->setMaterial($nextLevelInfo['award_material_cid'], $award_material_num);
				$award[] = array('cid' => $nextLevelInfo['award_material_cid'], 'num' => $award_material_num);
			}
			if ($nextLevelInfo['award_card_cid'] > 0 && $nextLevelInfo['award_card_num'] > 0) {
				$award_card_num = (int)$nextLevelInfo['award_card_num'];
				$awardRot->setCard($nextLevelInfo['award_card_cid'], $award_card_num);
				$award[] = array('cid' => $nextLevelInfo['award_card_cid'], 'num' => $award_card_num);
			}
			if ($nextLevelInfo['award_decorate_cid'] > 0 && $nextLevelInfo['award_decorate_num'] > 0) {
				$award_decorate_num = (int)$nextLevelInfo['award_decorate_num'];
				$awardRot->setDecorate($nextLevelInfo['award_decorate_cid'], $award_decorate_num);
				$award[] = array('cid' => $nextLevelInfo['award_decorate_cid'], 'num' => $award_decorate_num);
			}
			$awardRot->sendOne($uid);
			
			Hapyfish2_Ipanda_Bll_UserResult::setLevelUp($uid, true);
			Hapyfish2_Ipanda_Bll_UserResult::setLevelUpInfo($uid, 'award', $award);
			
			//可以解锁的动物
			$animalList = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
			$animal = array();
			if ($animalList) {
				foreach ($animalList as $item) {
					if ($item['need_user_level'] == $user['level']) {
						$animal[] = $item['cid'];
					}
				}
			}
			Hapyfish2_Ipanda_Bll_UserResult::setLevelUpInfo($uid, 'animal', $animal);

			//派发事件
			$event = array('uid' => $uid, 'level' => $user['level']);
			Hapyfish2_Ipanda_Bll_Event::levelUp($event);

            //add log,每日升级人数统计
            $logger = Hapyfish2_Util_Log::getInstance();
            $logger->report('2001', array($uid, $user['level']));
        
			return true;
		}

		return false;
	}
}