<?php

class Hapyfish2_Ipanda_HFC_Card
{
	public static function getUserCard($uid)
    {
        $key = 'i:u:card:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	try {
	            $dalCard = Hapyfish2_Ipanda_Dal_Card::getDefaultInstance();
	            $result = $dalCard->get($uid);
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
        
        $cards = array();
        if ( is_array($data) ) {
	        foreach ($data as $cid => $item) {
	        	$cards[$cid] = array('count' => $item[0], 'update' => $item[1]);
	        }
        }
        
        return $cards;
    }
    
    public static function updateUserCard($uid, $cards, $savedb = true)
    {
        $key = 'i:u:card:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        
        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 900);
        }

        if ($savedb) {
            $data = array();
        	foreach ($cards as $cid => $item) {
        		$data[$cid] = array($item['count'], 0);
        	}
        	$ok = $cache->save($key, $data);
        	if ($ok) {
        		try {
	        		$dalCard = Hapyfish2_Ipanda_Dal_Card::getDefaultInstance();
	        		foreach ($cards as $cid => $item) {
	        			if ($item['update']) {
	        				$dalCard->update($uid, $cid, $item['count']);
	        			}
	        		}
        		} catch (Exception $e) {
        			
        		}
        	}
        } else {
            $data = array();
        	foreach ($cards as $cid => $item) {
        		$data[$cid] = array($item['count'], $item['update']);
        	}
        	$ok = $cache->update($key, $data);
        }
        
        if ($ok) {
        	Hapyfish2_Ipanda_Bll_UserResult::setCardChange($uid, true);
        }
        
        return $ok;
    }
    
    public static function addUserCard($uid, $cid, $count = 1, $cards = null)
    {
    	if (!$cards) {
	    	$cards = self::getUserCard($uid);
	    	if ($cards === null) {
	    		return false;
	    	}
    	}
    	
    	if (isset($cards[$cid])) {
    		$cards[$cid]['count'] += $count;
    		$cards[$cid]['update'] = 1;
    	} else {
    		$cards[$cid] = array('count' => $count, 'update' => 1);
    	}

    	return self::updateUserCard($uid, $cards);
    }
    
    public static function useUserCard($uid, $cid, $count = 1, $cards = null)
    {
        if (!$cards) {
	    	$cards = self::getUserCard($uid);
	    	if ($cards === null) {
	    		return false;
	    	}
    	}

        if (!isset($cards[$cid]) || $cards[$cid]['count'] < $count) {
    		return false;
    	} else {
    		$cards[$cid]['count'] -= $count;
    		$cards[$cid]['update'] = 1;
    		return self::updateUserCard($uid, $cards);
    	}
    }
    
    public static function getCardStatus($uid)
    {
        $key = 'i:u:cardstatus:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	try {
	            $dalCardStatus= Hapyfish2_Ipanda_Dal_CardStatus::getDefaultInstance();
	            $data = $dalCardStatus->get($uid);
	            if ($data) {
	            	$data = json_decode($data, true);
	            	$cache->add($key, $data);
	            } else {
	            	return null;
	            }
        	}catch (Exception $e) {
        		return null;
        	}
        }
        
        $status = array();
        if (!empty($data)) {
        	$status = array();
        	$t = time();
	      	foreach ($data as $cid => $item) {
				if ($item[1] > $t) {
					$status[$cid] = $item;
				}
			}
        }
        
        return $status;
    }
    
    public static function updateCardStatus($uid, $status, $savedb = false)
    {
        $key = 'i:u:cardstatus:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        
        if (!$savedb) {
    		$savedb = $cache->canSaveToDB($key, 900);
        }
        if ($savedb) {
        	$ok = $cache->save($key, $status);
        	if ($ok) {
        		try {
        			$info = array('status' => json_encode($status));
        			$dalCardStatus= Hapyfish2_Ipanda_Dal_CardStatus::getDefaultInstance();
        			$dalCardStatus->update($uid, $info);
        		} catch (Exception $e) {
        		}
        	}
        	
        	return $ok;
        } else {
        	return $cache->update($key, $status);
        }
    }
    
}