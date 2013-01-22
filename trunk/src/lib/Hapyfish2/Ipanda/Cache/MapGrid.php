<?php

class Hapyfish2_Ipanda_Cache_MapGrid
{
    public static function updateToGrid($uid, $id, $cid, $x, $z, $mirro, $nodes)
    {
    	$key = 'i:u:mapgrid:' . $uid;        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid);
        }
        
        $item = $data['item'];
        $itemKey = $id . ':' . $cid;
        $rect = self::getRect($id, $cid, $x, $z, $mirro, $nodes);
    	$item[$itemKey] = $rect;
    	$data['item'] = $item;
    	
		$newGrid = array();
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
    	
    	return $cache->set($key, $data);
    }
    
    /**
     * 升级建筑，只改变cid
     */
    public static function upgradeBuidlingToGrid($uid, $id, $cid, $x, $z, $mirro, $nodes, $newCid)
    {
    	$key = 'i:u:mapgrid:' . $uid;        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid);
        }
        
        $item = $data['item'];
        $oldItemKey = $id . ':' . $cid;
        //移除原来的数据
        if (isset($item[$oldItemKey])) {
        	unset($item[$oldItemKey]);
        }
        
        $itemKey = $id . ':' . $newCid;
        $rect = self::getRect($id, $newCid, $x, $z, $mirro, $nodes);
    	$item[$itemKey] = $rect;
    	$data['item'] = $item;
    	
		$newGrid = array();
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
    	
    	return $cache->set($key, $data);
    }
    
    /**
     * 更改培育物动物会改变培育物对应的cid，只改变cid
     */
    public static function upgradePhytotronToGrid($uid, $id, $cid, $x, $z, $mirro, $nodes, $newCid)
    {
    	$key = 'i:u:mapgrid:' . $uid;        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid);
        }
        
        $item = $data['item'];
        $oldItemKey = $id . ':' . $cid;
        //移除原来的数据
        if (isset($item[$oldItemKey])) {
        	unset($item[$oldItemKey]);
        }
        
        $itemKey = $id . ':' . $newCid;
        $rect = self::getRect($id, $newCid, $x, $z, $mirro, $nodes);
    	$item[$itemKey] = $rect;
    	$data['item'] = $item;
    	
		$newGrid = array();
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
    	
    	return $cache->set($key, $data);
    }
    
    public static function loadGrid($uid, $save = false)
    {
    	$item = array();
    	
        //decorate
		$decorateList = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
		if (!empty($decorateList)) {
			$decorateBasicList = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
	        foreach ($decorateList as $v) {
				if (0 == $v['status']) {
	       			continue;
	       		}
	       		$id = $v['id'];
	       		$cid = $v['cid'];
	       		$basicInfo = $decorateBasicList[$cid];
	       		$rect = self::getRect($id, $cid, $v['x'], $v['z'], $v['mirro'], $basicInfo['nodes']);
	       		$itemKey = $id . ':' . $cid;
	       		$item[$itemKey] = $rect;
	    	}
		}
    	
    	//building
    	$buildingList = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
    	if (!empty($buildingList)) {
	    	$buildingAssetList = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
	    	foreach ($buildingList as $v) {
				if (0 == $v['status']) {
	       			continue;
	       		}
	       		$id = $v['id'];
	       		$cid = $v['cid'];
	       		$basicInfo = $buildingAssetList[$cid];
	       		$rect = self::getRect($id, $cid, $v['x'], $v['z'], $v['mirro'], $basicInfo['nodes']);
	       		$itemKey = $id . ':' . $cid;
	       		$item[$itemKey] = $rect;
	    	}
    	}
    	
    	//phytotron
    	$phytotronList = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
    	if (!empty($phytotronList)) {
	    	$phytotronAnimalList = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getPhytotronAnimalList($uid);
	        foreach ($phytotronList as $v) {
				if (0 == $v['status']) {
	       			continue;
	       		}
	       		$paId = $v['ipanda_user_phytotron_animal_id'];
	       		$row = $phytotronAnimalList[$paId];
	       		$id = $v['id'];
	       		$cid = $row['phytotron_cid'];
	       		$rect = self::getRect($id, $cid, $v['x'], $v['z'], $v['mirro'], '2*2');
	       		$itemKey = $id . ':' . $cid;
	       		$item[$itemKey] = $rect;
	    	}
    	}
    	
        $data['item'] = $item;
		$newGrid = array();
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
    	
        if ($save) {
	    	$key = 'i:u:mapgrid:' . $uid;        
	        $cache = Hapyfish2_Cache_Factory::getMC($uid);
	    	$cache->set($key, $data);
        }
    	
    	return $data;
    }
    
    public static function addToGridItem($item, $uid, $id, $cid, $x, $z, $mirro, $nodes)
    {
    	$itemKey = $id . ':'. $cid;
    	$rect = self::getRect($id, $cid, $x, $z, $mirro, $nodes);
    	if (isset($item[$itemKey])) {
			$row = $item[$itemKey];
			$same = true;
			foreach($rect as $k => $v) {
				if (!isset($row[$k])) {
					$same = false;
					break;
				}
			}
			if ($same) {
				return $item;
			}
		}
		$item[$itemKey] = $rect;
        return $item;
    }
    
	public static function addToGrid($uid, $id, $cid, $x, $z, $mirro, $nodes)
    {
    	$key = 'i:u:mapgrid:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid);
        }
        
        $item = $data['item'];
        $itemKey = $id . ':' . $cid;
        $rect = self::getRect($id, $cid, $x, $z, $mirro, $nodes);
        if (isset($item[$itemKey])) {
			$row = $item[$itemKey];
			$same = true;
			foreach($rect as $k => $v) {
				if (!isset($row[$k])) {
					$same = false;
					break;
				}
			}
			if ($same) {
				return $item;
			}
		}
        
    	$item[$itemKey] = $rect;
    	$data['item'] = $item;
    	
		$newGrid = array();
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
    	
    	$cache->set($key, $data);
        
        return $data;
    }
    
    public static function flushGrid($uid, $data = null)
    {
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
    
    	if ($data == null) {
		    $key = 'i:u:mapgrid:' . $uid;
			$data = $cache->get($key);
	        if ($data === false) {
	        	$data = self::loadGrid($uid, true);
	        	return;
	        }
		}
       
		$newGrid = array();
		$item = $data['item'];
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
        $cache->set($key, $data);
        
        return $data;
    }
    
    public static function getGridData($uid)
    {
		$key = 'i:u:mapgrid:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid, true);
        }
        
        return $data;
    }
    
    public static function getGrid($uid)
    {
		$data = self::getGridData($uid);
		return $data['grid'];
    }
    
	public static function getGridList()
    {
		$data = self::getGridData($uid);
		return $data['item'];
    }
    
	public static function getGridListById($uid, $id, $cid)
    {
		$item = self::getGridList();
        $key = $id . ':'. $cid;
        if (isset($item[$key])) {
			return $item[$key];
        }
        return null;
    }
    
    public static function getRect($id, $cid, $x, $z, $mirro, $nodes)
    {
    	$data = array();
    	$size = explode('*', $nodes);
    	if ($mirro == 0) {
    		$xWidth = $size[0];
    		$zWidth = $size[1];
    	} else {
    		$xWidth = $size[1];
    		$zWidth = $size[0];
    	}
    	$v = $id . ':' . $cid . ':' . $x . ':' . $z . ':' . $mirro;
    	for($i = 0; $i < $xWidth; $i++) {
	    	for($j = 0; $j < $zWidth; $j++) {
	    		$tx = $x + $i;
	    		$tz = $z + $j;
	    		$data[$tx . ',' . $tz] = $v;
	    	}
    	}
    	
    	return $data;
    }
    
    public static function getCleanRect($x, $z, $mirro, $nodes)
    {
    	$data = array();
    	$size = explode('*', $nodes);
    	if ($mirro == 0) {
    		$xWidth = $size[0];
    		$zWidth = $size[1];
    	} else {
    		$xWidth = $size[1];
    		$zWidth = $size[0];
    	}
    	for($i = 0; $i < $xWidth; $i++) {
	    	for($j = 0; $j < $zWidth; $j++) {
	    		$tx = $x + $i;
	    		$tz = $z + $j;
	    		$data[$tx . ',' . $tz] = 1;
	    	}
    	}
    	
    	return $data;
    }
    
    public static function clear($uid)
    {
		$key = 'i:u:mapgrid:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
        return $cache->delete($key);
    }
    
    public static function removeFromGrid($uid, $id, $cid)
    {
    	$key = 'i:u:mapgrid:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid);
        }
        
        $item = $data['item'];
        if (empty($item)) {
        	return true;
        }
        $itemKey = $id . ':' . $cid;
        if (!isset($item[$itemKey])) {
			return true;
		}
		
		unset($item[$itemKey]);
    	$data['item'] = $item;
    	
		$newGrid = array();
        foreach ($item as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
        
    	return $cache->set($key, $data);
    }
    
    public static function removeByType($uid, $type)
    {
    	$key = 'i:u:mapgrid:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	$data = self::loadGrid($uid);
        }
        
        $item = $data['item'];
        $newItem = array();
		foreach ($item as $k => $v) {
			$temp = explode(':', $k);
			$t = substr($temp[1], -2);
			if ($t != $type) {
				$newItem[$k] = $v;
			}
		}
    	$data['item'] = $newItem;
    	
		$newGrid = array();
        foreach ($newItem as $k => $v) {
        	foreach ($v as $m => $n) {
        		$newGrid[$m] = $n;
        	}
        }
        $data['grid'] = $newGrid;
        
    	return $cache->set($key, $data);
    }
}
