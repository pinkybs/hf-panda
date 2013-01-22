<?php

class Hapyfish2_Ipanda_Cache_Building
{    
	public static function reloadAllIds($uid)
    {
        try {
            $dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
            $ids = $dalBuilding->getAllIds($uid);
            if ($ids) {
        		$key = 'i:u:bldids:all:';
        		$key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
        		$key = $key . $uid;
        		$cache = Hapyfish2_Cache_Factory::getMC($uid);
            	$cache->set($key, $ids);
            } else {
            	return null;
            }
            
            return $ids;
        } catch (Exception $e) {
        	return null;
        }
    }
    
	public static function pushOneIdInAll($uid, $id)
    {
        $key = 'i:u:bldids:all:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);

        if ($ids === false) {
			return null;
        } else {
        	if (empty($ids)) {
        		$ids = array($id);
        	} else {
        		$ids[] = $id;
        	}
        	$cache->set($key, $ids);
        	return $ids;
        }
    }
    
    public  static  function getAllIds($uid)
    {
        $key = 'i:u:bldids:onforest:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
		$ids = false;
        if ($ids === false) {
        	try {
	            $dalBuilding = Hapyfish2_Ipanda_Dal_Building::getDefaultInstance();
	            $ids = $dalBuilding->getOnForestIds($uid);
	            
	            if ($ids) {
	            	$cache->add($key, $ids);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $ids;
    }
    
	public static function pushOneIdOnForest($uid, $id)
    {
        $key = 'i:u:bldids:onforest:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);

        if ($ids === false) {
        	return null;
        } else {
        	$contain = false;
        	if (empty($ids)) {
        		$ids = array($id);
        	} else {
        		foreach ($ids as $v) {
        			if ($v == $id) {
        				$contain = true;
        				break;
        			}
        		}
        		if (!$contain) {
					$ids[] = $id;
        		}
        	}
        	if(!$contain) {
				$cache->set($key, $ids);
        	}
			return $ids;
        }
    }
    
    public static function takelovelog($uid, $buildingInfo, $num)
    {
    	$key = 'i:u:bldids:takelovelog:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $buildingInfo['uid']. ':'. $buildingInfo['id'];
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        
        if ($ids === false) {
        	$ids['time'] = $buildingInfo['op_start_time'];
        	$ids['list'][] =  array('uid' => $uid , "num" => $num);
        }
        else 
        {
        	if($ids['time'] == $buildingInfo['op_start_time'])
        	{
        		$ids['list'][] =  array('uid' => $uid , "num" => $num);
        	}
        	else
        	{
        		$ids['time'] = $buildingInfo['op_start_time'];
        		$ids['list'] = array();
        		$ids['list'][] =  array('uid' => $uid , "num" => $num);
        	}
        }
        $cache->set($key, $ids);
        return $ids; 
    }
    
    public static function cantakelove($uid, $buildingInfo)
    {
    	$key = 'i:u:bldids:takelovelog:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $buildingInfo['uid'].":".$buildingInfo['id'];
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        if(empty($ids))
        {
        	return true;
        }
        else
        {
        	if($ids['time'] != $buildingInfo['op_start_time'])
        	{
        		return true;
        	}
        	else 
        	{
        		foreach ($ids['list'] as $v)
        		{
        			if($v['uid'] == $uid)
        			{
        				return false;
        			}
        		}
        		return true;
        	}
        }
    }
    
    public static function cleartakelog($uid, $id)
    {
    	$key = 'i:u:bldids:takelovelog:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid . ':' . $id;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->set($key, "");
    }

	public static function setForestAnimalNum($uid, $num)
    {
    	$key = 'i:u:bldids:forestanimalnum:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->set($key, $num);
        return true;
    }
    
	public static function getForestAnimalNum($uid)
    {
    	$key = 'i:u:bldids:forestanimalnum:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
    	$ids = $cache->get($key);
        return $ids;
    }
}