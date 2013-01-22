<?php

class Hapyfish2_Ipanda_Cache_User
{
	public static function isAppUser($uid)
    {
        $key = 'i:u:isapp:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getbasickey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);

        if ($data === false) {
			if ($cache->isNotFound()) {
				$levelInfo = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
				if (!$levelInfo) {
					return false;
				} else {
					$data = 'Y';
					$cache->set($key, $data);
					return true;
				}
			} else {
				return false;
			}
        }
        
        if ($data == 'Y') {
        	return true;
        } else {
        	return false;
        }
    }
    
    public static function setAppUser($uid)
    {
        $key = 'i:u:isapp:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->set($key, 'Y');
    }
    
    /*
     * 记录交互
     * 
     */
	public static function logInteractive($uid, $fuid, $info)
    {
    	//type 1 培育屋租用 2 爱心分享 3 维修分享
    	//一个建筑 的爱心分享 只记录一次
    	//只记录5个人5个操作
    	$user_num = 5 ;
    	$log_num = 5;
    	$key = 'i:u:loginteractive:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        $now = date('Y-m-d');
        $noData = empty($data);
        
        if($noData || !isset($data[$fuid]) || empty($data[$fuid])) {
        	if (!$noData) {
	        	if(sizeof($data) >= $user_num) {
		        	return true;
		        }
		        
	        	foreach ($data as $loginfo) {
		        	foreach ($loginfo['list'] as $value) {
		        		if($value['type'] == 2 && $value['id'] == $info['id']) {
		        			return true;
		        		}
		        	}
	        	}
        	} else {
        		$data = array();
        	}
        	
        	$data[$fuid] = array();
        	$data[$fuid]['list'][] = $info;
        	$data[$fuid]['date'] = $now;
        	$cache->set($key, $data);
        }
        else {
        	if($now == $data[$fuid]['date']) {
	        	if(sizeof($data[$fuid]['list']) >= $log_num) {
	        		return true;
	        	}
	        	
	        	foreach ($data as $loginfo) {
		        	foreach ($loginfo['list'] as $value) {
		        		if($value['type'] == 2 && $value['id'] == $info['id']) {
		        			return true;
		        		}
		        	}
	        	}
	        	
        		$data[$fuid]['list'][] = $info;
        	}
        	else {
        		foreach ($data as $loginfo) {
		        	foreach ($loginfo['list'] as $value) {
		        		if($value['type'] == 2 && $value['id'] == $info['id']) {
		        			return true;
		        		}
		        	}
	        	}
	        	
        		$data[$fuid]['list'] = array();
        		$data[$fuid]['list'][] = $info;
        		$data[$fuid]['date'] = $now;
        	}
        	$cache->set($key, $data);
        }
        return true;
    }
    //交互的次数
    public static function getInteractionNum($uid,$fuid)
    {
    	$key = 'i:u:loginteractive:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        $now = date('Y-m-d');
        $noData = empty($data);
        if($noData  || !isset($data[$fuid]) || empty($data[$fuid]) )
        {
        	return 0;
        }
        $num = sizeof($data[$fuid]['list']);
        return $num;
    }
    
    public static function getleaveInteractionNum($uid,$fuid)
    {
    	return 5 - self::getInteractionNum($uid,$fuid);
    }
    //每日奖励次数
    public static function logInteractionAward($uid,$num)
    {
    	$key = 'i:u:loginteractionaward:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid .":" . date('Y-m-d');
        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->set($key, $num , 86400);
    }
    
    //获取奖励次数
    public static function getInteractionAward($uid)
    {
    	$key = 'i:u:loginteractionaward:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid .":" . date('Y-m-d');
        
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
       	
        if(empty($data))
        {
        	return 0;
        }
        return $data;
        
    }
    
    public static function checkInteraction($uid, $id)
	{
		$key = 'i:u:loginteractive:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        if ($data === false || empty($data)) {
        	return false;
        }
        
        foreach($data as $row)
        {
        	foreach ($row['list'] as $v)
        	{
        		if(  $v['id'] == $id && $v['type'] ==  2)
        		{
        			return $v;
        		}
        	}
        }
        return false;
	}
	
	public static function readInteractive($uid)
    {
    	
        $key = 'i:u:loginteractive:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
   		return $data;
    }
    
	public static function removeInteractive($uid,$fuid)
    {
        $key = 'i:u:loginteractive:';
        $key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $data = $cache->get($key);
        $row = array();
        foreach ($data as $k => $v)
        {
        	if($k != $fuid)
        	{
        		$row[$k] = $v;
        	}
        }
        
        $cache->set($key, $row);
   		return $row;
    }
    
    public static function readBuildInteraction($uid)
    {
    	$keybuild = 'i:u:loginteractivebuild:';
        $keybuild = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($keybuild);
        $keybuild = $keybuild . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $list = $cache->get($keybuild);
        return $list;
    }
    
	public static function removeBuildInteraction($uid,$id)
    {
    	$keybuild = 'i:u:loginteractivebuild:';
        $keybuild = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($keybuild);
        $keybuild = $keybuild . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $list = $cache->get($keybuild);
        foreach ($list as &$v)
        {
        	if($v == $id)
        	{
        		unset($v) ;
        		break;
        	}
        }
        $cache->set($keybuild ,$list);
        return $list;
    }
}