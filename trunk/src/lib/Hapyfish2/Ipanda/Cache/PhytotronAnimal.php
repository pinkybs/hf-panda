<?php

class Hapyfish2_Ipanda_Cache_PhytotronAnimal
{
	public static function getIds($uid)
    {
        $key = 'i:u:phyanimids:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);

        if ($ids === false) {
        	try {
	            $dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
	            $ids = $dalPhytotronAnimal->getAllIds($uid);
	            if (!empty($ids)) {
	            	$cache->add($key, $ids);
	            } else {
	            	$cache->add($key, array());
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $ids;
    }
    
    public static function reloadIds($uid)
    {
        try {
            $dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
            $ids = $dalPhytotronAnimal->getAllIds($uid);
			$cache = Hapyfish2_Cache_Factory::getMC($uid);
        	$key = 'i:u:phyanimids:' . $uid;
            if (!empty($ids)) {
            	$cache->set($key, $ids);
            } else {
            	$cache->set($key, array());
            	return null;
            }
            
            return $ids;
        } catch (Exception $e) {
        	return null;
        }
    }
	
	public static function getPhytotronAnimalList($uid)
    {
    	$key = 'i:u:phytotronanimallist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
    	if(!USE_CACHE)
		{
			$ids = false;
		}
        if ($ids === false) {
        	try {
	            $dal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
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
    
	public static function updatetocache($uid,$id,$info)
    {
    	$key = 'i:u:phytotronanimallist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        foreach ($ids as $i => $value)
        {
        	if($value['id'] ==  $id)
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
	public static function addtocache($uid,$info)
    {
    	$key = 'i:u:phytotronanimallist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        $ids[] = $info;
        $cache->set($key, $ids);
        return $ids;
    }
}