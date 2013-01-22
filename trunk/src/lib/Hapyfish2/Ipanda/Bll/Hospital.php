<?php

class Hapyfish2_Ipanda_Bll_Hospital
{
	const Rate = 2;
	//初始化医院
 	public static function getInit($uid)
 	{
// 		$result = array(
// 			array('id'=>1, 'status'=>0, 'buildStatus'=>0, 'endtime'=>1325347200),
// 			array('id'=>2, 'status'=>1, 'buildStatus'=>1, 'endtime'=>1325347200),
// 			array('id'=>3, 'status'=>2, 'buildStatus'=>2, 'endtime'=>1325347200),
// 			array('id'=>4, 'status'=>3, 'buildStatus'=>2, 'endtime'=>1325347200)
// 		);
 		$time = time();
 		$userVo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
 		$hospital = Hapyfish2_Ipanda_Cache_Hospital::getHospital();
 		$userHospital = Hapyfish2_Ipanda_Cache_Hospital::getUserHospital($uid);
 		$userHospital = self::checkuserhospital($uid, $userHospital);
 		$list = array();
 		foreach($userHospital as $k => $v){
 			$data = array();
 			$data['id'] = $k;
 			if($v['stage'] < 1){
 				if($userVo['level'] >= $hospital[$k]['level']){
 					$data['status'] = 1;
 				}else{
 					$data['status'] = 0;
 				}
 				$data['buildStatus'] = 0;
 			}else if($v['stage'] >= 1 && $v['stage'] < 4){
 				$data['buildStatus'] = $v['stage'] -1;
 				$data['status'] = 1;
 			}else{
 				$data['status'] = $v['stage'] -2;
 				$data['buildStatus'] = 0;
 			}
 			 $data['leaveTime'] = $v['end_time'] - $time > 0 ? $v['end_time'] - $time : 0;
 			 $data['material_cid'] = $v['drug_id'];
 			 if($v['drug_id'] > 0){
 			 	$drug = Hapyfish2_Ipanda_Cache_Hospital::getDrug();
 			 	$data['totalTime'] = $drug[$v['drug_id']]['creat_time'];
 			 }
 			$list[] = $data;
 		}
 		return $list;
 	}
 	
 	public static function gethospitalVo($uid, $id)
 	{
 		$initHospital = self::getInit($uid);
 		foreach($initHospital as $k => $v){
 			if($v['id'] == $id){
 				return $v;
 			}
 		}
 	}
 	
 	//产生疾病动物
 	public static function buildDisease($uid)
 	{
 		//check 等级
 		$userVo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
 		$level = $userVo['level'];
 		$list = array();
 		$limit = self::getLimit($level);
 		if($limit < 1){
 			return null;
 		}
 		$userDisNum = Hapyfish2_Ipanda_Cache_Hospital::getUserDNum($uid);
 		if($userDisNum >= $limit){
 			return null;
 		}
 		$mirro = self::getMirro($uid);
 		if(!$mirro){
 			return null;
 		}
 		$rate = rand(1, 100);
 		$userRate = self::getRateLimit($level);
 		if($rate <= $userRate){
 			$data = self::addDisease($uid, $mirro, $userDisNum);
 		}else{
 			return null;
 		}
 		if($data){
 			$list['animal_cid'] = $data['cid'];
 			$list['id'] = $data['id'];
 			$list['dis_id'] = $data['dis_id'];
 			$list['name'] = $data['name'];
 			$list['list'] = $data['list'];
 			$list['cid'] = $data['animal_cid'];
 			$list['die_time'] = $data['die_time'] - time();
 		}
 		return $list;
 	}
 	//获取生病动物上限
 	public static function getLimit($level)
 	{
 		$limit = 0;
 		if($level < 8){
 			return $limit;
 		}else if(8 <= $level && $level < 15)
 		{
 			$limit = 1;
 		}else if(15 <= $level && $level < 20)
 		{
 			$limit = 2;
 		}else if(21 <= $level && $level < 25)
 		{
 			$limit = 3;
 		}else if(26 <= $level && $level < 30)
 		{
 			$limit = 4;
 		}else if($level >= 30)
 		{
 			$limit = 5;
 		}
 		return $limit;
 	}
 	
