<?php

class Hapyfish2_Ipanda_Bll_Building
{
	public static function getBuildingListOnForest($uid, $building_id = 0, $vistor_uid = 0)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		$animal_list = array(
			'build' => array(),
			'mood' => array()
		);
		
		if (empty($list)) {
			if ($building_id == 0) {
				$data = array('building_list' => array(), 'animal_list' => $animal_list);
			} else {
				return $data;
			}
		}
		
		$t = time();
		$unit_time = UNIT_TIME;
		$animal_num = 0;
		$savedb = USE_CACHE ? false : true;
		$building_list = array();
		$animalUpdateList = array();
		
		$buildingAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
		$animalAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
		$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalListForCid($uid);
		$intimacyState = Hapyfish2_Ipanda_Bll_Card::getIntimacyState($uid);

		foreach ($list as $key => $value)
		{
       		if (0 == $value['status']) {
       			continue;
       		}
       		
       		$id = $value['id'];
       		
			if (!empty($building_id) && $building_id != $id) {
       			continue;
       		}
       		
       		//$paId = $value['cid'];
       		$cid = $value['cid'];
       		
       		//$row = Hapyfish2_Ipanda_Bll_BasicInfo::getBuildingInfo($value['cid']);
       		$row = $buildingAssetList[$cid];
       		
       		//动物信息
       		//$animalInfo = Hapyfish2_Ipanda_Bll_BasicInfo::getPhytotronAnimalInfo($row['animal_cid']);
       		$animalInfo = $animalAssetList[$row['animal_cid']];
       		//$animal = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalByCid($uid,$row['animal_cid']);
       		if (isset($phytotronAnimalList[$row['animal_cid']])) {
       			$animal = $phytotronAnimalList[$row['animal_cid']];
       		} else {
       			$animal = null;
       		}
       		
       		//结算 
       		$check_one_run_time = 0;
       		$cnum = 0;
       		$validNum = 0;
       		if ($value['wait_visitor_num'] > 0) {	
	       		$inter = $t - $value['pre_service_end_time'];
	       		//可离开动物数
	       		$cnum = floor( $inter / $unit_time );
	       		//有效动物数
	       		$validNum = $cnum;
	       		$info = array();
	       		if ($cnum > 0) {
	       			//等待动物数>可离开动物数
	       			if($value['wait_visitor_num'] > $cnum) {
	       				$info['wait_visitor_num'] = $value['wait_visitor_num'] - $cnum;
	       			} else {
	       				$cnum = $value['wait_visitor_num'];
	       				$validNum = $cnum;
	       				$info['wait_visitor_num'] =  0;
	       			}
		       		//最后结算时间
		       		$info['pre_service_end_time'] = $value['pre_service_end_time'] + $unit_time * $cnum;

		       		$info['service_animal_num'] = $value['service_animal_num'] + $cnum;
		       		//2011-11-08 修改 只在结算时间未到 扣除 耐久
		       		//结算时间还未到
		       		if ($value['durable'] > 0) {
			       		if ($value['op_end_time'] >= $t) {
			       			//扣掉耐久度
				       		$info['durable'] = $value['durable'] - $cnum;
							if ($info['durable'] < 0) {
								$info['durable'] = 0;
								$validNum = $value['durable'];
							}
			       		} else {
			       			$info['durable'] = $value['durable'];
			       			$validNum = 0;
			       		}
		       		} else {
		       			$info['durable'] = 0;
		       			$validNum = 0;
		       		}
		       		
		       		//结算时间还未到，并且耐久度>0的时候才给钱(deposit表示可收钱多少进度，实际收入effect_deposit)
	       			if ($value['op_end_time'] >= $t && $validNum > 0) {
		       			$info['deposit'] = $value['deposit'] +	$validNum * $value['checkout_love'];
		       			$value['deposit'] = $info['deposit'];
		       		}
		       		
		       		if ($info['durable'] == 0) {
		       			$info['op_start_time'] = 0;
		       		}
		       		
		       		foreach ($info as $k => $v) {
		       			$value[$k] = $v;
		       		}
		       		
		       		//增加动物亲密度
		       		if (!empty($animal) && ($info['durable'] > 0) ) {
		       			if (isset($intimacyState[$animal['cid']])) {
		       				$service_num = 2*$cnum;
		       			} else {
		       				$service_num = $cnum;
		       			}
		       			
		       			if (isset($animalUpdateList[$animal['id']])) {
		       				$animalUpdateList[$animal['id']] += $service_num;
		       			} else {
		       				$animalUpdateList[$animal['id']] = $animal['service_num'] + $service_num;
		       			}
		       		}
		       		
		       		Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($uid, $id, $info, $savedb);
	       		}
	       		
	       		if ($value['wait_visitor_num'] > 0) {
	       			if (!isset($animal_list['build'][$id])) {
	       				$animal_list['build'][$id] = array(
	       					'num' => $value['wait_visitor_num'], 
	       					'cid' => $animalInfo['cid'], 
	       					'class_name' => $animalInfo['class_name'], 
	       					'name' => $row['name']
	       				);
	       			} else {
	       				$animal_list['build'][$id]['num'] += $value['wait_visitor_num'];
	       			}
	       			$animal_num += $value['wait_visitor_num'];
	       		}
       		}
       		
       		//当前状态
			//op_status
			//1 正常开业, 2 未建成, 3 维修中, 4 爱心等待收取, 5未使用
       		$state = $value['op_status'];
       		if ($t >= $value['op_end_time'] && $value['effect_deposit'] > 0 ) {
       			$state = 4;
       		}
       		$leave_time = $value['op_end_time'] - $t;
       		if ($leave_time < 0) {
       			$leave_time = 0;
       		}

       		if ($leave_time == 0 && empty($value['effect_deposit']) && $value['op_status'] != 2) {
       			$state = 5;
       		}
       		
       		//判断是不是在维修
       		if (0 == $value['durable'] && $value['fix_end_time'] > 0) {
       			//维修时间到了(已经维修好)
       			if ($t >= $value['fix_end_time']) {
       				$fixinfo['durable'] = $value['top_durable'];
       				$value['durable'] = $fixinfo['durable'];
       				
       				if ($value['pre_service_end_time'] < $value['op_end_time']) {
						$t_time = $value['pre_service_end_time'] - $value['fix_end_time'];
       				} else {
       					$t_time = $value['op_end_time'] - $value['fix_end_time'];
       				}
       				$full = false;
       				$effect_num = 0;
					if ($t_time >= $unit_time) {
						$t_cum = floor($t_time/$unit_time);
						$c = $value['durable'];
						$effect_num = $t_cum;
						if ($effect_num > $c) {
							$effect_num = $c;
							$full = true;
						}
						$effct_deposit = $effect_num * $value['checkout_love'];
						$fixinfo['effect_deposit'] = $value['effect_deposit'] + $effct_deposit;
						$value['effect_deposit'] = $fixinfo['effect_deposit'];
					}
					
					if (!$full) {
						//根据剩余时间和等待的动物计算出有效消费
						$t_time = $value['op_end_time'] - $value['pre_service_end_time'];
						if ($t_time >= $unit_time && $value['wait_visitor_num'] > 0) {
							$t_cum = floor($t_time/$unit_time);
							$c = $value['durable'] - $effect_num;
							$effect_num2 = $value['wait_visitor_num'] <= $t_cum ? $value['wait_visitor_num'] : $t_cum;
							if ($effect_num2 > $c) {
								$effect_num2 = $c;
							}
							$effct_deposit = $effect_num2 * $value['checkout_love'];
							$fixinfo['effect_deposit'] = $value['effect_deposit'] + $effct_deposit;
							$value['effect_deposit'] = $fixinfo['effect_deposit'];
						}
					}
					
					$fixinfo['fix_end_time'] = 0;
					$value['fix_end_time'] = $fixinfo['fix_end_time'];
					Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($uid, $id, $fixinfo, true);
       			} else {
       				//还在维修中
       				$state = 3;
       			}
       		}
			$fix_end_time = $value['fix_end_time'] - $t;
			if ($fix_end_time < 0) {
				$fix_end_time = 0;
			}
			
			//计算建筑属性
			$attr = $row['attribute'];
			$expAdditionRate = 0;
			$loveAdditionRate = 0;
			$effect_source = array();
			if (isset($value['attr'])) {
				$attr = json_decode($value['attr'], true);
				if (!empty($attr)) {
					$expAdditionRate = (int)array_pop($attr);
					$loveAdditionRate = (int)array_pop($attr);
					if (isset($value['effect_source'])) {
						$effect_source = json_decode($value['effect_source'], true);
					}
				}
			}

			if (1 == $state) {
				$check_one_run_time = $t - $value['pre_service_end_time'];
				if ($check_one_run_time < 0) {
					$check_one_run_time = 0;
				}
				if ($check_one_run_time > $unit_time) {
					$check_one_run_time = $unit_time;
				}
			}
			
			$cantakeflag = false;
       		if ($vistor_uid > 0) {
       			$cantakeflag = self::cantakeLove($vistor_uid, $value);
       			$can_take = floor($value['deposit'] * 0.3);
				$leave_num = $can_take - $value['take_deposit'];
				if ($leave_num <= 0) {
					$cantakeflag = false;
				}
       		}
       		
			$arr = array(
       				'id' 								=> $id,
       				'cid'								=> $value['cid'],
       				'uid'								=> $value['uid'],
       				//'name'							=> $row['name'],
       				//'class_name'						=> $row['class_name'],
       				//'content'							=> $row['content'],
       				'x' 								=> $value['x'],
       				'z' 								=> $value['z'],
       				'mirro' 							=> $value['mirro'],
       				//'item_type' 						=> $value['item_type'],
       				//'item_id' 						=> $value['item_id'],
       				//'nodes' 							=> $row['nodes'],
       				//'affect_nodes' 					=> $row['effect_nodes'],
       				//'need_time' 						=> null,
       				//'level' 							=> $value['level'],
       				'surplus_time'						=> $value['checkout_time'],
       				
       				'checkout_love'						=> $value['checkout_love'],
       		
       				'state'								=> $state,//1 正常开业 ,2 未建成 ,3 维修中 , 4 爱心等待收取 5,未使用
       				//'love_price'						=> $row['love_price'],
       				//'gold_price'						=> $row['gold_price'],
       				//'price_type'						=> $row['price_type'],
       				//'cheap_price'						=> $row['cheap_price'],
       				//'cheap_start_time'				=> $row['cheap_start_time'],
       				//'cheap_end_time'					=> $row['cheap_end_time'],
       				//'sale_price'						=> $row['sale_price'],
       				//'need_level'						=> $row['need_level'],
       				//'need_material'					=> $row['need_material'],
       				//'isnew'							=> $row['isnew'],
       				//'can_buy'							=> $row['can_buy'],
       				//'love_fee'						=> null,
       				//'safe_time'						=> null,
       				//'safe_love_num'					=> $row['safe_love_num'],
       				//'next_level_cid'					=> $row['next_level_cid'],
       				//'act_name'						=> $row['act_name'],
       				'durable'							=> $value['durable'],
       				'top_durable'						=> $value['top_durable'],
       				//'durable_time'					=> $row['durable_time'],
       				'fix_end_time'						=> $fix_end_time,
       				//'durable_basic_time'				=> $row['durable_time'],
       				//'need_num'						=> null,
       				//'surplus_need_num'				=> null,
       				'unit_time'							=> $unit_time,
       				'love'								=> $value['deposit'],
       				'take_deposit'						=> $value['take_deposit'],
       				'leave_time'						=> $leave_time,
       				'op_end_time'						=> $value['op_end_time'],
       				'op_start_time'						=> $value['op_start_time'],
       				'wait_visitor_num'					=> $value['wait_visitor_num'],
       				'checkout_time_type'				=> $value['checkout_time_type'],
       				'attribute'							=> $attr,
					'loveAdditionRate'					=> $loveAdditionRate,
					'expAdditionRate'					=> $expAdditionRate,
					'affectDecorateArr'					=> $effect_source,
       				//'animal_cid'						=> $row['animal_cid'],
       				'check_one_run_time'				=> $check_one_run_time,
       				//'material_group_id'				=> $row['material_group_id'],
					'can_take_love'						=> $cantakeflag,
					'effect_deposit'					=> $value['effect_deposit'],
       		);
       		
		    if (!empty($building_id) && $building_id == $id) {
		    	if (count($animalUpdateList) > 0) {
					foreach ($animalUpdateList as $id => $service_num) {
						Hapyfish2_Ipanda_Bll_PhytotronAnimal::update($uid, $id, array('service_num' => $service_num));
					}
				}
       			return $arr;
       		}
       		
       		$building_list[] = $arr;
		}
		
