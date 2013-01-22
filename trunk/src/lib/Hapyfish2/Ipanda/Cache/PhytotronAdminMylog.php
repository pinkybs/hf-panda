<?php

class Hapyfish2_Ipanda_Cache_PhytotronAdminMylog
{
    public static function getList($uid)
    {
    	$key = 'i:u:phytotronadminmyloglist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
		if(!USE_CACHE)
		{
			$data = false;
		}
        if ($data === false) {
        	try {
	            $dal = Hapyfish2_Ipanda_Dal_PhytotronAdminMylog::getDefaultInstance();
	            $data = $dal->getAll($uid);
	            if ($data) {
	            	$cache->add($key, $data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $data;
    }
    
    public static function addtocache($uid, $info)
    {
    	$key = 'i:u:phytotronadminmyloglist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        $data[] = $info;
        $cache->set($key, $data);
        return $data;
    }
    
	public static function deletecache($uid, $id)
    {
    	$key = 'i:u:phytotronadminmyloglist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        $newData = array();
        if (empty($data)) {
        	return $newData;
        }
        foreach ($data as $v) {
        	if ($v['id'] != $id) {
        		$newData[] = $v;
        	}
        }
        $cache->set($key, $newData);
        return $newData;
    }
    
	public static function updatetocache($uid, $log_id, $info)
    {
    	$key = 'i:u:phytotronadminmyloglist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if (!empty($data)) {
        	foreach ($data as &$v) {
        		if ($v['id'] == $log_id) {
					foreach ($info as $k1 => $v1) {
	        			$v[$k1] = $v1;
	        		}
	        		break;
        		}
        	}
        	$cache->set($key, $data);
        }
        
        return $data;
    }
    
	public static function updatetocachebyphytotronid($uid, $fuid, $phytotron_id, $info)
    {
    	$key = 'i:u:phytotronadminmyloglist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if (!empty($data)) {
			foreach ($data as &$v) {
        		if ($v['friend_uid'] == $fuid && $v['phytotron_id'] == $phytotron_id) {
					foreach ($info as $k1 => $v1) {
	        			$v[$k1] = $v1;
	        		}
	        		break;
        		}
        	}
        	$cache->set($key, $data);
        }
        
        return $data;
    }
}