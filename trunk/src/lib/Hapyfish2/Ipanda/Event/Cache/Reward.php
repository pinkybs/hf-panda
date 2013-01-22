<?php

class Hapyfish2_Ipanda_Event_Cache_Reward
{
	public static function getInit()
	{
		$key = 'ipanda:u:e:r';
		$cache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
		$init = $cache->get($key);
		if($init == false){
		     try {
	            $dal = Hapyfish2_Ipanda_Event_Dal_Reward::getDefaultInstance();
	            $data = $dal->getInit();
	            $init =array();
	            if ($data) {
	            	foreach($data as $k => $v){
	            		$init[$v['type']][] = $v;
	            	}
	            	$cache->set($key, $init);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
		}
		return $init;
	}
	public static function getEventDetail($type)
	{
		$data = array();
		$init = self::getInit();
		if(isset($init[$type])){
			$data = $init[$type];
		}
		return $data;
	}
}