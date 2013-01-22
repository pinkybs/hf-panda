<?php

class Hapyfish2_Ipanda_Bll_PhytotronAdminMylog
{
	public static function getId($uid)
	{
		$dal = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
		return $dal->getId($uid, "phytotronAdminMylog");
	}
	
	//获得正在担任管理的数量
	public static function getWorkingCount($uid)
	{
      	$num = 0;
		$list = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::getList($uid);
		if (empty($list)) {
			return $num;
		}
      	
       	foreach ($list as $v) {
       		if ($v['status'] == 0) {
       			$num++;
       		}		
       	}
       	
		return $num;
	}
	
	public static function sendadmininvite($uid,$info)
	{
		$id = self::getId($uid);
		$info['id'] = $id;
		
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_PhytotronAdminMylog::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotronadminmylog-add-err');
			info_log($e->getMessage(), 'phytotronadminmylog-add-err');
			return false;
		}
		
		//添加到缓存
		$data = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::addtocache($uid, $info);
		return true;
	}
	
	//我的管理员相关的信息
	public static function getList($uid, $type)
	{
		$data = array();
		$list = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::getList($uid);
		if (empty($list)) {
			return $data;
		}
		
		foreach ($list as $v) {
       		//1 是应聘的信息  当取 应聘信息时，过滤掉其它的信息 反之过滤掉不是应聘的信息
       		if (empty($v['id'])) {
       			continue;
       		}
       		
       		if ($type == 1) {
       			if ($v['status'] != 1) {
       				continue;
       			}
       		} else if (empty($type)) {
       			if ($v['status'] == 1 || $v['status'] == 5) {
       				continue;
       			}
       		}
       		
       		$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronInfo($v['friend_uid'], $v['phytotron_id']);
       		if (!$phytotron) {
       			self::remove($uid, $v['id']);
       			continue;
       		}
       		
       		$animal = Hapyfish2_Ipanda_Bll_PhytotronAnimal::getOnePhytotronAnimal($v['friend_uid'], $phytotron['ipanda_user_phytotron_animal_id']);
       		$product = json_decode( $phytotron['product_time_type'] );
       		$time_type =  $phytotron['product_time_type'];
       		$time_type = empty($time_type)? 0 : $time_type;
       		if (empty($type)) {
       			$benefit = $v['benefit'];
       		} else {
	       		$benefit = ceil($product[$time_type]['num']/10);
	       		$benefit = $benefit > 0 ? $benefit : 1;
       		}
       		if(empty($benefit))
       		{
       			$benefit = 0;
       		}
       		
       		$friendInfo = Hapyfish2_Platform_Bll_User::getUser($v['friend_uid']);
       		$data[] = array(
       			'id'			=> $v['id'],
       			'friend_uid'	=> $v['friend_uid'],
       			'friend_name'	=> $friendInfo['name'],
       			'friend_face'	=> $friendInfo['figureurl'],
       			'status'		=> $v['status'], //0:正常担当;1受聘;3离职4:应聘不成功过期，5 忽略
       			'animal'		=> $animal['animal_name'],
       			'benefit'		=> $benefit
       		);	
       	}
       	
       	return $data;
	}
	
	public static function getLogInfo($uid,$log_id)
	{
		$list = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::getList($uid);
		if (empty($list)) {
			return null;
		}
		
		foreach ($list as $v) {
       		if($v['id'] == $log_id) {
       			return $v;
       		}
       	}
       	
       	return null;
	}
	
	public static function changelogstatus($uid,$log_id,$status)
	{
		//更新数据库
		$dal = Hapyfish2_Ipanda_Dal_PhytotronAdminMylog::getDefaultInstance();
		$info = array("status" => $status,"update_time" => time());
		try {
			$dal->update($uid, $log_id, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotronadminmylog-update-err');
			info_log($e->getMessage(), 'phytotronadminmylog-update-err');
			return false;
		}
		//更新缓存
		$data = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::updatetocache($uid, $log_id, $info);
		return true;
	}
	
	public static function remove($uid,$log_id)
	{
		//更新数据库
		
		$dal = Hapyfish2_Ipanda_Dal_PhytotronAdminMylog::getDefaultInstance();
		try {
			$dal->delete($uid, $log_id);
			$data = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::deletecache($uid, $log_id);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotronadminmylog-remove-err');
			info_log($e->getMessage(), 'phytotronadminmylog-remove-err');
			return false;
		}
		//更新缓存
		
		return true;
	}
	
	public static function updatelogbyphytotronid($uid,$fuid,$phytotron_id,$info)
	{
		//更新数据库
		$dal = Hapyfish2_Ipanda_Dal_PhytotronAdminMylog::getDefaultInstance();
		
		try {
			
			$dal->updateBenefit($uid,$fuid,$phytotron_id, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotronadminmylog-update-err');
			info_log($e->getMessage(), 'phytotronadminmylog-update-err');
			return false;
		}
		//更新缓存
		$data = Hapyfish2_Ipanda_Cache_PhytotronAdminMylog::updatetocachebyphytotronid($uid ,$fuid,$phytotron_id, $info);
		return true;
	}
}