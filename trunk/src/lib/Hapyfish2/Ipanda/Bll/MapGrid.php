<?php

class Hapyfish2_Ipanda_Bll_MapGrid
{
	
	//取的解锁区域(可放单元)
	public static function getCleanMap($uid)
	{
		$data = array();
		//每块4个格子单元
		$block = 4;
		$blockNodes = $block*$block;
		
		//0是森林编号，默认为0
		$forest = Hapyfish2_Ipanda_Bll_Forest::getForestByNo($uid, 0);
		//初始格子单元(8*8)
		$nodes = $forest['nodes'];
		$size = explode('*', $nodes);
		//初始单元
		$n = ($size[0]*$size[1])/($blockNodes);
		//扩展单元数
		$n += $forest['extend_land'];
		
		//$n现在为总的单元数
		
		//现在最多100个块
		for($i = 2; $i <= 10; $i++) {
			if ($n >= $i*$i && $n < ($i+1)*($i+1)) {
				break;
			}
		}
		
		//成型的格子最大长度
		$countNodes = $i*$block;
		
		//成型的格子全部有效
		for($j = 0; $j < $countNodes; $j++) {
			for($k = 0; $k < $countNodes; $k++) {
				$data[$j . ',' . $k] = 0;
			}
		}
		
		//边上多出的块
		$spare = $n - $i*$i;
		if ($spare > 0) {
			$tileX = $countNodes;
			$tileY = 0;
			for($j = 0; $j < $spare; $j++) {
				for($k = 0; $k < $blockNodes; $k++) {
					$lieNum = floor($k/$block);
					$rowNum = $k%$block;
					$data[($tileX + $rowNum) . ',' . ($tileY + $lieNum)] = 0;
				}
			
				if ($tileY < $countNodes) {
					$tileX = $countNodes;
					$tileY += $block;
				} else {
					$tileX -= $block;
					$tileY = $countNodes;
				}
			}
		}
		
		return $data;
	}
	
	public static function getMap($uid)
	{
		$map = self::getCleanMap($uid);
		$grid = Hapyfish2_Ipanda_Cache_MapGrid::getGrid($uid);
		foreach ($grid as $k => $v) {
			if (isset($map[$k])) {
				$map[$k] = $v;
			} else {
				//越界
				//echo $k . '<br/>';
			}
		}
		
		return $map;
	}
	
	public static function checkMap($uid, $x, $z, $mirro, $nodes, $id = 0, $cid = 0)
	{
		if (CAN_OVERLAY){
			return true;
		}
		
		$map = self::getMap($uid);
		$rect = Hapyfish2_Ipanda_Cache_MapGrid::getCleanRect($x, $z, $mirro, $nodes);
		foreach ($rect as $k => $v) {
			if (isset($map[$k])) {
				if ($map[$k] != 0) {
					//已经有物品
					if ($id == 0) {
						return false;
					} else {
						$tmp = explode(':', $map[$k]);
						if ($id != $tmp[0] || $cid != $tmp[1]) {
							return false;
						}
					}
				}
			} else {
				//越界
				return false;
			}
		}
		return true;
	}
	
	public static function getEffectRange($x, $z, $mirro, $nodes, $effectNodes)
	{
		if ($nodes == $effectNodes) {
			return null;
		}
		
		$size = explode('*', $nodes);
		$rSize = explode('*', $effectNodes);
	    if ($mirro == 0) {
    		$xWidth = $size[0];
    		$zWidth = $size[1];
    		$rxWidth = $rSize[0];
    		$rzWidth = $rSize[1];
    	} else {
    		$xWidth = $size[1];
    		$zWidth = $size[0];
    		$rxWidth = $rSize[1];
    		$rzWidth = $rSize[0];
    	}
    	
    	if ($rxWidth <= $xWidth || $rzWidth <= $zWidth) {
    		return null;
    	}
    	
	    if (($rxWidth - $xWidth)%2 == 1 || ($rzWidth - $zWidth)%2 == 1) {
    		return null;
    	}

    	$data = array();
    	for($i = 0; $i < $xWidth; $i++) {
	    	for($j = 0; $j < $zWidth; $j++) {
	    		$v = ($x + $i) . ',' . ($z + $j);
	    		$data[$v] = 1;
	    	}
    	}
    	
    	$rx = $x - ($rxWidth - $xWidth)/2;
    	$rz = $z - ($rzWidth - $zWidth)/2;
    	$range = array();
	    for($i = 0; $i < $rxWidth; $i++) {
	    	for($j = 0; $j < $rzWidth; $j++) {
	    		$v = ($rx + $i) . ',' . ($rz + $j);
	    		if (!isset($data[$v])) {
	    			$range[] = $v;
	    		}
	    	}
    	}
    	
    	return $range;
	}
	