 	public static function getRateLimit($level)
 	{
 		$limit = 4;
 		if($level < 8){
 			return $limit;
 		}else if(8 <= $level && $level < 15)
 		{
 			$limit = 4;
 		}else if(15 <= $level && $level < 20)
 		{
 			$limit = 4;
 		}else if(21 <= $level && $level < 25)
 		{
 			$limit = 3;
 		}else if(26 <= $level && $level < 30)
 		{
 			$limit = 3;
 		}else if($level >= 30)
 		{
 			$limit = 2;
 		}
 		return $limit;
 	}
 	//获得生病动物坐标
 	public static function getMirro($uid)
 	{
 		$map = Hapyfish2_Ipanda_Bll_MapGrid::getMap($uid);
 		$list = array();
 		$result = array();
 		if($map){
 			foreach($map as $k=>$v){
 				if($v == 0){
 					$list[] = $k;
 				}
 			}
 		}
 		if(!empty($list)){
 			$keys = array_rand($list, 1);
 			$mirro = $list[$keys];
 			$arr = explode(',', $mirro);
 			$result['x'] = $arr[0];
 			$result['z'] = $arr[1];
 		}else{
 		return null;
 		}
 		return $result;
 	}
 	//将生病动物加入玩家装饰列表
 	public static function addDisease($uid, $mirro, $num)
 	{
 		$cid = self::getAnimalCid($uid);
 		$disList = Hapyfish2_Ipanda_Cache_Hospital::getDisease();
 		$disid = array_rand($disList);
 		$nameList = self::getNameList();
 		$nameKey = array_rand($nameList, 1);
 		$name = $nameList[$nameKey];
 		$time = time();
 		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($cid[1]);
 		$info['uid'] 				= $uid;
		$info['cid'] 				= $basicInfo['cid'];
		$info['item_type'] 			= $basicInfo['item_type'];
		$info['forest_no'] 			= 0;
		$info['x'] 					= $mirro['x'];
		$info['y'] 					= 0;
		$info['z'] 					= $mirro['z'];
		$info['status'] 			= 1;
		$info['mirro'] 				= 0;
		$info['buy_time'] 			= $time;
		$info['buy_type'] = 0;
		$info['end_time'] = 0;
		$data = array();
		$addOk = Hapyfish2_Ipanda_Bll_Decorate::add($uid, $info);
 		if ($addOk) {
			//加入格子并计算加成影响
			Hapyfish2_Ipanda_Bll_MapGrid::addDecorate($uid, $info['id'], $info['cid'], $info['x'], $info['z'], $info['mirro'], $basicInfo);
			$data['id'] = $addOk;
			$data['cid'] = $cid[1];
			$data['uid'] = $uid;
			$data['dis_id'] = $disid;
			$data['status'] = 0;
			$data['name'] = $name;
			$data['die_time'] = time()+$disList[$disid]['continue_time'];
			Hapyfish2_Ipanda_Cache_Hospital::update($uid, $data);
			$data['animal_cid'] = $cid[0];
			Hapyfish2_Ipanda_Cache_Hospital::updateUserDNum($uid, $num+1);
			$ids = Hapyfish2_Ipanda_Cache_Hospital::getUderDisAniList($uid);
			if(!$ids){
				$ids = array();
			}
			$ids[] = $data['id'];
			Hapyfish2_Ipanda_Cache_Hospital::updateIds($uid, $ids);
			$data['list'] = self::getUserDrug($uid, $disid);
			return $data;
 		}
 		return null;
		
 	}
 	//获得生病动物
 	public static function getAnimalCid($uid)
 	{
 		$userAnimal = Hapyfish2_Ipanda_Cache_PhytotronAnimal::getIds($uid);
 		if(!$userAnimal){
 			return null;
 		}else{
 			$cidKey = array_rand($userAnimal, 1);
 			$cidLIst = self::getAtdcid();
 			$cid = $cidLIst[$userAnimal[$cidKey]];
 			return array($userAnimal[$cidKey], $cid);
 		}
 		
 	}
 	//生病动物与动物对应
 	public static function getAtdcid()
 	{
 		$data = array();
 		$data[161] = 3821;
 		$data[261] = 4121;
 		$data[361] = 3721;
 		$data[461] = 4021;
 		$data[561] = 4221;
 		$data[661] = 4721;
 		$data[761] = 3421;
 		$data[861] = 4421;
 		$data[961] = 4621;
 		$data[1061] = 3221;
 		$data[1161] = 3521;
 		$data[1261] = 3321;
 		$data[1361] = 3621;
 		$data[1461] = 3921;
 		$data[1561] = 4521;
 		$data[1661] = 4321;
 		return $data;
 		
 	}
 	//生病动物名称列表
 	public static function getNameList()
 	{
 		$nameList = array('亚伦', '亚当', '艾伯特', '安德鲁', '奥斯丁', '比尔', '鲍勃', '本森', '布兰特', '采妮', '丹尼斯', '加文', '汉克', '伊凡', '吉米', '邦妮', '贝尼尔', '卡米尔', '卡洛琳', '凯罗尔', '莎莉', '切莉', '康妮', '黛西', '达芙妮', '戴安娜', '桃瑞丝', '伊迪萨', '艾伦', '昆廷', '兰德尔', '伦道夫', '兰迪', '帕克 ', '史蒂文', '汤米', '伊芙 ', '菲奥纳', '弗里达 ', '吉娜', '吉莉安', '格瑞丝', '爱沙拉', '姬恩', '杰西卡', '姬尔', '乔安娜', '朱莉', '卡瑞达', '路易莎');
 		return $nameList;
 	}
 	//获取玩家草药信息
 	public static function getUserDrug($uid, $id)
 	{
 		$list = array();
 		$dis = Hapyfish2_Ipanda_Cache_Hospital::getDisease();
 		$detail = $dis[$id];
 		$userM = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
 		foreach($detail['cost'] as $k => $v){
 			$data = array();
 			$data['cid'] = $v['cid'];
 			if(isset($userM[$v['cid']])){
 				$data['num'] = $userM[$v['cid']]['count'];
 			}else{
 				$data['num'] = 0;
 			}
 			$list[] = $data;
 		}
 		return $list;
 	}
 	//check草药是否可以收取
 	public static function checkuserhospital($uid, $userHospital)
 	{
 		$time = time();
 		$change = 0;
 		foreach($userHospital as $k => $v){
 			if($v['stage'] == 5 && $v['end_time'] - $time <= 0){
 				$userHospital[$k]['stage'] = 6;
 				$change += 1;
 			}
 		}
 		if($change > 0){
 			Hapyfish2_Ipanda_Cache_Hospital::updateUserHospital($uid, $userHospital);
 		}
 		return $userHospital;
 	}
 	
