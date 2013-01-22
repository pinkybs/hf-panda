<?php

class Hapyfish2_Ipanda_Tool_Repair_MapGrid
{
	public static function repair($uid)
	{
		$buildingAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
		$decorateBasicList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();

		Hapyfish2_Ipanda_Cache_MapGrid::clear($uid);
		self::getBasicEffect($uid, $buildingAssetList);
		self::calcEffect($uid, $decorateBasicList);
	}
	
	public static function repairAll()
	{
		$buildingAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
		$decorateBasicList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();

		$db = Hapyfish2_Ipanda_Dal_Repair::getDefaultInstance();
		
		if (APP_STATUS_DEV == 4) {
			$dbNum = 1;
			$tbNum = 1;
		} else {
			$dbNum = DATABASE_NODE_NUM;
			$tbNum = 10;
		}
		
		for ($i = 0; $i < $dbNum; $i++) {
			for ($j = 0; $j < $tbNum; $j++) {
				$uidList = $db->getUidListByPage($i, $j);
				$kCount = count($uidList);
				for ($k = 0; $k < $kCount; $k++) {
					$uid = $uidList[$k]['uid'];
					if (self::check($uid, $buildingAssetList, $decorateBasicList)) {
						Hapyfish2_Ipanda_Cache_MapGrid::clear($uid);
						self::getBasicEffect($uid, $buildingAssetList);
						self::calcEffect($uid, $decorateBasicList);
						info_log($uid, 'MapGrid-repairAll');
					}
				}
			}
		}
	}
	
	public static function check($uid, &$buildingAssetList, &$decorateBasicList)
	{
		$level = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		if ($level < 3) {
			return false;
		}
		
		$data = Hapyfish2_Ipanda_Bll_MapGrid::getMap($uid);
		$decoreateIds = array();
		$decoreateIds2 = array();
		$buildingIds = array();
		$buildingIds2 = array();
		$phytotronIds = array();
		$phytotronIds2 = array();
		foreach($data as $v) {
			if ($v != 0) {
				$temp = explode(':', $v);
				$id = $temp[0];
				$cid = $temp[1];
				$type = substr($cid, -2);
				if ($type == '21') {
					if (!isset($decoreateIds[$id])) {
						$decoreateIds[$id] = 1;
						$decoreateIds2[$id] = $cid;
					} else {
						$decoreateIds[$id] += 1;
					}
				} else if ($type == '11') {
					if (!isset($buildingIds[$id])) {
						$buildingIds[$id] = 1;
						$buildingIds2[$id] = $cid;
					} else {
						$buildingIds[$id] += 1;
					}
				} else if ($type == '31') {
					if (!isset($phytotronIds[$id])) {
						$phytotronIds[$id] = 1;
						$phytotronIds2[$id] = $cid;
					} else {
						$phytotronIds[$id] += 1;
					}
				}
			}
		}

		if (!empty($buildingIds)) {
			foreach ($buildingIds as $k1 => $v1) {
				$buildingBasicInfo = $buildingAssetList[$buildingIds2[$k1]];
				if ($buildingBasicInfo) {
					$nodes = explode('*', $buildingBasicInfo['nodes']);
					$size = $nodes[0]*$nodes[1];
					if ($v1 > $size) {
						//有幻影
						info_log($uid . ':1:' . $k1, 'MapGrid-repairAll-Building');
						return true;
					}
				}
			}
			
			$buildingList = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
			$ids1 = array();
			foreach ($buildingList as $v) {
				if ($v['status'] == 1) {
					$ids1[$v['id']] = 1;
				}
			}
			
			if (empty($ids1)) {
				info_log($uid . ':2:', 'MapGrid-repairAll-Building');
				return true;
			}
			
			foreach ($buildingIds as $k1 => $v1) {
				//
				if (!isset($ids1[$k1])) {
					info_log($uid . ':3:' . $k1, 'MapGrid-repairAll-Building');
					return true;
				}
			}
			
			foreach ($ids1 as $k1 => $v1) {
				//
				if (!isset($buildingIds[$k1])) {
					info_log($uid . ':4:' . $k1, 'MapGrid-repairAll-Building');
					return true;
				}
			}
		}
		
		if (!empty($decoreateIds)) {
			foreach ($decoreateIds as $k1 => $v1) {
				$decoreateBasicInfo = $decorateBasicList[$decoreateIds2[$k1]];
				if ($decoreateBasicInfo) {
					$nodes = explode('*', $decoreateBasicInfo['nodes']);
					$size = $nodes[0]*$nodes[1];
					if ($v1 > $size) {
						//有幻影
						info_log($uid . ':1:' . $k1, 'MapGrid-repairAll-Decoreate');
						return true;
					}
				}
			}
			
			$decorateList = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
			$ids2 = array();
			foreach ($decorateList as $v) {
				if ($v['status'] == 1) {
					$ids2[$v['id']] = 1;
				}
			}
			
			if (empty($ids2)) {
				info_log($uid . ':2:', 'MapGrid-repairAll-Decoreate');
				return true;
			}
			
			foreach ($decoreateIds as $k1 => $v1) {
				//
				if (!isset($ids2[$k1])) {
					info_log($uid . ':3:' . $k1, 'MapGrid-repairAll-Decoreate');
					return true;
				}
			}
			
			foreach ($ids2 as $k1 => $v1) {
				//
				if (!isset($decoreateIds[$k1])) {
					info_log($uid . ':4:' . $k1, 'MapGrid-repairAll-Decoreate');
					return true;
				}
			}
		}
		
		if (!empty($phytotronIds)) {
			foreach ($phytotronIds as $k1 => $v1) {
				$size = 2*2;
				if ($v1 > $size) {
					//有幻影
					info_log($uid . ':1:' . $k1, 'MapGrid-repairAll-phytotron');
					return true;
				}
			}
			
			$phytotronList = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
			$ids3 = array();
			foreach ($phytotronList as $v) {
				if ($v['status'] == 1) {
					$ids3[$v['id']] = 1;
				}
			}
			
			if (empty($ids3)) {
				info_log($uid . ':2:', 'MapGrid-repairAll-phytotron');
				return true;
			}
			
			foreach ($phytotronIds as $k1 => $v1) {
				//
				if (!isset($ids3[$k1])) {
					info_log($uid . ':3:' . $k1, 'MapGrid-repairAll-phytotron');
					return true;
				}
			}
			
			foreach ($ids3 as $k1 => $v1) {
				//
				if (!isset($phytotronIds[$k1])) {
					info_log($uid . ':4:' . $k1, 'MapGrid-repairAll-phytotron');
					return true;
				}
			}
		}
		
		return false;
	}
	
