<?php

class Hapyfish2_Ipanda_Bll_PhytotronAdmin
{
	public static function getId($uid)
	{
		$dal = Hapyfish2_Ipanda_Dal_UserSequence::getDefaultInstance();
		return $dal->get($uid,"e");
	}
	
	public static function getPhytotronAdminList($uid,$phytotron_id)
	{
       $list = Hapyfish2_Ipanda_Cache_PhytotronAdmin::getPhytotronAdminList($uid);
       $data = array();
       foreach ($list as $v)
       {
       		$row = array();
       		if($v['ipanda_user_phytotron_id'] == $phytotron_id)
       		{
       			if(!empty($v['friend_uid']))
       			{
       				$friendInfo = Hapyfish2_Platform_Bll_User::getUser($v['friend_uid']); 
       				$row['friend_pic'] 		= $friendInfo['figureurl'];
       				$row['friend_name'] 	= $friendInfo['name'];
       			}
       			else 
       			{
       				$row['friend_pic'] 		= null;
       				$row['friend_name'] 	= null;
       			}
       			$row['friend_uid'] 		= $v['friend_uid'];
       			
       		
       			$data[] = $row;
       		}
       }
       return $data;
	}
	
	//取得某个培育屋管理员数量
	public static function getPhytotronAdminCount($uid, &$adminList, $id)
	{
       $n = 0;
       foreach ($adminList as $v) {
       		if ($v['ipanda_user_phytotron_id'] == $id) {
				$n++;
       		}
       }
       
       return $n;
	}
	
	public static function addphytotronrobotadmin($uid,$phytotron_id)
	{
		$id = self::getId($uid);
		$info = array(
			"id"						=> $id,
			"uid"						=> $uid,
			"friend_uid"				=> 0,
			"ipanda_user_phytotron_id"	=> $phytotron_id,
			"status"					=> 0,
			"benefit"					=> 0,
			"create_time"				=> time(),
		);
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_PhytotronAdmin::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotronadmin-add-err');
			info_log($e->getMessage(), 'phytotronadmin-add-err');
			return false;
		}
		
		//添加到缓存
		$data = Hapyfish2_Ipanda_Cache_PhytotronAdmin::addtocache($uid, $info);
		
		return true;
	}
	
	//添加好友管理员
	public static function addphytotronfriendadmin($uid,$friend_uid,$phytotron_id)
	{
		$id = self::getId($uid);
		$info = array(
			"id"						=> $id,
			"uid"						=> $uid,
			"friend_uid"				=> $friend_uid,
			"ipanda_user_phytotron_id"	=> $phytotron_id,
			"status"					=> 0,
			"benefit"					=> 0,
			"create_time"				=> time(),
		);
		try {
			//添加到数据库
			$dal = Hapyfish2_Ipanda_Dal_PhytotronAdmin::getDefaultInstance();
			$dal->insert($uid, $info);
		} catch (Exception $e) {
			$msg = json_encode($info);
			info_log($msg, 'phytotronadmin-add-err');
			info_log($e->getMessage(), 'phytotronadmin-add-err');
			return false;
		}
		
		//添加到缓存
		$data = Hapyfish2_Ipanda_Cache_PhytotronAdmin::addtocache($uid, $info);
		
		return true;
	}
	
	public static function isphytotronadmin($uid,$phytotron_id,$friend_uid)
	{
		$list = Hapyfish2_Ipanda_Cache_PhytotronAdmin::getPhytotronAdminList($uid);
       	if(!$list || count($list) == 0 )
       	{
       		return false;
       	}
		$data = array();
       	foreach ($list as $v)
       	{
       		if($v['friend_uid'] == $friend_uid)
       		{
       			return true;
       		}
       	}
       	return false;
	}
	
	//判断是不是有还有资格担任管理员
	public static function hasAdminNum($uid)
	{
		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		$levelbasicinfo = Hapyfish2_Ipanda_Bll_BasicInfo::getUserLevelInfo($userLevel);
		if (!$levelbasicinfo) {
			return false;
		}
		
		$can_admin_num = $levelbasicinfo['admin_num'];
		$current_admin_num = Hapyfish2_Ipanda_Bll_PhytotronAdminMylog::getWorkingCount($uid);
		if ($can_admin_num <= $current_admin_num) {
			return false;
		}
		
		return true;
	}
	
	/*
	 * 
	 * 删除管理员
	 */
	public static function deleteAdmin($uid,$fuid,$phytotron_id)
	{
		try {
			//从数据库删除
			$dal = Hapyfish2_Ipanda_Dal_PhytotronAdmin::getDefaultInstance();
			$dal->deleteAdmin($uid, $fuid ,$phytotron_id);
		} catch (Exception $e) {
			$msg = "$uid, $fuid ,$phytotron_id";
			info_log($msg, 'phytotronadmin-delete-err');
			info_log($e->getMessage(), 'phytotronadmin-delete-err');
			return false;
		}
		
		//从缓存删除
		$data = Hapyfish2_Ipanda_Cache_PhytotronAdmin::deletecache($uid, $fuid ,$phytotron_id);
		
		return true;
	}
}