 	//建造医院
 	public static function buildHospital($uid, $id)
 	{
 		if($id < 1 || $id > 4){
 			return array('error'=>-1);
 		}
 		$userHospital = Hapyfish2_Ipanda_Cache_Hospital::getUserHospital($uid);
 		$userMaterial = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
 		if($userHospital[$id]['stage'] >= 4){
 			return array('error'=>-1);
 		}
 		$hospital = Hapyfish2_Ipanda_Cache_Hospital::getHospital();
 		$need = $hospital[$id]['cost'];
 		//检查材料
 		$check = self::checkUser($uid, $need, $userMaterial);
 		if($check){
 			return $check;
 		}
 		//扣材料
 		$ok = self::decNeed($uid, $need);
 		if($ok){
 			//升级医院
 			if($userHospital[$id]['stage'] == 0){
 				$userHospital[$id]['stage'] += 2;
 			}else{
 				$userHospital[$id]['stage'] += 1;
 			}
 			Hapyfish2_Ipanda_Cache_Hospital::updateUserHospital($uid, $userHospital);
 			//发奖励
 			$reward = $hospital[$id]['reward'];
 			$hNum = count($reward);
	 		if ($hNum > 0) {
				Hapyfish2_Ipanda_Cache_ComboHit::add($uid, $hNum);
			}
 			$award = new Hapyfish2_Ipanda_Bll_Award();
 			foreach($reward as $key=>$v){
	 			if($v['cid'] == 1){
	 				$award->setLove($v['num']);
	 			}else if($v['cid'] == 3){
	 				$award->setEnergy($v['num']);
	 			}else if ($v['cid'] == 5){
	 				$award->setExp($v['num']);
	 			}else{
	 				$award->setMaterial($v['cid'], $v['num']);
	 			}
 			}
 			$ok = $award->sendOne($uid);
 			if($ok){
 				return $reward;
 			}
 		}
 	}
 	//check用户材料等是否够
 	public static function checkUser($uid, $need, $userMaterial)
 	{
 		$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
 		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
	 	foreach($need as $key => $v){
 			if($v['cid'] == 1){
 				if($userLove < $v['num']){
 					return array('error'=>-100);
 				}
 			}else if($v['cid'] == 3){
	 			if ($userEnergy['energy'] < $v['num']) {
					return array('error'=>-105);
				}
 			}else{
 				if(!isset($userMaterial[$v['cid']]) || $userMaterial[$v['cid']]['count'] < $v['num']){
 					return array('error'=>-106, 'list' => array('cid'=>$v['cid'], 'num'=> $v['num'] - $userMaterial[$v['cid']]['count']));
 				}
 			}
	 	}
 	}
 	//扣除材料等
 	public static function decNeed($uid, $need)
 	{
 		foreach($need as $k=>$v){
 			if($v['cid'] == 1){
 			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $v['num']);
				if (!$ok) {
					return false;
				} else {
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$v['num']);
				}
 			}else if($v['cid'] == 3){
	 			$ok = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, $v['num']);
				if(!$ok) {
					return false;
				}else{
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -$v['num']);
				}
 			}else{
 				$ok = Hapyfish2_Ipanda_HFC_Material::useMultiple($uid, array($v));
 				Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, $v['cid'], -$v['num']);
 			}
 		}
 		return true;
 	}
 	//生产草药
 	public static function productDrug($uid, $cid, $id)
 	{
 		if($id < 1 || $id > 4){
 			return array('error'=>-1);
 		}
 		$time = time();
 		$drug = Hapyfish2_Ipanda_Cache_Hospital::getDrug();
 		$userHospital = Hapyfish2_Ipanda_Cache_Hospital::getUserHospital($uid);
 		if(!isset($drug[$cid])){
 			return array('error'=>-1);
 		}
 		if($userHospital[$id]['stage'] < 4){
 			return array('error'=>-1);
 		}
 		$need = $drug[$cid]['cost'];
 		$userMaterial = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
 		$check = self::checkUser($uid, $need, $userMaterial);
 		if($check){
 			return $check;
 		}
 		$ok = self::decNeed($uid, $need, $userMaterial);
 		if($ok){
 			$userHospital[$id]['stage'] = 5;
 			$userHospital[$id]['drug_id'] = $cid;
 			$userHospital[$id]['end_time'] = $time + $drug[$cid]['creat_time'];
 			Hapyfish2_Ipanda_Cache_Hospital::updateUserHospital($uid, $userHospital);
 			return array('create_time' => $drug[$cid]['creat_time']);
 		}else{
 			return array('error'=>-1);
 		}
 	}
 	//加速草药
 	public static function completeProduct($uid, $id)
 	{
 		$drug = Hapyfish2_Ipanda_Cache_Hospital::getDrug();
 		$userHospital = Hapyfish2_Ipanda_Cache_Hospital::getUserHospital($uid);
 		if($id < 1 || $id > 4){
 			return -1;
 		}
 		if($userHospital[$id]['stage'] != 5){
 			return -1;
 		}
 		$time = time();
 		if($userHospital[$id]['end_time'] <= $time){
 			return -1101;
 		}
 		$drugId = $userHospital[$id]['drug_id'];
 		$need = $drug[$drugId]['funnel'];
 		$userCard = Hapyfish2_Ipanda_Bll_Card::getInfo($uid, 443);
 		if($userCard['count'] < $need){
 			return -803;
 		}
 		$ok = Hapyfish2_Ipanda_HFC_Card::useUserCard($uid, 443, $need);
 		if($ok){
 			$userHospital[$id]['stage'] = 6;
 			$userHospital[$id]['end_time'] = $time;
 			Hapyfish2_Ipanda_Cache_Hospital::updateUserHospital($uid, $userHospital);
 		}
 	}
 	//收草药
	public static function getDurg($uid, $id)
 	{
 		$drug = Hapyfish2_Ipanda_Cache_Hospital::getDrug();
 		$userHospital = Hapyfish2_Ipanda_Cache_Hospital::getUserHospital($uid);
 		if($id < 1 || $id > 4){
 			return -1;
 		}
 		if($userHospital[$id]['stage'] < 5 ){
 			return -1;
 		}
 		$time = time();
 		if($time < $userHospital[$id]['end_time']){
 			return -1;
 		}
 		$cid = $userHospital[$id]['drug_id'];
 		$userEnergy = Hapyfish2_Ipanda_HFC_User::getUserEnergy($uid);
 		if ($userEnergy['energy'] < 1) {
			return -105;
		}
 		$award = new Hapyfish2_Ipanda_Bll_Award();
 		$award->setMaterial($cid, 1);
 		$ok = $award->sendOne($uid);
 		if($ok){
 			$ok1 = Hapyfish2_Ipanda_HFC_User::decUserEnergy($uid, 1);
				if(!$ok) {
					return -1;
				}else{
					Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '3', -1);
			}
 			$userHospital[$id]['stage'] = 4;
 			$userHospital[$id]['drug_id'] = 0;
 			Hapyfish2_Ipanda_Cache_ComboHit::add($uid, 1);
 			Hapyfish2_Ipanda_Cache_Hospital::updateUserHospital($uid, $userHospital);
 			return array('cid'=>$cid, 'num'=> 1);
 		}
 	}
 	//祈祷复活
 	public static function pray($uid, $ids)
 	{
 		$idList = explode(",", $ids);
 		if(empty($idList)){
 			return -1;
 		}
 		$userIds = Hapyfish2_Ipanda_Cache_Hospital::getUderDisAniList($uid);
 		$list = Hapyfish2_Ipanda_Cache_Hospital::getDisList($uid, $userIds);
 		if(empty($list)){
 			return -1;
 		}
 		$num = count($idList);
 		$time = time();
 		foreach($idList as $k => $id){
 			if(!isset($list[$id])){
 				return -1;
 			}
	 		if($list[$id]['die_time'] > $time){
	 			return -1;
	 		}
 		}
 		$cost = 3*$num;
 		$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
 		if($userGold < $cost){
 		    return -102;
 		}
 		//复活动物
 		$newids = array_diff($userIds, $idList);
 		$ok = Hapyfish2_Ipanda_Cache_Hospital::delIds($uid, $idList);
 		if($ok){
 			Hapyfish2_Ipanda_Cache_Hospital::updateIds($uid, $newids);
 			foreach($idList as $k => $id){
 				$decorate = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
	 			if (empty($decorate)) {
					return -104;
				}
				$ok1 = Hapyfish2_Ipanda_Bll_Decorate::remove($uid, $id);
	 			if ($ok1) {
					Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $id, $decorate['cid'], $decorate['x'], $decorate['z'], $decorate['mirro']);
				} else {
					return -2;		
				}
 			}
 			$goldInfo = array(
					'uid' 			=> $uid,
					'cost' 			=> $cost,
					'summary' 		=> '为死亡的动物祈祷',
					'create_time' 	=> $time,
					'user_level' 	=> '',
					'cid' 			=> '',
					'num' 			=> ''
				);
				
			$ok2 = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
			if(!$ok2){
				return -2;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$cost);
 		}
 	}
 	//治疗动物
 	public static function heal($uid, $id)
 	{
 		$info = Hapyfish2_Ipanda_Cache_Hospital::getUserDisAni($uid, $id);
 		if(!$info){
 			return array('error'=>-1);
 		}
 		$time = time();
 		if($info['die_time'] <= $time){
 			return array('error'=>-1103);
 		}
 		$userDisNum = Hapyfish2_Ipanda_Cache_Hospital::getUserDNum($uid);
 		$dis = Hapyfish2_Ipanda_Cache_Hospital::getDisease();
 		$dis_id = $info['dis_id'];
 		$detail = $dis[$dis_id];
 		$userM = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
 		$need = $detail['cost'];
 		$check = self::checkUser($uid, $need, $userM);
 		if($check){
 			return $check;
 		}
 		$ok = self::decNeed($uid, $need);
 		$ids = array($id);
 		$idList = Hapyfish2_Ipanda_Cache_Hospital::getUderDisAniList($uid);
 		$newids = array_diff($idList, $ids);
 		if($ok){
 			Hapyfish2_Ipanda_Cache_Hospital::delIds($uid, $ids);
 			Hapyfish2_Ipanda_Cache_Hospital::updateIds($uid, $newids);
 			$decorate = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
 			$userDisNum -= 1;
 			Hapyfish2_Ipanda_Cache_Hospital::updateUserDNum($uid, $userDisNum);
	 		if (empty($decorate)) {
				return array('error'=>-104);
			}
			$ok1 = Hapyfish2_Ipanda_Bll_Decorate::remove($uid, $id);
	 		if ($ok1) {
				Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $id, $decorate['cid'], $decorate['x'], $decorate['z'], $decorate['mirro']);
			} else {
				return array('error'=>-2);		
			}
			$reward = $detail['reward'];
			$hnum = count($reward);
			if($hnum>0){
				Hapyfish2_Ipanda_Cache_ComboHit::add($uid, $hnum);
			}
			$award = new Hapyfish2_Ipanda_Bll_Award();
			$anId = $info['cid'];
			$atdList = self::getAtdcid();
			foreach($atdList as $anmailId => $acid){
				if($anId == $acid){
					$anmailCid = $anmailId;
					break;
				}
			}
			$animalInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($anmailCid);
			$className = $animalInfo['class_name'];
 			foreach($reward as $key=>$v){
	 			if($v['cid'] == 1){
	 				$award->setLove($v['num']);
	 			}else if($v['cid'] == 3){
	 				$award->setEnergy($v['num']);
	 			}else if ($v['cid'] == 6){
	 				Hapyfish2_Ipanda_Bll_PhytotronAnimal::addintimacy($uid, $anmailCid, $v['num']);
	 			}
	 			else if($v['cid'] == 443){
	 				$award->setCard($v['cid'], $v['num']);
	 			}
	 			else{
	 				$award->setMaterial($v['cid'], $v['num']);
	 			}
 			}
 			$ok = $award->sendOne($uid);
 			if($ok){
 				return array('class_name' => $className);
 			}
 		} 		
 		
 	}
 	//生病动物数据
 	public static function initDisDecorate($uid, $id)
 	{
 		$result = array();
 		$info = Hapyfish2_Ipanda_Cache_Hospital::getUserDisAni($uid, $id);
 		$atdList = self::getAtdcid();
 		foreach($atdList as $anmailId => $acid){
			if($info['cid'] == $acid){
				$result['cid'] = $anmailId;
				continue;
			}
		}
 		$disid = $info['dis_id'];
 		$list =self::getUserDrug($uid, $disid);
 		$time = time();
 		$leftTime = $info['die_time'] - $time> 0?$info['die_time'] - $time :0;
 		$result['id'] = $info['id'];
 		$result['dis_id'] = $info['dis_id'];
 		$result['name'] = $info['name'];
 		$result['list'] = $list;
 		$result['animal_cid'] = $info['cid'];
 		$result['die_time'] = $leftTime;
 		return $result;
 		
 	}
 	//墓碑数据
 	public static function initTombstone($uid, $id)
 	{
 		$result = array();
 		$info = Hapyfish2_Ipanda_Cache_Hospital::getUserDisAni($uid, $id);
 		$disid = $info['dis_id'];
 		$time = time();
 		if($info['die_time'] > $time){
 			return -1;
 		}
 		$year = date('Y',$info['die_time']);
 		$nomth =  date('m',$info['die_time']);
 		$day =  date('d',$info['die_time']);
 		$result['die_time'] = $year.'.'.$nomth.'.'.$day;
 		$result['name'] = $info['name'];
 		$atdList = self::getAtdcid();
 		foreach($atdList as $anmailId => $acid){
			if($info['cid'] == $acid){
				$result['animal_cid'] = $anmailId;
				continue;
			}
		}
 		$result['needGold'] = 3;
 		$result['dis_id'] = $info['dis_id'];
 		return $result;
 		
 	}
 	//check生病动物是否死亡
	public static function checkDeath($uid)
 	{
 		$list = Hapyfish2_Ipanda_Cache_Hospital::getDisList($uid);
 		$result = array();
 		$time = time();
 		if($list){
 			foreach($list as $k => $v){
 				$data = array();
 				if($v['status'] == 0 && $v['die_time'] <= $time){
 					$atdList = self::getAtdcid();
 					self::changeToTombstone($uid, $v['id']);
 					$year = date('Y',$v['die_time']);
			 		$nomth =  date('m',$v['die_time']);
			 		$day =  date('d',$v['die_time']);
 					$data['die_time'] = $year.'.'.$nomth.'.'.$day;;
 					$data['id'] = $v['id'];
 					$data['name'] = $v['name'];
					foreach($atdList as $anmailId => $acid){
						if($v['cid'] == $acid){
							$data['animal_cid'] = $anmailId;
							continue;
						}
					}
 					$data['dis_id'] = $v['dis_id'];
 					$data['needGold'] = 3;
 					$result[] = $data;
 				}
 			
 			}
 		}
 		return $result;
 	}
 	//生病动物变成墓碑
 	public static function changeToTombstone($uid, $id)
 	{
 		$info = Hapyfish2_Ipanda_Cache_Hospital::getUserDisAni($uid, $id);
 		$userDisNum = Hapyfish2_Ipanda_Cache_Hospital::getUserDNum($uid);
 		$info['status'] = 1;
 		Hapyfish2_Ipanda_Cache_Hospital::update($uid, $info);
 		$userDisNum -= 1;
 		Hapyfish2_Ipanda_Cache_Hospital::updateUserDNum($uid, $userDisNum);
 		$decorateInfo = Hapyfish2_Ipanda_Bll_Decorate::getInfo($uid, $id);
		if (!$decorateInfo) {
			return false;
		}
		$decorateInfo['cid'] = 4821;
		$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($decorateInfo['cid']);
		$updateOk = Hapyfish2_Ipanda_Bll_Decorate::update($uid, $id, $decorateInfo);
		if($updateOk){
			Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $id, $info['cid'], $decorateInfo['x'], $decorateInfo['z'], $decorateInfo['mirro']);
			Hapyfish2_Ipanda_Bll_MapGrid::addDecorate($uid, $id, $decorateInfo['cid'], $decorateInfo['x'], $decorateInfo['z'], $decorateInfo['mirro'], $basicInfo);
		}
 	}
 	//生病动物列表
 	public static function getDisList($uid)
 	{
 		$list = array();
 		$result = Hapyfish2_Ipanda_Cache_Hospital::getDisList($uid);
 		$time = time();
 		if($result){
 			foreach($result as $k => $v){
 				$data = array();
 				$leftTime = $v['die_time'] - $time;
 				if($leftTime > 0){
 					$data['leaveTime'] = $leftTime;
 					$data['id'] = $v['id'];
 					$list[] = $data;
 				}
 			}
 		}
 		return $list;
 	}
 	//取得用户材料
 	public static function getUserMaterial($uid)
 	{
 		$list = array();
 		$userM = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
 		if($userM){
 			$data = array();
 			foreach($userM as $k => $v){
 				$data['cid'] = $k;
 				$data['num'] = $v['count'];
 				$list[] = $data;
 			}
 		}
 		return $list;
 	}
 	
}