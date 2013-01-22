<?php

class Hapyfish2_Ipanda_Bll_Expire
{
	const EXPIRE_ALTER_DAY = 2;
	
	public static function getDecorateList($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
		if (empty($list)) {
			return $data;
		}
		
		$t = time();
		$temp = array();
		
		$decorateList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		foreach ($list as $v) {
			//不是永久
			if (isset($v['end_time']) && $v['end_time'] > 0) {
				$basicInfo = $decorateList[$v['cid']];
				//不包括不能续费的
				if (isset($basicInfo['can_renewal']) && $basicInfo['can_renewal'] == 0) {
					continue;
				}
				
				if ($v['end_time'] - $t < self::EXPIRE_ALTER_DAY*86400) {
					if (isset($temp[$v['cid']])) {
						$temp[$v['cid']] += 1;
					} else {
			     		$temp[$v['cid']] = 1;
					}
				}
				
			}
		}
		
		if (!empty($temp)) {
			foreach ($temp as $cid => $num) {
				$data[] = array('cid' => $cid, 'num' => $num);
			}
		}
		
		return $data;
	}
	
	public static function hasExpireDecorate($uid, $highcache = false)
	{
		if ($highcache) {
			$list = Hapyfish2_Ipanda_Cache_Decorate::getListFromHighCache($uid);
		} else {
			$list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
		}
		
		if (empty($list)) {
			return false;
		}
		
		$t = time();
		if ($highcache) {
			$startTime = $t;
		} else {
			$startTime = 0;
		}
		
		$decorateList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		foreach ($list as $v) {
			//不是永久	
			if (isset($v['end_time']) && $v['end_time'] > $startTime) {
				$basicInfo = $decorateList[$v['cid']];
				//不包括不能续费的
				if (isset($basicInfo['can_renewal']) && $basicInfo['can_renewal'] == 0) {
					continue;
				}
				
				if ($v['end_time'] - $t < self::EXPIRE_ALTER_DAY*86400) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	public static function getDecoratePairList($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
		if (empty($list)) {
			return $data;
		}
		
		$t = time();

		$decorateList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		foreach ($list as $v) {
			//不是永久	
			if (isset($v['end_time']) && $v['end_time'] > 0) {
				$basicInfo = $decorateList[$v['cid']];
				//不包括不能续费的
				if (isset($basicInfo['can_renewal']) && $basicInfo['can_renewal'] == 0) {
					continue;
				}
				
				if ($v['end_time'] -$t < self::EXPIRE_ALTER_DAY*86400) {
					if (isset($data[$v['cid']])) {
						$data[$v['cid']][]= array('id' => $v['id'], 'end_time' => $v['end_time']);
					} else {
			     		$data[$v['cid']] = array(array('id' => $v['id'], 'end_time' => $v['end_time']));
					}
				}
			}
		}
		
		return $data;
	}
	
	public static function getPhytotronList($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		if (empty($list)) {
			return $data;
		}
		
		$t = time();
		$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalList($uid);
		foreach ($list as $v) {
			//不是永久	
			if (isset($v['stop_time']) && $v['stop_time'] > 0) {
	    		$pid = $v['ipanda_user_phytotron_animal_id'];
	    		$row = $phytotronAnimalList[$pid];
				if ($v['stop_time'] - $t < self::EXPIRE_ALTER_DAY*86400) {
					$data[] = array('id' => $v['id'], 'cid' => $row['phytotron_cid'], 'unlock_id' => $v['ipanda_phytotron_unlock_list_id']);
				}
			}
		}
		
		return $data;
	}
	
	public static function hasExpirePhytotron($uid, $highcache = false)
	{
		if ($highcache) {
			$list = Hapyfish2_Ipanda_Cache_Phytotron::getListFromHighCache($uid);
		} else {
			$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		}
		
		if (empty($list)) {
			return false;
		}
		
		$t = time();
		if ($highcache) {
			$startTime = $t;
		} else {
			$startTime = 0;
		}
		foreach ($list as $v) {
			//不是永久	
			if (isset($v['stop_time']) && $v['stop_time'] > $startTime) {
				if ($v['stop_time'] - $t < self::EXPIRE_ALTER_DAY*86400) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	public static function getPhytotronPairList($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		if (empty($list)) {
			return $data;
		}
		
		$t = time();
		$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalList($uid);
		foreach ($list as $v) {
			//不是永久	
			if (isset($v['stop_time']) && $v['stop_time'] > 0) {
	    		$pid = $v['ipanda_user_phytotron_animal_id'];
	    		$row = $phytotronAnimalList[$pid];
				if ($v['stop_time'] -$t < self::EXPIRE_ALTER_DAY*86400) {
					$data[$v['id']] = array('id' => $v['id'], 'cid' => $row['phytotron_cid'], 'unlock_id' => $v['ipanda_phytotron_unlock_list_id'], 'stop_time' => $v['stop_time']);
				}
			}
		}
		
		return $data;
	}

	public static function renewalDecorateByCid($uid, $cid, $priceType)
	{
		//
		$list = self::getDecoratePairList($uid);
		if (empty($list)) {
			return -103;
		}
		
		if (!isset($list[$cid])) {
			return -104;
		}
		
		$decorateList = $list[$cid];
		$num = count($decorateList);
		
		$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
		if ($basicInfo['price_type'] == 0 && $priceType != 1) {
			return -104;
		}
		if ($basicInfo['price_type'] == 1 && $priceType != 2) {
			return -104;
		}
		
		if ($priceType == 1) {
			//检查爱心是否足够
			$love = $basicInfo['love_price'] * $num;
			$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
			if ($userLove < $love) {
				return -100;
			}
		} else if ($priceType == 2) {
			//检查金币是否足够
			$gold = $basicInfo['gold_price'] * $num;
			$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
			if ($userGold < $gold) {
				return -102;
			}
		} else {
			return -104;
		}
		
		$t = time();
		//开始 
		if ($priceType == 1) {
			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $love);
			if (!$ok) {
				return -100;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$love);
		} else if ($priceType == 2) {
			$goldInfo = array(
				'uid' 			=> $uid,
				'cost' 			=> $gold,
				'summary' 		=> '续费装饰' . $basicInfo['name'] . '(' . $num . '个),CID:' . $basicInfo['cid'],
				'create_time' 	=> $t,
				'user_level' 	=> '',
				'cid' 			=> $cid,
				'num' 			=> $num
			);
			$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
			if (!$ok) {
				return -102;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$gold);
		}
		
		if ($priceType == 1) {
			foreach ($decorateList as $item) {
				$info['end_time'] = $item['end_time'] + $basicInfo['effect_time']*86400;
				Hapyfish2_Ipanda_Bll_Decorate::update($uid, $item['id'], $info);
			}
		} else if ($priceType == 2) {
			$info['end_time'] = 0;
			foreach ($decorateList as $item) {
				Hapyfish2_Ipanda_Bll_Decorate::update($uid, $item['id'], $info);
			}
		}

		return 1;
	}
	
	public static function renewalAllDecorate($uid, $priceType)
	{
		//
		$list = self::getDecoratePairList($uid);
		if (empty($list)) {
			return -103;
		}
		
		$basicInfoList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		
		$love = 0;
		$gold = 0;
		foreach ($list as $cid => $decorateList) {
			$basicInfo = $basicInfoList[$cid];
			$num = count($basicInfoList);
			if ($basicInfo['price_type'] == 0 && $priceType != 1) {
				return -104;
			}
			if ($basicInfo['price_type'] == 1 && $priceType != 2) {
				return -104;
			}
			
			if ($priceType == 1) {
				$love += $basicInfo['love_price'] * $num;
			} else if ($priceType == 2) {
				$gold += $basicInfo['gold_price'] * $num;
			}
		}
		
		if ($priceType == 1) {
			//检查爱心是否足够
			$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
			if ($userLove < $love) {
				return -100;
			}
		} else if ($priceType == 2) {
			//检查金币是否足够
			$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
			if ($userGold < $gold) {
				return -102;
			}
		} else {
			return -104;
		}
		
		$t = time();
		//开始 
		if ($priceType == 1) {
			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $love);
			if (!$ok) {
				return -100;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$love);
		} else if ($priceType == 2) {
			$summary = '批量续费装饰:';
			foreach ($list as $cid => $decorateList) {
				$basicInfo = $basicInfoList[$cid];
				$num = count($decorateList);
				$summary .= $basicInfo['name'] . '(' . $num . '个)';
			}
			
			$goldInfo = array(
				'uid' 			=> $uid,
				'cost' 			=> $gold,
				'summary' 		=> $summary,
				'create_time' 	=> $t,
				'user_level' 	=> 0,
				'cid' 			=> 0,
				'num' 			=> 0
			);
			
			$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
			if (!$ok) {
				return -102;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$gold);
		}
		
		foreach ($list as $cid => $decorateList) {
			if ($priceType == 1) {
				foreach ($decorateList as $item) {
					$info['end_time'] = $item['end_time'] + $basicInfo['effect_time']*86400;
					Hapyfish2_Ipanda_Bll_Decorate::update($uid, $item['id'], $info);
				}
			} else if ($priceType == 2) {
				$info['end_time'] = 0;
				foreach ($decorateList as $item) {
					Hapyfish2_Ipanda_Bll_Decorate::update($uid, $item['id'], $info);
				}
			}
		}
		
		return 1;
	}
	
	public static function renewalPhytotron($uid, $id)
	{
		$list = self::getPhytotronPairList($uid);
		if (empty($list)) {
			return -103;
		}
		
		if (!isset($list[$id])) {
			return -104;
		}
		
		$phytotronInfo = $list[$id];
		$phytotronUnlockInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronUnlockInfo($phytotronInfo['unlock_id']);
		
		$love = $phytotronUnlockInfo['love_price'];
		$gold = $phytotronUnlockInfo['gold_price'];
		
		if ($love > 0) {
			//检查爱心是否足够
			$userLove = Hapyfish2_Ipanda_HFC_User::getUserLove($uid);
			if ($userLove < $love) {
				return -100;
			}
		} else if ($gold > 0) {
			//检查金币是否足够
			$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
			if ($userGold < $gold) {
				return -102;
			}
		}
		
		//材料
		$need_material = $phytotronUnlockInfo['need_material'];
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
				return array('eid' => -106, 'data' => $errorData);
			}
		}		

		//开始 
		$t = time();
		if ($love > 0) {
			$ok = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $love);
			if (!$ok) {
				return -2;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$love);
		} else if ($gold > 0) {
			$goldInfo = array(
				'uid' 			=> $uid,
				'cost' 			=> $gold,
				'summary' 		=> '续费培育屋' . ',CID:' . $phytotronInfo['cid'],
				'create_time' 	=> $t,
				'user_level' 	=> '',
				'cid' 			=> $phytotronInfo['cid'],
				'num' 			=> 1
			);
			$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
			if (!$ok) {
				return -102;
			}
			Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$gold);
		}
		
		//扣除材料
		if (!empty($need_material)) {
			$ok = Hapyfish2_Ipanda_HFC_Material::useMultiple($uid, $need_material, $userMaterial);
			if (!$ok) {
				info_log($uid . ':' . json_encode($need_material), 'need_material.err');
			}
		}
		
		if ($phytotronUnlockInfo['id'] == 5 && $phytotronUnlockInfo['effect_time'] == 0) {
			$info['stop_time'] = 0;
		} else {
			$info['stop_time'] = $phytotronInfo['stop_time'] + $phytotronUnlockInfo['effect_time']*86400;
		}
		Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
		
		return 1;
	}
}