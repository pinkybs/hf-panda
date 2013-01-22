<?php

class ForestController extends Hapyfish2_Controller_Action_Api
{	
	/**
	 * 放装饰物
	 */
	public function putdecorateAction()
	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		$cid = $this->_request->getParam('cid');
		$x = $this->_request->getParam('x');
		$z = $this->_request->getParam('z');
		
		//购买类型 1 爱心 2 金币
		$type = $this->_request->getParam('type');
		
		if ($x === null || $z === null) {
			$this->echoErrResult(-101);
		}
		$x = (int)$x;
		$z = (int)$z;
		if ($x < 0 || $z < 0) {
			$this->echoErrResult(-101);
		}
		
		if (empty($id) && empty($cid)) {
			$this->echoErrResult(-104);
		}
		
		if (empty($id)) {
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($cid);
			if (empty($basicInfo)) {
				$this->echoErrResult(-101);
			}
			$id = 0;
			$mirro = 0;
		} else {
			$decorateInfo = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
			if (!$decorateInfo) {
				$this->echoErrResult(-103);
			}
			$cid = $decorateInfo['cid'];
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($cid);
			if (empty($basicInfo)) {
				$this->echoErrResult(-101);
			}
			$mirro = $decorateInfo['mirro'];
		}
		
		//检查坐标
		$ok = Hapyfish2_Ipanda_Bll_MapGrid::checkMap($uid, $x, $z, $mirro, $basicInfo['nodes'], $id, $cid);
		if (!$ok) {
			//不能重叠
			$this->echoErrResult(-108);
		}
		
