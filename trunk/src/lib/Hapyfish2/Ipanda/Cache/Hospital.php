<?php

class Hapyfish2_Ipanda_Cache_Hospital
{
	public static function getBasicMC()
	{
		$key = 'mc_0';
		return Hapyfish2_Cache_Factory::getBasicMC($key);
	}
	//医院
	public static function getHospital()
	{
		$key = 'i:u:h:h:config';
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$tpl = $localcache->get($key);
		if (!$tpl) {
			$cache = self::getBasicMC();
			$tpl = $cache->get($key);
			if (!$tpl) {
				$tpl = self::loadHospital();
			}
			if ($tpl) {
				$localcache->set($key, $tpl);
			}
		}
		return $tpl;
	}
	
	public static function loadHospital()
	{
		$db = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
		$tpl = $db->getHospital();
		if ($tpl) {
			$data = array();
			foreach($tpl as $k => $v){
				$cost = array();
				$reward = array();
				$consumption = json_decode($v['cost'], true);
				$rewardList = json_decode($v['reward'], true);
				foreach($consumption as $k1 => $v1){
					$list['cid'] = $v1['cid'];
					$list['num'] = $v1['num'];
					$cost[] = $list;
				}
				foreach($rewardList as $k2=>$v2){
					$r['cid'] = $v2['cid'];
					$r['num'] = $v2['num'];
					$reward[] = $r;
				}
				$v['cost'] = $cost;
				$v['reward'] =  $reward;
				$data[$v['id']] = $v;
			}
			$key = 'i:u:h:h:config';
			$cache = self::getBasicMC();
			$cache->set($key, $data);
		}
		return $data;
	}
	
	//疾病
	public static function getDisease()
	{
		$key = 'i:u:h:d:config';
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$tpl = $localcache->get($key);
		if (!$tpl) {
			$cache = self::getBasicMC();
			$tpl = $cache->get($key);
			if (!$tpl) {
				$tpl = self::loadDisease();
			}
			if ($tpl) {
				$localcache->set($key, $tpl);
			}
		}
		return $tpl;
	}
	
	public static function loadDisease()
	{
		$db = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
		$tpl = $db->getDisease();
		if ($tpl) {
			$data = array();
			foreach($tpl as $k => $v){
				$cost = array();
				$reward = array();
				$consumption = json_decode($v['cost']);
				$rewardList = json_decode($v['reward']);
				foreach($consumption as $k1 => $v1){
					$list['cid'] = $v1->cid;
					$list['num'] = $v1->num;
					$cost[] = $list;
				}
				foreach($rewardList as $k2=>$v2){
					$r['cid'] = $v2->cid;
					$r['num'] = $v2->num;
					$reward[] = $r;
				}
				$v['cost'] = $cost;
				$v['reward'] = $reward;
				$data[$v['id']] = $v;
			}
			$key = 'i:u:h:d:config';
			$cache = self::getBasicMC();
			$cache->set($key, $data);
		}
		return $data;
	}
	//草药
	public static function getDrug()
	{
		$key = 'i:u:h:drug:config';
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$tpl = $localcache->get($key);
		if (!$tpl) {
			$cache = self::getBasicMC();
			$tpl = $cache->get($key);
			if (!$tpl) {
				$tpl = self::loadDrug();
			}
			if ($tpl) {
				$localcache->set($key, $tpl);
			}
		}
		return $tpl;
	}
	
	public static function loadDrug()
	{
		$db = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
		$tpl = $db->getDrug();
		if ($tpl) {
			$data = array();
			foreach($tpl as $k => $v){
				$cost = array();
				$consumption = json_decode($v['cost']);
				foreach($consumption as $k1 => $v1){
					$list['cid'] = $v1->cid;
					$list['num'] = $v1->num;
					$cost[] = $list;
				}
				$v['cost'] = $cost;
				$data[$v['cid']] = $v;
			}
			$key = 'i:u:h:drug:config';
			$cache = self::getBasicMC();
			$cache->set($key, $data);
		}
		return $data;
	}
	
