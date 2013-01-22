<?php

class Hapyfish2_Ipanda_Cache_ComboHit
{
    const MAX_LENGTH = 300;
    const TIME_STEP = 1800;
	
	public static function add($uid, $num)
    {
		$key = 'i:u:combohit:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if (!$data) {
        	$data = array(
        		'd' => array(),
        		't' => 0
        	);
        } else {
        	$len = self::MAX_LENGTH;
        	$count = count($data['d']);
			if ($count > $len) {
				for($i = 0, $l = $count - $len; $i < $l; $i++) {
					array_shift($data['d']);
				}
        		//$data['d'] = array_slice($data['d'], -$len);
        	}
        }

        $now = time();
        if (isset($data['d'][$now])) {
        	$data['d'][$now] += $num;
        } else {
        	$data['d'][$now] = $num;
        }
        return $cache->set($key, $data);
    }
    
    public static function checkout($uid, $hitNum, $ts)
    {
    	$key = 'i:u:combohit:' . $uid;
    	$cache = Hapyfish2_Cache_Factory::getMC($uid);
    	$data = $cache->get($key);
    	if (!$data) {
    		return -1;
    	}
    	
        if ($data['t'] == $ts) {
    		return -100;
    	}
    	
    	if (count($data['d']) == 0) {
    		return -2;
    	}
    	
    	$ok = false;
    	$n = 0;
    	$tmpData = array();
    	$now = time();
    	foreach ($data['d'] as $t => $num) {
    		if ($now - $t > self::TIME_STEP) {
    			continue;
    		}
    		
    		if ($n + $num <= $hitNum) {
    			$n += $num;
    		} else {
    			if ($n < $hitNum) {
    				$nz = $hitNum - $n;
    				$tmpData[$t] = $num - $nz;
    				$n = $hitNum;
    			} else {
    				$tmpData[$t] = $num;
    			}
    		}
    	}
    	
    	$newData = array(
    		'd' => $tmpData,
    		't' => $ts
    	);
    	
    	$ok = $cache->set($key, $newData);
    	
    	//允许一定的偏差
    	if ($n + 10 < $hitNum) {
    		info_log($uid . ':' . $n . '<' . $hitNum, 'hitCheckout');
    		//return -3;
    	}
    	
    	if (!$ok) {
    		return 0;
    	}
    	
    	return 1;
    }
    
}