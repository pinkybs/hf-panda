<?php

class ServiceController extends Hapyfish2_Controller_Action_Api
{    
    /**
     * 建筑升级信息
     * 
     */
    public function  buildinglevelupinfoAction()
    {
		$uid = $this->uid;
		$id = $this->_request->getParam('id');
		
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		if (!$building) {
			$this->echoErrResult(-103);
		}
		
		$cid = $building['cid'];
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
		
		$next_cid = $basicInfo['next_level_cid'];
    	if (empty($next_cid)) {
			$this->echoErrResult(-702);
		}
		
		$nextInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($next_cid);
        if (empty($nextInfo)) {
			$this->echoErrResult(-702);
		}
		
		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		//判断等级有没有达到
		if($userLevel < $nextInfo['need_level']) {
			//查找有没有解锁些项的log
			$ok = Hapyfish2_Ipanda_Bll_BuildingLevelUnlock::checkByBuildingid($uid, $id, $nextInfo['level']);
			if($ok) {
				$result['status'] = 1;
			} else {
				$result['status'] = -1;
			}
		} else {
			$result['status'] = 1;
		}
		
		$animalInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($basicInfo['animal_cid']);
    	$product_material = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterial($nextInfo, $animalInfo);
		
		$nextInfo['product_material'] = json_decode($product_material,true);
		$nextInfo['checkout_love'] = $nextInfo['checkout_time'][$building['checkout_time_type']]['love'];
		$result['nextInfo'] = $nextInfo;
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
    }
    
    /**
     * 解锁 等级 限制
     */
	public function buildingunlocklevelAction()
	{
		$uid = $this->uid;
		$id = $this->_request->getParam('id');
		
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		if (!$building) {
			$this->echoErrResult(-103);
		}
		
		$cid = $building['cid'];
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
		
		$next_cid = $basicInfo['next_level_cid'];
		if (empty($next_cid)) {
			$this->echoErrResult(-702);
		}
		
		$nextInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($next_cid);
        if (empty($nextInfo)) {
			$this->echoErrResult(-702);
		}
		
		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		//判断等级有没有达到
		if($userLevel >= $nextInfo['need_level']) {
			$this->echoErrResult(-704);
		}
		
		$ok = Hapyfish2_Ipanda_Bll_BuildingLevelUnlock::checkByBuildingid($uid, $id, $nextInfo['level']);
		if($ok) {
			//已解锁
			$result['status'] = 1;
		} else {
			//花金币解锁
			$gold = Hapyfish2_Ipanda_Bll_Gold::get($uid);
			if($gold < $nextInfo['unlock_level_gold']) {
				$this->echoErrResult(-102);
			}
			else {
				$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $nextInfo['unlock_level_gold'],
					'summary' 		=> '花费金币解锁建筑等级限制',
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
				if (!$ok) {
					$this->echoErrResult(-102);
				}
			}
			//解锁
			$info['ipanda_user_building_id'] 	= $id;
			$info['level'] 						= $nextInfo['level'];
			$info['uid'] 						= $uid;
			$ok = Hapyfish2_Ipanda_Bll_BuildingLevelUnlock::addlog($uid, $info);
			if($ok) {
				$result['status'] = 1;
			} else {
				$this->echoErrResult(-103);
			}
		}
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   }
   
