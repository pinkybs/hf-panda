<?php

class AnimalController extends Hapyfish2_Controller_Action_Api
{    
    /*
     * 动物 解锁列表
     * 
     */
    public function unlocklistAction()
    {		
		$uid = $this->uid;

    	$result['list'] = Hapyfish2_Ipanda_Bll_PhytotronAnimal::unlocklist($uid);
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
    }
    
    /*
     * 解锁动物
     */
    public function unlockAction()
    {		
		$uid = $this->uid;
		
		$cid = $this->_request->getParam('cid');
		if (empty($cid)) {
			$this->echoErrResult(-104);
		}
		
		//获得动物的基本信息
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($cid);
		if (empty($basicInfo)) {
			$this->echoErrResult(-601);
		}
		
		$userAniamlList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalListForCid($uid);
		if (!empty($userAniamlList[$cid])) {
			$this->echoErrResult(-602);
		}
		
		//判断解锁条件是否达到
		if (!empty($basicInfo['unlock_condition'])) {
			$unlock_condtion = $basicInfo['unlock_condition'];
			for ($i = 0, $count = count($unlock_condtion); $i < $count; $i++) {
				$theCid = $unlock_condtion[$i]['cid'];
				if($theCid == 0) {
					continue;
				}
				
				if (empty($userAniamlList[$theCid])) {
					//条件中的动物 未解锁
					$this->echoErrResult(-603);
				} else if($userAniamlList[$theCid]['animal_level'] < $unlock_condtion[$i]['level']) {
					//条件中的动物等级不足
					$this->echoErrResult(-604);
				}
			}
		}
	
		//扣钱 扣金币 解锁
		$price_type = $basicInfo['price_type'];
		//爱心
		if ($price_type == 0) {
			$love = (int)$basicInfo['love_price'];
			if ($love > 0) {
				$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
				if ($userLove < $love) {
					$this->echoErrResult(-100);
				}
				
				//扣除爱心
				$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $love);
				if (!$ok) {
					$this->echoErrResult(-2);
				}
			}
		} else if ($price_type == 1) {
			$gold = (int)$basicInfo['gold_price'];
			$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
			if ($userGold < $gold) {
				$this->echoErrResult(-102);
			}
			
			//宝石
			$goldInfo = array(
				'uid' 			=> $uid,
				'cost' 			=> $gold,
				'summary' 		=> '解锁动物:' . $basicInfo['name'],
				'create_time' 	=> time(),
				'user_level' 	=> '',
				'cid' 			=> '',
				'num' 			=> ''
			);
			$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
			if (!$ok) {
				$this->echoErrResult(-2);
			}
		}
		
		//解锁动物
		$animalInfo['uid'] = $uid;
		$animalInfo['ipanda_phytotron_unlock_list_id'] = 0;
		$animalInfo['phytotron_cid'] = $basicInfo['phytotron_cid'];
		$animalInfo['phytotron_class_name'] = $basicInfo['phytotron_class_name'];
		$animalInfo['phytotron_name'] = $basicInfo['phytotron_name'];
		$animalInfo['product_time_type'] = 0;
		$animalInfo['animal_cid'] = $basicInfo['cid'];
		$animalInfo['animal_class_name'] = $basicInfo['class_name'];
		$animalInfo['header_class'] = $basicInfo['header_class'];
		$animalInfo['animal_name'] = $basicInfo['name'];
		$animalInfo['service_num'] = 0;
		$animalInfo['product_time_unlock'] = null;
		Hapyfish2_Ipanda_Bll_PhytotronAnimal::add($uid, $animalInfo);
		
