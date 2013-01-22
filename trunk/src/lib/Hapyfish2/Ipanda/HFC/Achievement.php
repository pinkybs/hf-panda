<?php

class Hapyfish2_Ipanda_HFC_Achievement
{
	public static function getUserAchievement($uid)
    {
		$key = 'i:u:ach:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        $data = $cache->get($key);

        if ($data === false) {
        	try {
            	$dalAchievement = Hapyfish2_Ipanda_Dal_Achievement::getDefaultInstance();
            	$data = $dalAchievement->get($uid);
	            if ($data) {
	            	$cache->add($key, $data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        $achievement =  array(
	        	'num_1' => $data[0],
	        	'num_2' => $data[1],
	        	'num_3' => $data[2],
	        	'num_4' => $data[3],
	        	'num_5' => $data[4],
	        	'num_6' => $data[5],
        		'num_7' => $data[6],
        		'num_8' => $data[7],
        		'num_9' => $data[8],
        		'num_10' => $data[9],
        		'num_11' => $data[10],
        		'num_12' => $data[11],
        		'num_13' => $data[12],
        		'num_14' => $data[13],
        		'num_15' => $data[14],
        		'num_16' => $data[15],
        		'num_17' => $data[16],
        		'num_18' => $data[17],
        		'num_19' => $data[18],
        		'num_20' => $data[19],
        		'num_21' => $data[20],
        		'num_22' => $data[21],
        		'num_23' => $data[22],
        		'num_24' => $data[23],
        		'num_25' => $data[24],
        		'num_26' => $data[25],
        		'num_27' => $data[26],
        		'num_28' => $data[27],
        		'num_29' => $data[28],
        		'num_30' => $data[29],
        		'num_31' => $data[30],
        		'num_32' => $data[31],
        		'num_33' => $data[32],
        		'num_34' => $data[33],
        		'num_35' => $data[34],
        		'num_36' => $data[35],
        		'num_37' => $data[36],
        		'num_38' => $data[37],
        		'num_39' => $data[38],
        		'num_40' => $data[39]
        );
        
        return $achievement;
    }
    
    public static function updateUserAchievement($uid, $info)
    {
    	$achievement = self::getUserAchievement($uid);
        if ($achievement) {
        	foreach ($info as $k => $v) {
        		if (isset($achievement[$k])) {
    				$achievement[$k] = $v;
    			}
        	}

    		return self::saveUserAchievement($uid, $achievement);
    	}
    }

    public static function updateUserAchievementByFieldData($uid, $field, $newData)
    {
    	$achievement = self::getUserAchievement($uid);
    	if ($achievement) {
    		if (isset($achievement[$field])) {
	    		$achievement[$field] = $newData;
	    		return self::saveUserAchievement($uid, $achievement);
    		}
    	}
    }
    
    public static function updateUserAchievementByField($uid, $field, $change)
    {
    	$achievement = self::getUserAchievement($uid);
    	if ($achievement) {
    		if (isset($achievement[$field])) {
    			$achievement[$field] += $change;
    			return self::saveUserAchievement($uid, $achievement);
    		}
    	}
    	
    	return false;
    }
    
    public static function updateUserAchievementByMultiField($uid, $info)
    {
    	$achievement = self::getUserAchievement($uid);
    	if ($achievement) {
    		foreach ($info as $k => $v) {
	    		if (isset($achievement[$k])) {
	    			$achievement[$k] += $v;
	    		}
    		}
    		self::saveUserAchievement($uid, $achievement);
    	}
    }
    
    public static function saveUserAchievement($uid, $achievement, $savedb = false)
    {
    	$key = 'i:u:ach:' . $uid;
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
    	$data = array(
    		$achievement['num_1'],$achievement['num_2'],$achievement['num_3'],$achievement['num_4'],$achievement['num_5'],
    		$achievement['num_6'],$achievement['num_7'],$achievement['num_8'],$achievement['num_9'],$achievement['num_10'],
    		$achievement['num_11'],$achievement['num_12'],$achievement['num_13'],$achievement['num_14'],$achievement['num_15'],
    		$achievement['num_16'],$achievement['num_17'],$achievement['num_18'],$achievement['num_19'],$achievement['num_20'],
    		$achievement['num_21'],$achievement['num_22'],$achievement['num_23'],$achievement['num_24'],$achievement['num_25'],
    		$achievement['num_26'],$achievement['num_27'],$achievement['num_28'],$achievement['num_29'],$achievement['num_30'],
    		$achievement['num_31'],$achievement['num_32'],$achievement['num_33'],$achievement['num_34'],$achievement['num_35'],
    		$achievement['num_36'],$achievement['num_37'],$achievement['num_38'],$achievement['num_39'],$achievement['num_40']
    	);
    	
    	if (!$savedb) {
    		$savedb = $cache->canSaveToDB($key, 900);
    	}
    	
    	if ($savedb) {
    		$ok = $cache->save($key, $data);
    		if ($ok) {
				try {
					$info = array(
    					'num_1' => $achievement['num_1'], 'num_2' => $achievement['num_2'], 'num_3' => $achievement['num_3'],
						'num_4' => $achievement['num_4'], 'num_5' => $achievement['num_5'], 'num_6' => $achievement['num_6'],
    					'num_7' => $achievement['num_7'], 'num_8' => $achievement['num_8'], 'num_9' => $achievement['num_9'],
    					'num_10' => $achievement['num_10'], 'num_11' => $achievement['num_11'], 'num_12' => $achievement['num_12'],
    					'num_13' => $achievement['num_13'], 'num_14' => $achievement['num_14'], 'num_15' => $achievement['num_15'],
    					'num_16' => $achievement['num_16'], 'num_17' => $achievement['num_17'], 'num_18' => $achievement['num_18'],
						'num_19' => $achievement['num_19'], 'num_20' => $achievement['num_20'], 'num_21' => $achievement['num_21'],
						'num_22' => $achievement['num_22'], 'num_23' => $achievement['num_23'], 'num_24' => $achievement['num_24'],
						'num_25' => $achievement['num_25'], 'num_26' => $achievement['num_26'], 'num_27' => $achievement['num_27'],
						'num_28' => $achievement['num_28'], 'num_29' => $achievement['num_29'], 'num_30' => $achievement['num_30'],
						'num_31' => $achievement['num_31'], 'num_32' => $achievement['num_32'], 'num_33' => $achievement['num_33'],
						'num_34' => $achievement['num_34'], 'num_35' => $achievement['num_35'], 'num_36' => $achievement['num_36'],
						'num_37' => $achievement['num_37'], 'num_38' => $achievement['num_38'], 'num_39' => $achievement['num_39'],
						'num_40' => $achievement['num_40']
					);
	            	$dalAchievement = Hapyfish2_Ipanda_Dal_Achievement::getDefaultInstance();
	            	$dalAchievement->update($uid, $info);
	        	} catch (Exception $e) {
	        	}
	        	
	        	return $ok;
    		}
    	} else {
    		return $cache->update($key, $data);
    	}
    }
    
}