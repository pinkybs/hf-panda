<?php

class Hapyfish2_Ipanda_Cache_PhytotronAdmin
{
    public static function getPhytotronAdminList($uid)
    {
    	$key = 'i:u:phytotronadminlist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
		
        if ($ids === false) {
        	try {
	            $dal = Hapyfish2_Ipanda_Dal_PhytotronAdmin::getDefaultInstance();
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
    	$key = 'i:u:phytotronadminlist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
        $ids[] = $info;
        $cache->set($key, $ids);
        return $ids;
    }
	public static function deletecache($uid, $fuid ,$phytotron_id)
    {
    	$key = 'i:u:phytotronadminlist:';
    	$key = Hapyfish2_Ipanda_Cache_Memkey::getuserkey($key);
        $key = $key . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $ids = $cache->get($key);
		$data = array();
		//为了保证只删除一个
		$flag = 0;
        foreach ($ids as $v)
        {
        	if( ( $v['uid'] == $uid ) && ( $v['friend_uid'] == $fuid ) && ( $v['ipanda_user_phytotron_id'] == $phytotron_id ) && empty($flag) )
        	{
        		$flag = 1;
        		continue ;
        	}
        	else
        	{
        		$data[] = $v ;
        	}
        }
        $cache->set($key, $data);
        return $data;
    }
}