	public static function calcEffect($uid, $id, $cid, $x, $z, $mirro, $basicInfo = null, $gridData = null)
	{
		if ($basicInfo == null) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
		}
		if (!$basicInfo) {
			return null;
		}
		
		$nodes = $basicInfo['nodes'];
		$effectNodes = $basicInfo['effect_nodes'];
		$range = self::getEffectRange($x, $z, $mirro, $nodes, $effectNodes);
		if (!$range || empty($range)) {
			return null;
		}
		
		if ($gridData == null) {
			$gridData = Hapyfish2_Ipanda_Cache_MapGrid::getGridData($uid);
			if (empty($gridData) || empty($gridData['grid'])) {
				return null;
			}
		}
		
		$grid = $gridData['grid'];
		$data = array();
		foreach ($range as $v) {
			if (isset($grid[$v])) {
				if (!isset($data[$grid[$v]])) {
					$data[$grid[$v]] = 1;
				}
			}
		}
		
		//没有影响物
		if (empty($data)) {
			return null;
		}
		
		$item = array();
		foreach ($data as $k => $v) {
			$temp = explode(':', $k);
			$type = substr($temp[1], -2);
			//[[id,cid,type],[...]]
			$item[] = array((int)$temp[0], (int)$temp[1], $type);
		}
		
