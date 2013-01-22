<?php

class Hapyfish2_Ipanda_Cache_BuildingUnlock
{
    public static function getList($uid)
    {
    	$key = 'i:u:buildingunlocklist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
		if(!USE_CACHE)
		{
			$ids = false;
		}
        if ($ids === false) {
        	try {
	            $dal = Hapyfish2_Ipanda_Dal_BuildingUnlock::getDefaultInstance();
	            $ids = $dal->getAll($uid);
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
    public static function addtocache($uid,$info)
    {
    	$key = 'i:u:buildingunlocklist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        $ids[] = $info;
        $cache->set($key, $ids);
        return $ids;
    }
	
}