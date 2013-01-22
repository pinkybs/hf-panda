<?php

class Hapyfish2_Ipanda_HFC_Building
{
	public static function getAll($uid, $savehighcache = false)
    {
        $ids = Hapyfish2_Ipanda_Cache_Building::getAllIds($uid);
        
        if (!$ids) {
        	return null;
        }
        
        $keys = array();
        foreach ($ids as $id) {
        	$keys[] = 'i:u:bld:' . $uid . ':' . $id;
        }
        
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        $data = $cache->getMulti($keys);
         
        if ($data === false) {
        	return null;
        }
       
        //check all in memory
        $nocacheKeys = array();
        $empty = true;
        foreach ($data as $k => $item) {
        	if ($item == null) {
        		$nocacheKeys[] = $k;
        	} else {
        		$empty = false;
        	}
        }
        if(!USE_CACHE)
        {
			$empty = true;
        }
       
        if ($empty) {
        	try {
        		//Hapyfish2_Ipanda_Cache_Building::reloadOnIslandIds($uid);
        		
	            $dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	            $result = $dalBuilding->getAll($uid);
	          	
	            if ($result) {
	            	$data = array();
	            	foreach ($result as $item) {
	            		$key = 'i:u:bld:' . $uid . ':' . $item['id'];
	            		$data[$key] = $item;
	            	}
	            	$cache->addMulti($data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        } else if (!empty($nocacheKeys)) {
        	foreach ($nocacheKeys as $key) {
        		$tmp = split(':', $key);
        		$data[$key] = self::loadOne($uid, $tmp[4]);
        	}
        }
         
        $buildings = array();
      
        foreach ($data as $item) {
        	if ($item) {
	        	$buildings[$item['id']] = $item;
        	}
        }
		
		return $buildings;
    }
    
    public static function getOne($uid, $id, $status = 1)
    {
    	$key = 'i:u:bld:' . $uid . ':' . $id;
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
    	$item = $cache->get($key);
    	
    	if ($item === false) {
    		try {
	    		$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	    		$item = $dalBuilding->getOne($uid, $id);
	    		if ($item) {
	    			$cache->add($key, $item);
	    		} else {
	    			return null;
	    		}
    		} catch (Exception $e) {
    			return null;
    		}
    	}
    	
    	return $item;
    }
    
    public static function loadOne($uid, $id)
    {
		try {
	    	$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	    	$item = $dalBuilding->getOne($uid, $id);
	    	if ($item) {
	    		$key = 'i:u:bld:' . $uid . ':' . $id;
	    		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
	    		$cache->save($key, $item);
	    	} else {
	    		return null;
	    	}
	    	
	    	return $item;
		}catch (Exception $e) {
			err_log($e->getMessage());
			return null;
		}
    }
    
    public static function updateFieldOfBuilding($uid, $id, $fieldInfo)
    {
    	$building = self::getOne($uid, $id);
    	if ($building) {
    		foreach ($fieldInfo as $k => $v) {
    			if(isset($building[$k])) {
    				$plant[$k] = $v;
    			}
    		}
			return self::updateOne($uid, $id, $building);
    	}
    	
    	return false;
    }
    
    public static function updateOne($uid, $id, $building, $savedb = false)
    {
    	$key = 'i:u:bld:' . $uid . ':' . $id;
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
    	
    	$memData = $cache->get($key); 
    	foreach ($building as $k => $v)
    	{
    		$memData[$k] = $v;
    	}
    	
    	if (!$savedb) {
    		$savedb = $cache->canSaveToDB($key, 900);
    	}
    	
    	if ($savedb) {
    		$ok = $cache->save($key, $memData);
    		if ($ok) {
	    		//save to db
	    		try {
	    			//info_log(join(",",$building),"updateOne".$uid);
	    			$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	    			$dalBuilding->update($uid, $id, $building);
	    		}catch (Exception $e) {
	    			err_log($e->getMessage());
	    		}
    		}
    	} else {
    		$ok = $cache->update($key, $memData);
    	}
    	
    	return $ok;
    }
    
	public static function updateAll($uid, $building)
    {
    	$list = self::getAll($uid);
    	foreach ($list as $v)
    	{
	    	$key = 'i:u:bld:' . $uid . ':' . $v['id'];
	    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
	    	
	    	$memData = $cache->get($key); 
	    	foreach ($building as $k => $v)
	    	{
	    		$memData[$k] = $v;
	    	}
	    	$cache->save($key, $memData);
    	}
    	 
    	return true;
    }
    public static function removeOne($uid, $id)
    {
		$key = 'i:u:bld:' . $uid . ':' . $id;
		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
		return $cache->delete($key);
    }
    
    public static function getNewBuildingId($uid)
    {
        try {
    		$dalUserSequence = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
    		return $dalUserSequence->get($uid, 'b', 1);
    	} catch (Exception $e) {
    	}
    	
    	return 0;
    }
    
    public static function addOne($uid, &$building , $basicInfo =null)
    {
    	$result = false;
    	
    	try {
    		$id = self::getNewBuildingId($uid);
    		if ($id > 0) {
    			$building['id'] = $id;
    		
	    		$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	    		$dalBuilding->insert($uid, $building);
	    		
	    		self::loadOne($uid, $id);
    			Hapyfish2_Ipanda_Cache_Building::pushOneIdInAll($uid, $id);
				if ($building['status'] == 1) {
					Hapyfish2_Ipanda_Cache_Building::pushOneIdOnForest($uid, $id);
				}	    		
	    		$result = true;
    		}
    	} catch (Exception $e) {
    		info_log($e->getMessage(),"putbuilding");
    	}
    	
    	return $result;
    }
    
    public static function updateBuildingConsume($uid,$id,$info,$savedb = false)
    {
    	//info_log(json_encode($info),"updateBuildingConsume".'-'.$uid.'-'.$id);
    	$key = 'i:u:bld:' . $uid . ':' . $id;
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
    	
    	$memData = $cache->get($key); 
    	foreach ($info as $k => $v)
    	{
    		$memData[$k] = $v;
    	}
    	if (!$savedb) {
    		$savedb = $cache->canSaveToDB($key, 900);
    	}
    	
    	$ok = $cache->update($key, $memData);
    	if ($savedb) {
    		if ($ok) {
	    		//save to db
	    		try {
	    			$dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	    			$dalBuilding->update($uid, $id, $info);
	    		}catch (Exception $e) {
	    			info_log($e->getMessage(),"updateBuildingConsume");
	    		}
    		}
    	} 
    	return $ok;
    }
    
}