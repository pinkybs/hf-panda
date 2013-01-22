<?php

class Hapyfish2_Ipanda_Bll_Phytotron
{
	public static function getId($uid)
	{
		$dal = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
		return $dal->getId($uid,"phytotron");
	}
	
	protected static function getAnimalLevel(&$animalLevelList, $service_num)
	{
		$cur = array();
		$next = array();
		foreach ($animalLevelList as $v) {
			if ($v['service_num'] > $service_num) {
				$next = $v;
				break;
			}
			$cur = $v;
		}
		
		if (empty($cur)) {
			return 1;
		} else {
			return $cur['level'];
		}
	}
	
	public static function getPhytotronList($uid, $phytotron_id = 0, $vistor_uid = 0, $highcache = false)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid, $highcache);
		if (empty($list)) {
			return $data;
		}
		
		$t = time();
       
		$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalList($uid);
		$phytotronUnlockList = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronUnlockList();
		$animalBasicInfoList = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
		$animalLevelList = Hapyfish2_Ipanda_Cache_Basic_Asset::getAnimalLevelList();
		$adminList = Hapyfish2_Ipanda_Cache_PhytotronAdmin::getPhytotronAdminList($uid);

		foreach ($list as $key => $value)
		{
			if (isset($value['stop_time']) && $value['stop_time'] > 0) {
				//放60秒缓冲时间
				if ($value['stop_time'] < $t - 60) {
					$ok = self::removePhytotron($uid, $value['id']);
					if ($ok) {
						if (1 == $value['status']) {
							$paId = $value['ipanda_user_phytotron_animal_id'];
							$row = $phytotronAnimalList[$paId];
							Hapyfish2_Ipanda_Bll_MapGrid::removePhytotron($uid, $value['id'], $row['phytotron_cid'], $value['x'], $value['z'], $value['mirro']);
						}
						info_log('[' . $uid . ']' . $value['id'] . '-' . $value['ipanda_user_phytotron_animal_id'], 'expire-phytotron-remove');
					}
					continue;
				}
			}
			
			if (0 == $value['status']) {
				continue;
			}
    		
			if (!empty($phytotron_id) && $value['id'] != $phytotron_id) {
				continue;
			}
    		
    		$paId = $value['ipanda_user_phytotron_animal_id'];
    		//$row = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid, $paId);
    		$row = $phytotronAnimalList[$paId];
    		$row['animal_level'] = self::getAnimalLevel($animalLevelList, $row['service_num']);
 
    		//$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($row['animal_cid']);
    		$basicInfo = $animalBasicInfoList[$row['animal_cid']];
    		//var_dump($basicInfo['product_time']);
    		$product_time_item = $basicInfo['product_time'];
    		$product_time_unlock = $row['product_time_unlock'];
    		//var_dump($row);
    		$product_time_item[0]['used'] = 0;
    		$product_time_item[0]['unlocked'] = 1;
    		$product_time_item[1]['used'] = 0;
    		$product_time_item[1]['unlocked'] = (int) $product_time_unlock[1];
    		$product_time_item[2]['used'] = 0;
    		$product_time_item[2]['unlocked'] = (int)  $product_time_unlock[2];
    		$product_time_item[3]['used'] = 0;
    		$product_time_item[3]['unlocked'] = (int)  $product_time_unlock[3];
    		$product_time_item[$value['product_time_type']]['used'] = 1;
    		$product_need_time = $product_time_item[$value['product_time_type']]['time'];
    		$product_num = $product_time_item[$value['product_time_type']]['num'];
  			//$product_leave_time = $row['product_end_time'] - $row['product_start_time'];
  			//if ($product_leave_time < 0) {
  			//	$product_leave_time = 0;
  			//}
  		
    		if (empty($row['animal_level'])) {
    			$row['animal_level'] = 1;
    		}
    		
	  		$next_animal_level = $row['animal_level'] + 1;
	  		//$levelInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getAnimalLevelInfo($next_animal_level);
	  		$levelInfo = $animalLevelList[$next_animal_level];
	  		$animal_next_level_num = $levelInfo['service_num'] - $row['service_num'];
	  		if ($animal_next_level_num < 0) {
	  			$animal_next_level_num = 0;
	  		}

  			//*培育动物 
	  		$state = $value['op_status'];
	  		//是否正常运营状态
	  		if ($state == 1) {
	  			//是否正在培育动物
	  			if ($value['end_time'] == 0) {
	  				//开始培育动物
	  				self::product($value, $product_need_time, $product_num);
	  				$value['end_time'] = $t + $product_need_time;
	  			} else if($value['end_time'] <= $t) {
	  				//培育结束，等待释放
	  				$state = 4;
	  			}
	  		}
	  		
	    	if (empty($value['end_time'])) {
	  			$time_type = 0;
	  			$leave_time = 0;
	  		} else {
	  			$time_type = 1;
	  			$leave_time = $value['end_time'] - $t;
	  			if ($leave_time < 0) {
	  				$leave_time = 0;
	  			}
	  		}

	  		//$need = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronNeedAdminInfo($uid, $value['id']);
	  		if ($value['ipanda_phytotron_unlock_list_id'] == 0) {
	  			$admin_num = 0;
	  		} else {
		  		$need = $phytotronUnlockList[$value['ipanda_phytotron_unlock_list_id']];
	  			$admin_num = $need['admin_num'];
	  		}
  			
			$admin_loadNum = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminCount($uid, $adminList, $value['id']);

			$cantakeflag = true;
    		if ($vistor_uid > 0) {
    			$cantakeflag = self::canRentAnimal($vistor_uid, $value);
    			//info_log("cantakeflag:".$cantakeflag,"cantakeflag".$uid);
    			if ($value['leave_num'] <= 1) {
    				//info_log("leave_num:".$value['leave_num'],"cantakeflag".$uid);
    				$cantakeflag = false;
    			}
    		}
    		
    		if (!isset($value['mirro'])) {
    			$value['mirro'] = 0;
    		}
    		
    		//加成属性
			$expAdditionRate = 0;
			$effect_source = array();
			if (isset($value['effect_exp'])) {
				$expAdditionRate = $value['effect_exp'];
				if (isset($value['effect_source'])) {
					$effect_source = json_decode($value['effect_source'], true);
				}
			}

  			$pInfo = array(
    				'id' 								=> $value['id'],
  					'uid' 								=> $value['uid'],
    				'cid'								=> $row['phytotron_cid'],
    				//'name'							=> $row['phytotron_name'],
    				//'class_name'						=> $row['phytotron_class_name'],
    				//'content'							=> null,
    				//'ipanda_user_phytotron_animal_id' => $value['ipanda_user_phytotron_animal_id'],
    				'x' 								=> $value['x'],
    				'z' 								=> $value['z'],
  					'mirro' 							=> $value['mirro'],
    				//'item_type' 						=> $value['item_type'],
    				//'item_id' 						=> null,
    				//'nodes' 							=> "2*2",
    				//'affect_nodes' 					=> null,
    				//'need_time' 						=> null,
    				'level' 							=> $row['animal_level'],
    				//'surplus_time'					=> $product_leave_time,
    				'product_time_type' 				=> $value['product_time_type'],
    				'product_time_item' 				=> $product_time_item,
    				'admin_num'							=> $admin_num,
  					'admin_loadNum'						=> $admin_loadNum,
    				'product_num'						=> $product_num,
    				'leave_animal_num'					=> $value['leave_num'],
    				'animal_cid'						=> $row['animal_cid'],
    				//'animal_class_name'				=> $row['animal_class_name'],
    				//'animal_name'						=> $row['animal_name'],	
    				'animal_level'						=> $row['animal_level'],
    				'animal_next_level_num'				=> $animal_next_level_num,
    				'service_num'						=> $row['service_num'],
  					'time_type'							=> $time_type,
  					'leave_time'						=> $leave_time,
  					'total_time'						=> $product_need_time,
  					'state'								=> $state, // 1 正常开业 2 未建成  3 未剪彩 4 等待放动物
    				//'consume_building'				=> $basicInfo['consume_building'],
  					//'forest_no'						=> $row['forest_no'],
  					'basic_id' 							=> $value['ipanda_phytotron_unlock_list_id'],
  					'can_rent_animal'					=> $cantakeflag,
  					'expAdditionRate'					=> $expAdditionRate,
  					'affectDecorateArr'					=> $effect_source
    		);
    			
          	if (!empty($phytotron_id) && $value['id'] == $phytotron_id) {
   				return $pInfo;
   			}

   			$data[] = $pInfo;
    	}
    	
		return $data;
	}
	
	public static function getOnePhytotron($uid, $id)
	{
		$phytotron = self::getPhytotronInfo($uid, $id);
		if ($phytotron) {
			$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalList($uid);
    		$paId = $phytotron['ipanda_user_phytotron_animal_id'];
    		$row = $phytotronAnimalList[$paId];
    		$phytotron['cid'] = $row['phytotron_cid'];
    		$phytotron['animal_cid'] = $row['animal_cid'];
		}
		
		return $phytotron;
	}
	
	public static function getDepotList($uid)
	{
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		$data = array();
       
		if (!empty($list)) {
       		$animalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalList($uid);
       		$t = time();
			foreach ($list as $k => $v) {
				if (1 == $v['status']) {
					continue;
				}
	       		
				$expire = -1;
				if (isset($v['stop_time']) && $v['stop_time'] > 0) {
					$expire = $v['stop_time'] - $t;
					if ($expire < 0) {
						$expire = 0;
						continue;
					}
				}
	       		$paId = $v['ipanda_user_phytotron_animal_id'];
	       		$data[] = array(
	       			'id' 		=> $v['id'],
	       			'cid'		=> $animalList[$paId]['phytotron_cid'],
	       			'basic_id' 	=> $v['ipanda_phytotron_unlock_list_id'],
	       			'expire' 	=> $expire
	       		);
			}
       }
       
       return $data;
	}
	
	public static function getPhytotronInfo($uid, $phytotron_id)
	{
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		if (empty($list)) {
			return null;
		}
		
		foreach ($list as $v)
		{
			if($v['id'] == $phytotron_id)
			{
				return $v;
			}
		}
		
		return null;
	}
	
	public static function getPhytotronNeedAdminInfo($uid, $phytotron_id)
	{
		$row = self::getPhytotronInfo($uid, $phytotron_id);
		$paId = $row['ipanda_phytotron_unlock_list_id'];
		
		//$data = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid, $paId);
       
       	$phytotronInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronUnlockInfo($paId);
       		
       	$result['admin_num'] = $phytotronInfo['admin_num'];
       	$result['admin_gold'] = $phytotronInfo['admin_gold'];
       	return $result;
	}
	
	//判断玩家的管理员是不空缺
	public static function isNeedAdmin($uid)
	{
	   $list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
       $data = array();
    
       foreach ($list as $value)
       {
       		$paId = $value['ipanda_user_phytotron_animal_id'];
		
       		$data = Hapyfish2_Ipanda_Bll_PhytotronAdmin::getPhytotronAdminList($uid, $value['id']);
       	
       		$phytotronInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronUnlockInfo($data['ipanda_phytotron_unlock_list_id']);
       	
       		$result['admin_num'] = $phytotronInfo['admin_num'];
       		
       		if(!$data || (count($data) < $result['admin_num']))
       		{
       			return true;
       		}
       		
       }
       
       return false;
	}
	
	public static function  getPhytotronAnimalInfo($uid, $phytotron_id)
	{
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		foreach ($list as $v)
		{
			if($v['id'] == $phytotron_id)
			{
				$pid = $v['ipanda_user_phytotron_animal_id'];
				$data = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($uid,$pid);
				return $data;
			}
		}
		return null;
	}
	
	public static function updatePhytotron($uid,$id,$info)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
			$dal->update($uid ,$id, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotron-update-err');
			info_log($e->getMessage(), 'phytotron-update-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Phytotron::updatetocache($uid, $id, $info);
		
		return true;
	}
	
	public static function removePhytotron($uid, $id)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
			$dal->delete($uid,$id);
		} catch (Exception $e) {
			info_log($msg, 'phytotron-delete-err');
			info_log($e->getMessage(), 'phytotron-delete-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Phytotron::deletecache($uid, $id);
		
		return true;
	}
	
	public static function updateAllPhytotron($uid,$info)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
			$dal->updateAll($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotron-update-err');
			info_log($e->getMessage(), 'phytotron-update-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Phytotron::updatealltocache($uid, $info);
		
		return true;
	}
	
	public static function addPhytotron($uid, &$info)
	{
		$info['id'] = self::getId($uid);
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotron-insert-err');
			info_log($e->getMessage(), 'phytotron-insert-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Phytotron::addtocache($uid, $info);
		
		return true;
	}
	
	/*
	 * 培育动物
	 */
	public static function product($data,$time = 0,$num)
	{
		//判断是不是正常营业
		if($data['op_status'] == 1 && !empty($time))
		{
			//更新数据库
			$info['end_time'] = time() + $time;
			$info['product_num'] 	= $num;
			$info['leave_num'] 		= $num;
			try {
				//添加到数据库
				
				$dal = Hapyfish2_Ipanda_Dal_Phytotron::getDefaultInstance();
				$dal->update($data['uid'],$data['id'], $info);
			} catch (Exception $e) {
				$msg = json_encode($info);
				info_log($msg, 'phytotron-product-err');
				info_log($e->getMessage(), 'phytotron-product-err');
				return false;
			}
			//更新缓存 
			Hapyfish2_Ipanda_Cache_Phytotron::updatetocache($data['uid'],$data['id'], $info);
			return true;
		}
		else 
		{
			info_log("op_status:".$data['op_status'].",time:".$time.".","phytotron-product-err");
		}
		
		return false;
	}
	
	/*
	 * 培育动物的数量
	 */
	public static function getProductNum($data)
	{
		$paId = $data['ipanda_user_phytotron_animal_id'];
       	$row = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($data['uid'], $paId);
    
       	$basicInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($row['animal_cid']);
       	
       	$product_time_item = $basicInfo['product_time'];
       	
       	$num = $product_time_item[$data['product_time_type']]['num'];
       	return $num;
       	
	}
	
	/*
	 * 检查当天有没有租用过
	 * 
	 */
	public static function canRentAnimal($uid,$phytotronInfo)
	{
		return Hapyfish2_Ipanda_Cache_Phytotron::canrentanimal($uid,$phytotronInfo);
	}
	
	/*
	 * 记录日志
	 */
	public static function rentAnimalLog($uid,$phytotronInfo,$num)
	{
		return Hapyfish2_Ipanda_Cache_Phytotron::addrentlog($uid, $phytotronInfo, $num);
	}
}