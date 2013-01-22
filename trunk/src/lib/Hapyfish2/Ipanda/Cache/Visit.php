<?php

class Hapyfish2_Ipanda_Cache_Visit
{    
    public static function dailyVisit($uid, $fid)
    {
    	if ($uid == $fid) {
			return;
		}
    	
    	$key = 'i:u:dlyvisit:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
		$data = $cache->get($key);
		if ($data === false) {
			$data = array();
		}
		
		$num = count($data);

		//最多只记录20个
		if ($num > 20) {
			return;
		}
		
		if (in_array($fid, $data)) {
			return;
		}
		
		$data[] = (int)$fid;
		
		$t = time();
		$today = strtotime(date('Ymd', $t));
		$expire = $today + 86400 - $t;
		if ($expire < 10) {
			$expire = 86400;
		}
		
		$cache->set($key, $data, $expire);
    }
    
}