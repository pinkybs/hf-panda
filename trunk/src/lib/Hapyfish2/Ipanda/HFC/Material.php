<?php

class Hapyfish2_Ipanda_HFC_Material
{
	public static function getUserMaterial($uid)
    {
        $key = 'i:u:material:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	try {
	            $dalMaterial = Hapyfish2_Ipanda_Dal_Material::getDefaultInstance();
	            $result = $dalMaterial->get($uid);
	            if ($result) {
	            	$data = array();
	            	foreach ($result as $cid => $count) {
	            		$data[$cid] = array($count, 0);
	            	}
	            	$cache->add($key, $data);
	            } else {
	            	return array();
	            }
        	} catch (Exception $e) {
        		
        	}
        }
        
        $material = array();
        if ( is_array($data) ) {
	        foreach ($data as $cid => $item) {
	        	$material[$cid] = array('count' => $item[0], 'update' => $item[1]);
	        }
        }
        
        return $material;
    }
    
    public static function updateUserMaterial($uid, $material, $savedb = true)
    {
        $key = 'i:u:material:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        
        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 900);
        }

        $ok = false;
        if ($savedb) {
            $data = array();
        	foreach ($material as $cid => $item) {
        		$data[$cid] = array($item['count'], 0);
        	}
        	$ok = $cache->save($key, $data);
        	if ($ok) {
        		try {
	        		$dalMaterial = Hapyfish2_Ipanda_Dal_Material::getDefaultInstance();
	        		foreach ($material as $cid => $item) {
	        			if ($item['update']) {
	        				$dalMaterial->update($uid, $cid, $item['count']);
	        			}
	        		}
        		} catch (Exception $e) {
        			info_log('[Hapyfish2_Ipanda_HFC_Material::updateUserMaterial]: ' . $e->getMessage(), 'db.err');
        		}
        	}
        } else {
            $data = array();
        	foreach ($material as $cid => $item) {
        		$data[$cid] = array($item['count'], $item['update']);
        	}
        	$ok = $cache->update($key, $data);
        }
        
        if ($ok) {
        	Hapyfish2_Ipanda_Bll_UserResult::setMaterialChange($uid, true);
        }
        
        return $ok;
    }
    
    public static function addUserMaterial($uid, $cid, $count = 1, $material = null)
    {
    	if (!$material) {
	    	$material = self::getUserMaterial($uid);
	    	if ($material === null) {
	    		return false;
	    	}
    	}
    	
    	if (isset($material[$cid])) {
    		$material[$cid]['count'] += $count;
    		$material[$cid]['update'] = 1;
    	} else {
    		$material[$cid] = array('count' => $count, 'update' => 1);
    	}

    	$ok = self::updateUserMaterial($uid, $material);
    	
    	if ($ok) {
	    	//派发任务
	    	//获得材料
	    	$event = array('uid' => $uid, 'cid' => $cid, 'num' => $count);
	    	Hapyfish2_Ipanda_Bll_Event::gainMaterial($event);
    	}
    	
    	return $ok;
    }
    
    public static function useUserMaterial($uid, $cid, $count = 1, $material = null)
    {
        if (!$material) {
	    	$material = self::getUserMaterial($uid);
	    	if (!$material) {
	    		return false;
	    	}
    	}

        if (!isset($material[$cid]) || $material[$cid]['count'] < $count) {
    		return false;
    	} else {
    		$material[$cid]['count'] -= $count;
    		$material[$cid]['update'] = 1;
    		return self::updateUserMaterial($uid, $material);
    	}
    }
	
	public static function useMultiple($uid, $info, $material = null)
	{
	    if (!$material) {
	    	$material = self::getUserMaterial($uid);
	    	if (!$material) {
	    		return false;
	    	}
    	}
		
		foreach($info as $data) {
			$cid = $data['cid'];
			$num = $data['num'];
			if ($cid > 0) {
				if (!isset($material[$cid])) {
					return false;
				}
	    		$material[$cid]['count'] -= $num;
	    		$material[$cid]['update'] = 1;
			}
		}
		
		return Hapyfish2_Ipanda_HFC_Material::updateUserMaterial($uid, $material);
	}
	
	public static function useMultiple2($uid, $info, $material = null)
	{
	    if (!$material) {
	    	$material = self::getUserMaterial($uid);
	    	if (!$material) {
	    		return false;
	    	}
    	}
		
		foreach($info as $cid => $num) {
			if (!isset($material[$cid])) {
				return false;
			}
    		$material[$cid]['count'] -= $num;
    		$material[$cid]['update'] = 1;
		}
		
		return Hapyfish2_Ipanda_HFC_Material::updateUserMaterial($uid, $material);
	}
    
}