    public static function calcEffect($uid, &$decorateBasicList)
	{
		$data = Hapyfish2_Ipanda_Bll_MapGrid::getMap($uid);
		$x = 0;
		$z = 0;
		$decorateList = array();
		foreach($data as $k => $v) {
			$tmp = explode(',', $k);
			if ($tmp[0] > $x) {
				$x = $tmp[0];
			}
			if ($tmp[1] > $z) {
				$z = $tmp[1];
			}
			if ($v != 0) {
				$temp = explode(':', $v);
				$type = substr($temp[1], -2);
				if ($type == '21') {
					$effect_id = $temp[0];
					if (!isset($decorateList[$effect_id])) {
						$decorateList[$effect_id] = array($temp[0], $temp[1], $temp[2], $temp[3], $temp[4]);
					}
				}
			}
		}
		
		$itemList = array();
		foreach ($decorateList as $k => $v) {
			$id = $v[0];
			$cid = $v[1];
			$x = $v[2];
			$z = $v[3];
			$mirro = $v[4];
			$basicInfo = $decorateBasicList[$cid];
			$itemList[$id] = Hapyfish2_Ipanda_Bll_MapGrid::calcEffect($uid, $id, $cid, $x, $z, $mirro, $basicInfo);
		}

		foreach ($itemList as $k => $v) {
			$item = $v['item'];
			if (empty($item)) {
				continue;
			}
			$attribute_change = $v['attribute_change'];
			$attr_love = $v['attr_love'];
			$attr_exp = $v['attr_exp'];
			foreach ($item as $d) {
				//building
				if ($d[2] == '11') {
					$building = Hapyfish2_Ipanda_HFC_Building::getOne($uid, $d[0]);
					if ($building) {
						$attr = json_decode($building['attr'], true);
						for($i = 0; $i < 6; $i++) {
							$attr[$i] += $attribute_change[$i];
						}
						$attr[6] += $attr_love;
						$attr[7] += $attr_exp;
						$building['attr'] = json_encode($attr);
						
						$effect_source = json_decode($building['effect_source'], true);
						if (empty($effect_source)) {
							$effect_source = array($k);
						} else {
							if (!in_array($k, $effect_source)) {
								$effect_source[] = $k;
							}
						}
						$building['effect_source'] = json_encode($effect_source);
						
						Hapyfish2_Ipanda_HFC_Building::updateOne($uid, $d[0], $building, true);
					}
				} else if ($d[2] == '31') {
					//phytotron
					$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronInfo($uid, $d[0]);
					if ($phytotron) {
						if (!isset($phytotron['effect_exp'])) {
							$phytotron['effect_exp'] = $attr_exp;
						} else{
							$phytotron['effect_exp'] += $attr_exp;
						}
						if (!isset($phytotron['effect_exp'])) {
							$effect_source = array($k);
						} else {
							$effect_source = json_decode($phytotron['effect_source'], true);
							if (empty($effect_source)) {
								$effect_source = array($k);
							} else {
								if (!in_array($k, $effect_source)) {
									$effect_source[] = $k;
								}
							}
						}
						$phytotron['effect_source'] = json_encode($effect_source);
						
						Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $d[0], $phytotron);
					}
				}
			}
		}
	}
	
	public static function getBasicEffect($uid, &$buildingAssetList)
	{
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return;
		}
		
		foreach ($list as $item) {
			$basicInfo = $buildingAssetList[$item['cid']];
			if ($basicInfo) {
				$item['attr'] = $basicInfo['attribute'];
				if (!empty($item['attr'])) {
					$item['attr'][] = 0;
					$item['attr'][] = 0;
				}
				
				$item['attr'] = json_encode($item['attr']);
				Hapyfish2_Ipanda_HFC_Building::updateOne($uid, $item['id'], $item, true);
			}
		}
		
		$info = array('effect_exp' => 0, 'effect_source' => '[]');
		$ok = Hapyfish2_Ipanda_Bll_Phytotron::updateAllPhytotron($uid, $info);
	}
}