   /**
    * 升级建筑 
    */
   	public function buildinglevelupAction()
   	{	
		$uid = $this->uid;
		
		$id =  $this->_request->getParam('id');
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		$building = Hapyfish2_Ipanda_HFC_Building::getOne($uid, $id);
		if (!$building) {
			$this->echoErrResult(-103);
		}
		
		$cid = $building['cid'];
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
		
		$next_cid = $basicInfo['next_level_cid'];
		if (empty($next_cid)) {
			$this->echoErrResult(-702);
		}
		
		$nextInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($next_cid);
   	    if (empty($nextInfo)) {
			$this->echoErrResult(-702);
		}
		
   		//体力
		$energy = EG_PER_BUILDING;
		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
		if ($userEnergy['energy'] < $energy) {
			$this->echoErrResult(-105);
		}

   		//材料
   		//只有爱心的时候需要材料
		if ($nextInfo['gold_price'] == 0) {
	   		$need_material = $nextInfo['need_material'];
			if (!empty($need_material)) {
				$userMaterial = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
				$errorData = array();
				foreach ($need_material as $material) {
					$cid = $material['cid'];
					if ($cid > 0) {
						$num = $material['num'];
						if (!isset($userMaterial[$cid]) || $userMaterial[$cid]['count'] < $num) {
							$errorData[] = array('cid' => $cid, 'num' => ($num - $userMaterial[$cid]['count']));
						} else {
							Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, $cid, -$num);
						}
					}
				}
				if (!empty($errorData)) {
					$error = array('eid' => -106, 'data' => $errorData);
					$this->echoError($error);
				}
			}
		}
		
   		//爱心
		if ($nextInfo['price_type'] == 0) {
			$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
			if ($userLove < $nextInfo['love_price']) {
				$this->echoErrResult(-100);
			}
		} else if ($nextInfo['price_type'] == 1) {
			$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
			if ($userGold < $nextInfo['gold_price']) {
				$this->echoErrResult(-102);
			}
		} else {
			$this->echoErrResult(-1);
		}
		
		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		//判断等级有没有达到
		if ($userLevel < $nextInfo['need_level']) {
			//查找有没有解锁些项的log
			$ok = Hapyfish2_Ipanda_Bll_BuildingLevelUnlock::checkByBuildingid($uid, $id, $nextInfo['level']);
			if(!$ok) {
				$this->echoErrResult(-705);
			}
		}
		
   		//消耗体力
		$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
		if (!$ok) {
			info_log($uid, 'energy_fix.err');
		} else {
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
		}
		
