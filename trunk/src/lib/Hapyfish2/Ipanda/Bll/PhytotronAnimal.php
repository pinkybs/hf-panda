<?php

class Hapyfish2_Ipanda_Bll_PhytotronAnimal
{
	public static function getId($uid)
	{
		$dal = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
		return $dal->getId($uid,"PhytotronAnimal");
	}
	
	public static function getPhytotronAnimalList($uid)
	{
		$list = Hapyfish2_Ipanda_Cache_PhytotronAnimal::getPhytotronAnimalList($uid);
		$lst = array();
		if (!empty($list)) {
			foreach ($list as $v) {
				$lst[$v['id']] = $v;
			}
		}
		return $lst;
	}
	
	public static function getOnePhytotronAnimal($uid, $paId)
	{
		$list = self::getPhytotronAnimalList($uid);
		if (empty($list)) {
			return null;
		}
		
		foreach ($list as $key => $value) {
			if ($value['id'] == $paId){
				$level_info = Hapyfish2_Ipanda_Bll_BasicInfo::getCurrentAnimalLevelInfo($value['service_num']);
				$value['animal_level'] = $level_info['level'];
				$value['love_price'] = $level_info['love_price'];
				return $value;
			}
		}
		
		return null;
	}
	
	public static function getPhytotronAnimalByCid($uid, $cid)
	{
		$list = self::getAnimalListForCid($uid);
		
		return $list[$cid];
	}
	
	public static function getAnimalList($uid)
	{
		$data = array();
		$list = self::getPhytotronAnimalList($uid);
       	if (empty($list)) {
       		return $data;
       	}
       
       	foreach ($list as $key => $value) {
       		//$paId = $value['animal_cid'];
       		//$row = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($value['animal_cid']);
       		//$consume_building = $row['consume_building'];
       		$level_info = Hapyfish2_Ipanda_Bll_BasicInfo::getCurrentAnimalLevelInfo($value['service_num']);
       		$data[] = array(
       				'id' 								=> $value['id'],
       				'cid'								=> $value['animal_cid'],
       				//'name'							=> $value['animal_name'],
       				//'class_name'						=> $value['animal_class_name'],
       				'animal_level'						=> $level_info['level'],
       				//'item_type'						=> $row['item_type'],
       				//'content'							=> null,
       				//'need_user_level' 				=> $row['need_user_level'],
       				//'unlock_condition' 				=> $row['unlock_condition'],
       				//'buy_type' 						=> $row['item_type'],
       				//'price' 							=> $level_info['love_price'],
       				//'consume_building' 				=> sizeof($consume_building),
       				//'consume_building_list' 			=> $consume_building,
       				//'phytotron_cid' 					=> $row['phytotron_cid'],
       				//'phytotron_class_name' 			=> $row['phytotron_class_name'],
       				//'phytotron_name' 					=> $row['phytotron_name'],
       		);
        }
        
       	return $data;
	}
	
	public static function getAnimalListForCid($uid)
	{
		$list = self::getPhytotronAnimalList($uid);
       	$data = array();
       
       	foreach ($list as $key => $value)
        {
       		$paId = $value['animal_cid'];
       		$level_info = Hapyfish2_Ipanda_Bll_BasicInfo::getCurrentAnimalLevelInfo($value['service_num']);
       		$data[$paId] = array(
       				'id' 								=> $value['id'],
       				'cid'								=> $value['animal_cid'],
       				//'name'							=> $value['animal_name'],
       				//'class_name'						=> $value['animal_class_name'],
       				//'header_class'					=> $value['header_class'],
       				'animal_level'						=> $level_info['level'],
       				'service_num'						=> $value['service_num'],
       				'level_info'						=> $level_info
       		);
        }
        
       	return $data;
	}
	
	/*
	 * 
	 * 增加亲密度
	 */
	public static function update($uid,$id,$info)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
			$dal->update($uid,$id, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'PhytotronAnimal-update-err');
			info_log($e->getMessage(), 'PhytotronAnimal-update-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_PhytotronAnimal::updatetocache($uid, $id, $info);
	}
	
	public static function add($uid,$info)
	{
		$info['id'] = self::getId($uid);
		
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'PhytotronAnimal-insert-err');
			info_log($e->getMessage(), 'PhytotronAnimal-insert-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_PhytotronAnimal::addtocache($uid, $info);
	}
	
	/*
	 * 
	 * 解锁列表 
	 */
	public static function unlocklist($uid)
	{
		//先结算一下
		Hapyfish2_Ipanda_Bll_Building::settleBuildingListOnForest($uid);
		
		$basicList = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
    	$userAniamlList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalListForCid($uid);
    	$intimacyState = Hapyfish2_Ipanda_Bll_Card::getIntimacyState($uid);
    	
    	$data = array();
    	foreach ($basicList as $v) {
    		$cid = $v['cid'];
    		$animal = array('cid' => $cid);
    		if (empty($userAniamlList[$cid])) {
    			//未解锁
    			//$v['status'] = 2;
    			$animal['status'] = 2;
    		} else  {
    			//$v['status'] = 1;
    			$animal['status'] = 1;
    			//$levelInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getCurrentAnimalLevelInfo($userAniamlList[$cid]['service_num']);
    			//$v['level_info'] = $levelInfo;
    			$animal['level_info'] = $userAniamlList[$cid]['level_info'];
    		}
    		
    		if (isset($intimacyState[$cid])) {
    			$animal['isDouble'] = 1;
    		} else {
    			$animal['isDouble'] = 0;
    		}
    		
    		if (!empty($v['unlock_condition'])) {
    			$unlock_condtion = $v['unlock_condition'];
    			$tmp = array();
    			foreach ($unlock_condtion as $c) {
    				if ($c['cid'] > 0) {
    					$tmp[] = $c;
    				}
    			}
    			for ($i = 0, $count = count($tmp); $i < $count; $i++) {
    				$theCid = $tmp[$i]['cid'];
    				if (empty($userAniamlList[$theCid])) {
    					//条件中的动物 未解锁
    					$status = 2;
    				} else if($userAniamlList[$theCid]['animal_level'] < $tmp[$i]['level']) {
    					//条件中的动物等级不足
    					$status = 3;
    				} else {
    					//条件 中的动物 达到要求
    					$status = 1 ;
    				}
    				$tmp[$i]['status'] = $status;
    				$tmp[$i]['header_class'] = $basicList[$theCid]['header_class'];
    			}
    			
    			//$v['unlock_condition'] = $tmp;
    			$animal['unlock_condition'] = $tmp;
    		}
    		
    		//$data[] = $v;
    		$data[] = $animal;
    	}
    	return $data;
	}
	
	public static function isUnlock($uid, $cid)
	{
		$list = Hapyfish2_Ipanda_Cache_PhytotronAnimal::getPhytotronAnimalList($uid);
		if (empty($list)) {
			return false;
		}
		
		foreach ($list as $v) {
			if ($v['animal_cid'] == $cid) {
				return true;
			}
        }
        
        return false;
	}
	
	/*
	 * 
	 * 增加亲密度
	 */
	public static function addintimacy($uid,$cid,$num = 1)
	{
		$animalInfo = self::getPhytotronAnimalByCid($uid, $cid);
		if(empty($animalInfo))
		{
			return -601;
		}

		$hasIntimacyState = Hapyfish2_Ipanda_Bll_Card::hasIntimacyState($uid, $cid);
		if ($hasIntimacyState) {
			$num *= 2;
		}
		$animalupdate['service_num'] = $animalInfo['service_num'] + $num;
		self::update($uid, $animalInfo['id'], $animalupdate);
		return true;
	}
}