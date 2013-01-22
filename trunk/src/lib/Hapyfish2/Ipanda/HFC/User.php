<?php

class Hapyfish2_Ipanda_HFC_User
{
	public static function resumeEnergy($uid, &$userEg)
	{
		$t = time();
		$energy_recover_time = $userEg['energy_recover_time'];
		if (empty($energy_recover_time)) {
			$userEg['energy_recover_time'] = $t;
			self::updateUserEnergy($uid, $userEg);
			return false;
		}

		$energy_recover_time = EG_RECOVERY_TIME;

		//体力值已经满了
		if ($userEg['energy'] >= $userEg['energy_top']) {
			//时间间隔超过恢复间隔
			if ($userEg['energy_recover_time'] + $energy_recover_time < $t) {
				$userEg['energy_recover_time'] = $t;
				self::updateUserEnergy($uid, $userEg);
			}
			return false;
		}

		//体力值没有满并且超过恢复间隔
		if ($userEg['energy_recover_time'] + $energy_recover_time < $t) {
			$rate = floor(($t - $userEg['energy_recover_time'])/$energy_recover_time);
			$egChange = EG_RECOVERY_ENERGY*$rate;
			if ($userEg['energy'] + $egChange >= $userEg['energy_top']) {
				$userEg['energy'] = $userEg['energy_top'];
			} else {
				$userEg['energy'] += $egChange;
			}
			$userEg['energy_recover_time'] += $rate*$energy_recover_time;
			self::updateUserEnergy($uid, $userEg);

			return true;
		}

		return false;
	}
	
	public static function getUserVO($uid)
	{
		$keys = array(
			'i:u:exp:' . $uid,
			'i:u:love:' . $uid,
			'i:u:gold:' . $uid,
			'i:u:level:' . $uid,
			'i:u:energyinfo:' . $uid,
			'i:u:admin_num:' . $uid,
			'i:u:title:' . $uid,
		);
		
		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
		
		$userVO = array('uid' => $uid);
		
		if (!USE_CACHE) {
			try {
			    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $newUserVo = $dalUser->getUserVo($uid);	           
	            if ($newUserVo) {
					$userExp = $newUserVo['exp'];
					$userVO['exp'] = $userExp;
					$cache->save($keys[0], $userExp);
					
					$userLove = $newUserVo['love'];
					$userVO['love'] = $userLove;
					$cache->save($keys[1], $userLove);
					
					$userGold = $newUserVo['gold'];
					$userVO['gold'] = $userGold;
					$cache->save($keys[2], $userGold);
					
					$userLevel = $newUserVo['level'];
					$userVO['level'] = $userLevel;
					$cache->save($keys[3], $userLevel);
					
					$userEg = array('energy' => $newUserVo['energy'], 'energy_top' => $newUserVo['energy_top'], 'energy_recover_time' => $newUserVo['energy_recover_time']);
					self::resumeEnergy($uid, $userEg);
					$userVO['energy'] = $userEg['energy'];
					$userVO['energy_top'] = $userEg['energy_top'];
					$userVO['energy_recover_time'] = $userEg['energy_recover_time'];
					$userEgData = array($userEg['energy'], $userEg['energy_top'], $userEg['energy_recover_time']);
					$cache->save($keys[4], $userEgData);
		
					$adminNum = $newUserVo['admin_num'];
					$userVO['admin_num'] = $adminNum;
					$cache->save($keys[5], $adminNum);
					
					$userTitle = $newUserVo['title'];
					$userTitleList = $newUserVo['title_list'];
					$userVO['title'] = $userTitle;
					$userVO['title_list'] = $userTitleList;
					$userTitleData = array($userTitle, $userTitleList);
					$cache->save($keys[6], $userTitleData);
	            } else {
	            	return null;
	            }
			} catch (Exception $e) {
				err_log($e->getMessage());
				return null;
			}
		} else {
			$data = $cache->getMulti($keys);
		
			if ($data === false) {
				return null;
			}
			
			$userExp = $data[$keys[0]];
			if ($userExp === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userExp = $dalUser->getExp($uid);
		            $cache->add($keys[0], $userExp);
				} catch (Exception $e) {
					return null;
				}
			}
			if (!$userExp) {
				$userExp = 0;
			}
			$userVO['exp'] = $userExp;
			
			$userLove = $data[$keys[1]];
			if ($userLove === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userLove = $dalUser->getLove($uid);
		            $cache->add($keys[1], $userLove);
				} catch (Exception $e) {
					return null;
				}
			}
			if (!$userLove) {
				$userLove = 0;
			}
			$userVO['love'] = $userLove;
			
