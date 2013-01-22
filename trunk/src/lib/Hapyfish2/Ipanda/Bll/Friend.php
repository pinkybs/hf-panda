<?php

class Hapyfish2_Ipanda_Bll_Friend
{
	public static function getRankList($uid, $pageIndex = 1, $pageSize = 50)
	{
		$friendList = array();
		$friendList[] = array(
			'uid' => GM_UID_LELE,
			'isGM' => 1,
			'gotGift' => 1,
			'name' => GM_NAME_LELE,
			'face' => STATIC_HOST . '/' . GM_FACE_LELE,
			'exp' => 999999999,
			'level' => 99,
			'canSteal' => 0
		);

		$fids = Hapyfish2_Platform_Bll_Friend::getFriendIds($uid);
		if (empty($fids)) {
			$fids = array($uid);
		} else {
			$fids[] = $uid;
		}

		foreach ($fids as $fid) {
			$userInfo = Hapyfish2_Ipanda_HFC_User::getUser($fid, array('exp' => 1, 'level' => 1));
			if ($userInfo) {
				$info = Hapyfish2_Platform_Bll_User::getUser($fid);
				$friendList[] = array(
					'uid' => $fid,
					'isGM' => 0,
					'gotGift' => 1,
					'name' => $info['name'],
					'face' => $info['figureurl'],
					'exp' => $userInfo['exp'],
					'level' => $userInfo['level'],
					'needEmployee' => 1,
					'canSteal' => 0,
					'canRent' => 0,
				);
			}
		}

		return array('friends' => $friendList, 'maxPage' => 1);
	}

	public static function getNotAdminFriendList($uid,$phytotron_id ,$pageIndex = 1, $pageSize = 50)
	{
        require_once(CONFIG_DIR . '/language.php');
		$friendList = array();

		$fids = Hapyfish2_Platform_Bll_Friend::getFriendIds($uid);
		foreach ($fids as $fid) {
			$userInfo = Hapyfish2_Ipanda_HFC_User::getUser($fid, array('exp' => 1, 'level' => 1));
			//判断不是当前的培育屋的管理员，也还有当管理员资格的好友
			$is_admin = Hapyfish2_Ipanda_Bll_PhytotronAdmin::isphytotronadmin($uid,$phytotron_id,$fid);
			$can_admin = Hapyfish2_Ipanda_Bll_PhytotronAdmin::hasAdminNum($fid);
			if ($userInfo && !$is_admin && $can_admin) {
				$info = Hapyfish2_Platform_Bll_User::getUser($fid);
				$friendList[] = array(
					'uid' => $fid,
					'name' => $info['name'],
					'face' => $info['figureurl'],
					'exp' => $userInfo['exp'],
					'level' => $userInfo['level'],
				);
			}
		}

		return array('friends' => $friendList, 'maxPage' => 1);
	}
	public static function getNeedAdminFriendList($uid, $pageIndex = 1, $pageSize = 50)
	{
        require_once(CONFIG_DIR . '/language.php');
		$friendList = array();

		$fids = Hapyfish2_Platform_Bll_Friend::getFriendIds($uid);
		foreach ($fids as $fid) {
			$userInfo = Hapyfish2_Ipanda_HFC_User::getUser($fid, array('exp' => 1, 'level' => 1));
			$isNeedAdmin = Hapyfish2_Ipanda_Bll_Phytotron::isNeedAdmin($fid);
			if ($userInfo && $isNeedAdmin) {
				$info = Hapyfish2_Platform_Bll_User::getUser($fid);
				$friendList[] = array(
					'uid' => $fid,
					'name' => $info['name'],
					'face' => $info['figureurl'],
					'exp' 		=> $userInfo['exp'],
					'level' 	=> $userInfo['level'],
				);
			}
		}

		return array('friends' => $friendList, 'maxPage' => 1);
	}
	public static function isFriend($uid, $friend_uid)
	{
		$fids = Hapyfish2_Platform_Bll_Friend::getFriendIds($uid);
		if(in_array($friend_uid,$fids))
		{
			return true;
		}
		return false;
	}


    public static function getFriendList($uid, $pageIndex = 1, $pageSize = 50)
	{
		$friendList = array();

		$fids = Hapyfish2_Platform_Bll_Friend::getFriendIds($uid);
        if ($fids) {
    		foreach ($fids as $fid) {
    			$userInfo = Hapyfish2_Ipanda_HFC_User::getUser($fid, array('exp' => 1, 'level' => 1));
    			if ($userInfo) {
    				$info = Hapyfish2_Platform_Bll_User::getUser($fid);
    				$friendList[] = array(
    					'uid' => $fid,
    					'name' => $info['name'],
    					'face' => $info['figureurl'],
    					'exp' => $userInfo['exp'],
    					'level' => $userInfo['level']
    				);
    			}
    		}
        }

		return array('friends' => $friendList, 'maxPage' => 1);
	}
}