		if (count($animalUpdateList) > 0) {
			foreach ($animalUpdateList as $id => $service_num) {
				Hapyfish2_Ipanda_Bll_PhytotronAnimal::update($uid, $id, array('service_num' => $service_num));
			}
		}
       
		Hapyfish2_Ipanda_Cache_Building::setForestAnimalNum($uid, $animal_num);
      
		$data = array('building_list' => $building_list, 'animal_list' => $animal_list);
		return $data;
	}
	
	public static function settleBuildingListOnForest($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return $data;
		}
		
		$t = time();
		$animal_num = 0;
		$unit_time = 60;
		$savedb = USE_CACHE ? false : true;
		$animalUpdateList = array();
		
		$buildingAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
		$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalListForCid($uid);
		$intimacyState = Hapyfish2_Ipanda_Bll_Card::getIntimacyState($uid);
		
		foreach ($list as $key => $value)
		{
       		if (0 == $value['status']) {
       			continue;
       		}
       		
       		$id = $value['id'];
       		
       		$cid = $value['cid'];
       		
       		$row = $buildingAssetList[$cid];
       		
       		//动物信息
       		$animal = $phytotronAnimalList[$row['animal_cid']];
       		
       		//结算 
       		$cnum = 0;
       		$validNum = 0;
       		if ($value['wait_visitor_num'] > 0) {	
	       		$inter = $t - $value['pre_service_end_time'];
	       		//可离开动物数
	       		$cnum = floor($inter/$unit_time);
	       		//有效动物数
	       		$validNum = $cnum;
	       		$info = array();
	       		if ($cnum >= 1) {
					//等待动物数>可离开动物数
	       			if($value['wait_visitor_num'] > $cnum) {
	       				$info['wait_visitor_num'] = $value['wait_visitor_num'] - $cnum;
	       			} else {
	       				$cnum = $value['wait_visitor_num'];
	       				$validNum = $cnum;
	       				$info['wait_visitor_num'] =  0;
	       			}
					//最后结算时间
					$info['pre_service_end_time'] = $value['pre_service_end_time'] + $unit_time * $cnum;
					
		       		$info['service_animal_num'] = $value['service_animal_num'] + $cnum;
	       			//2011-11-08 修改 只在结算时间未到 扣除 耐久
		       		//结算时间还未到
		       		if ($value['durable'] > 0) {
			       		if ($value['op_end_time'] >= $t) {
			       			//扣掉耐久度
				       		$info['durable'] = $value['durable'] - $cnum;
							if ($info['durable'] < 0) {
								$info['durable'] = 0;
								$validNum = $value['durable'];
							}
			       		} else {
			       			$info['durable'] = $value['durable'];
			       			$validNum = 0;
			       		}
		       		} else {
		       			$info['durable'] = 0;
		       			$validNum = 0;
		       		}
		       		
		       		//结算时间还未到，并且耐久度>0的时候才给钱(deposit表示可收钱多少进度，实际收入effect_deposit)
	       			if (($value['op_end_time'] >= $t) && ($info['durable'] > 0) ) {
		       			$info['deposit'] = $value['deposit'] +	$validNum * $value['checkout_love'];
		       			$value['deposit'] = $info['deposit'];
		       		}
		       		
		       		if ($info['durable'] == 0) {
		       			$info['op_start_time'] = 0;
		       		}
		       		
		       		foreach ($info as $k => $v) {
		       			$value[$k] = $v;
		       		}
		       		
		       		Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($uid, $id, $info, $savedb);
		       		
		       		//增加动物亲密度
		       		if (!empty($animal) && ($info['durable'] > 0)) {
						if (isset($intimacyState[$animal['cid']])) {
		       				$service_num = 2*$cnum;
		       			} else {
		       				$service_num = $cnum;
		       			}
		       			
		       			if (isset($animalUpdateList[$animal['id']])) {
		       				$animalUpdateList[$animal['id']] += $service_num;
		       			} else {
		       				$animalUpdateList[$animal['id']] = $animal['service_num'] + $service_num;
		       			}
		       		}
	       		}
	       		
       			$animal_num += $value['wait_visitor_num'];
       		}
       		
		    //判断是不是在维修
       		if (0 == $value['durable'] && $value['fix_end_time'] > 0) {
       			//维修时间到了(已经维修好)
       			if ($t >= $value['fix_end_time']) {
       				$fixinfo['durable'] = $value['top_durable'];
       				$value['durable'] = $fixinfo['durable'];
       				
       				if ($value['pre_service_end_time'] < $value['op_end_time']) {
						$t_time = $value['pre_service_end_time'] - $value['fix_end_time'];
       				} else {
       					$t_time = $value['op_end_time'] - $value['fix_end_time'];
       				}
       				$full = false;
       				$effect_num = 0;
					if ($t_time >= $unit_time) {
						$t_cum = floor($t_time/$unit_time);
						$c = $value['durable'];
						$effect_num = $t_cum;
						if ($effect_num > $c) {
							$effect_num = $c;
							$full = true;
						}
						$effct_deposit = $effect_num * $value['checkout_love'];
						$fixinfo['effect_deposit'] = $value['effect_deposit'] + $effct_deposit;
						$value['effect_deposit'] = $fixinfo['effect_deposit'];
					}
					
					if (!$full) {
						//根据剩余时间和等待的动物计算出有效消费
						$t_time = $value['op_end_time'] - $value['pre_service_end_time'];
						if ($t_time >= $unit_time && $value['wait_visitor_num'] > 0) {
							$t_cum = floor($t_time/$unit_time);
							$c = $value['durable'] - $effect_num;
							$effect_num2 = $value['wait_visitor_num'] <= $t_cum ? $value['wait_visitor_num'] : $t_cum;
							if ($effect_num2 > $c) {
								$effect_num2 = $c;
							}
							$effct_deposit = $effect_num2 * $value['checkout_love'];
							$fixinfo['effect_deposit'] = $value['effect_deposit'] + $effct_deposit;
							$value['effect_deposit'] = $fixinfo['effect_deposit'];
						}
					}
					
					$fixinfo['fix_end_time'] = 0;
					$value['fix_end_time'] = $fixinfo['fix_end_time'];
					Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($uid, $id, $fixinfo, true);
       			}
       		}
       		
       		$data[$key] = $value;
		}
		
		if (count($animalUpdateList) > 0) {
			foreach ($animalUpdateList as $id => $s_num) {
				Hapyfish2_Ipanda_Bll_PhytotronAnimal::update($uid, $id, array('service_num' => $s_num));
			}
		}

		Hapyfish2_Ipanda_Cache_Building::setForestAnimalNum($uid, $animal_num);
		
		return $data;
	}
	
	public static function addBuilding($uid, &$info, $basicInfo = null)
	{
		return Hapyfish2_Ipanda_HFC_Building::addOne($uid, $info, $basicInfo);
	}
	
	public static function removeBuilding($uid,$building_id)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
			$dal->delete($uid, $building_id);
		} catch (Exception $e) {
			
			info_log("$uid,$building_id", 'building-delete-err');
			info_log($e->getMessage(), 'building-delete-err');
			return false;
		}
		Hapyfish2_Ipanda_HFC_Building::removeOne($uid, $building_id);
		return true;
	}
	
	public static function updateBuilding($uid, $building_id, $info)
	{
		return Hapyfish2_Ipanda_HFC_Building::updateOne($uid, $building_id, $info, true);
	}
	
	public static function updateAllBuilding($uid,$info)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
			$dal->updateAll($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'building-update-err');
			info_log($e->getMessage(), 'building-update-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_HFC_Building::updateAll($uid, $info);
		return true;
	}
	
	public static function getInDepot($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return $data;
		}

       	foreach ($list as $v) {
       		if (1 == $v['status']) {
       			continue;
       		}
       		
       		$data[] = array(
       			'id' 	=> $v['id'],
       			'cid'	=> $v['cid']
       		);
       	}
       	
       	return $data;
	}
	
	public static function getInForest($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return $data;
		}

       	foreach ($list as $v) {
       		if (1 == $v['status']) {
       			$data[] = $v;
       		}
       	}
       	
       	return $data;
	}
	
	public static function getOne($uid,$id)
	{
		return	Hapyfish2_Ipanda_HFC_Building::getOne($uid, $id);
	}
	
	public static function hadAnimalConsumeBuilding($uid,$consumeBuilding)
	{
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return false;
		}
	
		foreach ($list as $v) {
			if(($v['op_status'] == 1) && in_array($v['item_id'], $consumeBuilding) && ($v['durable'] > 0) && $v['status'] == 1) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function randBuilding($list, $num)
	{
		//未满的优先
		$list1 = array();
		$list2 = array();
		foreach ($list as $v) {
			$maxNum = floor($v['checkout_time']/60);
			if ($maxNum > $v['top_durable']) {
				$maxNum = $v['top_durable'];
			}
			if ($v['effect_deposit'] < $maxNum*$v['checkout_love']) {
				$list1[] = $v;
			} else {
				$list2[] = $v;
			}
		}
		
		$n1 = count($list1);
		if ($num == 1) {
			if ($n1 > 1) {
				$key = array_rand($list1, $num);
				return array($list1[$key]);
			} if ($n1 == 1) {
				return $list1;
			} else {
				$key = array_rand($list2, $num);
				return array($list2[$key]);
			}
		} else {
			$data = array();
			if ($num < $n1) {
				$keys = array_rand($list1, $num);
				foreach ($keys as $v) {
					$data[] = $list1[$v]; 
				}
				return $data;
			} else if ($num == $n1) {
				return $list1;
			} else {
				$data = $list1;
				$num2 = $num - $n1;
				$keys = array_rand($list2, $num2);
				foreach ($keys as $v) {
					$data[] = $list2[$v];
				}
				return $data;
			}
		}
	}
	
	/*
	 * 接受动物消费
	 * $rent = 0  不是租的， 1 是租的，租的没有离开的
	 */
	public static function reciveAnimal($uid, $num, $consumebuilding, $animalBasic, $rent = 0)
	{
		//平均分配到每个建筑上
		$data = self::canConsume($uid, $consumebuilding);
		$list = $data['list'];
		$allClass = $data['all_class'];
		$list_num = count($list);
		
		//如果每个建筑分配少于1个 ，随机取 $num 个建筑
		
		$result = array('mood' => array(), 'build' => array());
		
		//没有某一建筑
		//某一数值没达到要求
		$flag = false;
		if (count($allClass) > 0) {
			$need_build = array();
			foreach ($consumebuilding as $item_id) {
				if (!isset($allClass[$item_id])) {
					$need_build[] = $item_id;
					$flag = true;
				}
			}
		}
		
		$run_num = mt_rand(0, 4);
		$top_num = floor($num/2);
		if ($run_num > $top_num) {
			$run_num = $top_num;
		}
		
		if ($flag && $num > 1 && $run_num> 0 && !$rent) {
			$arr = array();
			for ($i = 0; $i < $run_num; $i++) {
				$need = $need_build[array_rand($need_build)];
				$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingInfo($need);
				$msgData['name'] = $basicInfo['name'];
				$msg = Hapyfish2_Ipanda_Bll_Language::getText('why has not {*name*} build,can you build it?', $msgData);
				$arr[] = array(
					'cid' => $animalBasic['cid'],
					'need_build' => $need,
					'msg' => $msg,
				);
			}
			$result['mood'] = $arr;
			$num -= $run_num;
		}
		
		$per_num = floor($num/$list_num);
		$data = array();
		if ($per_num < 1) {
			$per_num = 1;
			
			/*
			$rand_keys = array_rand($list, $num);
			if($num == 1) {
				$data[] = $list[$rand_keys];
			} else {
				foreach ($rand_keys as $v) {
					$data[] = $list[$v]; 
				}
			}*/
			$data = self::randBuilding($list, $num);
		} else {
			$data = $list;
		}
		
		//给 列表  $data 分配 $per_num 个动物
		$list_num = count($data);
		$tempnum = $num - ($list_num-1)*$per_num;
		$hasQuestion = false;
		$now = time();
		$unit_time = UNIT_TIME;
		foreach ($data as $v) {
			// 如果已经没有动物等待消费 且爱心为0 开始营业 累加到等待消费字段上
			// 如果已经没有动物等待消费 爱心大于0 累加到等待消费字段上  更新上次阶段性结算为当前时间
			// 如果有动物等待消费  也累加到等待消费的字段上
			$info = array();
			if ($v['wait_visitor_num'] == 0) {
				if ($v['effect_deposit'] <= 0) {
					$info['op_start_time'] = $now;
					$info['op_end_time'] = $now + $v['checkout_time'];
					$info['pre_service_end_time'] = $now;
					//根据耐久时间计算出可能的收费数量
					$t = floor($v['checkout_time']/$unit_time);
					$c = $v['durable'];
					$effect_num = $per_num <= $t ? $per_num : $t;
					$effect_num = $effect_num <= $c ? $effect_num : $c;
					$effect_deposit = $effect_num * $v['checkout_love'];
					$info['effect_deposit'] = $effect_deposit;
				} else {
					$t_time = $v['op_end_time'] - $now;
					if ($t_time >= $unit_time) {
						$t = floor($t_time/$unit_time);
						$c = $v['durable'];
						$effect_num = $per_num <= $t ? $per_num : $t;
						$effect_num = $effect_num <= $c ? $effect_num : $c;
						$effect_deposit = $effect_num * $v['checkout_love'];
						$info['effect_deposit'] = $v['effect_deposit'] + $effect_deposit;
					}
					$info['pre_service_end_time'] = $now;
				}
			}
			else if($now < $v['op_end_time'])
			{
				//条件是未到结算时间
				
				//计算本次动物消费开始的时间
				
				//1 计算剩余动物消费结束 时间
				$t1 = $v['wait_visitor_num'] * $unit_time;
				$t2 = $v['pre_service_end_time'] + $t1;
				$t_time = $v['op_end_time'] - $t2;
				if ($t_time >= $unit_time) {
					//计算剩余耐久
					$c = $v['durable'] - $v['wait_visitor_num'];
					if($c > 0) {
						$t = floor($t_time/$unit_time);
						$effect_num = $per_num <= $t ? $per_num : $t;
						$effect_num = $effect_num <= $c ? $effect_num : $c;
						$effect_deposit = $effect_num * $v['checkout_love'];
						$info['effect_deposit'] = $v['effect_deposit'] + $effect_deposit;
					}
				}
			}
			
			$info['wait_visitor_num'] = $v['wait_visitor_num'] + $per_num;
			
			$ok = Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($v['uid'], $v['id'], $info, true);
			
			if($ok) {
				if(sizeof($result['build']) == 0) {
					$t = $tempnum;
				} else {
					$t = $per_num;
				}
				
				//只有熊猫才会出问题，并且只有一只会有
				if (!$hasQuestion && $animalBasic['cid'] == 161) {
					//questionId, questionTick
					//熊猫问题100问返回结果
					$question = Hapyfish2_Ipanda_Bll_PandaQuestion::get($uid);
					$hasQuestion = true;
					
					$result['build'][$v['id']] = array(
						'id'	=> $v['id'],
						'num'	=> $t,
						'cid'	=> $animalBasic['cid'],
						'class_name'	=> $animalBasic['class_name'],
						'name'	=> $animalBasic['name'],
						'questionId' => $question['id'],
						'questionTick' => $question['tick']
					);
				} else {
					$result['build'][$v['id']] = array(
						'id'	=> $v['id'],
						'num'	=> $t,
						'cid'	=> $animalBasic['cid'],
						'class_name'	=> $animalBasic['class_name'],
						'name'	=> $animalBasic['name']
					);
				}
			}
		}
		
		if ($rent == 0) {
	        //成就(培育动物数)
	    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '4', $num);
	    	
	    	//派发任务
	    	//培育动物
	    	$event = array('uid' => $uid, 'animal_cid' => $animalBasic['cid'], 'num' => $num);
	    	Hapyfish2_Ipanda_Bll_Event::receiveAnimal($event);
		} else {
	        //成就(领养动物数)
	    	Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '5', $num);
	    	
	    	//派发任务
	    	//养动物数
	    	$event = array('uid' => $uid, 'animal_cid' => $animalBasic['cid'], 'num' => $num);
	    	Hapyfish2_Ipanda_Bll_Event::rantAnimal($event);
		}
		
		return $result;
	}
	
	/*
	 * 可消费建筑列表
	 */
	public static function canConsume($uid, $consumebuilding)
	{
		//$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		$list = self::settleBuildingListOnForest($uid);
		$data = array();
		$allClass = array();
		$needClass = array();
		
		$itemIdList = array();
		foreach ($consumebuilding as $item_id) {
			if (!isset($itemIdList[$item_id])) {
				$itemIdList[$item_id] = 1;
			}
		}
		
		foreach ($list as $v) {
			if ($v['status'] == 1 && $v['op_status'] == 1) {
				if (isset($itemIdList[$v['item_id']])) {
					if ($v['durable'] > 0) {
						$data[] = $v;
					}
					if (!isset($allClass[$v['item_id']])) {
						$allClass[$v['item_id']] = 1;
					}
				}
			}
		}
		
		return array('list' => $data, 'all_class' => $allClass);
	}
	
	 /*
	  * 结算
	  * 
	  */
	public static function checkout($data)
	{
		if (empty($data)) {
			return  false;
		}
		
		//结算，增加玩家的爱心
		//如果有等待消费的动物，耐久大于  0，开始新一轮的结算周期
		//没有等待的动物 ，就把爱心 营业开始和结束时间置为 0
		$info['deposit'] = 0;
		$info['take_deposit'] = 0;
		$info['effect_deposit'] = 0;
		
		if ($data['wait_visitor_num'] > 0 && $data['durable'] > 0) {
			$unit_time = UNIT_TIME;
			$now = time();
			$info['op_start_time'] = $now;
			$info['op_end_time'] = $now + $data['surplus_time'];
			//根据耐久和时间计算新一轮的有效消费
			$t = floor($data['surplus_time']/$unit_time);
			$c = $data['durable'];
			$effect_num = $data['wait_visitor_num'] <= $t ? $data['wait_visitor_num'] : $t;
			$effect_num = $effect_num <= $c ? $effect_num : $c;
			$effect_deposit = $effect_num * $data['checkout_love'];
			$info['effect_deposit'] = $effect_deposit;
		} else {
			$info['op_start_time'] = 0;
			$info['op_end_time'] = 0;
		}
		
		//$savedb = USE_CACHE ? false : true;
		$ret = Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($data['uid'], $data['id'], $info, true);
		if ($ret) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	 * 升级建筑
	 */
	public static function levelup($building, $nextInfo, $basicInfo)
	{		
		$data['cid'] 				= $nextInfo['cid'];
		$data['level'] 				= $nextInfo['level'];
		$data['checkout_time'] 		= $nextInfo['checkout_time'][$building['checkout_time_type']]['time'];
		$data['checkout_love'] 		= $nextInfo['checkout_time'][$building['checkout_time_type']]['love'];
		$data['durable'] 			= $nextInfo['durable'];
		$data['top_durable'] 		= $nextInfo['durable'];
		//$data['op_start_time'] 	= 0;
		//$data['op_end_time'] 		= 0;
		//$data['deposit'] 			= 0;
		$data['take_deposit'] 		= 0;
		//$data['wait_visitor_num'] = 0;
		//更新加成属性
		$attr = array();
		if (isset($building['attr'])) {
			$attr = json_decode($building['attr'], true);
		}
		if (empty($attr)) {
			$attr = $nextInfo['attribute'];
			$attr[6] = 0;
			$attr[7] = 0;
		} else {
			$newAttr = $nextInfo['attribute'];
			$oldAttr = $basicInfo['attribute'];
			for($i = 0; $i < 6; $i++) {
				if ($newAttr[$i] > $oldAttr[$i]) {
					$attr[$i] += ($newAttr[$i] - $oldAttr[$i]);
				}
			}
		}
		$data['attr'] = json_encode($attr);
		
		$ok = Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($building['uid'], $building['id'], $data, true);
		if (!$ok) {
			$error = -103;
			$status = -1;
		} else {
			$error = null;
			$status = 1;
			//更新格子数据
			Hapyfish2_Ipanda_Cache_MapGrid::upgradeBuidlingToGrid($building['uid'], $building['id'], $building['cid'], $building['x'], $building['z'], $building['mirro'], $basicInfo['nodes'], $nextInfo['cid']);
		}
	
		return array('error' => $error, 'status' => $status);
	}
	
	/*
	 *  检查是否已偷取
	 */
	public static function cantakeLove($uid, $buildingInfo)
	{
		return Hapyfish2_Ipanda_Cache_Building::cantakelove($uid, $buildingInfo);
	}
	
	/*
	 * 偷取
	 * 
	 */
	public static function takeLove($uid, $buildingInfo, $num)
	{
		//偷取，更新缓存，先不落地
		$info = array('take_deposit' => $num);
		Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($buildingInfo['uid'], $buildingInfo['id'], $info, false);
		//记录log
		Hapyfish2_Ipanda_Cache_Building::takelovelog($uid, $buildingInfo, $num);
		return true;
	}
	
	public static function getMaterial($level, $buildingInfo, $animalInfo)
	{
		$data = Hapyfish2_Ipanda_Bll_BasicInfo::getMaterialGroupList();
		$preData = array();
		foreach ($data as $v)
		{
			if( $v['building_level'] == $buildingInfo['level'] && $v['nature_type'] == $animalInfo['nature_type']&& $v['material_group_id'] == $buildingInfo['material_group_id'])
			{
				$attribute = $buildingInfo['attribute'];
				$theAttr = json_decode($v['attr_condtion']);
				$flag = 1;
				for($i = 0, $num = count($attribute); $i < $num; $i++ )
				{
					if($attribute[$i] < $theAttr[$i])
					{
						$flag = 0;
						break;
					}	
				}
				if($flag == 0)
				{
					break;
				}
				$preData = $v;
			}
		}
		return $preData['material'];
	}
	
}