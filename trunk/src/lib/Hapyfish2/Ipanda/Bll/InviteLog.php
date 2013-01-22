<?php

class Hapyfish2_Ipanda_Bll_InviteLog
{
    public static function addInvite($actor, $target, $time, $sig)
    {
        $info = array(
            'actor' => $actor,
            'target' => $target,
            'status' => 1,
        	'sig' => $sig,
            'time' => $time
        );
        try {
        	$dalInvite = Hapyfish2_Ipanda_Dal_InviteLog::getDefaultInstance();
            $dalInvite->addInvite($info);
        }catch (Exception $e) {
            err_log($e->getMessage());
            info_log('addInvite:'.$e->getMessage(), 'err_Hapyfish2_Ipanda_Bll_InviteLog');
        }
    }

	public static function add($uid, $fid, $t = null)
	{
		$ok = false;
		if (!$t) {
			$t = time();
		}
		$info = array(
			'uid' => $uid,
			'fid' => $fid,
			'time' => $t
		);


		try {
			$dalLog = Hapyfish2_Ipanda_Dal_InviteLog::getDefaultInstance();
			$dalLog->insert($uid, $info);

			$dalUser = Hapyfish2_Ipanda_Dal_User::getDefaultInstance();
			$dalUser->update($fid, array('inviter' => $uid));

			$ok = true;

		} catch (Exception $e) {
            info_log('add:'.$e->getMessage(), 'err_Hapyfish2_Ipanda_Bll_InviteLog');
		}

		return $ok;
	}

	public static function getAll($uid)
	{
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_InviteLog::getDefaultInstance();
			return $dalLog->getAll($uid);
		} catch (Exception $e) {
		}

		return null;
	}

	public static function getAllOfFlow($uid)
	{
		//2011-02-18  开始1297958400 2011 03 09
		$time = 1299654498;
		try {
			$dalLog = Hapyfish2_Ipanda_Dal_InviteLog::getDefaultInstance();
			return $dalLog->getAllByTime($uid, $time);
		} catch (Exception $e) {
		}

		return null;
	}
}