			$userGold = $data[$keys[2]];
			if ($userGold === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userGold = $dalUser->getGold($uid);
		            $cache->add($keys[2], $userGold, 3600);
				} catch (Exception $e) {
					return null;
				}
			}
			if (!$userGold) {
				$userGold = 0;
			}
			$userVO['gold'] = $userGold;
			
			$userLevel = $data[$keys[3]];
			if ($userLevel === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userLevel = $dalUser->getLevel($uid);
		            if ($userLevel) {
		            	$cache->add($keys[3], $userLevel);
		            } else {
		            	return null;
		            }
				} catch (Exception $e) {
					err_log($e->getMessage());
					return null;
				}
			}
			$userVO['level'] = $userLevel;
			
			$userEnergyData = $data[$keys[4]];
			if ($userEnergyData === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userEnergyData = $dalUser->getEnergy($uid);
		            if ($userLevel) {
		            	$cache->add($keys[4], $userEnergyData);
		            } else {
		            	return null;
		            }
				} catch (Exception $e) {
					err_log($e->getMessage());
					return null;
				}
			}
			
			$userEg = array('energy' => $userEnergyData[0], 'energy_top' => $userEnergyData[1], 'energy_recover_time' => $userEnergyData[2]);
			self::resumeEnergy($uid, $userEg);
			$userVO['energy'] = $userEg['energy'];
			$userVO['energy_top'] = $userEg['energy_top'];
			$userVO['energy_recover_time'] = $userEg['energy_recover_time'];

			$adminNum = $data[$keys[5]];
			if ($adminNum === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $adminNum = $dalUser->getAdminNum($uid);
		            if ($adminNum) {
		            	$cache->add($keys[5], $adminNum);
		            } else {
		            	return null;
		            }
				} catch (Exception $e) {
					err_log($e->getMessage());
					return null;
				}
			}
			if (!$adminNum) {
				$adminNum = 0;
			}
			$userVO['admin_num'] = $adminNum;
			
			$userTitleData = $data[$keys[6]];
			if ($userTitleData === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userTitleData = $dalUser->getTitle($uid);
		            if ($userTitleData) {
		            	$cache->add($keys[6], $userTitleData);
		            } else {
		            	return null;
		            }
				} catch (Exception $e) {
					err_log($e->getMessage());
					return null;
				}
			}
			$userVO['title'] = $userTitleData[0];
			$userVO['title_list'] = $userTitleData[1];
		}

        return $userVO;
	}
	
	
	public static function getUser($uid, $fields)
    {
    	$keys = array();
    	$getExp = false;
    	$getLevel = false;
    	$getLove = false;
    	$getGold = false;
    	if (isset($fields['exp'])) {
    		$keyExp = 'i:u:exp:' . $uid;
    		$keys[] = $keyExp;
    		$getExp = true;
    	}

    	if (isset($fields['love'])) {
    		$keyLove = 'i:u:love:' . $uid;
    		$keys[] = $keyLove;
    		$getLove = true;
    	}
    	
		if (isset($fields['level'])) {
			$keyLevel = 'i:u:level:' . $uid;
    		$keys[] = $keyLevel;
    		$getLevel = true;
    	}
    	
        if (isset($fields['gold'])) {
    		$keyGold = 'i:u:gold:' . $uid;
    		$keys[] = $keyGold;
    		$getGold = true;
    	}
    	
    	if (empty($keys)) {
    		return null;
    	}
    	
    	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->getMulti($keys);
		if ($data === false) {
			return null;
		}
		
		$user = array('uid' => $uid);
		
		if ($getExp) {
			$userExp = $data[$keyExp];
			if ($userExp === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userExp = $dalUser->getExp($uid);
		            $cache->add($keyExp, $userExp);
				} catch (Exception $e) {
					return null;
				}
			}
			if (!$userExp) {
				$userExp = 0;
			}
			$user['exp'] = $userExp;
		}
		
		if ($getLove) {
			$userLove = $data[$keyLove];
			if ($userLove === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userLove = $dalUser->getLove($uid);
		            $cache->add($keyLove, $userLove);
				} catch (Exception $e) {
					return null;
				}
			}
			if (!$userLove) {
				$userLove = 0;
			}
			$user['love'] = $userLove;
		}
		
		if ($getLevel) {
			$userLevel = $data[$keyLevel];
			if ($userLevel === null) {
				try {
				    $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
		            $userLevel = $dalUser->getLevel($uid);
		            if ($userLevel) {
		            	$cache->add($keyLevel, $userLevel);
		            } else {
		            	return null;
		            }
				} catch (Exception $e) {
					return null;
				}
			}
			if (!$userLevel) {
				$userLevel = 1;
			}
			$user['level'] = $userLevel;
		}
		
    	if ($getGold) {
			$userGold = $data[$keyGold];
			if ($userGold === null) {
				try {
					$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
					$userGold = $dalUser->getGold($uid);
					$cache->add($keyGold, $userGold, 3600);
	        	} catch (Exception $e) {
	        		return null;
	        	}
			}
			if (!$userGold) {
				$userGold = 0;
			}
			$user['gold'] = $userGold;
		}

        return $user;
    }
    
    public static function getUserExp($uid)
    {
        $key = 'i:u:exp:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$exp = $cache->get($key);
        if ($exp === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $exp = $dalUser->getExp($uid);
	            $cache->add($key, $exp);
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $exp;
    }
    
    public static function getUserLove($uid)
    {
        $key = 'i:u:love:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$love = $cache->get($key);
		if(!USE_CACHE)
		{
			$love = false;
		}
        if ($love === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $love = $dalUser->getLove($uid);
	            $cache->add($key, $love);
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $love;
    }
    
	public static function getUserEnergy($uid)
    {
        $key = 'i:u:energyinfo:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
		
		if (!USE_CACHE) {
			$data = false;
		}
		
        if ($data === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $data = $dalUser->getEnergy($uid);
				if ($data) {
	            	$cache->add($key, $data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        $userEg = array('energy' => $data[0], 'energy_top' => $data[1], 'energy_recover_time' => $data[2]);
       	self::resumeEnergy($uid, $userEg);
       	
       	return $userEg;
    }
    
    public static function getUserGold($uid)
    {
        $key = 'i:u:gold:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$gold = $cache->get($key);
        if ($gold === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $gold = $dalUser->getGold($uid);
	            $cache->add($key, $gold, 3600);
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $gold;
    }
    
    public static function reloadUserGold($uid)
    {
        try {
        	$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$gold = $dalUser->getGold($uid);
        	$key = 'i:u:gold:' . $uid;
        	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
	        $cache->save($key, $gold, 3600);
        } catch (Exception $e) {
        	return null;
		}
        
        return $gold;
    }
    
    public static function getUserLevel($uid)
    {
        $key = 'i:u:level:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$level = $cache->get($key);
		
        if ($level === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $level = $dalUser->getLevel($uid);
	            if ($level) {
	            	$cache->add($key, $level);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        return $level;
    }
    
    public static function updateUserExp($uid, $userExp, $savedb = true)
    {
		$key = 'i:u:exp:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        
        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 900);
        }
        
        if ($savedb) {
        	$ok = $cache->save($key, $userExp);
        	if ($ok) {
        		try {
        			$info['exp'] = $userExp;
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $info);
        		} catch (Exception $e) {
        		}
        	}
        	return $ok;
        } else {
        	return $cache->update($key, $userExp);
        }
    }
    
    public static function incUserExp($uid, $expChange)
    {
    	if ($expChange <= 0) {
    		return false;
    	}
    	
    	$userExp = self::getUserExp($uid);
    	if ($userExp === null) {
    		return false;
    	}

    	$userExp += $expChange;
    	
    	$ok = self::updateUserExp($uid, $userExp);
    	
        if ($ok) {
    		Hapyfish2_Ipanda_Bll_UserResult::mergeExp($uid, $expChange);
    		Hapyfish2_Ipanda_Bll_User::checkLevelUp($uid);
    	}
    }
    
    public static function updateUserLove($uid, $userLove, $savedb = false)
    {
		$key = 'i:u:love:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        
        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 900);
        }
        if ($savedb) {
        	$ok = $cache->save($key, $userLove);
        	if ($ok) {
        		try {
        			$info = array('love' => $userLove);
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $info);
        		} catch (Exception $e) {
        		}
        	}
        	
        	return $ok;
        } else {
        	return $cache->update($key, $userLove);
        }
    }
    
    public static function incUserLove($uid, $loveChange, $savedb = false)
    {
    	if ($loveChange <= 0) {
    		return false;
    	}
    	
    	$userLove = self::getUserLove($uid);
    	if ($userLove === null) {
    		return false;
    	}
    	
    	$userLove += $loveChange;
    	
        if (!USE_CACHE) {
    		$savedb = true;
    	}
    	
    	$ok = self::updateUserLove($uid, $userLove, $savedb);
    	
    	if ($ok) {
    		Hapyfish2_Ipanda_Bll_UserResult::mergeLove($uid, $loveChange);
    		//成就(获得爱心数)
    		Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '1', $loveChange);
    	}
    	
    	return $ok;
    }
    
    public static function decUserLove($uid, $loveChange, $savedb = false)
    {
    	if ($loveChange <= 0) {
    		return false;
    	}
    	
    	$userLove = self::getUserLove($uid);
    	
    	if ($userLove === null) {
    		return false;
    	}
    	
    	if ($userLove < $loveChange) {
    		return false;
    	}
    	
    	$userLove -= $loveChange;
    	
        if (!USE_CACHE) {
    		$savedb = true;
    	}
    	
    	$ok = self::updateUserLove($uid, $userLove, $savedb);
        if ($ok) {
        	Hapyfish2_Ipanda_Bll_UserResult::mergeLove($uid, -$loveChange);
        	
        	//成就(花费爱心数)
    		Hapyfish2_Ipanda_Bll_Achievement::incAchievement($uid, '2', $loveChange);
    		
	    	//派发任务
	    	$event = array('uid' => $uid, 'love' => $loveChange);
	    	Hapyfish2_Ipanda_Bll_Event::consumeLove($event);
    	}
    	
    	return $ok;
    }
    
	public static function updateUserEnergy($uid, $userEnergy, $savedb = false)
    {
		$data = array($userEnergy['energy'], $userEnergy['energy_top'], $userEnergy['energy_recover_time']);
    	
    	$key = 'i:u:energyinfo:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        
        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 900);
        }
        if ($savedb) {
        	$ok = $cache->save($key, $data);
        	if ($ok) {
        		try {
        			$info = array('energy' => $userEnergy['energy'], 'energy_top' => $userEnergy['energy_top'], 'energy_recover_time' => $userEnergy['energy_recover_time']);
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $info);
        		} catch (Exception $e) {
        		}
        	}
        	
        	return $ok;
        } else {
        	return $cache->update($key, $data);
        }
    }
    
    public static function incUserEnergy($uid, $change, $savedb = false)
    {
    	if ($change <= 0) {
    		return false;
    	}
    	
    	$userEnergy = self::getUserEnergy($uid);
    	
    	if ($userEnergy === null) {
    		return false;
    	}
    	
    	$userEnergy['energy'] += $change;
    	$userEnergy['energy'] = $userEnergy['energy'] <= $userEnergy['energy_top'] ? $userEnergy['energy'] : $userEnergy['energy_top'] ;
    	if (!USE_CACHE) {
    		$savedb = true;
    	}
    	
    	$ok = self::updateUserEnergy($uid, $userEnergy, $savedb);
    	
    	if ($ok) {
    		Hapyfish2_Ipanda_Bll_UserResult::mergeEnergy($uid, $change);
    	}
    	
    	return $ok;
    }
    
    public static function fullUserEnergy($uid, $savedb = false)
    {    	
    	$userEnergy = self::getUserEnergy($uid);
    	
    	if ($userEnergy === null) {
    		return false;
    	}
    	$temp = $userEnergy['energy'];
    	$userEnergy['energy'] = $userEnergy['energy_top'];
    	
    	if (!USE_CACHE) {
    		$savedb = true;
    	}
    	
    	//return self::updateUserEnergy($uid, $userEnergy, $savedb);
    	$ok = self::updateUserEnergy($uid, $userEnergy, $savedb);
    	
        if ($ok) {
        	$change = $userEnergy['energy'] - $temp;
    		Hapyfish2_Ipanda_Bll_UserResult::mergeEnergy($uid, $change);
    	}
    	
    	return $ok;
    }
    
    public static function decUserEnergy($uid, $change, $savedb = false)
    {
    	if ($change <= 0) {
    		return false;
    	}
    	
    	$userEnergy = self::getUserEnergy($uid);
    	
        if ($userEnergy == null) {
    		return false;
    	}
    	
    	if ($userEnergy['energy'] < $change) {
    		return false;
    	}
    	
    	$userEnergy['energy'] -= $change;
    	
        if (!USE_CACHE) {
    		$savedb = true;
    	}
    	
    	$ok = self::updateUserEnergy($uid, $userEnergy, $savedb);
    	
        if ($ok) {
    		Hapyfish2_Ipanda_Bll_UserResult::mergeEnergy($uid, -$change);
    	}
    	
    	return $ok;
    }
    
    public static function incUserExpAndLove($uid, $expChange, $loveChange)
    {
    	self::incUserExp($uid, $expChange);
    	self::incUserLove($uid, $loveChange);
    }
    
    public static function updateUserLevel($uid, $level)
    {
    	$key = 'i:u:level:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);

        $savedb = true;
        if ($savedb) {
        	$ok = $cache->save($key, $level);
        	if ($ok) {
        		try {
        			$info = array('level' => $level);
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $info);
        		} catch (Exception $e) {
        		}
        	}
        	return $ok;
        } else {
        	return $cache->update($key, $level);
        }
    }
    
    public static function updateUserAdminNum($uid, $num, $savedb = true)
    {
    	$key = 'i:u:admin_num:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);

        if ($savedb) {
        	$ok = $cache->save($key, $num);
        	if ($ok) {
        		try {
        			$info = array('admin_num' => $num);
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $info);
        		} catch (Exception $e) {
        		}
        	}
        	return $ok;
        } else {
        	return $cache->update($key, $num);
        }
    }
    
    public static function getUserTitle($uid)
    {
    	$key = 'i:u:title:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
        if (!USE_CACHE) {
			$data = false;
    	}
		
        if ($data === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $data = $dalUser->getTitle($uid);
	            if ($data) {
	            	$cache->add($key, $data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }
        
        //title,title_list
        $userTitle = array(
        	'title' => $data[0],
        	'title_list' => $data[1]
        );
        
        return $userTitle;
    }
    
    public static function updateUserTitle($uid, $titleInfo, $savedb = false)
    {
		$key = 'i:u:title:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = array($titleInfo['title'], $titleInfo['title_list']);
		
        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 900);
        }

        if ($savedb) {
        	$ok = $cache->save($key, $data);
        	if ($ok) {
        		try {
        			$info = array(
        				'title' => $titleInfo['title'], 'title_list' => $titleInfo['title_list']
        			);
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $info);
        		} catch (Exception $e) {
        			err_log($e->getMessage());
        		}
        	}
        	
        	return $ok;
        } else {
        	return $cache->update($key, $data);
        }
    }
    
    public static function gainTitle($uid, $title, $savedb = true)
    {
    	$userTitle = self::getUserTitle($uid);
    	if (!$userTitle) {
    		return false;
    	}
    	
    	$title = (int)$title;
    	$titleList = $userTitle['title_list'];
    	if (!empty($titleList)) {
    		$tmp = json_decode($titleList, true);
    		if (in_array($title, $tmp)) {
    			return false;
    		}
    	} else {
    		$tmp = array();
    	}
    	
    	$tmp[] = $title;
    	
    	$newUserTitle = array('title' => $userTitle['title'], 'title_list' => json_encode($tmp));
    	
		return self::updateUserTitle($uid, $newUserTitle, $savedb);
    }
    
    public static function changeTitle($uid, $title, $savedb = false)
    {
    	$userTitle = self::getUserTitle($uid);
    	if (!$userTitle) {
    		return false;
    	}
    	
    	$title = (int)$title;
    	$titleList = $userTitle['title_list'];
    	if (!empty($titleList)) {
    		$tmp = json_decode($titleList, true);
    		if (!in_array($title, $tmp)) {
    			return false;
    		}
    	} else {
    		return false;
    	}
    	
    	$userTitle['title'] = $title;
    	
		return self::updateUserTitle($uid, $userTitle, $savedb);
    }
    
    public static function getUserLoginInfo($uid)
    {
        $key = 'i:u:login:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	try {
	            $dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
	            $data = $dalUser->getLoginInfo($uid);
	            if ($data) {
	            	$cache->add($key, $data);
	            } else {
	            	return null;
	            }
        	} catch (Exception $e) {
        		return null;
        	}
        }

        $loginInfo = array(
        	'last_login_time' => $data[0],
        	'today_login_count' => $data[1],
        	'active_login_count' => $data[2],
        	'max_active_login_count' => $data[3],
			'all_login_count' => $data[4]
        );

        return $loginInfo;
    }

    public static function updateUserLoginInfo($uid, $loginInfo, $savedb = false)
    {

    	$key = 'i:u:login:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);

        if (!$savedb) {
        	$savedb = $cache->canSaveToDB($key, 3600);
        }

        $data = array(
    		$loginInfo['last_login_time'], $loginInfo['today_login_count'],
    		$loginInfo['active_login_count'], $loginInfo['max_active_login_count'],
			$loginInfo['all_login_count']
    	);

        if ($savedb) {
        	$ok = $cache->save($key, $data);
        	if ($ok) {
        		try {
        			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
        			$dalUser->update($uid, $loginInfo);
        		} catch (Exception $e) {
        		}
        	}

        	return $ok;
        } else {
        	return $cache->update($key, $data);
        }
    }

}