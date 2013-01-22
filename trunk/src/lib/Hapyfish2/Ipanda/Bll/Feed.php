<?php

class Hapyfish2_Ipanda_Bll_Feed
{
	public static function config()
	{
		$data = array(
			'interaction' 	=> array(1, 2, 3, 4, 5, 6),
			'notice'		=> array(0, 7, 8, 9)
		);
		
		return $data;
	}
	
	public static function getFeedData($uid)
	{
		$data = Hapyfish2_Ipanda_Cache_Feed::getFeedData($uid);
		if ($data === false) {
			return array();
		}
		
		$result = array();
		foreach ($data as $feed) {
			$result[] = array(
				'uid' => $feed[0],
				'template_id' => $feed[1],
				'type' => $feed[2],
				'actor' => $feed[3],
				'target' => $feed[4],
				'title' => $feed[5],
				'create_time' => self::getTime($feed[6])
			);
		}
		
		return $result;
	}
	
	public static function flushFeedData($uid)
	{
		Hapyfish2_Ipanda_Cache_Feed::flush($uid);
	}
	
	public static function insertMiniFeed($feed)
	{
	    $uid = $feed['uid'];
	    
	    $newfeed = array(
	    	(int)$feed['uid'], 
	    	(int)$feed['template_id'], 
	    	(int)$feed['type'], 
	    	(int)$feed['actor'], 
	    	(int)$feed['target'], 
	    	$feed['title'], 
	    	(int)$feed['create_time']
	    );
	    
	    Hapyfish2_Ipanda_Cache_Feed::insertMiniFeed($uid, $newfeed);
	    
		//update user feed status
		$data = self::config();
		if (in_array($feed['type'] ,$data['interaction'] )) {
			Hapyfish2_Ipanda_Cache_Feed::incNewMiniFeedInteractionCount($uid);
		} else if(in_array($feed['type'] ,$data['notice'] )) {
			Hapyfish2_Ipanda_Cache_Feed::incNewMiniFeedNoticeCount($uid);
		} else {
        	Hapyfish2_Ipanda_Cache_Feed::incNewMiniFeedCount($uid);
		}
	}
	
	public static function getFeed($uid, $pageIndex = 1, $pageSize = 50)
    {
		//get user mini feed
        $feeds = self::getFeedData($uid);
        
        if (empty($feeds)) {
        	return array(
        		'feeds' => array(),
        		'new' => array()
        	);
        }
        
        $new = Hapyfish2_Ipanda_Cache_Feed::getNewMiniFeedCount($uid);
        Hapyfish2_Ipanda_Cache_Feed::clearNewMiniFeedCount($uid);
        $f = self::buildFeed($feeds);
        return array(
        	'feeds' => $f,
        	'new' => $new
        );
    }
    
    protected static function buildFeed(&$feeds)
    {
        $tpl = Hapyfish2_Ipanda_Cache_Basic_Info::getFeedTemplate();
    	for($i = 0, $count = count($feeds); $i < $count; $i++) {
    		$template_id = $feeds[$i]['template_id'];
        	$tplTitle = isset($tpl[$template_id]) ? $tpl[$template_id] : '';
        	$feedTitle = isset($feeds[$i]['title']) ? $feeds[$i]['title'] : array();
        	$title = self::buildTemplate($feeds[$i]['actor'], $feeds[$i]['target'], $tplTitle, $feedTitle);
    	    if ($title) {
                $feeds[$i]['title'] = $title;
            }
            else {
                $feeds[$i]['title'] = '';
            }
            unset($feeds[$i]['uid']);
            unset($feeds[$i]['template_id']);
            $feeds[$i]['createTime'] = $feeds[$i]['create_time'];
            unset($feeds[$i]['create_time']);
            
            //
            $feeds[$i]['actorName'] = self::getName($feeds[$i]['actor']);
        }

        return $feeds;
    }
    
    protected static function getName($uid)
    {
		$name = '';
		if ($uid == GM_UID_LELE) {
			$name = GM_NAME_LELE;
		} else {
			$user = Hapyfish2_Platform_Bll_User::getUser($uid);
			if (!empty($user)) {
				$name = $user['name'];
			}
		}
		
		return $name;
    }
    
    protected static function buildTemplate($actor_id, $target_id, $tplTitle, $feedTitle)
    {
        if ($feedTitle == null) {
            $feedTitle = array();
        }

        if (!is_array($feedTitle)) {
            return false;
        }

        $actorName = self::getName($actor_id);
        if (empty($actorName)) {
            $actor_name = "____";
        }
        else {
            $actor_name = '<a href="event:' . $actor_id . '"><font color="#00CC99" face="微软雅黑" size="12">' . $actorName . '</font></a>';
        }

        $feedTitle['actor'] = $actor_name;

        if ($target_id) {
        	$targetName = self::getName($target_id);
            if (empty($targetName)) {
                $target_name = "____";
            }
            else {
            	$target_name = '<a href="event:' . $target_id . '"><font face="微软雅黑" size="12" color="#00CC99">' .  $targetName . '</font></a>';
            }

            $feedTitle['target'] = $target_name;
        }

        $keys = array();
        $values = array();
        
		foreach ($feedTitle as $k => $v) {
			$keys[] = '{*' . $k . '*}';
			$values[] = $v;
		}
        
        return str_replace($keys, $values, $tplTitle);
    }
    
    public static function getTime($create_time)
    {
    	require CONFIG_DIR . '/language.' . COUNTRY . '.php';
    	$now = time();
    	$l = $now - $create_time;
    	if ($l >= 86400) {
    		$t = floor($l/86400);
    		return $t . $language["day before"];
    	} else if ($l >= 3600) {
    		$t = floor($l/3600);
    		return $t . $language["hour before"];
    	} else if ($l >= 60) {
    		$t = floor($l/60);
    		return $t . $language["minute before"];
    	} else {
    		return $l . $language["second before"];
    	}
    }

}