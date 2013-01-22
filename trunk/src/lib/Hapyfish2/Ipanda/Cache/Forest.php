<?php

class Hapyfish2_Ipanda_Cache_Forest
{
    public static function getForestList($uid)
    {
    	$key = 'i:u:forestlist:';	
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
		//var_dump($ids);
        if(!USE_CACHE)
        {
        	$ids = false;
        }
        if ($ids === false) {
        	try {
	            $dalForest = Hapyfish2_Ipanda_Dal_Forest::getDefaultInstance();
	            $ids = $dalForest->getAll($uid);
	            
	            if ($ids) {
	            	$cache->set($key, $ids);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $ids;
    }
	public static function updatetocache($uid,$forest_no)
    {
    	$key = 'i:u:forestlist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        for($i = 0 ,$num = count($ids) ; $i<$num ;$i++ )
        {
        	if($ids[$i]['forest_no'] == $forest_no)
        	{
        		$ids[$i]['extend_land'] += 1;
        		break;
        	}
        }
        $cache->set($key, $ids);
        return $ids;
    }
}