        //成就(动物解锁数)
   		Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '11', 1);
   		
    	//派发任务
    	//解锁动物
    	$event = array('uid' => $uid, 'animal_cid' => $animalInfo['animal_cid']);
    	Hapyfish2_Ipanda_Bll_Event::unlockAnimal($event);
		
		$result['status'] = 1;
    	$result['list'] = Hapyfish2_Ipanda_Bll_PhytotronAnimal::unlocklist($uid);
		$result['animalVo'] = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
    }
    
   	/**
   	 * 租动物
   	 */
   	public function rantanimalAction()
   	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		$fuid =  $this->_request->getParam('fuid');
		if (empty($id) || empty($fuid)) {
			$this->echoErrResult(-104);
		}
   		//判断帮助次数
		$interactiveNum = Hapyfish2_Ipanda_Cache_User::getInteractionNum($fuid , $uid);
		if( $interactiveNum >= 5)
		{
			$this->echoErrResult(-713);
		}
		
		//取得培育屋信息
   		$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($fuid, $id);
   		if (!$phytotron) {
   			$this->echoErrResult(-103);
   		}
   		
   		//是否租过
   		//$canRent = Hapyfish2_Ipanda_Bll_Phytotron::canRentAnimal($uid, $phytotron);
   		$canRent = $phytotron['can_rent_animal'];
   		if (!$canRent) {
   			$this->echoErrResult(-507);
   		}
   		
   		//判断玩家等级
   		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
   		if($userLevel < RENT_ANIMAL_LEVEL) {
   			$this->echoErrResult(-107);
   		}
   		
   		//培育物和动物基本信息
   		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($phytotron['animal_cid']);
   		
   		//判断有没有消费的建筑
   		$consumeBuilding = $basicInfo['consume_building'];
   		$buildFlag = Hapyfish2_Ipanda_Bll_Building::hadAnimalConsumeBuilding($uid, $consumeBuilding);
       	if (!$buildFlag) {
       		//$this->echoErrResult(-504);
       		$error['eid'] = -504;
       		$error['data'] = $basicInfo['cid'];
       		$msgdata['name'] = $basicInfo['name'];
       		$error['msg'] = Hapyfish2_Ipanda_Bll_Language::getText('have not {*name*} consume build,can not release', $msgdata);
        	$this->echoError($error);
       	}
   		
   		//判断有没有到达消费上限
   		$forest_no = 0;
   		$animalTop = Hapyfish2_Ipanda_Bll_Forest::getAnimalTop($uid, $forest_no);
   		$currentNum = Hapyfish2_Ipanda_Bll_Forest::getCurrentAnimalNum($uid, $forest_no);
   		if ($currentNum >= $animalTop) {
   			$this->echoErrResult(-503);
        }
        
   		//剩余的动物 
   		if ($phytotron['leave_animal_num'] <= 1) {
   			$this->echoErrResult(-508);
   		}
   		
        //体力  2012-02-07 修改 注销
   	   	/*
   		$energy = EG_RENT_ANIMAL;
		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
		if ($userEnergy['energy'] < $energy) {
			$this->echoErrResult(-105);
		}
		
   		$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
		if (!$ok) {
		} else {
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
		}
		*/
   		
		//加经验
		Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_RECIVE_ANIMAL);
		$goods = array(array('cid' => 5, 'num' => EXP_RECIVE_ANIMAL));
		$update['goodsVo']['goods'] = $goods;
		$update['goodsVo']['bid'] = $id;
		$update['goodsVo']['type'] = 1;
        
   		//租到动物 ，记录日志
   		$num = rand(1, 5);
   		if($num >= $phytotron['leave_animal_num']) {
   			$num = $phytotron['leave_animal_num'] - 1;
   		}
   		$can_num = $animalTop - $currentNum;
   		if ($num >= $can_num) {
   			$num = $can_num ;
   		}
   		
		$reciveList = Hapyfish2_Ipanda_Bll_Building::reciveAnimal($uid, $num, $consumeBuilding, $basicInfo, 1);
		Hapyfish2_Ipanda_Bll_Phytotron::rentAnimalLog($uid, $phytotron, $num);
		
		//发消息日志
		$minifeed = array(
			'uid' => $fuid,
	        'template_id' => 3,
	        'actor' => $uid,
	        'target' => $fuid,
	        'title' => array('actor' => $uid, 'num' => $num, 'animal_name' => $basicInfo['name']),
	        'type' => 3,
	        'create_time' => time()
		);
	    Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($minifeed);
		
		//更新培育屋
		$info['leave_num'] =$phytotron['leave_animal_num'] - $num;
		Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($fuid, $phytotron['id'], $info);
		
		$userVo = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
		//记录操作
		$loginfo['type'] = 1;
		$loginfo['id'] = $phytotron['id'];
		$loginfo['num'] = $num;
		$loginfo['uid'] = $userVo['uid'];
		$loginfo['face'] = $userVo['face'];
		$loginfo['name'] = $userVo['name'];
		
		Hapyfish2_Ipanda_Cache_User::logInteractive($fuid, $uid, $loginfo);
		
		//成就(领养动物次数)
		Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '6', 1);
		
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
		
		$result['status'] = 1;
		$result['animal_list'] = $reciveList;
		$result['rent_info'] = array('cid' => $basicInfo['cid'] ,'num' =>$num ,'header_icon' => $basicInfo['header_class']);
		Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, $basicInfo['cid'], +$num);
		$ret = $this->returnResult($result, $update);
		$this->echoResult($ret);
   	}
   	
	/**
	 * 收动物
	 */
	public function reciveanimalAction()
	{
		$id = $this->_request->getParam('id');
		$uid = $this->uid;
		
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
        $phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronInfo($uid, $id);
        if (!$phytotron) {
        	$this->echoErrResult(-103);
        }
        
	    if ($phytotron['end_time'] > time()) {
        	$this->echoErrResult(-502);
        }
		
	 	$lockkey = 'i:u:lock:reciveanimal:' . $uid . ':'. $id;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
        
        //体力
		$energy = EG_RECIVE_ANIMAL;
		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
		if ($userEnergy['energy'] < $energy) {
			$lock->unlock($lockkey);
			$this->echoErrResult(-105);
		}
        
        //是不是到了上限
        $forest_no = 0;
        $animalTop = Hapyfish2_Ipanda_Bll_Forest::getAnimalTop($uid, $forest_no);
        //当前森林的动物
        $currentNum = Hapyfish2_Ipanda_Bll_Forest::getCurrentAnimalNum($uid, $forest_no);
        
        $productNum = Hapyfish2_Ipanda_Bll_Phytotron::getProductNum($phytotron);
        
        if (($productNum + $currentNum ) > $animalTop) {
        	$error['eid'] = -503;
        	//$error['msg'] = '(' . $currentNum . ' +' . $productNum . ')  >' . $animalTop;
        	$lock->unlock($lockkey);
        	$this->echoError($error);
        }
        
	   	//消耗体力
		$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
		if (!$ok) {
		} else {
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
		}
        
        //当前动物有无消费建筑
        $paId = $phytotron['ipanda_user_phytotron_animal_id'];
       	$animalInfo = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid, $paId);
       	
       	$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($animalInfo['animal_cid']);
       	
       	$consumeBuilding = $basicInfo['consume_building'];
       	
       	$buildFlag = Hapyfish2_Ipanda_Bll_Building::hadAnimalConsumeBuilding($uid, $consumeBuilding);
       	if (!$buildFlag) {
       		$error['eid'] = -504;
       		$error['data'] = $basicInfo['cid'];
       		$msgdata['name'] = $basicInfo['name'];
       		$error['msg'] = Hapyfish2_Ipanda_Bll_Language::getText('have not {*name*} consume build,can not release', $msgdata);
        	$lock->unlock($lockkey);
       		$this->echoError($error);
       	}
       	
		//加经验
		$gainExp = EXP_RECIVE_ANIMAL;
		$expAdditionRate = $phytotron['effect_exp'];
		if ($expAdditionRate > 0) {
			$gainExp = floor($gainExp*(100+$expAdditionRate)/100);
		}
		Hapyfish2_Ipanda_HFC_User::incUserExp($uid, $gainExp);
		$goods = array(array('cid' => 5, 'num' => $gainExp));
		$time = time();
		$bell = Hapyfish2_Ipanda_Event_Bll_Christmas::bell($uid, $time);
		if($bell){
			$userBell['cid'] = 2145;
			$userBell['num'] = 1;
			$goods[] = $userBell;
		}
		$update['goodsVo']['goods'] = $goods;
		$update['goodsVo']['bid'] = $id;
		$update['goodsVo']['type'] = 1;
		
		$hNum = count($goods);
		if ($hNum > 0) {
			Hapyfish2_Ipanda_Cache_ComboHit::add($uid, $hNum);
		}
       	
        //放出动物 其中有一部分是因一定概率不满离开
        //收动物
        $reciveList = Hapyfish2_Ipanda_Bll_Building::reciveAnimal($uid, $productNum, $consumeBuilding, $basicInfo);
       
        //收完动物后重新生产
       	$product_time_item = $basicInfo['product_time'];
       	$product_need_time = $product_time_item[$phytotron['product_time_type']]['time'];
       	$product_num = $product_time_item[$phytotron['product_time_type']]['num'];
       
        Hapyfish2_Ipanda_Bll_Phytotron::product($phytotron, $product_need_time, $product_num);
        
        $result['status'] = 1;
       	$result['animal_list'] = $reciveList;
       	
        /*
       	$forestVo = Hapyfish2_Ipanda_Bll_Forest::getForestByNo($uid,$forest_no);
		$result['forestVo'] = $forestVo;
       	$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$result['buildingVo'] = $buildingListOnForest['building_list'];
		$result['animal_list'] = $buildingListOnForest['animal_list'];
		$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
		*/
       	
       	//获得此培育物的管理员列表
       	$admin_list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $id);
       	foreach ($admin_list as $v) {
       		$send_num = floor($productNum /10);
       		$send_num =  $send_num > 0 ? $send_num : 1;
       		if (!empty($v['friend_uid'])){
       			$updateInfo['benefit'] = $send_num;
       			Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::updatelogbyphytotronid($v['friend_uid'], $uid, $id, $updateInfo);
       		}
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
       	
       	//判断是否能掉铃铛
       	//释放锁
       	$lock->unlock($lockkey);
       	
       	$result['id'] = $id;
       	$result['phytotron'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, $id);
       	$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$result['buildingVo'] 	= $buildingListOnForest['building_list'];
		
       	$data = $this->returnResult($result, $update);
        $this->echoResult($data);
	}
    
    /**
     * 
     * 增加亲密度
     * 
     */
    public function addintimacyAction()
    {
		$uid = $this->uid;
		
		$cid = $this->_request->getParam('cid');
		if (empty($cid)) {
			$this->echoErrResult(-104);
		}
		
		$ok = Hapyfish2_Ipanda_Bll_PhytotronAnimal::addintimacy($uid, $cid);
		if ($ok < 0) {
			$this->echoErrResult($ok);
		}
		
    	//派发任务
    	$event = array('uid' => $uid, 'cid' => $cid);
    	Hapyfish2_Ipanda_Bll_Event::addIntimacy($event);
		
    	$result['status'] = 1;
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	/**
   	 * 
   	 */
   	public function answerquestionAction()
   	{
   		$uid = $this->uid;
   		
   		$id = $this->_request->getParam('id');
   		$tick = $this->_request->getParam('tick');
		if (empty($id) || empty($tick)) {
			$this->echoErrResult(-104);
		}
		
		$ok = Hapyfish2_Ipanda_Bll_PandaQuestion::awswer($uid, $id, $tick);
		if (!$ok) {
			$this->echoErrResult(-2);
		}
		
    	//派发任务
    	$event = array('uid' => $uid, 'id' => $id);
    	Hapyfish2_Ipanda_Bll_Event::answerQuestion($event);
		
		$result['status'] = 1;
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
 }
