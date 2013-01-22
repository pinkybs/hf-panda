<?php

class Hapyfish2_Ipanda_Cache_PandaQuestion
{
    public static function add($uid, $info)
    {
		$key = 'i:u:pandaquestion:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if (!$data) {
        	$data = array();
        }

        $data[$info['tick']] = $info['id'];
        
        return $cache->set($key, $data);
    }
    
    public static function checkout($uid, $info)
    {
    	$key = 'i:u:pandaquestion:' . $uid;
    	$cache = Hapyfish2_Cache_Factory::getMC($uid);
    	$data = $cache->get($key);
    	if (!$data) {
    		return false;
    	}
    	
    	$ok = false;
    	if (isset($data[$info['tick']]) && $data[$info['tick']] == $info['id']) {
    		unset($data[$info['tick']]);
    		//检查过期问题
    		$t = time();
    		$d = array();
    		foreach ($data as $tick => $id) {
    			$tmp = explode('_', $tick);
    			//有效时间8小时
    			if ($t < $tmp[1] + 28800) {
    				$d[$tick] = $id;
    			}
    		}
    		
    		$ok = $cache->set($key, $d);
    	}
    	
    	return $ok;
    }
    
}