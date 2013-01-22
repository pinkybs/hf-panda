<?php

class Hapyfish2_Ipanda_Cache_Feed
{
	public static function getFeedData($uid)
	{
		$key = 'i:u:feed:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		return $cache->get($key);
	}
	
	public static function flush($uid)
	{
		$key = 'i:u:feed:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->set($key, array());
	}
	
	public static function insertMiniFeed($uid, $feed)
    {
        $key = 'i:u:feed:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getFeed($uid);
        $cache->insertMiniFeed($key, $feed);
    }
    
	public static function insertIslandMinifeed($uid, $feed)
    {
        $key = 'i:u:feed:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getFeed($uid);
        $cache->insertIslandMinifeed($key, $feed);
    }
    
	public static function insertPlantManageMinifeed($uid, $feed)
    {
        $key = 'i:u:feed:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getFeed($uid);
        $cache->insertPlantManageMinifeed($key, $feed);
    }
    
    public static function getNewMiniFeedCount($uid)
    {
		$key = 'i:u:feed:interaction:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$count = $cache->get($key);
		if ($count === false) {
			$count = 0;
		}
		$new[0] = $count;
		
		$key = 'i:u:feed:notice:count:' . $uid;
		$count = $cache->get($key);
		if ($count === false) {
			$count = 0;
		}
		$new[1] = $count;
		
		return $new;
    }
    
	public static function incNewMiniFeedCount($uid)
	{
		$key = 'i:u:feed:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->increment($key, 1);
	}
	public static function incNewMiniFeedNoticeCount($uid)
	{
		$key = 'i:u:feed:notice:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->increment($key, 1);
	}
	public static function incNewMiniFeedInteractionCount($uid)
	{
		$key = 'i:u:feed:interaction:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->increment($key, 1);
	}
	public static function clearNewMiniFeedCount($uid)
	{
		$key = 'i:u:feed:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->set($key, 0);
		$key = 'i:u:feed:notice:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->set($key, 0);
		$key = 'i:u:feed:interaction:count:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getFeed($uid);
		$cache->set($key, 0);
	}
    

}