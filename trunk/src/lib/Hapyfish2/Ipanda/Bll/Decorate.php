<?php

class Hapyfish2_Ipanda_Bll_Decorate
{
	public static function getId($uid)
	{
		$dal = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
		return $dal->getId($uid, 'decorate');
	}
	
	public static function getList($uid, $highcache = false)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid, $highcache);
		if (empty($list)) {
			return $data;
		}

		$t = time();
		foreach ($list as $v) {
			if (isset($v['end_time']) && $v['end_time'] > 0) {
				//放60秒缓冲时间
				if ($v['end_time'] < $t - 60) {
					$ok = self::remove($uid, $v['id']);
					if ($ok) {
						if (1 == $v['status']) {
							Hapyfish2_Ipanda_Bll_MapGrid::removeDecorate($uid, $v['id'], $v['cid'], $v['x'], $v['z'], $v['mirro']);
						}
						info_log('[' . $uid . ']' . $v['id'] . '-' . $v['cid'], 'expire-decorate-remove');
					}
					continue;
				}
			}
			
			if (0 == $v['status']) {
       			continue;
       		}
			
			if (!isset($v['mirro'])) {
				$v['mirro'] = 0;
			}
			
     		$data[] = array(
       			'id' 		=> $v['id'],
       			'cid'		=> $v['cid'],
       			'x' 		=> $v['x'],
       			'z' 		=> $v['z'],
     			'mirro' 	=> $v['mirro']
       		);
		}
		
		return $data;
	}
	
	public static function getDepotList($uid)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
		if (empty($list)) {
			return $data;
		}

		$t = time();
		foreach ($list as $v) {
       		if (1 == $v['status']) {
       			continue;
       		}
			$expire = -1;
			if (isset($v['end_time']) && $v['end_time'] > 0) {
				$expire = $v['end_time'] - $t;
				if ($expire < 0) {
					$expire = 0;
					continue;
				}
			}
       		$data[] = array(
       			'id' 	=> $v['id'],
       			'cid'	=> $v['cid'],
       			'expire' => $expire
       		);
		}

		return $data;
	}
	
	public static function getInfo($uid, $id)
	{
		$list = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
		if (empty($list)) {
			return null;
		}
		
		foreach ($list as $v) {
			if($v['id'] == $id) {
				return $v;
			}
		}
		
		return null;
	}
	
	public static function add($uid, &$info)
	{
		$info['id'] = self::getId($uid);
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'decorate-insert-err');
			info_log($e->getMessage(), 'decorate-insert-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Decorate::addtocache($uid, $info);
		
		return $info['id'];
	}
	
	public static function update($uid, $id, $info)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
			$dal->update($uid,$id, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'decorate-update-err');
			info_log($e->getMessage(), 'decorate-update-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Decorate::updatetocache($uid, $id, $info);
		
		return true;
	}
	
	public static function remove($uid, $id)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
			$dal->delete($uid,$id);
		} catch (Exception $e) {
		
			info_log($e->getMessage(), 'decorate-delete-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Decorate::deletecache($uid, $id);
		
		return true;
	}
	
	public static function updateAll($uid, $info)
	{
		//更新数据库
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_Decorate::getDefaultInstance();
			$dal->updateAll($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'Decorate-update-err');
			info_log($e->getMessage(), 'Decorate-update-err');
			return false;
		}
		//更新缓存 
		Hapyfish2_Ipanda_Cache_Decorate::updatealltocache($uid, $info);
		
		return true;
	}
}