   		//爱心
		if($nextInfo['price_type'] == 0) {
			$updateMoney = $nextInfo['love_price'];
			//扣除爱心
			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $updateMoney);
			if(!$ok) {
				$this->echoErrResult(-100);
			}

			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$updateMoney);
			
		   	//扣除材料
			if (!empty($need_material)) {
				$ok = Hapyfish2_Ipanda_HFC_Material::useMultiple($uid, $need_material, $userMaterial);
				if (!$ok) {
					info_log($uid . ':' . json_encode($need_material), 'need_material.err');
				}
			}
		} else {
			//宝石
			if ($nextInfo['gold_price'] > 0) {
				//宝石
				$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $nextInfo['gold_price'],
					'summary' 		=> '升级建筑' . $nextInfo['name'] . ',CID:' . $nextInfo['cid'],
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
				if (!$ok) {
					$this->echoErrResult(-102);
				}
				
				Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$nextInfo['gold_price']);
			}
		}

		//升级
		$levelUp = Hapyfish2_Ipanda_Bll_Building::levelup($building, $nextInfo, $basicInfo);

		if($levelUp['status'] == 1) {
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $nextInfo['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::upgradeBuilding($event);
			
			$result['status'] = 1;
		} else  {
			$this->echoErrResult($levelUp['error']);
		}
		
		$result['buildingVo'] = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
	
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 维修
   	 */
   	public function fixbuildingAction()
	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		if(empty($id)) {
			$this->echoErrResult(-104);
		}
		
		//判断是不是帮好友维修
		$fuid =  $this->_request->getParam('fuid');
		if ($fuid == $uid) {
			$fuid = 0;
		}
		
		if(!empty($fuid)) {
			$userVo = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
			if($userVo['level'] < FIX_BUILD_LEVEL) {
				$this->echoErrResult(-703);
			}
			$building_uid = $fuid;
		} else {
			$building_uid = $uid;
		}
		
		$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($building_uid, $id);
		if (!$building) {
			$this->echoErrResult(-708);
		}
		
		if ($building['durable'] > 0){
			$this->echoErrResult(-706);
		}
		
   		if ($building['fix_end_time'] > 0){
   			$this->echoErrResult(-709);
		}
		
		$cid = $building['cid'];
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
		
		//判断各种需求条件
		//体力
		if ($fuid == 0) {
			$energy = EG_FIX_MY_BUILDING;
		} else {
			$energy = EG_FIX_FRIEND_BUILDING;
		}
		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
		if ($userEnergy['energy'] < $energy) {
			$this->echoErrResult(-105);
		}
		
		//材料
		$fix_material = $basicInfo['need_fix'];
		if (!empty($fix_material)) {
			$userMaterial = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
			$errorData = array();
			foreach ($fix_material as $material) {
				$cid = $material['cid'];
				if ($cid > 0) {
					$num = $material['num'];
					if (!isset($userMaterial[$cid]) || $userMaterial[$cid]['count'] < $num) {
						$errorData[] = array('cid' => $cid, 'num' => ($num - $userMaterial[$cid]['count']));
					} else {
						Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, $cid, -$num);
					}
				}
			}
			if (!empty($errorData)) {
				$error = array('eid' => -106, 'data' => $errorData);
				$this->echoError($error);
			}
		}
		
		//消耗体力
		$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
		if (!$ok) {
			info_log($uid, 'energy_fix.err');
		} else {
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
		}
		
		Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_FIX_BUILDING);
		$goods = array(array('cid' => 5, 'num' => EXP_FIX_BUILDING));
		$update['goodsVo']['goods'] = $goods;
		$update['goodsVo']['bid'] = $id;
		$update['goodsVo']['type'] = 0;
		
		//扣除材料
		if (!empty($fix_material)) {
			$ok = Hapyfish2_Ipanda_HFC_Material::useMultiple($uid, $fix_material, $userMaterial);
			if (!$ok) {
				info_log($uid . ':' . json_encode($fix_material), 'need_fix.err');
			}
		}
		
		$t = time();
		//开始维修
		$info['fix_end_time'] = $t + $basicInfo['durable_time'];
		Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($building_uid, $building['id'], $info, true);
		
		if(!empty($fuid)) {
			//发消息
			$minifeed = array(
				'uid' => $fuid,
	            'template_id' => 6,
	            'actor' => $uid,
	            'target' => $fuid,
	            'title' => array('actor' => $uid, 'building' => $basicInfo['name']),
	            'type' => 6,
	            'create_time' => $t
			);
	        Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($minifeed);
		}
		
		if(!empty($fuid)) {
			//记录操作
			$loginfo['type'] = 3;
			$loginfo['id'] = $id;
			$loginfo['uid'] = $userVo['uid'];
			$loginfo['face'] = $userVo['face'];
			$loginfo['name'] = $userVo['name'];
			//维修不记录 2011-11-02
			//Hapyfish2_Ipanda_Cache_User::logInteractive($fuid, $uid, $loginfo);
		}
		
        //成就(维修建筑次数)
    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '7', 1);
    	
    	//派发任务
    	$event = array('uid' => $uid, 'fuid' => $fuid);
    	Hapyfish2_Ipanda_Bll_Event::fix($event);
		
		$result['status'] = 1;
		$result['build'] = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($building_uid, $id);
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 完成维修
   	 */
	public function completefixbuildingAction()
   	{
		$uid = $this->uid;
		$id =  $this->_request->getParam('id');
		
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		$result['status'] = 1;
		$result['build']  = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 改变方向(装饰物，建筑，培育屋)
   	 */
   	public function changedirectionAction()
   	{
   		$uid = $this->uid;
   		$id = $this->_request->getParam('id');
   		$type = $this->_request->getParam('type');
   		$mirro = (int)$this->_request->getParam('mirro');
   		
   		if (empty($id)) {
   			$this->echoErrResult(-104);
   		}
   		
   		if ($mirro != 0) {
   			$mirro = 1;
   		}
   		
   		if ($type == 0) {
   			//建筑
   			$building = Hapyfish2_Ipanda_HFC_Building::getOne($uid, $id);
   			if (!$building) {
				$this->echoErrResult(-104);
			}
			
			if ($building['mirro'] != $mirro) {
				$cid = $building['cid'];
				$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
				if (empty($basicInfo)) {
					$this->echoErrResult(-101);
				}
				//检查是不是正方形
				//正方形，占地格子不变，加成影响也不变
				//其他需要检查坐标和影响
				$nodes = $basicInfo['nodes'];
				$tmp = explode('*', $nodes);
				$isSquare = true;
				$x = $building['x'];
				$z = $building['z'];
				if ($tmp[0] != $tmp[1]) {
					$isSquare = false;
					//检查是否坐标冲突
					$ok = Hapyfish2_Ipanda_Bll_MapGrid::checkMap($uid, $x, $z, $mirro, $nodes, $id, $cid);
					if (!$ok) {
						//不能重叠
						$this->echoErrResult(-108);
					}
				}
				
   				$info = array('mirro' => $mirro);
   				$ok = Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $id, $info);
   				if ($ok) {
   					if (!$isSquare) {
   						//重新计算加成
   						Hapyfish2_Ipanda_Bll_MapGrid::updateBuilding($uid, $id, $cid, $x, $z, $building['mirro'], $x, $z, $mirro, $basicInfo);
   					}
   				}
			}
   			
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $building['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::moveBuilding($event);
   		} else if ($type == 1) {
   			//培育屋
   			$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getOnePhytotron($uid, $id);
			if (!$phytotron) {
				$this->echoErrResult(-103);
			}

			if ($phytotron['mirro'] != $mirro) {
				//培育屋都是2*2的
				//旋转不造成影响
				
	   			$info = array('mirro' => $mirro);
	   			Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
			}
   			
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $phytotron['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::movePhytotron($event);
   		} else if ($type == 2) {
   			//装饰
   			$decorate = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
   			if (!$decorate) {
   				$this->echoErrResult(-104);
   			}
   			
   			if ($decorate['mirro'] != $mirro) {
   				$cid = $decorate['cid'];
   				$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
				if (empty($basicInfo)) {
					$this->echoErrResult(-101);
				}
				//检查是不是正方形
				//正方形，占地格子不变，加成影响也不变
				//其他需要检查坐标和影响
				$nodes = $basicInfo['nodes'];
				$tmp = explode('*', $nodes);
				$isSquare = true;
				$x = $decorate['x'];
				$z = $decorate['z'];
				if ($tmp[0] != $tmp[1]) {
					$isSquare = false;
					//检查是否坐标冲突
					$ok = Hapyfish2_Ipanda_Bll_MapGrid::checkMap($uid, $x, $z, $mirro, $nodes, $id, $cid);
					if (!$ok) {
						//不能重叠
						$this->echoErrResult(-108);
					}
				}
				
				$info = array('mirro' => $mirro);
				$ok = Hapyfish2_Ipanda_Bll_Decorate::update($uid, $id, $info);
				if ($ok) {
					if (!$isSquare) {
						Hapyfish2_Ipanda_Bll_MapGrid::updateDecorate($uid, $id, $cid, $x, $z, $decorate['mirro'], $x, $z, $mirro, $basicInfo);
					}
				}
   			}
			
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $decorate['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::moveDecorate($event);
   		} else {
   			$this->echoErrResult(-104);
   		}
   		
   		$result['status'] = 1;
   		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 偷爱心
   	 */
   	public function takefriendbuildingloveAction()
   	{
		$uid = $this->uid;
		
		$id =  $this->_request->getParam('id');
		$fuid =  $this->_request->getParam('fuid');
		if (empty($id) || empty($fuid)) {
			$this->echoErrResult(-104);
		}
		
		//判断玩家等级
   		$userVo = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
		if ($userVo['level'] < TAKE_BUILD_LOVE_LEVEL) {
			$this->echoErrResult(-703);
		}

		$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($fuid, $id);
		if (!$building) {
			$this->echoErrResult(-708);
		}
		//判断帮助次数
		$interactiveNum = Hapyfish2_Ipanda_Cache_User::getInteractionNum($fuid , $uid);
		if( $interactiveNum >= 5)
		{
			$this->echoErrResult(-713);
		}
		//判断是不是已经偷过
   		$canflag = Hapyfish2_Ipanda_Bll_Building::cantakeLove($uid, $building);
   		if (!$canflag) {
   			$this->echoErrResult(-711);
   		}
   		
		//判断结算状态
   		if (empty($building['love']) || (time() < $building['op_end_time'])) {
			$this->echoErrResult(-505);
		}
		
		//判断是不是已经达到总量了，1/3
		//take_deposit
		$can_take = floor($building['love'] * 0.3);
		$leave_num = $can_take - $building['take_deposit'];
		if ($leave_num <= 0) {
			$this->echoErrResult(-710);
		}
		/*
   		$energy = EG_TAKE_FRIEND_LOVE;
		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
		if ($userEnergy['energy'] < $energy) {
			$error['eid'] = -105;
			$error['data'] = Hapyfish2_Ipanda_Bll_Card::getRelateCard($uid, 3);
			$this->echoError($error);
		}
		
		$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
		if (!$ok) {
		} else {
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
		}
		*/
		//偷取 
		$num = rand(1, 20);
		$num = $num > $leave_num ? $leave_num : $num;
		$ret = Hapyfish2_Ipanda_Bll_Building::takeLove($uid, $building, $num);
		if ($ret) {
			//增加爱心
			$ok = Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $num);
			//加经验
			Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_TAKE_FRIEND_LOVE);
			
	    	//派发任务
	    	$event = array('uid' => $uid, 'fid' => $fuid, 'love' => $num);
	    	Hapyfish2_Ipanda_Bll_Event::takeFriendLove($event);
			
			$goods = array(
				array('cid' => 1, 'num' => $num),
				array('cid' => 5, 'num' => EXP_TAKE_FRIEND_LOVE)
			);
			$update['goodsVo']['goods'] = $goods;
			$update['goodsVo']['bid'] = $id;
			$update['goodsVo']['type'] = 0;
		}
		
		//发消息
		$minifeed = array(
			'uid' => $fuid,
            'template_id' => 2,
            'actor' => $uid,
            'target' => $fuid,
            'title' => array('actor' => $uid, 'num' => $num),
            'type' => 2,
            'create_time' => time()
		);
        Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($minifeed);
        
		//记录操作
		$loginfo['type'] = 2;
		$loginfo['id'] = $id;
		$loginfo['uid'] = $userVo['uid'];
		$loginfo['face'] = $userVo['face'];
		$loginfo['name'] = $userVo['name'];
		Hapyfish2_Ipanda_Cache_User::logInteractive($fuid, $uid, $loginfo);
		
		$result['status'] = 1;
		
		//记录奖励 给玩家奖励  1 木头 或 石块   AND 1 能量  帮助满5次
		$interactiveNum++ ;
		if($interactiveNum == 5)
		{
			$awardnum = Hapyfish2_Ipanda_Cache_User::getInteractionAward($uid);
			if($awardnum < 20)
			{
				
				//发奖励
				$mkey = rand(0,1);
				$awardArr = array(151,251);
				$award = $awardArr[$mkey];
				$ok2 = Hapyfish2_Ipanda_HFC_Material::addUserMaterial($uid, $award, 1);
				if ($ok2) {
					$result['materialVo'] = Hapyfish2_Ipanda_Bll_Material::getList($uid);
				}
				$ok = Hapyfish2_Ipanda_HFC_User::incUserEnergy($uid, 1);
				array_push( $update['goodsVo']['goods'] , array( 'cid' => $award , num => 1)  );
				$awardnum++;
				Hapyfish2_Ipanda_Cache_User::logInteractionAward($uid,$awardnum);
			}
		}
		$ret = $this->returnResult($result, $update);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 道具卡列表
   	 * 
   	 */
   	public function cardshoplistAction()
   	{
		//$uid = $this->uid;
		
		$data = Hapyfish2_Ipanda_Bll_BasicInfo::getCardList();
		
		$list = array();
		foreach ($data as $v) {
			if ($v['can_buy'] == 1) {
				//$list[] = $v;
				$list[] = array(
					'cid' => $v['cid'],
					'item_type' => $v['item_type'],
					'is_new' => $v['is_new']
				);
			}
		}
		
		$result['list'] = $list;
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 购卡
   	 * 
   	 */
   	public function buycardAction()
   	{
		$uid = $this->uid;
		
		$cid = $this->_request->getParam('cid');
		$num = $this->_request->getParam('num');
		if (empty($cid)) {
			$this->echoErrResult(-104);
		}
		
		if (empty($num)) {
			$num = 1;
		}
		
		$cardInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getCardInfoByCid($cid);
		if($cardInfo['can_buy'] != 1){
			$this->echoErrResult(-808);
		}
		if(!$cardInfo) {
			$this->echoErrResult(-802);
		}
		
		//等级判断
		//扣钱
		$price = $cardInfo['price'] * $num;
		if($cardInfo['price_type'] == 0 ) {
			$savedb = USE_CACHE ? false : true;
			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $price, $savedb);
			if ($ok) {
				//add log
				//$summary = '购买' . $background['name'];
				$summary = '购买道具,CID:'.$cardInfo['cid'].',num:'.$num;
				Hapyfish2_Ipanda_Bll_ConsumeLog::love($uid, $price, $summary, time());
				
			} else {
				$this->echoErrResult(-100);
			}
		} else {
			$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $price,
					'summary' 		=> '购买道具,CID:'.$cardInfo['cid'].',num:'.$num,
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
			$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
			if (!$ok) {
				$this->echoErrResult(-102);
			}
		}
		//入库，更新缓存 
		if($ok) {
			if ($cardInfo['item_type'] == 41) {
				//材料道具，直接加材料
				$ok2 = Hapyfish2_Ipanda_HFC_Material::addUserMaterial($uid, $cardInfo['effect_cid'], $num*$cardInfo['effect_num']);
				if ($ok2) {
					$result['materialVo'] = Hapyfish2_Ipanda_Bll_Material::getList($uid);
				}
			} else {
				$ok2 = Hapyfish2_Ipanda_HFC_Card::addUserCard($uid, $cid, $num);
			}
		}
		
		$result['status'] = 1;

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 道具列表
   	 * 
   	 */
   	public function mycardlistAction()
   	{		
		$uid = $this->uid;
		$list = Hapyfish2_Ipanda_Bll_Bag::getList($uid);
		
		$result['list'] = $list;
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/*
   	 * 
   	 * 使用道具
   	 */
	public function usecardAction()
	{
		$uid = $this->uid;
		$cid =  $this->_request->getParam('cid');
		if (empty($cid)) {
			$this->echoErrResult(-104);
		}
		
		$type = 0;
		$obj_id = $this->_request->getParam('obj_id');
		if (!empty($obj_id)) {
			// 1培育屋加速2 建筑维修加速
			$type = $this->_request->getParam('type');
			$param['obj_id'] = $obj_id;
			$param['type'] = $type;
		}
		
		$param['cid'] = $cid;

		//使用
		$returnMsg = array();
		$ok = Hapyfish2_Ipanda_Bll_Card::useCard($uid, $cid, $param, $returnMsg);
		if (!$ok) {
			$eid = $returnMsg['eid'];
			$error = array('eid' => $eid);
			if ($eid == -803) {
				$error['data'] = array('cid' => $cid);
			}
			$this->echoError($error);
		} else {
			$result['status'] = 1;
		}
		
        //成就(使用道具次数)
    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '12', 1);

		//前端非要数组
		if ($type == 1) {
			$result['phytotronVo'][] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, $obj_id);
		}
		else if($type == 2) {
			$result['buildingVo'][] = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $obj_id);
		}
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
   	
	/**
	 * 
	 * 成就完成的情况
	 * 
	 */
	public function myachievementAction()
   	{		
   		$uid = $this->uid;
		$list = Hapyfish2_Ipanda_Bll_Achievement::getAchievementDetail($uid);
		
		$result['list'] = $list;
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 修改称号
   	 *
   	 */
   	public function changetitleAction()
   	{
   		$uid = $this->uid;
   		$id = $this->_request->getParam('id');
   		
   		$achiInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementInfo($id);
   		if (!$achiInfo) {
   			$this->echoErrResult(-104);
   		}
   		
		$ok = Hapyfish2_Ipanda_HFC_User::changeTitle($uid, $id, true);
		if (!$ok) {
			$this->echoErrResult(-2);
		}
   		
   		$result['status'] = 1;
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}
   	   	
   	public function buildingunlockAction()
   	{
   		$uid = $this->uid;
   		$cid = $this->_request->getParam('cid');
   		if (empty($cid)) {
			$this->echoErrResult(-104);
		}
   		
   		$buildingInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
   		
   		if(!$buildingInfo)
   		{
   			$this->echoErrResult(-702);
   		}
   		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		//判断等级有没有达到
		if($userLevel >= $buildingInfo['need_level']) {
			$this->echoErrResult(-2);
		}
   		//有没有解锁记录
   		$ok = Hapyfish2_Ipanda_Bll_BuildingUnlock::checkByCid($uid, $cid);
   		if($ok)
   		{
   			$result['status'] = 1;
   		}
   		else 
   		{
   		//判断金币
   		//花金币解锁
			$gold = Hapyfish2_Ipanda_Bll_Gold::get($uid);
			if($gold < $buildingInfo['unlock_level_gold']) {
				$this->echoErrResult(-102);
			}
			else {
				$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $buildingInfo['unlock_level_gold'],
					'summary' 		=> '花费金币解锁建筑等级限制',
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid,$goldInfo);
				if (!$ok) {
					$this->echoErrResult(-102);
				}
			}
   			//解锁
			$info['cid'] 	= $cid;
			$info['uid'] 	= $uid;
			$ok = Hapyfish2_Ipanda_Bll_BuildingUnlock::addlog($uid, $info);
			if($ok) {
				$result['status'] = 1;
				$result['buildingUnlock'] 	= Hapyfish2_Ipanda_Bll_BuildingUnlock::getlist($uid);
			} else {
				$this->echoErrResult(-103);
			}
   		}
   
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	public function paddedmaterialAction()
   	{
   		$uid = $this->uid;
   		$info = $this->_request->getParam('info');
   		if (empty($info)) {
			$this->echoErrResult(-104);
		}
		$data = explode(',',$info);
		//计算价格
		$price = 0;
		$totalArr = array();
		foreach ($data as $v)
		{
			$arr = explode('|',$v);
			
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getMaterialInfo($arr[0]);

			if($basicInfo)
			{
				$totalArr[] = $arr; 
				$price += $arr[1] * $basicInfo['gold_price'];
				
			}
		}
		$price = ceil($price);

		if(empty($price))
		{
			$this->echoErrResult(-104);
		}
		//判断金币数量
		//扣钱
   		$gold = Hapyfish2_Ipanda_Bll_Gold::get($uid);
		if($gold < $price) {
			$this->echoErrResult(-102);
		}
		else 
		{
			$goldInfo = array(
				'uid' 			=> $uid,
				'cost' 			=> $price,
				'summary' 		=> '补齐材料:' . $info,
				'create_time' 	=> time(),
				'user_level' 	=> '',
				'cid' 			=> '',
				'num' 			=> ''
			);
			$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid,$goldInfo);
			if (!$ok) {
				$this->echoErrResult(-102);
			}
		}
		//维修用户加材料
		foreach ($totalArr as $v)
		{

			Hapyfish2_Ipanda_HFC_Material::addUserMaterial($uid, $v[0],$v[1]);
		}
		$result['status'] = 1;
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}
 }