	public static function getUserDNum($uid)
	{
		$key = 'i:u:h:u:d:n'.$uid;
	 	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
		$data = $cache->get($key);
        if ($data === false) {
        	try {
	            $list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
	            $data = 0;
	            if($list){
	            	foreach($list as $k => $v){
	            		$info = Hapyfish2_Ipanda_Bll_BasicInfo::getDecorateInfo($v['cid']);
	            		if($info['act_name'] == 'disease')
	            		{
	            			$data +=1;
	            		}
	            	
	            	}
	            }
	           $cache->save($key, $data);
        	} catch (Exception $e) {
        		return null;
        	}
        }
        return $data;
	}
	
	public static function updateUserDNum($uid, $num)
	{
		$key = 'i:u:h:u:d:n'.$uid;
	 	$cache = Hapyfish2_Cache_Factory::getHFC($uid);
	 	$cache->save($key, $num);
	}
	
	public static function update($uid, $data)
 	{
 		$key = 'i:u:h:d:'.$uid.':'.$data['id'];
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		$cache->save($key, $data);
 		$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
 		try{
 			$dal->updateUuserdis($data);
 		}catch(Exception $e) {
			return false;
		}
 	}
 	
 	public static function getUserDisAni($uid, $id)
 	{
 		$key = 'i:u:h:d:'.$uid.':'.$id;
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		$data = $cache->get($key);
 		if($data === false)
 		{
 			$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
	 		try{
	 			$data = $dal->getUserDis($uid, $id);
	 			if($data){
	 				$cache->save($key, $data);
	 			}
	 		}catch(Exception $e) {
				return false;
			}
 		}
 		return $data;
 	}
 	
 	public function getDisList($uid, $ids = 0)
 	{
 		$keys = array();
 		$list = array();
 		if($ids == 0){
 			$ids = self::getUderDisAniList($uid);
 		}
        foreach ($ids as $id) {
        	$keys[] = 'i:u:h:d:' . $uid . ':' . $id;
        }
        $cache = Hapyfish2_Cache_Factory::getHFC($uid);
        $data = $cache->getMulti($keys);
        if ($data === false) {
        	return null;
        }
       foreach($data as $k=>$v){
           if($v){
       	       $list[$v['id']] = $v;
           }
       }
       return $list;
 	}
 	
 	public static function getUderDisAniList($uid)
 	{
 		$key = 'ipanda:u:h:s:id:List'.$uid;
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		$list = $cache->get($key);
 		if($list === false){
 			$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
	 		try{
	 			$list = $dal->getUserDisList($uid);
	 			if($list){
	 				$cache->save($key, $list);
	 			}
	 		}catch(Exception $e) {
				return false;
			}
 		}
 		return $list;
 	}
 	
 	public static function updateIds($uid, $ids)
 	{
 		$key = 'ipanda:u:h:s:id:List'.$uid;
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		$list = $cache->save($key, $ids);
 	}
 	
 	public static function getUserHospital($uid)
 	{
 		$key = 'ipanda:u:h:h:all'.$uid;
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		$data = $cache->get($key);
 		if($data === false){
 			try{
 				$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
 				$list = $dal->getUserHospital($uid);
 				if($list){
 					foreach($list as $k=> $v){
 						$data[$v['id']] = $v;
 					}
 				}else{
 					for($i=1;$i<=4;$i++){
 						$data[$i]['uid'] = $uid;
 						$data[$i]['id'] = $i;
 						$data[$i]['drug_id'] = 0;
 						$data[$i]['end_time'] = 0;
 						$data[$i]['stage'] = 0;
 					} 
 					$cache->save($key, $data);
 				}
 			}catch (Exception $e){
 				return null;
 			}
 		}
 		return $data;
 	}
 	
 	public static function updateUserHospital($uid, $data)
 	{
 		$key = 'ipanda:u:h:h:all'.$uid;
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		$cache->save($key, $data);
 		try{
 			$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
 			foreach($data as $k=>$v){
 				$dal-> updateUserHospital($v);
 			}
 		}catch (Exception $e){
 		}
 	}
 	
 	public static function delIds($uid, $ids)
 	{
 		if(!is_array($ids)){
 			return false;
 		}
 		$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
 		$cache = Hapyfish2_Cache_Factory::getHFC($uid);
 		foreach($ids as $k=>$id){
 			$key = 'i:u:h:d:' . $uid . ':' . $id;
 			$cache->delete($key);
 			$dal->deleteUserDis($uid, $id);
 		}
 		return true;
 	}
}