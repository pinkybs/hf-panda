<?php

class Hapyfish2_Ipanda_HFC_PhytotronAnimal
{
	public static function getOne($uid, $cid)
	{
	    $key = 'i:u:phyanimal:' . $uid . ':' . $cid;
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
    	$item = $cache->get($key);
    	
    	if ($item === false) {
    		try {
	    		$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
	    		$item = $dalPhytotronAnimal->getOne($uid, $cid);
	    		if ($item) {
	    			$cache->add($key, $item);
	    		} else {
	    			return null;
	    		}
    		}catch (Exception $e) {
    			return null;
    		}
    	}
    	
    	$data = array(
        	'uid' => $uid,
        	'animal_cid' => $cid,
       		'ipanda_phytotron_unlock_list_id' => $item[1],
       		'phytotron_cid' => $item[2],
       		'service_num' => $item[3],
       		'animal_level' => $item[4]
    	);
    	
		return $data;
	}
	
	public static function getAll($uid)
	{
		$ids = Hapyfish2_Ipanda_Cache_PhytotronAnimal::getIds($uid); 
        if (!$ids) {
        	return null;
        }
        
		$keys = array();
        foreach ($ids as $id) {
        	$keys[] = 'i:u:phyanimal:' . $uid . ':' . $id;
        }
        
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        $data = $cache->getMulti($keys);
        
        if ($data === false) {
        	return null;
        }
        
	    //check all in memory
        $nocacheKeys = array();
        $empty = true;
        foreach ($data as $k => $item) {
        	if ($item == null) {
        		$nocacheKeys[] = $k;
        	} else {
        		$empty = false;
        	}
        }
        
	    if ($empty) {
        	try {
        		Hapyfish2_Ipanda_Cache_PhytotronAnimal::reloadIds($uid);
        		
	            $dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
	            $result = $dalPhytotronAnimal->getList($uid);
	            if ($result) {
	            	$data = array();
	            	foreach ($result as $item) {
	            		$key = 'i:u:phyanimal:' . $uid . ':' . $item[0];
	            		$data[$key] = $item;
	            	}
	            	$cache->addMulti($data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        } else if (!empty($nocacheKeys)) {
        	foreach ($nocacheKeys as $key) {
        		$tmp = split(':', $key);
        		$data[$key] = self::loadOne($uid, $tmp[4]);
        	}
        }
        
        $list = array();
        foreach ($data as $item) {
        	if ($item) {
        		$id = $item[0];
        		$vaildIds[] = $id;
	        	$list[$id] = array(
		        	'uid' => $uid,
		        	'animal_cid' => $id,
	        		'ipanda_phytotron_unlock_list_id' => $item[1],
	        		'phytotron_cid' => $item[2],
	        		'service_num' => $item[3],
	        		'animal_level' => $item[4]
	        	);
        	}
        }
		
		return $list;
	}
    
    public static function loadOne($uid, $cid)
    {
		try {
	    	$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
	    	$item = $dalPhytotronAnimal->getOne($uid, $cid);
	    	if ($item) {
	    		$key = 'i:u:phyanimal:' . $uid . ':' . $cid;
	    		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
	    		$cache->save($key, $item);
	    	} else {
	    		return null;
	    	}
	    	
	    	return $item;
		} catch (Exception $e) {
			err_log($e->getMessage());
			return null;
		}
    }
	
    public static function addOne($uid, $info)
    {
    	$result = false;
    	try {
    		$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
    		$dalPhytotronAnimal->insert($uid, $info);
    		
    		self::loadOne($uid, $id);
			Hapyfish2_Ipanda_Cache_PhytotronAnimal::reloadIds($uid);
    		$result = true;
    	} catch (Exception $e) {
    		
    	}
    	
    	return $result;
    }
    
    public static function updateOne($uid, $cid, $item, $savedb = false)
    {
    	$key = 'i:u:phyanimal:' . $uid . ':' . $cid;
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
    	$data = array(
    		$item['animal_cid'], $item['ipanda_phytotron_unlock_list_id'], $item['phytotron_cid'], $item['service_num'], $item['animal_level']
    	);
    	
    	if (!$savedb) {
    		$savedb = $cache->canSaveToDB($key, 900);
    	}
    	
    	if ($savedb) {
    		$ok = $cache->save($key, $data);
    		if ($ok) {
	    		//save to db
	    		try {
		    		$info = array(
		    			'service_num' => $item['service_num'], 
		    			'animal_level' => $item['animal_level']
		    		);
			    			
		    		$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
		    		$dalPhytotronAnimal->updateByCid($uid, $cid, $info);
	    		} catch (Exception $e) {
	    			err_log($e->getMessage());
	    		}
    		}
    	} else {
    		$ok = $cache->update($key, $data);
    	}
    	
    	return $ok;
    }
    
    public static function saveOne($uid, $cid, $item)
    {
		try {
    		$info = array(
    			'service_num' => $item['service_num'], 
    			'animal_level' => $item['animal_level']
    		);
	    			
    		$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
    		$dalPhytotronAnimal->updateByCid($uid, $cid, $info);
    	} catch (Exception $e) {
    		err_log($e->getMessage());
    	}
    }
}