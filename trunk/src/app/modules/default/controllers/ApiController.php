<?php

class ApiController extends Hapyfish2_Controller_Action_Api
{
    public function assetlistAction()
    {
    	header('Cache-Control: no-store, no-cache, must-revalidate');
		require (CONFIG_DIR . '/asset.php');
		$v = Hapyfish2_Ipanda_Bll_CacheVersion::get('initData');
		$assetResult['interface']['GiftGetActInitStatic'] .= '?v='. $v;
    	echo json_encode($assetResult);
    	exit;
    }
	
	public function initdataAction()
	{
		header('Cache-Control: max-age=2592000');
		$gz = $this->_request->getParam('gz', 1);
		if ($gz == 1) {
			header('Content-Type: application/octet-stream');
			echo Hapyfish2_Ipanda_Bll_BasicInfo::getInitData('1.0', true);
		} else {
			echo Hapyfish2_Ipanda_Bll_BasicInfo::getInitData('1.0');
		}
		exit;
	}
	
	public function inituserinfoAction()
	{
		$uid = $this->uid;
		
		//加锁，防止刷
	    $key = 'i:u:lock:default:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($key);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
        
		$loginInfo = Hapyfish2_Ipanda_Bll_User::updateUserTodayInfo($uid);
		
        //release lock
        $lock->unlock($key);
		
		$result['userVo'] = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
		
		$result['acts'] = Hapyfish2_Ipanda_Bll_Act::get($uid, $loginInfo);
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/*
	 * 获取好友列表 
	 * 
	 */
	function getfriendsAction()
	{
		$pageIndex = $this->_request->getParam('pageIndex', 1);
        $pageSize = $this->_request->getParam('pageSize', 20);

        $rankResult = Hapyfish2_Ipanda_Bll_Friend::getRankList($this->uid, $pageIndex, $pageSize);
		
        $data = $this->returnResult($rankResult);
        $this->echoResult($data);
	}
	
	/*
	 *  初始化的ipanda中的信息
	 */
	public function initipandaAction()
	{		
		$uid = $this->_request->getParam('uid');
		$hospital = $this->_request->getParam('hospital');
		$forest_no = $this->_request->getParam('forest_no');
		$forest_no = empty($forest_no) ? 1 : $forest_no; 
		
		if (empty($uid) || $uid == $this->uid) {
			$fuid = 0;
			$uid = $this->uid;
			$isHome = true;
			if($hospital == 1){
				$animalDieNotice = Hapyfish2_Ipanda_Bll_Hospital::checkDeath($uid);
				$disease = Hapyfish2_Ipanda_Bll_Hospital::buildDisease($uid);
				$diseaselist = Hapyfish2_Ipanda_Bll_Hospital::getDisList($uid);
				if(!empty($diseaselist)){
					$update['diseaseList'] = $diseaselist;
				}
				if($disease){
					$update['diseaseVo'] = $disease;
				}
				if(!empty($animalDieNotice)){
					$update['animalDieNotice'] = $animalDieNotice;
				}
				
			}
			$ziyuan = Hapyfish2_Ipanda_Cache_Resources::getUserResourcesVo($uid);
			if(!empty($ziyuan)){
				$result['ziyuanVo'] = $ziyuan;
			}
			$result['package']['phytotronVo'] = Hapyfish2_Ipanda_Bll_Phytotron::getDepotList($uid);
			$result['package']['buildingVo']  = Hapyfish2_Ipanda_Bll_Building::getInDepot($uid);
			$result['package']['decorateVo']  = Hapyfish2_Ipanda_Bll_Decorate::getDepotList($uid);
			$result['cardVo']  		= Hapyfish2_Ipanda_Bll_Card::getList($uid);
			$result['materialVo'] 	= Hapyfish2_Ipanda_Bll_Material::getList($uid);
			$result['buildingUnlock'] 	= Hapyfish2_Ipanda_Bll_BuildingUnlock::getlist($uid);
			//交互记录
			$result['interactive'] = Hapyfish2_Ipanda_Cache_User::readInteractive($uid);
			
			$result['taskVo'] = Hapyfish2_Ipanda_Bll_Task::getUserTaskOpenTaskIds($this->uid);
		} else {
			$fuid = $this->uid;
			$isHome = false;
			//派发事件
			$event = array('uid' => $this->uid, 'fid' => $uid);
			Hapyfish2_Ipanda_Bll_Event::visitFriend($event);
		}
		
		if ($uid == GM_UID_LELE) {
			$result = Hapyfish2_Ipanda_Bll_DumpUser::getDumpUserResult($uid);
		} else {
			$vistor_uid = $fuid;
			$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, 0, $vistor_uid, true);
			$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid, true);
			$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, 0, $vistor_uid);
			$result['buildingVo'] 	= $buildingListOnForest['building_list'];
			$result['animal_list'] 	= $buildingListOnForest['animal_list'];
			$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
			$result['forestVo'] 	= Hapyfish2_Ipanda_Bll_Forest::getForestByNo($uid, $forest_no);
			$result['userVo'] 		= Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
			$result['interactiveNum'] = Hapyfish2_Ipanda_Cache_User::getleaveInteractionNum($fuid , $uid);
			
