<?php

class Hapyfish2_Ipanda_Bll_Achievement
{
	public static function getAchievementDetail($uid)
	{
		$data = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementList();
		$list = Hapyfish2_Ipanda_HFC_Achievement::getUserAchievement($uid);
		
		$result = array();
		foreach ($data as $v) {
			$groupId = 'num_' . $v['group_id'];
			if (isset($list[$groupId])) {
				$v['complete_num'] = $list[$groupId];
			} else {
				$v['complete_num'] = 0;
			}
			$result[] = $v;
        }
        
        return $result;
	}
	
	public static function incAchievement($uid, $field, $num)
	{
    	$achievement = Hapyfish2_Ipanda_HFC_Achievement::getUserAchievement($uid);
    	if ($achievement) {
    		$index = 'num_' . $field;
    		if (isset($achievement[$index])) {
    			$old = $achievement[$index];
    			$achievement[$index] += $num;
    			$ok = Hapyfish2_Ipanda_HFC_Achievement::saveUserAchievement($uid, $achievement);
    			if ($ok) {
    				$data = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementInfoByGroup($field);
    				if (!empty($data)) {
    					foreach ($data as $item) {
    					    if ($item['condition'] > $old && $item['condition'] <= $achievement[$index]) {
    					    	//成就完成获得称号
    					    	$id = (int)$item['id'];
    							$ok2 = Hapyfish2_Ipanda_HFC_User::gainTitle($uid, $id);
    					        if ($ok2) {
    								Hapyfish2_Ipanda_Bll_UserResult::addAchieveCompletedId($uid, $id);
    							}
    						}
    					}
    				}
    			}
    			
    			return $ok;
    		}
    	}
    	
    	return false;
	}
	
	public static function incAchievementByMultiField($uid, $info)
	{
	    $achievement = Hapyfish2_Ipanda_HFC_Achievement::getUserAchievement($uid);
    	if ($achievement) {
    		$old = array();
    		foreach ($info as $k => $v) {
    			$index = 'num_' . $k;
	    		if (isset($achievement[$index])) {
	    			$old[$k] = $achievement[$index];
	    			$achievement[$index] += $v;
	    		}
    		}
    		$ok = Hapyfish2_Ipanda_HFC_Achievement::saveUserAchievement($uid, $achievement);
   	    	if ($ok) {
   	    		foreach ($info as $k => $v) {
   	    			$index = 'num_' . $k;
	   				$data = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementInfoByGroup($k);
	   				if (!empty($data)) {
	   					foreach ($data as $item) {
		   					if ($item['condition'] > $old[$k] && $item['condition'] <= $achievement[$index]) {
    					    	//成就完成获得称号
    					    	$id = (int)$item['id'];
    							$ok2 = Hapyfish2_Ipanda_HFC_User::gainTitle($uid, $id);
    							if ($ok2) {
    								Hapyfish2_Ipanda_Bll_UserResult::addAchieveCompletedId($uid, $id);
    							}
		   					}
	   					}
	   				}
   	    		}
   			}
   			
   			return $ok;
    	}
    	
    	return false;
	}
	
	public static function updateAchievement($uid, $field, $num)
	{
    	$achievement = Hapyfish2_Ipanda_HFC_Achievement::getUserAchievement($uid);
    	if ($achievement) {
    		$index = 'num_' . $field;
    		if (isset($achievement[$index])) {
    			$old = $achievement[$index];
    			$achievement[$index] = $num;
    			$ok = Hapyfish2_Ipanda_HFC_Achievement::saveUserAchievement($uid, $achievement);
    			if ($ok) {
    				$data = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementInfoByGroup($field);
    				if (!empty($data)) {
    				    foreach ($data as $item) {
    					    if ($item['condition'] > $old && $item['condition'] <= $achievement[$index]) {
    					    	//成就完成获得称号
    					    	$id = (int)$item['id'];
    							$ok2 = Hapyfish2_Ipanda_HFC_User::gainTitle($uid, $id);
    					        if ($ok2) {
    								Hapyfish2_Ipanda_Bll_UserResult::addAchieveCompletedId($uid, $id);
    							}
    						}
    					}
    				}
    			}
    			
    			return $ok;
    		}
    	}
    	
    	return false;
	}
}