		if (empty($id)) {			
			if ($basicInfo['price_type'] == 2) {
				if ($type == 1) {
					$buy_type = 0;
				} else if ($type == 2) {
					$buy_type = 1;
				} else {
					$this->echoErrResult(-104);
				}
			} else {
				$buy_type = $basicInfo['price_type'];
			}
			
			//购买流程
			if ($buy_type == 0) {
				$updateMoney = $basicInfo['love_price'];
				$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
				if ($userLove < $updateMoney) {
					$this->echoErrResult(-100);
				}
				
				//扣除爱心
				$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $updateMoney);
				if ($ok) {
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$updateMoney);
				}
			} else if ($buy_type == 1) {
				$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
				if ($userGold < $basicInfo['gold_price']) {
					$this->echoErrResult(-102);
				}
				
				$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $basicInfo['gold_price'],
					'summary' 		=> '花费金币购买装饰,CID:' . $basicInfo['cid'],
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
				if ($ok) {
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$basicInfo['gold_price']);
				}
			} else {
				$this->echoErrResult(-1);
			}
	
			if($ok) {
				//添加建筑到用户表
				$t = time();
				$info['uid'] 				= $uid;
				$info['cid'] 				= $basicInfo['cid'];
				$info['item_type'] 			= $basicInfo['item_type'];
				$info['forest_no'] 			= 0;
				$info['x'] 					= $x;
				$info['y'] 					= 0;
				$info['z'] 					= $z;
				$info['status'] 			= 1;
				$info['mirro'] 				= 0;
				$info['buy_time'] 			= $t;
				if ($buy_type == 0) {
					$info['buy_type'] = 0;
					//有效时间
					if ($basicInfo['effect_time'] > 0) {
						$info['end_time'] = $t + $basicInfo['effect_time']*86400;
					} else {
						$info['end_time'] = 0;
					}
				} else {
					$info['buy_type'] = 1;
					$info['end_time'] = 0;
				}
				
				$addOk = Hapyfish2_Ipanda_Bll_Decorate::add($uid, $info);
				if ($addOk) {
					//加入格子并计算加成影响
					Hapyfish2_Ipanda_Bll_MapGrid::addDecorate($uid, $info['id'], $info['cid'], $info['x'], $info['z'], $info['mirro'], $basicInfo);
				}
				
		        //成就(装饰购买次数)
		    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '10', 1);
		    	
		    	//派发任务
		    	$event = array('uid' => $uid, 'cid' => $basicInfo['cid']);
		    	Hapyfish2_Ipanda_Bll_Event::putDecorate($event);
			} else {
				$this->echoErrResult(-2);
			}
		} else {
			//更新流程
			$change = true;
			if ($decorateInfo['status'] == 1 && $decorateInfo['x'] == $x && $decorateInfo['z'] == $z) {
				$change = false;
			}
			if ($change) {
				$info = array();
				$info['status'] = 1;
				$info['x'] 		= $x;
				$info['z'] 		= $z;
				$updateOk = Hapyfish2_Ipanda_Bll_Decorate::update($uid, $id, $info);
				if ($updateOk) {
					//更新格子并重新计算加成影响
					if ($decorateInfo['status'] == 0) {
						Hapyfish2_Ipanda_Bll_MapGrid::addDecorate($uid, $id, $cid, $x, $z, $decorateInfo['mirro'], $basicInfo);
					} else {
						Hapyfish2_Ipanda_Bll_MapGrid::updateDecorate($uid, $id, $cid, $decorateInfo['x'], $decorateInfo['z'], $decorateInfo['mirro'], $x, $z, $decorateInfo['mirro'], $basicInfo);
					}
				}
			}
			
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $cid);
	    	Hapyfish2_Ipanda_Bll_Event::moveDecorate($event);
		}
		
		$result = array();
		$result['status'] = 1;
		if(empty($id))
		{
			$result['decorate'] = $info; 
		}
		else
		{
	       	$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid);
	       	$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
			$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
			$result['buildingVo'] 	= $buildingListOnForest['building_list'];
			$result['animal_list'] 	= $buildingListOnForest['animal_list'];
			$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
		}
		$ret = $this->returnResult($result);

		$this->echoResult($ret);
	}
	
	/**
	 * 放建筑
	 */
	public function putbuildingAction()
	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		$cid = $this->_request->getParam('cid');
		$x = $this->_request->getParam('x');
		$z = $this->_request->getParam('z');
		$result = array();
		$time_type = $this->_request->getParam('time_type');
		if (!in_array($time_type, array(0,1,2,3))) {
			$time_type = 0;
		}
		
		if ($x === null || $z === null) {
			$this->echoErrResult(-101);
		}
		$x = (int)$x;
		$z = (int)$z;
		if ($x < 0 || $z < 0) {
			$this->echoErrResult(-101);
		}
		
		if (empty($id) && empty($cid)) {
			$this->echoErrResult(-104);
		}
		
		if (empty($id)) {
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
			if (empty($basicInfo)) {
				$this->echoErrResult(-101);
			}
			$mirro = 0;
			$id = 0;
		} else {
			$buildingInfo = Hapyfish2_Ipanda_HFC_Building::getOne($uid, $id);
			$cid = $buildingInfo['cid'];
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
			if (empty($basicInfo)) {
				$this->echoErrResult(-101);
			}
			$mirro = $buildingInfo['mirro'];
		}
		
		//检查坐标
		$ok = Hapyfish2_Ipanda_Bll_MapGrid::checkMap($uid, $x, $z, $mirro, $basicInfo['nodes'], $id, $cid);
		if (!$ok) {
			//不能重叠
			$this->echoErrResult(-108);
		}
		
		//新建筑，需要购买
		if(empty($id)) {
			//等级
			$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
			if (!$userLevel) {
				$this->echoErrResult(-1);
			}
			
			if ($userLevel < $basicInfo['need_level']) {
				//判断有没有解锁
				$ok = Hapyfish2_Ipanda_Bll_BuildingUnlock::checkByCid($uid, $cid);
				if(!$ok)
				{
					$this->echoErrResult(-107);
				}
			}
			
			//材料
			$need_material = $basicInfo['need_material'];
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
			
			//爱心
			if ($basicInfo['price_type'] == 0) {
				$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
				if ($userLove < $basicInfo['love_price']) {
					$this->echoErrResult(-100);
				}
			} else if ($basicInfo['price_type'] == 1) {
				$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
				if ($userGold < $basicInfo['gold_price']) {
					$this->echoErrResult(-102);
				}
			} else {
				$this->echoErrResult(-1);
			}
			
			//开始购买流程
			//爱心
			if($basicInfo['price_type'] == 0) {
				$updateMoney = $basicInfo['love_price'];
				//扣除爱心
				$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $updateMoney);
				if(!$ok) {
					$this->echoErrResult(-100);
				}

				Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$updateMoney);
			} else {
				//宝石
				if ($basicInfo['gold_price'] > 0) {
					//宝石
					$goldInfo = array(
						'uid' 			=> $uid,
						'cost' 			=> $basicInfo['gold_price'],
						'summary' 		=> '购买建筑' . $basicInfo['name'] . ',CID:' . $basicInfo['cid'],
						'create_time' 	=> time(),
						'user_level' 	=> '',
						'cid' 			=> '',
						'num' 			=> ''
					);
					$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
					if (!$ok) {
						$this->echoErrResult(-102);
					}
					
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$basicInfo['gold_price']);
				}
			}
			
			//扣除材料
			if (!empty($need_material)) {
				$ok = Hapyfish2_Ipanda_HFC_Material::useMultiple($uid, $need_material, $userMaterial);
				if (!$ok) {
					info_log($uid . ':' . json_encode($need_material), 'need_material.err');
				}
			}

			$checkout = $basicInfo['checkout_time'];
			$i = 0;
			foreach ($checkout as $v)
			{
				$time_type = $i;
				if($v['default'] == 1)
				{
					$checkout_time = $v['time'];
					$checkout_love = $v['love'];
					break;
				}
				$i++;
			}
			//$checkout_time = $checkout[$time_type]['time'];
			//$checkout_love = $checkout[$time_type]['love'];
			
			//添加建筑到用户表
			$info['cid'] 				= $basicInfo['cid'];
			$info['uid'] 				= $uid;
			$info['level'] 				= $basicInfo['level'];
			$info['item_id'] 			= $basicInfo['item_id'];
			$info['item_type'] 			= $basicInfo['item_type'];
			$info['forest_no'] 			= 0;
			$info['checkout_time_type'] = $time_type;
			$info['checkout_time'] 		= $checkout_time;
			$info['checkout_love'] 		= $checkout_love;
			$info['deposit'] 			= 0;
			$info['x'] 					= $x;
			$info['z'] 					= $z;
			$info['status'] 			= 1;
			$info['mirro'] 				= 0;
			$info['buy_time'] 			= time();
			$info['durable'] 			= $basicInfo['durable'];
			$info['top_durable']		= $basicInfo['durable'];
			$info['op_status'] 			= 2;
			$attr = $basicInfo['attribute'];
			$attr[6] = 0;
			$attr[7] = 0;
			$info['attr'] 				= json_encode($attr);
			
			$addOk = Hapyfish2_Ipanda_Bll_Building::addBuilding($uid, $info, $basicInfo);
			if ($addOk) {
				//加入格子并计算加成影响
				Hapyfish2_Ipanda_Bll_MapGrid::addBuilding($uid, $info['id'], $info['cid'], $info['x'], $info['z'], $info['mirro'], $basicInfo);
			}
			$disease = Hapyfish2_Ipanda_Bll_Hospital::buildDisease($uid);
			$diseaselist = Hapyfish2_Ipanda_Bll_Hospital::getDisList($uid);
			if(!empty($diseaselist)){
				$update['diseaseList'] = $diseaselist;
			}
			if($disease){
				$update['diseaseVo'] = $disease;
				$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid, true);
			}
	        //成就(建筑建设数)
	    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '9', $num);
	    	
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $basicInfo['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::putNewBuilding($event);
		} else {
			//更新流程
			$info = array();
			$info['status'] = 1;
			$info['x'] 		= $x;
			$info['z'] 		= $z;
			Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $id, $info);
			
			if ($buildingInfo['status'] == 0) {
				Hapyfish2_Ipanda_Bll_MapGrid::addBuilding($uid, $id, $buildingInfo['cid'], $x, $z, $buildingInfo['mirro'], $basicInfo);
			} else {
				Hapyfish2_Ipanda_Bll_MapGrid::updateBuilding($uid, $id, $buildingInfo['cid'], $buildingInfo['x'], $buildingInfo['z'], $buildingInfo['mirro'], $x, $z, $buildingInfo['mirro'], $basicInfo);
			}
			
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $buildingInfo['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::moveBuilding($event);
		}
		
		$result['status'] = 1;
		
       	$result['phytotronVo'] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		$buildingListOnForest = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$result['buildingVo'] = $buildingListOnForest['building_list'];
		$result['animal_list'] = $buildingListOnForest['animal_list'];
		$result['animalVo'] = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
		if(isset($update['diseaseVo'])){
			$ret = $this->returnResult($result, $update);
		}else{
			$ret = $this->returnResult($result);
		}
		
		$this->echoResult($ret);
	}
	
	/*
	 * 放培育屋
	 */
	public function putphytotronAction()
	{		
		$uid = $this->uid;
		//echo $uid;exit;
		$forest_no = $this->_request->getParam('forest_no');
		$forest_no = empty($forest_no) ? 0 : $forest_no;
		$id = $this->_request->getParam('id');
		$time_type = $this->_request->getParam('time_type');
		$time_type = empty($time_type) ? 0 : $time_type;
		$basic_id = $this->_request->getParam('basic_id');
		$animal_id = $this->_request->getParam('animal_id');
		$x = $this->_request->getParam('x');
		$z = $this->_request->getParam('z');
		
		if ($x === null || $z === null ) {
			$this->echoErrResult(-101);
		}
		$x = (int)$x;
		$z = (int)$z;
		if ($x < 0 || $z < 0) {
			$this->echoErrResult(-101);
		}
		
		if (empty($id) && empty($animal_id)) {
			$this->echoErrResult(-104);
		}
		
		if (empty($id)) {
			if (empty($basic_id)) {
				$this->echoErrResult(-104);
			}
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronUnlockInfo($basic_id);
			if (!$basicInfo) {
				$this->echoErrResult(-103);
			}
			$mirro = 0;
			$id = 0;
			$cid = 0;
		} else {
			$phytotronInfo = Hapyfish2_Ipanda_Bll_Phytotron::getOnePhytotron($uid, $id);
			if (!$phytotronInfo) {
				$this->echoErrResult(-103);
			}
			$cid = $phytotronInfo['cid'];
			$mirro = $phytotronInfo['mirro'];
		}
		
		//检查坐标
		$ok = Hapyfish2_Ipanda_Bll_MapGrid::checkMap($uid, $x, $z, $mirro, '2*2', $id, $cid);
		if (!$ok) {
			//不能重叠
			$this->echoErrResult(-108);
		}

		//新建培育屋
		if (empty($id)) {
			//检查$animal_id是否合理
			$userAnimalInfo = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid, $animal_id);
			if (!$userAnimalInfo) {
				$this->echoErrResult(-104);
			}
			
			//判断条件
			
			//等级
			$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
			if (!$userLevel) {
				$this->echoErrResult(-1);
			}
			if ($userLevel < $basicInfo['level']) {
				$this->echoErrResult(-107);
			}
			
			//材料
			$need_material = $basicInfo['need_material'];
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
			
			//爱心
			$animalLevel = $userAnimalInfo['animal_level'];
			$animalLevelInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getAnimalLevelInfo($animalLevel);
			if ($animalLevelInfo) {
				$animalLove = $animalLevelInfo['love_price'];
			} else {
				$animalLove = 0;
			}
			if ($basicInfo['love_price'] > 0) {
				$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
				if ($userLove < $basicInfo['love_price'] + $animalLove) {
					$this->echoErrResult(-100);
				}
			} else if ($basicInfo['gold_price'] > 0) {
				$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
				if ($userGold < $basicInfo['gold_price']) {
					$this->echoErrResult(-102);
				}
				
				if ($animalLove > 0) {
					$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
					if ($userLove < $animalLove) {
						$this->echoErrResult(-100);
					}
				}
			} else {
				$this->echoErrResult(-1);
			}
			
			//是否已经用过了
			//TODO
			
			//开始购买流程
			$t = time();
			//爱心
			if ($basicInfo['love_price'] > 0) {
				//扣除爱心
				$love = $basicInfo['love_price'] + $animalLove;
				$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $basicInfo['love_price'] + $animalLove);
				if (!$ok) {
					$this->echoErrResult(-100);
				}

				Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$love);
			} else {
				if ($basicInfo['gold_price'] > 0) {
					//宝石
					$goldInfo = array(
						'uid' 			=> $uid,
						'cost' 			=> $basicInfo['gold_price'],
						'summary' 		=> '购买培育屋,animal_id:' . $animal_id . ',animal_level:' . $animalLevel,
						'create_time' 	=> $t,
						'user_level' 	=> '',
						'cid' 			=> '',
						'num' 			=> ''
					);
					$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
					if (!$ok) {
						$this->echoErrResult(-102);
					}
					
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$basicInfo['gold_price']);
				}
				
				if ($animalLove > 0) {
					//扣除爱心
					$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $animalLove);
					
					if ($ok) {
						Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$animalLove);
					}
				}
			}
			
			//扣除材料
			if (!empty($need_material)) {
				$ok = Hapyfish2_Ipanda_HFC_Material::useMultiple($uid, $need_material, $userMaterial);
				if (!$ok) {
					info_log($uid . ':' . json_encode($need_material), 'need_material.err');
				}
			}
			
			$info = array();
			$info['uid'] = $uid;
			$info['forest_no'] = $forest_no;
			$info['x'] = $x;
			$info['y'] = 0;
			$info['z'] = $z;
			$info['status'] = 1;
			$info['mirro'] = 0;
			$info['op_status'] = 2;
			$info['buy_time'] = $t;
			$info['item_type'] = 31;
			$info['product_num'] = 0;
			$info['leave_num'] = 0;
			$info['product_time_type'] = $time_type;
			$info['ipanda_user_phytotron_animal_id'] = $animal_id;
			$info['ipanda_phytotron_unlock_list_id'] = $basic_id;
			
			//计算到期时间
			$info['end_time'] = 0;
			if ($basicInfo['effect_time'] > 0) {
				$info['stop_time'] = $t + $basicInfo['effect_time']*86400;
			} else {
				$info['stop_time'] = 0;
			}
			$info['effect_exp'] = 0;
			$info['effect_source'] = '[]';
			
			$addOk = Hapyfish2_Ipanda_Bll_Phytotron::addPhytotron($uid, $info);
			
			$animalInfo['ipanda_phytotron_unlock_list_id'] = $basic_id;
			Hapyfish2_Ipanda_Bll_PhytotronAnimal::update($uid, $animal_id, $animalInfo);
			
			if ($addOk) {
				//加入格子并计算加成影响
				Hapyfish2_Ipanda_Bll_MapGrid::addPhytoron($uid, $info['id'], $userAnimalInfo['phytotron_cid'], $info['x'], $info['z'], $info['mirro']);
			}
			
	        //成就(建设培育屋数)
	    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '8', $num);
	    	
	    	//派发任务
	    	$event = array('uid' => $uid, 'animal_id' => $animal_id);
	    	Hapyfish2_Ipanda_Bll_Event::putNewPhytotron($event);
	    	
		} else {
			$info = array();
			$info['x'] = $x;
			$info['z'] = $z;
			$info['status'] = 1;
			
			Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
			
			if ($phytotronInfo['status'] == 0) {
				Hapyfish2_Ipanda_Bll_MapGrid::addPhytoron($uid, $id, $phytotronInfo['cid'], $x, $z, $phytotronInfo['mirro']);
			} else {
				Hapyfish2_Ipanda_Bll_MapGrid::updatePhytotron($uid, $id, $phytotronInfo['cid'], $phytotronInfo['x'], $phytotronInfo['z'], $phytotronInfo['mirro'], $x, $z, $phytotronInfo['mirro']);
			}
			
	    	//派发任务
	    	$event = array('uid' => $uid, 'cid' => $phytotronInfo['cid']);
	    	Hapyfish2_Ipanda_Bll_Event::movePhytotron($event);
		}

		$result['status'] = 1;
       	$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$result['buildingVo'] = $buildingListOnForest['building_list'];
		$result['animal_list'] = $buildingListOnForest['animal_list'];
		$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/*
	 * 
	 * 完成 建设
	 */
	public function putcompleteAction()
	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		
		//类型 0 建筑 1 培育屋
		$type = $this->_request->getParam('type');
		
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		if (0 == $type) {
			//当前建筑的状态
			$data = Hapyfish2_Ipanda_Bll_Building::getOne($uid, $id);
			if (!$data) {
				$this->echoErrResult(-103);
			}
			
			if($data['op_status'] == 2) {
				//体力
				$energy = EG_PER_BUILDING;
				$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
				if ($userEnergy['energy'] < $energy) {
					$this->echoErrResult(-105);
				}
				
				//消耗体力
				$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
				if (!$ok) {
					info_log($uid, 'energy_fix.err');
				} else {
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
				}
				
				Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_PER_BUILDING);
				$goods = array(array('cid' => 5, 'num' => EXP_PER_BUILDING));
				$update['goodsVo']['goods'] = $goods;
				$update['goodsVo']['bid'] = $id;
				$update['goodsVo']['type'] = 0;
				
				$info['op_status'] = 1;
				
		    	//派发任务
		    	$event = array('uid' => $uid, 'cid' => $data['cid']);
		    	Hapyfish2_Ipanda_Bll_Event::buildBuilding($event);
			}
			else {
				$info['op_status'] = 1;
			}
			
			$ok = Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $id, $info);			
			$data = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		}
		else {
			//当前培育屋的状态
			$data = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronInfo($uid, $id);
			if (!$data) {
				$this->echoErrResult(-103);
			}
			
			if ($data['op_status'] == 2) {
				//体力
				$energy = EG_PER_PHYTOTRON;
				$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
				if ($userEnergy['energy'] < $energy) {
					$this->echoErrResult(-105);
				}
				
				//消耗体力
				$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
				if (!$ok) {
					info_log($uid, 'energy_fix.err');
				} else {
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
				}
				
				Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_PER_PHYTOTRON);
				$goods = array(array('cid' => 5, 'num' => EXP_PER_PHYTOTRON));
				$update['goodsVo']['goods'] = $goods;
				$update['goodsVo']['bid'] = $id;
				$update['goodsVo']['type'] = 1;
				
				$info['op_status'] = 3;
			} else if ($data['op_status'] == 3) {
				$list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $id);
				$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($uid, $id);
		
				if (count($list) < $need['admin_num']) {
					$this->echoErrResult(-501);
				}
				$info['op_status'] = 1;
				
		    	//派发任务
		    	$event = array('uid' => $uid, 'ipanda_user_phytotron_animal_id' => $data['ipanda_user_phytotron_animal_id']);
		    	Hapyfish2_Ipanda_Bll_Event::buildPhytotron($event);
			} else {
				info_log(json_encode($data), 'putcomplete');
				$this->echoErrResult(-103);
			}
			
			$ok = Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
			$data = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, $id);
		}
		
		$result['status'] = 1;
		
		$result['type'] = $type;
		$result['data'] = $data;
		$result['id'] = $id;

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/*
	 * 放回仓库
	 */
	public function putpackageAction()
	{	
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		//类型 0 建筑 1 培育屋 2装饰
		$type = $this->_request->getParam('type');
		
		$info['status'] = 0;
		$info['mirro'] = 0;
		if (0 == $type) {
			$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
			if (!$building) {
				$this->echoErrResult(-103);
			}
			
			$buildingBasicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingInfo($building['cid']);
			if (!$buildingBasicInfo) {
				$this->echoErrResult(-103);
			}
			
			$info['wait_visitor_num'] = 0;
			$info['deposit'] = 0;
			$info['take_deposit'] = 0;
			$info['op_start_time'] = 0;
			$info['op_end_time'] = 0;
			$attr = $buildingBasicInfo['attribute'];
			$attr[6] = 0;
			$attr[7] = 0;
			$info['attr'] = json_encode($attr);
			$info['effect_source'] = '[]';

			$ok = Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $id, $info);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeBuilding($uid, $id, $building['cid'], $building['x'], $building['z'], $building['mirro']);
			}
		} else if (1 == $type) {
			$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getOnePhytotron($uid, $id);
			if (!$phytotron || $phytotron['status'] == 0) {
				$this->echoErrResult(-103);
			}
			
			$info['effect_exp'] = 0;
			$info['effect_source'] = '[]';
			$ok = Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removePhytotron($uid, $id, $phytotron['cid'], $phytotron['x'], $phytotron['z'], $phytotron['mirro']);
			}
		} else if (2 == $type) {
			$decorate = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($decorate['cid']);
			if($basicInfo['can_sale'] == 0){
				$this->echoErrResult(-1103);
			}
			if (!$decorate || $decorate['status'] == 0) {
				$this->echoErrResult(-103);
			}
			
			$ok = Hapyfish2_Ipanda_Bll_Decorate::update($uid, $id, $info);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $id, $decorate['cid'], $decorate['x'], $decorate['z'], $decorate['mirro']);
			}
		} else {
			$this->echoErrResult(-104);
		}
		
		if ($ok) {
			$result['status'] = 1;
		} else {
			$result['status'] = 0;
		}
		
       	$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
       	$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid);
		$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$result['buildingVo'] 	= $buildingListOnForest['building_list'];
		$result['animal_list'] 	= $buildingListOnForest['animal_list'];
		$result['animalVo'] 	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/*
	 * 全部放回仓库
	 * 
	 */
	public function putallpackageAction()
	{		
		$uid = $this->uid;
		
		//类型 0 建筑 1 培育屋 2 装饰
		$type = $this->_request->getParam('type');
		
		$info['status'] = 0;
		$info['mirro'] = 0;
		if (0 == $type ) {
			$info['wait_visitor_num'] = 0;
			$info['deposit'] = 0;
			$info['take_deposit'] = 0;
			$info['op_start_time'] = 0;
			$info['op_end_time'] = 0;
			$info['effect_source'] = '[]';
			
			$ok = Hapyfish2_Ipanda_Bll_Building::updateAllBuilding($uid, $info);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeAllBuilding($uid);
			}
		}
		else if(1 == $type) {
			$info['effect_exp'] = 0;
			$info['effect_source'] = '[]';
			$ok = Hapyfish2_Ipanda_Bll_Phytotron::updateAllPhytotron($uid, $info);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeAllPhytotron($uid);
			}
		}
		else if(2 == $type) {
			$userDecorate = Hapyfish2_Ipanda_Bll_Decorate::getList($uid);
			if($userDecorate){
				foreach($userDecorate as $k => $v){
					$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($v['cid']);
					if($basicInfo['can_recyle'] != 0){
						$ok = Hapyfish2_Ipanda_Bll_Decorate::update($uid, $v['id'], $info);
						if ($ok) {
							Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $v['id'], $v['cid'], $v['x'], $v['z'], $v['mirro']);
						}
					}
				}
			}
		}
		
		if ($ok) {
			$result['status'] = 1;
		} else {
			$result['status'] = 0;
		}
		
		$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid);
		$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		
		$result['buildingVo'] 	= $buildingListOnForest['building_list'];
		$result['animal_list'] 	= $buildingListOnForest['animal_list'];
		$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/**
	 * 更新 培育屋 建筑的 时间选项 及动物
	 */
	public function updatebuildingandphytotronAction()
	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		
		//类型 0 建筑 1 培育屋
		$type = $this->_request->getParam('type');
		//field 1 时间选项|时间选项 2  无｜更改动物
		$field = $this->_request->getParam('field');
		
		$num = $this->_request->getParam('num');
		if (empty($id) || empty($field)) {
			$this->echoErrResult(-104);
		}
		
		$update = array();
		
		if (0 == $type) {
			$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
			if (empty($building)) {
				$this->echoErrResult(-708);
			}
			
			if ($building['durable'] == 0) {
				$this->echoErrResult(-2);
			}
			
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($building['cid']);
			
			$checkout = $basicInfo['checkout_time'];
			if (!isset($checkout[$num])) {
				$this->echoErrResult(-104);
			}
			
			$checkout_love = $checkout[$num]['love'];
			if (empty($checkout_love)) {
				$this->echoErrResult(-103);
			}
			
			$checkout_time = $checkout[$num]['time'];
			$updateInfo['checkout_time_type'] = $num;
			$updateInfo['checkout_time'] = $checkout_time;
			$updateInfo['checkout_love'] = $checkout_love;
			
			$t = time();
			//重新分配和计算结算时间
			if ($building['op_end_time'] > $t) {
				$unit_time = UNIT_TIME;
				$n1 = floor($checkout_time/$unit_time);
				if ($n1 > $building['wait_visitor_num']) {
					$n1 = $building['wait_visitor_num'];
				}
				if ($n1 > $building['durable']) {
					$n1 = $building['durable'];
				}
				
				if ($n1 > 0) {
					$updateInfo['effect_deposit'] = $n1*$checkout_love;
					$updateInfo['deposit'] = 0;
					$updateInfo['op_start_time'] = $t;
					$updateInfo['op_end_time'] = $t + $checkout_time;
				} else {
					$updateInfo['effect_deposit'] = 0;
					$updateInfo['deposit'] = 0;
					$updateInfo['op_start_time'] = 0;
					$updateInfo['op_end_time'] = 0;
				}
			}
			
			$ret = Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $id, $updateInfo);
			
			if ($ret) {
		    	//派发任务
		    	$event = array('uid' => $uid, 'cid' => $building['cid']);
		    	Hapyfish2_Ipanda_Bll_Event::changeBuildingTime($event);
			}
		}
		else {
			$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, $id);
			if (empty($phytotron)) {
				$this->echoErrResult(-210);
			}
			
			if($field == 1) {
				if (!in_array($num, array(0,1,2,3))) {
					$this->echoErrResult(-103);
				}
				$updateInfo['product_time_type'] = $num;
				$updateInfo['end_time'] = 0;
			}
			else if($field == 2) {
				//换动物 ipanda_user_phytotron_animal_id
				$paId = $num;
       			$row = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid, $paId);
				if (empty($row)) {
					$this->echoErrResult(-601);
				}
				
				//改建费
				$love_price = $row['love_price'];
				if ($love_price > 0) {
					//检查
					$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
					if ($userLove < $love_price) {
						$this->echoErrResult(-100);
					}
					
					$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $love_price);
					if (!$ok) {
						$this->echoErrResult(-2);
					}
					
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$love_price);
				}
				
				$updateInfo = array();
				$updateInfo['ipanda_user_phytotron_animal_id'] = $num;
				$updateInfo['op_status'] = 2;
				$updateInfo['end_time'] = 0;
			}
			
			$ret = Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $updateInfo);
			
			if ($ret) {
				//更改培育屋时间
				if ($field == 1) {
			    	//派发任务
			    	$event = array('uid' => $uid, 'animal_cid' => $phytotron['animal_cid']);
			    	Hapyfish2_Ipanda_Bll_Event::changePhytotronTime($event);
				}
				
				//更改动物
				if ($field == 2) {
					//加经验
					Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_PER_PHYTOTRON);
					
					$goods = array(array('cid' => 5, 'num' => EXP_PER_PHYTOTRON));
					$update['goodsVo']['goods'] = $goods;
					$update['goodsVo']['bid'] = $id;
					$update['goodsVo']['type'] = 1;
					
					//更新格子数据
					//改了动物后，对应的培育屋cid会变化
					Hapyfish2_Ipanda_Cache_MapGrid::upgradePhytotronToGrid($uid, $phytotron['id'], $phytotron['cid'], $phytotron['x'], $phytotron['z'], $phytotron['mirro'], '2*2', $row['phytotron_cid']);
				}
			}
		}
		
		if ($ret) {
			$result['status'] = 1;
		} else {
			$result['status'] = 0;
		}
		
		if ($type == 0) {
			$result['buildingVo'] = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		} else {
			$result['phytotronVo'] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, $id);
		}

		$ret = $this->returnResult($result, $update);
		$this->echoResult($ret);
	}
	
	/*
	 * 卖出 0 建筑 1 培育屋 2 装饰 
	 */
	public function salethingAction()
	{		
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		//类型 0 建筑 1 培育屋  2装饰
		$type = $this->_request->getParam('type');
		$love = 0;
		
		if (0 == $type) {
			//获取 建筑信息
			$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
			if (empty($building)) {
				$this->echoErrResult(-104);
			}
	
			$ok = Hapyfish2_Ipanda_Bll_Building::removeBuilding($uid, $id);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeBuilding($uid, $id, $building['cid'], $building['x'], $building['z'], $building['mirro']);
				$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($building['cid']);
				if ($basicInfo && $basicInfo['sale_price'] > 0) {
					$love = $basicInfo['sale_price'];
				}
			} else {
				$this->echoErrResult(-2);
			}
		} else if(1 == $type) {
			//培育屋不能卖
			$this->echoErrResult(-104);
			//$ret = Hapyfish2_Ipanda_Bll_Phytotron::removePhytotron($uid, $id);
		} else if(2 == $type) {
			//获取 装饰信息
			$decorate = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
			$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($decorate['cid']);
			if($basicInfo['can_sale'] == 0){
				$this->echoErrResult(-1102);
			}
			if (empty($decorate)) {
				$this->echoErrResult(-104);
			}
			$ok = Hapyfish2_Ipanda_Bll_Decorate::remove($uid, $id);
			if ($ok) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $id, $decorate['cid'], $decorate['x'], $decorate['z'], $decorate['mirro']);
				if ($basicInfo && $basicInfo['sale_price'] > 0) {
					$love = $basicInfo['sale_price'];
				}
			} else {
				$this->echoErrResult(-2);		
			}
		} else {
			$this->echoErrResult(-104);
		}
		
		if ($ok) {
			//加爱心
			if ($love > 0) {
				$ok = Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $love, true);
				if ($ok) {
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', $love);
				}
			}
			
			$result['status'] = 1;
	        //成就(出售商品次数)
	    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '16', 1);
		}
		
   		$buildingListOnForest = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
   		$result['buildingVo'] = $buildingListOnForest['building_list'];
   		$result['animal_list'] = $buildingListOnForest['animal_list'];
   		$result['animalVo'] = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
   		$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid);
   		$result['phytotronVo'] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/*
	 * 扩地
	 */
	public function developlandAction()
	{
		$forest_no = $this->_request->getParam('forest_no');
		$forest_no = empty($forest_no) ? 0 : $forest_no;
		$uid = $this->uid;
		
		$buy_type = $this->_request->getParam('buy_type');
		$buy_type = empty($buy_type) ? 0 : $buy_type;
		$lock = Hapyfish2_Cache_Factory::getLock($uid);
		
		$key = 'i:u:deveplopland:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        
        //get lock
        $ok = $lock->lock($key);
		if (!$ok) {
			$this->echoErrResult(-101);
        }
       	
       	//判断扩第几块地
       	$forestVo = Hapyfish2_Ipanda_Bll_Forest::getForestByNo($uid, $forest_no);
		
		$result['forestVo'] = $forestVo;
		$theLandNum = $forestVo['extend_land'] + 1;
		
       	//获得扩地的条件
       		
       	$info = Hapyfish2_Ipanda_Bll_BasicInfo::getEntendForestInfo($theLandNum);
       	
       	//判断条件
       	
       	// 等级够不够
       	$userLevel= Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
       	if ($userLevel < $info['level']) {
	        //release lock
	        $lock->unlock($key);
	        $this->echoErrResult(-401);
       	}
       	
       	//扣钱
       	if ($buy_type == 0) {
       		//扣爱心       		
       		$userLove =  Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
       		if ($userLove < $info['love_price']) {
		        //release lock
		        $lock->unlock($key);
				$this->echoErrResult(-100);
       		}
       		
			//扣除爱心
			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $info['love_price']);
			if (!$ok) {
		        //release lock
		        $lock->unlock($key);
				$this->echoErrResult(-2);
			}
			
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$info['love_price']);
       	} else {
       		//扣金币
       		$gold = Hapyfish2_Ipanda_Bll_Gold::get($uid);
			if($gold < $info['gold_price']) {
		        //release lock
		        $lock->unlock($key);
		        
		        $this->echoErrResult(-102);
			} else {
				$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $info['gold_price'],
					'summary' 		=> '花费金币扩地',
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				
				$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
				if (!$ok) {
			        //release lock
			        $lock->unlock($key);
			        $this->echoErrResult(-2);
				}
				
				Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$info['gold_price']);
			}
       	}

       	//扩地
       	$ret = Hapyfish2_Ipanda_Bll_Forest::extend_land($uid, $forest_no);
       	if (!$ret) {
	        //release lock
	        $lock->unlock($key);
	        $this->echoErrResult(-103);
       	}
       	
		//加经验
		Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_EXPAND_LAND);
		
    	//派发任务
    	//扩地
    	$event = array('uid' => $uid, 'land_num' => $theLandNum + 1);
    	Hapyfish2_Ipanda_Bll_Event::expandLand($event);
    	
        //release lock
        $lock->unlock($key);
       	
       	$result['status'] = 1;
       	$forestVo = Hapyfish2_Ipanda_Bll_Forest::getForestByNo($uid,$forest_no);
		$result['forestVo'] = $forestVo;

       	//$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		//$result['buildingVo'] 	= $buildingListOnForest['building_list'];
		$result['animal_list'] 	= $buildingListOnForest['animal_list'];
		$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
		$result['expire']		= array('keepicon' => 1);
		
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
   	public function expirelistAction()
   	{
   		$uid = $this->uid;
   		$type = $this->_request->getParam('type');
   		$list = array();
   		
   		if ($type == 1) {
   			//装饰物
			$list = Hapyfish2_Ipanda_Bll_Expire::getDecorateList($uid);
   		} else if ($type == 2) {
   			//培育物屋
			$list = Hapyfish2_Ipanda_Bll_Expire::getPhytotronList($uid);
   		} else {
   			$this->echoErrResult(-104);
   		}
   		
   		$result = array(
   			'status' => 1,
   			'list'	=> $list
   		);
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}
   	
   	public function expirehandlerAction()
   	{
   		$uid = $this->uid;
   		$type = $this->_request->getParam('type');
   		
   		if ($type == 1) {
   			//装饰物
   			$cid = (int)$this->_request->getParam('cid', 0);
   			$priceType = $this->_request->getParam('priceType', 1);
   			if ($priceType != 1 && $priceType != 2) {
   				$this->echoErrResult(-104);
   			}
   			
   			if ($cid > 0) {
   				$code = Hapyfish2_Ipanda_Bll_Expire::renewalDecorateByCid($uid, $cid, $priceType);
   			} else {
   				$code = Hapyfish2_Ipanda_Bll_Expire::renewalAllDecorate($uid, $priceType);
   			}

	   		if ($code <= 0) {
	   			$this->echoErrResult($code);
	   		}
   		} else if ($type == 2) {
   			//培育物屋
   			$id = $this->_request->getParam('id');
   			if (empty($id)) {
   				$this->echoErrResult(-104);
   			}
   			
   			$ret = Hapyfish2_Ipanda_Bll_Expire::renewalPhytotron($uid, $id);
   			if (is_array($ret)) {
				$this->echoError($ret);
   			} else {
   				if ($ret <= 0) {
   					$this->echoErrResult($ret);
   				}
   			}
   		} else {
   			$this->echoErrResult(-104);
   		}
   		
   		$result = array('status' => 1);
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}
   	
   	public function setinteractionAction()
   	{
   		$uid = $this->uid;
   		$fuid = $this->_request->getParam('fuid');
   		if(empty($fuid))
   		{
   			$this->echoErrResult(-104);
   		}
   		$data = Hapyfish2_Ipanda_Cache_User::readInteractive($uid);
   		$row = $data[$fuid];
   		if(empty($row))
   		{
   			$this->echoErrResult(-103);
   		}
   		//计算增加的经验和爱心
   		$love = 0;
   		$exp = 0;
   		$userVO = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
   		$levelbasicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getUserLevelInfo($userVO['level']);
   		$list = array();
   		$i = 0;
   		$checkoutnum = 0;
   		foreach($row['list'] as $v)
   		{
   			//培育屋
   			if($v['type'] == 1)
   			{
   				$num = $levelbasicInfo['rent_award'] * $v['num'];
   				$love += $num;
   				$goods = array();
   				$goods[] = array('cid' => 1, 'num' => $num);
   				$goods[] = array('cid' => 5, 'num' => 1);
   				$list[$i] = $goods;
   			}
   			else 
   			{
   				//给建筑结算
   				$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $v['id']);
   				if($building['state'] != 4)
   				{
   					break;
   				}
   				Hapyfish2_Ipanda_Bll_Building::checkout($building);
   				$gainLove = $building['effect_deposit'];
				$gainExp = EXP_CHECKOUT_LOVE;
				//加成
				$loveAdditionRate = $building['loveAdditionRate'];
				if ($loveAdditionRate ) {
					if ($loveAdditionRate > 900) {
						$loveAdditionRate = 900;
					}
					if($loveAdditionRate <= -100){
						$gainLove = 1;
					}else{
						$gainLove = floor($gainLove*(100+$loveAdditionRate)/100);
					}
				}
				$expAdditionRate = $building['expAdditionRate'];
				if ($expAdditionRate > 0) {
					if ($expAdditionRate > 900) {
						$expAdditionRate = 900;
					}
					$gainExp = floor($gainExp*(100+$expAdditionRate)/100);
				}
   				
				$love += $gainLove;
				
   				$cid = $building['cid'];
				$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
				$animalInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($basicInfo['animal_cid']);
				$basicInfo['attribute'] = $building['attribute'];
	   			$get_material = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterial($basicInfo, $animalInfo);
				
				if (!empty($get_material)) {
					//添加材料 
					$get_material = json_decode($get_material, true);
					foreach ($get_material as $v) {
						if($v['num'] > 0 ) {
							Hapyfish2_Ipanda_HFC_Material::addUserMaterial($uid, $v['cid'], $v['num']);
						}
					}
				}
				$get_material[] = array('cid' => 1, 'num' => $gainLove);
   				$get_material[] = array('cid' => 5, 'num' => $gainExp + 1);
	   			$goods = array();
				foreach ($get_material as $v) {
					if($v['num'] > 0) {
						$goods[] = $v ;
					}
				}
				$list[$i] = $goods;
				$checkoutnum++;
				$exp += $gainExp;
   			}
   			
			$i++;
   			$exp++;
   		}
   		
   		if ($checkoutnum > 0) {
   			Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '3', $checkoutnum);
   		}
   		
   		$ok = Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $love);
   		if ($ok) {
		    //派发任务
		    $event = array('uid' => $uid, 'love' => $love);
		    Hapyfish2_Ipanda_Bll_Event::gainLove($event);
   		}
   		Hapyfish2_Ipanda_HFC_User::incUserExp($uid, $exp);
   		Hapyfish2_Ipanda_Cache_User::removeInteractive($uid, $fuid);

   		$result = array(
   			'status' => 1,
   			'goodsVo' => $list,
   			'fuid' => $fuid,
   		);
   		
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}

 }