		return array(
			'id' => $id,
			'item' => $item,
			'attribute_change' => $basicInfo['attribute_change'],
			'attr_love' => $basicInfo['attr_love'],
			'attr_exp' => $basicInfo['attr_exp']
		);
	}
	
	public static function addEffect($uid, $id, $effectData)
	{
		if (empty($effectData)) {
			return;
		}
		
		$item = $effectData['item'];
		if (empty($item)) {
			return;
		}
		
		$id = (int)$id;
		$attribute_change = $effectData['attribute_change'];
		$attr_love = $effectData['attr_love'];
		$attr_exp = $effectData['attr_exp'];
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
					if (empty($effect_source) || !in_array($id, $effect_source)) {
						$effect_source[] = $id;
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
						$effect_source = array($id);
					} else {
						$effect_source = json_decode($phytotron['effect_source'], true);
						if (empty($effect_source) || !in_array($id, $effect_source)) {
							$effect_source[] = $id;
						}
					}
					$phytotron['effect_source'] = json_encode($effect_source);
					
					Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $d[0], $phytotron);
				}
			}
		}
	}
	
	public static function removeEffect($uid, $id, $effectData)
	{
		if (empty($effectData)) {
			return;
		}
		
		$item = $effectData['item'];
		if (empty($item)) {
			return;
		}
		
		$id = (int)$id;
		$attribute_change = $effectData['attribute_change'];
		$attr_love = $effectData['attr_love'];
		$attr_exp = $effectData['attr_exp'];
		foreach ($item as $d) {
			//building
			if ($d[2] == '11') {
				$building = Hapyfish2_Ipanda_HFC_Building::getOne($uid, $d[0]);
				if ($building) {
					$attr = json_decode($building['attr'], true);
					for($i = 0; $i < 6; $i++) {
						$attr[$i] -= $attribute_change[$i];
						if ($attr[$i] < 0) {
							$attr[$i] = 0;
						}
					}
					$attr[6] -= $attr_love;
					/*
					if ($attr[6] < 0) {
						$attr[6] = 0;
					}*/
					$attr[7] -= $attr_exp;
					if ($attr[7] < 0) {
						$attr[7] = 0;
					}
					$building['attr'] = json_encode($attr);
					
					$effect_source = json_decode($building['effect_source'], true);
					if (!empty($effect_source)) {
						$source = array();
						foreach ($effect_source as $s) {
							if ($s != $id) {
								$source[] = $s;
							}
						}
						$building['effect_source'] = json_encode($source);
					}
					
					Hapyfish2_Ipanda_HFC_Building::updateOne($uid, $d[0], $building, true);
				}
			} else if ($d[2] == '31') {
				//phytotron
				$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronInfo($uid, $d[0]);
				if ($phytotron) {
					if (isset($phytotron['effect_exp'])) {
						$phytotron['effect_exp'] -= $attr_exp;
						if ($phytotron['effect_exp'] < 0) {
							$phytotron['effect_exp'] = 0;
						}
					}
					if (isset($phytotron['effect_exp'])) {
						$effect_source = json_decode($phytotron['effect_source'], true);
						if (!empty($effect_source)) {
							$source = array();
							foreach ($effect_source as $s) {
								if ($s != $id) {
									$source[] = $s;
								}
							}
							$phytotron['effect_source'] = json_encode($source);
						}
					}
					
					Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $d[0], $phytotron);
				}
			}
		}
	}
	
	public static function addDecorate($uid, $id, $cid, $x, $z, $mirro, $basicInfo = null)
	{
		if ($basicInfo == null) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
		}
		if (!$basicInfo) {
			return;
		}
		$gridData = Hapyfish2_Ipanda_Cache_MapGrid::addToGrid($uid, $id, $cid, $x, $z, $mirro, $basicInfo['nodes']);
		$effectData = self::calcEffect($uid, $id, $cid, $x, $z, $mirro, $basicInfo, $gridData);
		self::addEffect($uid, $id, $effectData);
	}
	
	public static function getEffectSouceList($uid, $id, $cid, $x, $z, $mirro, $map = null)
	{
		if (!$map) {
			$map = Hapyfish2_Ipanda_Bll_MapGrid::getMap($uid);
		}
		
		$x = 0;
		$z = 0;
		$decorateList = array();
		foreach($map as $k => $v) {
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
		
		$decorateBasicList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		$itemList = array();
		foreach ($decorateList as $k => $v) {
			$basicInfo = $decorateBasicList[$v[1]];
			$effectData = self::calcEffect($uid, $v[0], $v[1], $v[2], $v[3], $v[4], $basicInfo);
			if ($effectData) {
				$item = $effectData['item'];
				if (!empty($item)) {
					foreach ($item as $m) {
						if ($m[0] == $id && $m[1] == $cid) {
							$itemList[] = array(
								'id' => $effectData['id'],
								'attribute_change' => $effectData['attribute_change'],
								'attr_love' => $effectData['attr_love'],
								'attr_exp' => $effectData['attr_exp']
							);
						}
					}
				}
			}
		}
		
		return $itemList;
	}
	
	public static function addBuilding($uid, $id, $cid, $x, $z, $mirro, $buildingBasicInfo = null)
	{
		if (!$buildingBasicInfo) {
			$buildingBasicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingInfo($cid);
			if (!$buildingBasicInfo) {
				return;
			}
		}
		
		//加入格子
		$gridData = Hapyfish2_Ipanda_Cache_MapGrid::addToGrid($uid, $id, $cid, $x, $z, $mirro, $buildingBasicInfo['nodes']);
		
		$itemList = self::getEffectSouceList($uid, $id, $cid, $x, $z, $mirro);
		if (empty($itemList)) {
			return;
		}
		
		$attr = $buildingBasicInfo['attribute'];
		$attr[6] = 0;
		$attr[7] = 0;
		$source = array();
		foreach ($itemList as $v) {
			$attribute_change = $v['attribute_change'];
			for($i = 0; $i < 6; $i++) {
				$attr[$i] += $attribute_change[$i];
			}
			$attr[6] += $v['attr_love'];
			$attr[7] += $v['attr_exp'];
			$source[] = (int)$v['id'];
		}
		
		$info['attr'] = json_encode($attr);
		$info['effect_source'] = json_encode($source);
		
		//更新加成
		Hapyfish2_Ipanda_HFC_Building::updateOne($uid, $id, $info, true);
	}
	
	public static function addPhytoron($uid, $id, $cid, $x, $z, $mirro)
	{		
		//加入格子
		$gridData = Hapyfish2_Ipanda_Cache_MapGrid::addToGrid($uid, $id, $cid, $x, $z, $mirro, '2*2');
		
		$itemList = self::getEffectSouceList($uid, $id, $cid, $x, $z, $mirro);
		if (empty($itemList)) {
			return;
		}
		
		$effect_exp = 0;
		$source = array();
		foreach ($itemList as $v) {
			$effect_exp += $v['attr_exp'];
			$source[] = (int)$v['id'];
		}

		$info['effect_exp'] = $effect_exp;
		$info['effect_source'] = json_encode($source);
		
		//更新加成
		Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
	}

	public static function updateDecorate($uid, $id, $cid, $x1, $z1, $mirro1, $x2, $z2, $mirro2, $basicInfo = null)
	{
		if ($basicInfo == null) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
			if (!$basicInfo) {
				return;
			}
		}
		
		$removeEffectData = self::calcEffect($uid, $id, $cid, $x1, $z1, $mirro1, $basicInfo);
		self::removeEffect($uid, $id, $removeEffectData);
		
		Hapyfish2_Ipanda_Cache_MapGrid::updateToGrid($uid, $id, $cid, $x2, $z2, $mirro2, $basicInfo['nodes']);
		$addEffectData = self::calcEffect($uid, $id, $cid, $x2, $z2, $mirro2, $basicInfo);
		self::addEffect($uid, $id, $addEffectData);
	}
	
	public static function updateBuilding($uid, $id, $cid, $x1, $z1, $mirro1, $x2, $z2, $mirro2, $buildingBasicInfo = null)
	{
		if (!$buildingBasicInfo) {
			$buildingBasicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingInfo($cid);
			if (!$buildingBasicInfo) {
				return;
			}
		}
		
		Hapyfish2_Ipanda_Cache_MapGrid::updateToGrid($uid, $id, $cid, $x2, $z2, $mirro2, $buildingBasicInfo['nodes']);
		
		$itemList = self::getEffectSouceList($uid, $id, $cid, $x2, $z2, $mirro2);
		$attr = $buildingBasicInfo['attribute'];
		$attr[6] = 0;
		$attr[7] = 0;
		$source = array();
		foreach ($itemList as $v) {
			$attribute_change = $v['attribute_change'];
			for($i = 0; $i < 6; $i++) {
				$attr[$i] += $attribute_change[$i];
			}
			$attr[6] += $v['attr_love'];
			$attr[7] += $v['attr_exp'];
			$source[] = (int)$v['id'];
		}

		$info['attr'] = json_encode($attr);
		$info['effect_source'] = json_encode($source);
		//更新加成
		Hapyfish2_Ipanda_HFC_Building::updateOne($uid, $id, $info, true);
	}
	
	public static function updatePhytotron($uid, $id, $cid, $x1, $z1, $mirro1, $x2, $z2, $mirro2)
	{
		Hapyfish2_Ipanda_Cache_MapGrid::updateToGrid($uid, $id, $cid, $x2, $z2, $mirro2, '2*2');
		
		$itemList = self::getEffectSouceList($uid, $id, $cid, $x2, $z2, $mirro2);
		
		$effect_exp = 0;
		$source = array();
		foreach ($itemList as $v) {
			$effect_exp += $v['attr_exp'];
			$source[] = (int)$v['id'];
		}

		$info['effect_exp'] = $effect_exp;
		$info['effect_source'] = json_encode($source);
		
		//更新加成
		Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $id, $info);
	}
	
	public static function removeDecorate($uid, $id, $cid, $x, $z, $mirro, $basicInfo = null)
	{
		if (!$basicInfo) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
			if (!$basicInfo) {
				return;
			}
		}

		$removeEffectData = self::calcEffect($uid, $id, $cid, $x, $z, $mirro, $basicInfo);
		Hapyfish2_Ipanda_Cache_MapGrid::removeFromGrid($uid, $id, $cid);
		if ($removeEffectData != null) {
			self::removeEffect($uid, $id, $removeEffectData);
		}
	}
	
	public static function removeBuilding($uid, $id, $cid, $x, $z, $mirro)
	{
		Hapyfish2_Ipanda_Cache_MapGrid::removeFromGrid($uid, $id, $cid);
	}
	
	public static function removePhytotron($uid, $id, $cid, $x, $z, $mirro)
	{
		Hapyfish2_Ipanda_Cache_MapGrid::removeFromGrid($uid, $id, $cid);
	}
	
	public static function removeAllDecorate($uid)
	{
		$buildingList = Hapyfish2_Ipanda_Bll_Building::getInForest($uid);
		if (!empty($buildingList)) {
			$buildingAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
			foreach ($buildingList as $building) {
				$basicInfo = $buildingAssetList[$building['cid']];
				if ($basicInfo) {
					$attr = $basicInfo['attribute'];
					$attr[6] = 0;
					$attr[7] = 0;
					$info = array('attr' => json_encode($attr), 'effect_source' => '[]');
					Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $building['id'], $info);
				}
			}
		}
		
		$phytotronList = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		if (!empty($phytotronList)) {
			foreach ($phytotronList as $phytotron) {
				$info = array('effect_exp' => 0, 'effect_source' => '[]');
				Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $phytotron['id'], $info);
			}
		}
		
		Hapyfish2_Ipanda_Cache_MapGrid::removeByType($uid, '21');
	}
	
	public static function removeAllBuilding($uid)
	{
		Hapyfish2_Ipanda_Cache_MapGrid::removeByType($uid, '11');
	}
	
	public static function removeAllPhytotron($uid)
	{
		Hapyfish2_Ipanda_Cache_MapGrid::removeByType($uid, '31');
	}
	
	public static function getGrid($uid)
	{
		return Hapyfish2_Ipanda_Cache_MapGrid::getGrid($uid);
	}
	
	public static function clear($uid)
	{
		return Hapyfish2_Ipanda_Cache_MapGrid::clear($uid);
	}
	
}