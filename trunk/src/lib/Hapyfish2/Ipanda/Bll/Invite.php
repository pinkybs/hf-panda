<?php

class Hapyfish2_Ipanda_Bll_Invite
{

    public static function add($inviteUid, $newUid, $time = null)
	{
		if (!$time) {
			$time = time();
		}

		Hapyfish2_Ipanda_Bll_InviteLog::add($inviteUid, $newUid, $time);
        $targetuser = Hapyfish2_Platform_Bll_User::getUser($newUid);

        //todo add invite reward

		//add card
        //$ok = Hapyfish2_Ipanda_HFC_Card::addUserCard($inviteUid, 26341, 1);
		/*$ok = Hapyfish2_Ipanda_Bll_StarFish::add($inviteUid,3,'');
		$title = '你成功邀请用户<font color="#379636">'.$targetuser['name'].'</font>，获得系统奖励<font color="#FF0000">1000金币</font>和 <font color="#9F01A0">3个海星</font>,赶快去海星商城看下吧！';
		if ($ok) {
			$feed = array(
				'uid' => $inviteUid,
				'actor' => $inviteUid,
				'target' => $newUid,
				'template_id' => 0,
//				'title' => array('cardName' => '加速卡II'),
				'title' => array('title' => $title),
				'type' => 3,
				'create_time' => time()
			);
			Hapyfish2_Ipanda_Bll_Feed::insertMiniFeed($feed);
		} else {
			info_log('[' . $inviteUid . ':' . $newUid, 'invite_failure');
		}*/

	}

}