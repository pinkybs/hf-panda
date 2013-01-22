<?php

class Hapyfish2_Ipanda_Cache_Decorate
{
    public static function getList($uid, $savehighcache = false)
    {
    	$key = 'i:u:decoratelist:';	
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);

		if (!USE_CACHE) {
			$data = false;
		}
        
        if ($data === false) {
        	try {
	            $dal = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
	            $data = $dal->getAll($uid);
	            
	            if ($data) {
	            	$cache->set($key, $data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        if ($savehighcache) {
			$hc = Hapyfish2_Cache_HighCache::getInstance();
			$hc->set($key, $data);
		}
        
        return $data;
    }
    
    public static function getListFromHighCache($uid)
    {
    	$key = 'i:u:decoratelist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
        $key = $key . $uid;
		$hc = Hapyfish2_Cache_HighCache::getInstance();
        return $hc->get($key);
    }
    
	public static function addtocache($uid,$info)
    {
    	$key = 'i:u:decoratelist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        $ids[] = $info;
        $cache->set($key, $ids);
        return $ids;
    }
	public static function updatetocache($uid,$id,$info)
    {
    	$key = 'i:u:decoratelist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        
        for($i = 0 ,$num = count($ids); $i < $num ; $i++)
        {
        	if($ids[$i]['id'] ==  $id)
        	{
        		foreach ($info as $k => $v)
        		{
        			$ids[$i][$k] = $v;
        		}
        		break;
        	}
        }
        $cache->set($key, $ids);
        return $ids;
    }
	public static function deletecache($uid,$id)
    {
    	$key = 'i:u:decoratelist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        $data = array();
        for($i = 0 ,$num = count($ids); $i < $num ; $i++)
        {
        	if($ids[$i]['id'] ==  $id)
        	{
        		continue;
        	}
        	else 
        	{
        		$data[] = $ids[$i];
        	}
        }
        $cache->set($key, $data);
        return $data;
    }
	public static function updatealltocache($uid,$info)
    {
    	$key = 'i:u:decoratelist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        
        for($i = 0 ,$num = count($ids); $i < $num ; $i++)
        {
        		foreach ($info as $k => $v)
        		{
        			$ids[$i][$k] = $v;
        		}
        }
        $cache->set($key, $ids);
        return $ids;
    }
}