			if ($isHome) {
				$hasExpireDecorate = Hapyfish2_Ipanda_Bll_Expire::hasExpireDecorate($uid, true);
				$hasExpirePhytotron = Hapyfish2_Ipanda_Bll_Expire::hasExpirePhytotron($uid, true);
				if ($hasExpireDecorate || $hasExpirePhytotron) {
					$expire = array();
					if ($hasExpireDecorate) {
						$expire['icon'] = 1;
						//$expire['decorate'] = 1;
					}
					if ($hasExpirePhytotron) {
						$expire['phytotron'] = 1;
					}
					$result['expire'] = $expire;
				}
				
				//道具卡状态
				$cardStatus = Hapyfish2_Ipanda_Bll_Card::getCardStatus($uid);
				if (!empty($cardStatus)) {
					$result['cardStatus'] = $cardStatus;
				}
			}
		}
		if(!empty( $fuid ) )
		{
			$update['userVo'] 		= Hapyfish2_Ipanda_Bll_User::getUserInit($fuid);
			$ret = $this->returnResult($result,$update);
		}
		else 
		{
			if(isset($update['diseaseVo']) || isset($update['animalDieNotice']) || isset($update['diseaseList'])){
				$ret = $this->returnResult($result, $update);
			}else{
				$ret = $this->returnResult($result);
			}
			
		}
		$this->echoResult($ret);
	}
	
	/*
	 * 仓库的数据
	 */
	public function getdepotAction()
	{
		$uid = $this->_request->getParam('uid');

		if (empty($uid)) {
			$uid = $this->uid;
		}
		
		$result['phytotronVo'] 	= Hapyfish2_Ipanda_Bll_Phytotron::getDepotList($uid);
		$result['buildingVo']  	= Hapyfish2_Ipanda_Bll_Building::getInDepot($uid);
		$result['decorateVo']  	= Hapyfish2_Ipanda_Bll_Decorate::getDepotList($uid);

		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	/**
	 * 动物消费
	 * 收爱心
	 */
	public function checkoutloveAction()
	{
		$uid = $this->uid;
		
		$id = $this->_request->getParam('id');
		if (empty($id)) {
			$this->echoErrResult(-104);
		}
		
		//获取 建筑信息
		$building = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		if (!$building) {
			$this->echoErrResult(-103);
		}

		//判断是否可以结算
		if (empty($building['effect_deposit']) || (time() < $building['op_end_time'])) {
			$this->echoErrResult(-505);
		}
		//检查是否被交互
		$checkInteraction = Hapyfish2_Ipanda_Cache_User::checkInteraction($uid, $id);
		if($checkInteraction)
		{
			$error['eid'] = -504;
       		$msgdata['name'] = $checkInteraction['name'];
       		$error['msg'] = Hapyfish2_Ipanda_Bll_Language::getText('build locked', $msgdata);
		}
		
		$energy = EG_CHECKOUT_LOVE;
		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
		if ($userEnergy['energy'] < $energy) {
			$this->echoErrResult(-105);
		}
		
		$cid = $building['cid'];
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($cid);
		
		//结算并重置服务状态
		$updatedBuilding = Hapyfish2_Ipanda_Bll_Building::checkout($building);
		if (!$updatedBuilding) {
			$this->echoErrResult(-506);
		}
		else {	
			//消耗体力
		
			$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $energy);
			if (!$ok) {
				info_log($uid, 'energy_fix.err');
			} else {
				Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$energy);
			}
			
			$gainLove = $building['effect_deposit'];
			
			//经验不同的时间设置有不同
			$gainExp = EXP_CHECKOUT_LOVE;//默认
			$checkout_time_type = $building['checkout_time_type'];
			if (isset($basicInfo['checkout_time'][$checkout_time_type])) {
				$checkoutInfo = $basicInfo['checkout_time'][$checkout_time_type];
				if (isset($checkoutInfo['exp']) && $checkoutInfo['exp'] > 0) {
					$gainExp = $checkoutInfo['exp'];
				}
			}
			
			//加成
			$loveAdditionRate = $building['loveAdditionRate'];
			if ($loveAdditionRate) {
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
			
			$savedb = USE_CACHE ? false :true;
			$ok = Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $gainLove, $savedb);
			
			if ($ok) {
				//成就(收取爱心次数)
				Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '3', 1);
				
				//加经验
				Hapyfish2_Ipanda_HFC_User::incUserExp($uid, $gainExp);
				
		    	//派发任务
		    	$event = array('uid' => $uid, 'love' => $gainLove);
		    	Hapyfish2_Ipanda_Bll_Event::gainLove($event);
			}
			
			//获得材料
			$animalInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($basicInfo['animal_cid']);
			//
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
			
			$result['status'] = 1;
			$result['build'] = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $id);
		}
		
		$update = array();

		$get_material[] = array('cid' => 5, 'num' => $gainExp);
		$get_material[] = array('cid' => 1, 'num' => $gainLove);
		
		$goods = array();
		foreach ($get_material as $v) {
			if($v['num'] > 0) {
				$goods[] = $v ;
			}
		}
		$currNoviceGuide = Hapyfish2_Ipanda_Bll_Guide::getUserNoviceGuide($uid);
		if(empty($currNoviceGuide)){
			$time = time();
			$yuanxiao = Hapyfish2_Ipanda_Event_Bll_Christmas::springYuanxiao($uid, $time);
			if($yuanxiao){
				$userBell['cid'] = 3045;
				$userBell['num'] = 1;
				$goods[] = $userBell;
			}
		}
		$update['goodsVo']['goods'] = $goods;
		$update['goodsVo']['bid'] = $id;
		$update['goodsVo']['type'] =  0;
		$hNum = count($goods);
		if ($hNum > 0) {
			Hapyfish2_Ipanda_Cache_ComboHit::add($uid, $hNum);
		}

		
		$ret = $this->returnResult($result, $update);
		$this->echoResult($ret);
	}

	/**
	 * read feed Action
	 *
	 */
	public function readfeedAction()
	{		
		$uid = $this->uid;
		$pageIndex = $this->_request->getParam('pageIndex', 1);
		$pageSize = $this->_request->getParam('pageSize', 50);
		
		$feedList = Hapyfish2_Ipanda_Bll_Feed::getFeed($uid, $pageIndex, $pageSize);
		$result['news'] = $feedList['new'];
		$result['list'] = $feedList['feeds'];
		
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 获取可做管理员的好友列表
	 */
	public function getadminfriendsAction()
	{
		$pageIndex = $this->_request->getParam('pageIndex', 1);
        $pageSize = $this->_request->getParam('pageSize', 20);

        $rankResult = Hapyfish2_Ipanda_Bll_Friend::getNotAdminFriendList($this->uid, $pageIndex, $pageSize);

        $data = $this->returnResult($rankResult);
        $this->echoResult($data);
	}
	
	/*
	 *	培育屋的管理员 
	 */
	public function phytotronadminlistAction()
	{	
		$phytotron_id = $this->_request->getParam('phython_id');
		$uid = $this->_request->getParam('uid');
		
		if(empty($uid))
		{
			$uid = $this->uid;
		}
		//var_dump($uid);
		//var_dump($phytotron_id);exit;
        $result['list'] = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $phytotron_id);
		$result['need'] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($uid, $phytotron_id);
        $data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 聘用机器作为培育屋管理员
	 */
	public function phytotronbuyrobotAction()
	{		
		$phytotron_id = $this->_request->getParam('phython_id');
		
		$uid = $this->uid;
		
		$list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $phytotron_id);
		$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($uid, $phytotron_id);
		
		if (count($list) >= $need['admin_num']) {
			$this->echoErrResult(-201);
		} else {
			//花费金币租用管理员
			$gold = Hapyfish2_Ipanda_Bll_Gold::get($uid);
			//$gold = 110000000000;
			if ($gold < $need['admin_gold']) {
				$this->echoErrResult(-102);
			} else {
				$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $need['admin_gold'],
					'summary' 		=> '花费金币租用管理员',
					'create_time' 	=> time(),
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				
				$ret = Hapyfish2_Ipanda_Bll_Gold::consume($uid,$goldInfo);
				if (!$ret) {
					$this->echoErrResult(-2);
				} else {
					//为培育屋增加一个管理员
					$add_ret =	Hapyfish2_Ipanda_Bll_PhytotronAdmin::addphytotronrobotadmin($uid, $phytotron_id);
					if (!$add_ret) {
						$this->echoErrResult(-202);
					} else {
						$result['status'] = 1;
					}
				}
			}
		}
		
		$list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $phytotron_id);
		$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($uid, $phytotron_id);
		$result['admin_num'] = $need['admin_num'];
		$result['admin_loadNum'] = count($list);
		$result['id'] = $phytotron_id;

		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 邀请好友作为培育屋的管理员
	 */
	public function phytotronfriendadminAction()
	{		
		$phytotron_id = $this->_request->getParam('phython_id');
		$friend_uid = $this->_request->getParam('friend_uid');
		$friends = explode(',', $friend_uid);
		if (empty($friend_uid)) {
			$this->echoErrResult(-101);
		}
		
		$uid = $this->uid;
		
		//判断是不是好友		
		$list = array();
		foreach ($friends as $v) {
			$isfriend = Hapyfish2_Ipanda_Bll_Friend::isFriend($uid, $v);
			if (!$isfriend) {
				continue;
			}
			
			//判断好友是不是已经是这引培育屋的管理员
			$isadmin = Hapyfish2_Ipanda_Bll_PhytotronAdmin::isphytotronadmin($uid,$phytotron_id,$v);
			if ($isadmin) {
				continue;
			} else {
				//判断好友是不是已经当管理员满了
				$can_admin = Hapyfish2_Ipanda_Bll_PhytotronAdmin::hasAdminNum($v);
				if (!$can_admin) {
					continue;
				}
				
				//发送邀请 发给好友，所以uid是好友id 
				$info = array(
					'uid' 			=> $v,
					'friend_uid' 	=> $uid,
					'phytotron_id'	=> $phytotron_id,
					'status'		=> 1,
					'create_time'	=> time(),
				);
				$ret = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::sendadmininvite($v, $info);
				if (!$ret) {
					continue;
				}
			}
			
			$list[] = $v;
		}
		
		$result['status'] = 1;
		$result['list'] = $list;
		
        $data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 我要应聘
	 */
	public function getthejobAction()
	{		
		$log_id = $this->_request->getParam('log_id');
	    if (empty($log_id)){
        	$this->echoErrResult(-104);
        }
		
		$uid = $this->uid;
		
		//判断日志信息是不是正确
		$row = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getLogInfo($uid, $log_id);
        if (!$row || $row['status'] != 1) {
        	$this->echoErrResult(-206);
        }
        
        //判断自己是否有资格担当
		$userInfo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
	
		$can_admin_num = $userInfo['admin_num'];
		
		$current_admin_num = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getWorkingCount($uid);
		if ($can_admin_num <= $current_admin_num) {
			Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::remove($uid, $log_id);
			$this->echoErrResult(-204);
		}
		
		//判断好友的培育的管理员是不是已经满了或者管理员是不是有自己了
		$phytotron_id = $row['phytotron_id'];
		$friend_uid = $row['friend_uid'];
		$list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($friend_uid, $phytotron_id);
		$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($friend_uid, $phytotron_id);
		
		if (count($list) >= $need['admin_num']) {
			$change_ret = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::remove($uid, $log_id);
			$this->echoErrResult(-201);
		} else {
			$isadmin = Hapyfish2_Ipanda_Bll_PhytotronAdmin::isphytotronadmin($friend_uid, $phytotron_id, $uid);
			if($isadmin) {
				Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::remove($uid, $log_id);
				$this->echoErrResult(-203);
			} else {
				//担当管理员
				$ret = Hapyfish2_Ipanda_Bll_PhytotronAdmin::addphytotronfriendadmin($friend_uid, $uid, $phytotron_id);
				if ($ret) {
					//更改日志信息
					$change_ret = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::changelogstatus($uid, $log_id, 0);
					if (!$change_ret) {
						$this->echoErrResult(-103);
					}
					
					$result['status'] = 1;
				}
			}
		}
		
		//发消息
		$minifeed = array(
			'uid' => $uid,
            'template_id' => 4,
            'actor' => $uid,
            'target' => $friend_uid,
            'title' => array('actor' => $uid),
            'type' => 4,
            'create_time' => time()
		);
        Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($minifeed);
        
		$list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $phytotron_id);
		$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($uid, $phytotron_id);
		$result['admin_num'] = $need['admin_num'];
		$result['admin_loadNum'] = count($list);
		$result['id'] = $phytotron_id;
		
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 我的雇佣信息  @type 0 我管理 的培育屋,1 我的雇佣信息 
	 */
	public function myemployeeinfoAction()
	{
		$type = $this->_request->getParam('type');
		$type = empty($type) ? 0 : 1;
		$uid = $this->uid;
		
		$result['logList'] = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getList($uid, $type);
		
        $data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 好友的招聘信息
	 */
	public function friendphytotronjobAction()
	{
		$uid = $this->uid;
        //好友的列表
        
        //好友的培育屋
        //培育屋空缺管理员
        $result = Hapyfish2_Ipanda_Bll_Friend::getNeedAdminFriendList($uid);
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

	/*
	 * 
	 * 离职
	 */	
	public function resignphytotronadminAction()
	{
		$uid = $this->uid;
        //好友的列表
        $log_id = $this->_request->getParam('log_id');
        if (empty($log_id)){
        	$this->echoErrResult(-104);
        }
        
      	//获得当前应聘信息
      	$logInfo = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getLogInfo($uid, $log_id);
		if (empty($logInfo)) {
			$this->echoErrResult(-207);
        }
        
        //判断时间
        if ((time() - 7*86400 ) < $logInfo['update_time']) {
        	$this->echoErrResult(-208);
        }
        
        //离职 更新admin_log 及 ipanda_user_phytotron_admin
        Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::changelogstatus($uid, $log_id, 3);
        Hapyfish2_Ipanda_Bll_PhytotronAdmin::deleteAdmin($logInfo['friend_uid'], $uid, $logInfo['phytotron_id']);

        $result = array('status' => 1);
        $data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 解锁 管理权限 的等级限制
	 * 
	 */
	public function unlockadminlevelAction()
	{
		//每花一次金币 加一次资格
		$uid = $this->uid;
        //好友的列表
        //当前数量
        $userVO = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
        $cur_num = $userVO['admin_num'];
        $price = $cur_num*5;
        
    	 //扣金币
		$goldInfo = array(
				'uid' 			=> $uid,
				'cost' 			=> $price,
				'summary' 		=> '解锁管理员资格',
				'create_time' 	=> time(),
				'user_level' 	=> '',
				'cid' 			=> '',
				'num' 			=> ''
		);
		$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
		
		if(!$ok) {
			$this->echoErrResult(-102);
		}
		
        Hapyfish2_Ipanda_HFC_User::updateUserAdminNum($uid, $cur_num + 1);
		
        $result = array('status' => 1);
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 主动应聘
	 * 
	 */
	public function acceptphytotronadminAction()
	{
		$uid = $this->uid;
     	
        $phytotron_id = $this->_request->getParam('id');
        $fuid = $this->_request->getParam('fuid');

		if (empty($phytotron_id) || empty($fuid)) {
			$this->echoErrResult(-104);
		}
		
        //当前培育屋的管理员的
         //判断自己是否有资格担当
		$userInfo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
	
		$can_admin_num = $userInfo['admin_num'];
		$current_admin_num = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getWorkingCount($uid);
		if ($can_admin_num <= $current_admin_num) {
			$this->echoErrResult(-204);
		}
		
		//判断好友的培育的管理员是不是已经满了或者管理员是不是有自己了
		
		$list = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($fuid, $phytotron_id);
		$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($fuid, $phytotron_id);
		if (count($list)>= $need['admin_num']) {
			$this->echoErrResult(-201);
		} else {
			Hapyfish2_Ipanda_Bll_PhytotronAdmin::addphytotronfriendadmin($fuid, $uid, $phytotron_id);
			$info = array(
				'uid' 			=> $uid,
				'friend_uid' 	=> $fuid,
				'phytotron_id'	=> $phytotron_id,
				'status'		=> 0,
				'create_time'	=> time(),
			);
			Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::sendadmininvite($uid, $info);
		}
		
		//应聘
		$result = array('status' => 1);
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 领取奖励
	 */
	public function getphytotronadminawardAction()
	{
		$uid = $this->uid;
     
        $log_id = $this->_request->getParam('log_id');
		if (empty($log_id)) {
			$this->echoErrResult(-104);
		}
		
        //获得当前的日志内容
        $logInfo = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getLogInfo($uid, $log_id);
		if (empty($logInfo) || empty($logInfo['benefit'])) {
			$this->echoErrResult(-206);
		}

        //获得培育屋信息
        $phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($logInfo['friend_uid'], $logInfo['phytotron_id']);
		if (empty($phytotron)) {
			$this->echoErrResult(-210);
		}
		
       // $paId = $phytotron['ipanda_user_phytotron_animal_id'];
       	//$row = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid, $paId);
       	//var_dump($phytotron);exit;
       	$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($phytotron['animal_cid']);
       			
		//领取动物 
        $num = $logInfo['benefit'];  	
       	$consumeBuilding = $basicInfo['consume_building'];
       	
		$buildFlag = Hapyfish2_Ipanda_Bll_Building::hadAnimalConsumeBuilding($uid, $consumeBuilding);
       	if (!$buildFlag) {
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
   		if (($currentNum + $num) > $animalTop) {
        	$error['eid'] = -503;
        	$this->echoError($error);
        }
   
		Hapyfish2_Ipanda_Bll_Building::reciveAnimal($uid, $num, $consumeBuilding, $basicInfo, 1);
		//更新 日志 
		$updateinfo['benefit'] = 0;
		Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::updatelogbyphytotronid($uid, $logInfo['friend_uid'], $logInfo['phytotron_id'], $updateinfo);
		//加经验
		Hapyfish2_Ipanda_HFC_User::incUserExp($uid, EXP_ADMIN_PER_ANIMAL*$num);
		
		$result = array('status' => 1);
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}
	
	/*
	 * 忽略 日志信息
	 */
	public function ignorephytotronadminlogAction()
	{
		$log_id = $this->_request->getParam('log_id');
		if (empty($log_id)) {
			$this->echoErrResult(-104);
		}
		
		$uid = $this->uid;
		
		//判断日志信息是不是正确
		$row = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getLogInfo($uid, $log_id);
        if (!$row || $row['status'] != 1) {
        	$this->echoErrResult(-206);
        }
        
        $result['status'] = 1;
        Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::remove($uid, $log_id);
		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

    /*
     * 统计新手引导 
     * 
     */
    function reporthelpAction()
    {
        $uid = $this->uid;
        $step = $this->_request->getParam('step');

        $logger = Hapyfish2_Util_Log::getInstance();
        $logger->report('2002', array($uid, $step));
        
        exit;
    }
    
 }
