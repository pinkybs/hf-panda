<?php

class Hapyfish2_Ipanda_Bll_Gift
{
    private static $_errLogFile = 'err_Hapyfish2_Ipanda_Bll_Gift';

	public static function getGiftVoData($v = '1.0', $compress = false)
	{
		if (!$compress) {
			return self::restoreGift($v);
		} else {
			return self::restoreGiftCompress($v);
		}
	}

	public static function restoreGift($v = '1.0')
	{
		$file = TEMP_DIR . '/giftvo.' . $v . '.cache';
		if (is_file($file)) {
			return file_get_contents($file);
		} else {
			return self::dumpGift($v);
		}
	}

	public static function restoreGiftCompress($v = '1.0')
	{
		$file = TEMP_DIR . '/giftvo.' . $v . '.cache.zip';
		if (is_file($file)) {
			return file_get_contents($file);
		} else {
			return self::dumpGift($v, true);
		}
	}

	public static function dumpGift($v = '1.0', $compress = false)
	{
		$resultInitVo = self::getGiftVo();
		$file = TEMP_DIR . '/giftvo.' . $v . '.cache';
		$data = json_encode($resultInitVo);
		if ($compress) {
			$data = gzcompress($data, 9);
			$file .= '.zip';
		}

		file_put_contents($file, $data);
		return $data;
	}

	public static function getGiftVo()
	{
        $aryGift = array();
        $list = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftList();
        foreach ($list as $data) {
            if ($data['is_online'] == 0) {
                continue;
            }
            $aryGift[] = array(
                'type' => $data['type'],
                'lockLevel' => $data['need_lev'],
                'id' => $data['gid']
                //'name' => $data['name'],
                //'className' => $data['class_name']
            );
        }
        $resultGiftVo = array('gifts' => $aryGift);
        return $resultGiftVo;
	}

	public static function readReceive($uid)
	{
	    //has new gift
	    $mkey = 'i:u:gift:newrececnt:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        //$newReceCnt = $cache->get($mkey);
        //if (!$newReceCnt) {
        $cache->set($mkey, 0);
        //}
        return 1;
	}

	public static function getReceiveList($uid, &$newReceCnt)
	{
	    //has new gift
	    $mkey = 'i:u:gift:newrececnt:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $newReceCnt = (int)$cache->get($mkey);

	    $now = time();
	    $aryVo = array();
	    $dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
	    $list = $dalGift->getBagList($uid);
	    foreach ($list as $data) {
	        if (Hapyfish2_Platform_Bll_Friend::isFriend($uid, $data['from_uid'])) {
    	        $giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($data['gid']);
    	        if ($giftInfo && $giftInfo['is_online'] == 1) {
        	        $aryVo[] = array(
                        'id' => urlencode( base64_encode( join('|', array($data['uid'], $data['from_uid'], $data['date'], $data['method'])) ) ),
                        'date' => $data['create_time'],
                        'expTime' => (($data['create_time'] + 3600*24*5 - $now > 0) ? ($data['create_time'] + 3600*24*5 - $now) : 0),
                        'uid' => $data['from_uid'],
                        'hasGet' => (($data['status'] == 1) ? true : false),
                        'giftCid' => $data['gid'],
                        'giftType' => $giftInfo['type']
                        //'className' => $giftInfo['class_name']
                    );
    	        }
	        }
	    }
		return $aryVo;
	}

	public static function getRequestList($uid)
	{
		$now = time();
		$today = date('Ymd');
	    $aryVo = array();

        $fids = Hapyfish2_Platform_Bll_Friend::getFriendIds($uid);
        if ($fids) {
            foreach ($fids as $fid) {
                $aryGiftsVo = array();
                $mkey = 'i:u:gift:wish:' . $fid;
                $cache = Hapyfish2_Cache_Factory::getMC($fid);
                $wishCache = $cache->get($mkey);
        	    if ( $wishCache && isset($wishCache['dt']) /*&& $wishCache['dt'] == $today*/ && isset($wishCache['wish']) ) {
        	        $data = $wishCache['wish'];
        	        if ($data['gid_1']) {
	                    $giftInfo1 = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($data['gid_1']);
        	            if ($giftInfo1 && $giftInfo1['is_online'] == 1) {
        	                $aryGiftsVo[] = array($giftInfo1['gid'], $giftInfo1['type']);
        	            }
        	        }
        	        if ($data['gid_2']) {
        	            $giftInfo2 = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($data['gid_2']);
        	            if ($giftInfo2 && $giftInfo2['is_online'] == 1) {
        	                $aryGiftsVo[] = array($giftInfo2['gid'], $giftInfo2['type']);
        	            }
        	        }
        	        if ($data['gid_3']) {
        	            $giftInfo3 = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($data['gid_3']);
        	            if ($giftInfo3 && $giftInfo3['is_online'] == 1) {
        	                $aryGiftsVo[] = array($giftInfo3['gid'], $giftInfo3['type']);
        	            }
        	        }
        	        $canSend = true;
        	        if ($data['gained_1']) {
                        $aryGain1 = explode('|', $data['gained_1']);
                        if ($aryGain1 && $aryGain1[0]==$uid) {
                            $canSend = false;
                        }
        	        }
        	        if ($data['gained_2']) {
                        $aryGain2 = explode('|', $data['gained_2']);
        	            if ($aryGain2 && $aryGain2[0]==$uid) {
                            $canSend = false;
                        }
        	        }
        	        if ($data['gained_3']) {
                        $aryGain3 = explode('|', $data['gained_2']);
        	            if ($aryGain3 && $aryGain3[0]==$uid) {
                            $canSend = false;
                        }
        	        }
        	        $aryVo[] = array(
                        'id' => urlencode( base64_encode( join('|', array($uid, $fid)) ) ),
                        'date' => $data['create_time'],
                        'expTime' => (($data['create_time'] + 3600*24 - $now > 0) ? ($data['create_time'] + 3600*24 - $now) : 0),
                        'uid' => $fid,
        	            'hasGet' => $canSend,
                        'gifts' => $aryGiftsVo
                    );
                }
            }

            if ($aryVo) {
                $sortEle = array();
                foreach ($aryVo as $key => $val) {
                    $sortEle[$key]  = $val['date'];
                }
                array_multisort($sortEle, SORT_DESC, $aryVo);

            }
        }

		return $aryVo;
	}

	public static function ignore($uid, $ids)
	{

	    if (!$ids || count($ids) == 0) {
            return self::_retErrCode('-902');
	    }

	    $today = date('Ymd');
        $tm = time();
        $dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
        foreach ($ids as $id) {
            $aryId = explode('|', base64_decode(urldecode($id)));
    		if ( !$aryId || count($aryId)!=4 || !(isset($aryId[0]) && isset($aryId[1])) ) {
                return self::_retErrCode('-903');
    		}
    		if ($aryId[0] != $uid) {
                return self::_retErrCode('-904');
    		}

    		$fid = $aryId[1];
    		$dt = $aryId[2];
    		$method = $aryId[3];
            try {
                $rowBag = $dalGift->getBagInfo($uid, $fid, $dt, $method);
                if (!$rowBag || $rowBag['status']!=0) {
                    continue;
                }
                $dalGift->updateBagStatus($uid, $fid, $dt, $method, 2);
            }
    	    catch (Exception $e) {
                $errMsg = join(' ', array('Err-ignore:', $uid, 'ignor', $fid, 'date', $dt, $e->getMessage()));
                info_log($errMsg, self::$_errLogFile.$today);
    	    }
        }
        return self::_retResult();
	}

	public static function accept($uid, $ids)
	{

	    if (!$ids || count($ids) == 0) {
            return self::_retErrCode('-902');
	    }

	    $arySendGid = array();
	    $today = date('Ymd');
        $tm = time();
        $dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
        foreach ($ids as $id) {
            $aryId = explode('|', base64_decode(urldecode($id)));
    		if ( !$aryId || count($aryId)!=4 || !(isset($aryId[0]) && isset($aryId[1])) ) {
                return self::_retErrCode('-903');
    		}
    		if ($aryId[0] != $uid) {
                return self::_retErrCode('-904');
    		}

    		$fid = $aryId[1];
    		$dt = $aryId[2];
    		$method = $aryId[3];
            try {
                $rowBag = $dalGift->getBagInfo($uid, $fid, $dt, $method);
                if (!$rowBag || $rowBag['status']!=0) {
                    continue;
                }
                $dalGift->updateBagStatus($uid, $fid, $dt, $method, 1);
                $arySendGid[] = $rowBag['gid'];
            }
    	    catch (Exception $e) {
                $errMsg = join(' ', array('Err-ignore:', $uid, 'ignor', $fid, 'date', $dt, $e->getMessage()));
                info_log($errMsg, self::$_errLogFile.$today);
    	    }
        }

        //send item or decor to bag
        $aryCard = array();
        $aryItem = array();
        $aryDecor = array();
        $aryBuilding = array();
        foreach ($arySendGid as $gid) {
            $rowGift = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($gid);
            if (!$rowGift) {
                continue;
            }
            if (1 == $rowGift['type']) {
                $aryCard[] = array($rowGift['gid'], 1);
            }
            else if (2 == $rowGift['type']) {
                $aryItem[] = array($rowGift['gid'], 1);
            }
            else if (3 == $rowGift['type']) {
                $aryDecor[] = array($rowGift['gid'], 1);
            }
            else {
                $aryBuilding[] = array($rowGift['gid'], 1);
            }
        }

        $sendAward = new Hapyfish2_Ipanda_Bll_Award();
	    if ($aryCard && count($aryCard) > 0) {
            $sendAward->setCardList($aryCard);
        }
        if ($aryItem && count($aryItem) > 0) {
            $sendAward->setMaterialList($aryItem);
        }
        if ($aryDecor && count($aryDecor) > 0) {
            $sendAward->setDecorateList($aryDecor);
        }
	    if ($aryBuilding && count($aryBuilding) > 0) {
            $sendAward->setBuildingList($aryBuilding);
        }
        if (count($aryCard) > 0 || count($aryItem) > 0 || count($aryDecor) > 0 || count($aryBuilding) > 0) {
            $rst = $sendAward->sendOne($uid);
        }

        return self::_retResult();
	}

	public static function send($uid, $gid, $fids)
	{
        $msg = self::_sendToBag($uid, $gid, $fids, 0);

        if ($msg) {
            return self::_retErrCode($msg);
        }

		return self::_retResult();
	}

	public static function sendWish($id, $gid)
	{

		$aryId = explode('|', base64_decode(urldecode($id)));
		if ( !(isset($aryId[0]) && isset($aryId[1])) ) {
            return self::_retErrCode('-901');
		}
	    //check gid validate
		$giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($gid);
		if (!$giftInfo) {
            return self::_retErrCode('-902');
		}

		$uid = $aryId[0];
		$fid = $aryId[1];

		$today = date('Ymd');
		$tm = time();
		$mkey = 'i:u:gift:wish:' . $fid;
        $cache = Hapyfish2_Cache_Factory::getMC($fid);
        $wishCache = $cache->get($mkey);
        if (!$wishCache /*|| $wishCache['dt'] != $today*/) {
            return self::_retErrCode('-905');
        }
        $rowWish = $wishCache['wish'];
        $dealt = 0;
        if ($rowWish['gid_1'] == $gid) {
            $dealt = 1;
        }
	    else if ($gid == $rowWish['gid_2']) {
	        $dealt = 2;
	    }
	    else if ($gid == $rowWish['gid_3']){
	        $dealt = 3;
	    }
	    if (!$dealt) {
            return self::_retErrCode('-901');
	    }
	    /*if (!empty($rowWish['gained_'.$dealt])) {
            return self::_retErrCode('some_one_has_already_send_wish_to_him');
	    }*/


		try {
		    //update wish
		    $info = array('gained_'.$dealt => ($uid.'|'.$tm));
		    //$dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
		    //$dalGift->updateWish($fid, $info);
		    //update wish cache
		    $rowWish['gained_'.$dealt] = ($uid.'|'.$tm);
		    $wishCache = array('dt' => $wishCache['dt'], 'wish' => $rowWish);
            $cache->set($mkey, $wishCache);

            /*
		    //update friend wish
		    $rowFriendWish = $dalGift->getFriendWish($uid, $fid);
		    if ( !$rowFriendWish || date('Ymd', $rowFriendWish['create_time'])!=$today
		        || ($rowFriendWish['gid_1'] != $gid && $rowFriendWish['gid_2'] != $gid && $rowFriendWish['gid_3'] != $gid) ) {
                return self::_retErrCode('-901');
		    }

		    $info = array('dealt' => $dealt);
		    $dalGift->updateFriendWish($uid, $fid, $info);*/
		}
	    catch (Exception $e) {
            $errMsg = join(' ', array('Err-sendWish:', $uid, 'sendbackWish:', $gid, 'to', $fid, $e->getMessage()));
            info_log($errMsg, self::$_errLogFile.$today);
            return self::_retErrCode('-1');
	    }

        $msg = self::_sendToBag($uid, $gid, array($fid), 1);
        if ($msg) {
            return self::_retErrCode($msg);
        }

		return self::_retResult();
	}

    //method 0 or 1 2 3
	private static function _sendToBag($uid, $gid, $fids, $method)
	{
	    //empty fids
        if (!$fids || count($fids) == 0) {
            return '-906';
        }
        $fids = array_unique($fids);

        //check gid validate
		$giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($gid);
		if (!$giftInfo || $giftInfo['is_online'] == 0) {
            return '-902';
		}
		//check user level
		$userLev = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		if (!$userLev || $userLev<$giftInfo['need_lev']) {
            return '-907';
		}

		$today = date('Ymd');
		//read today sent uids cache
		if (0 == $method) {
		    //送礼
	        $mkey = 'i:u:gift:sent:g:uids:' . $uid;
		}
		else {
		    //帮忙实现愿望
		    $mkey = 'i:u:gift:sent:w:uids:' . $uid;
		}
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $sentCache = $cache->get($mkey);
	    if ( $sentCache && isset($sentCache['dt']) && $sentCache['dt'] == $today && isset($sentCache['ids']) ) {
            $sentUids = $sentCache['ids'];
            /*if ( count($sentUids) >= 20 || (count($fids) + count($sentUids) > 20) ) {
                return '-908';
            }*/
        }
        else {
            $sentUids = array();
        }

		//send gift
		$dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();

		foreach ($fids as $fid) {
		    //check is app user
		    if (!Hapyfish2_Ipanda_Cache_User::isAppUser($fid)) {
		        continue;
		    }
		    //check is friend
		    if (!Hapyfish2_Platform_Bll_Friend::isFriend($uid, $fid)) {
		        continue;
		    }
		    //check if is sent today
		    if (in_array($fid, $sentUids)) {
		        continue;
		    }

		    $info = array(
		    		'uid' => $fid,
		    		'from_uid' => $uid,
		    		'date' => $today,
		    		'method' => $method,
		    		'gid' => $gid,
		    		'status' => 0,
		    		'create_time' => time()
		    );

		    try {
		        $rowBag = $dalGift->getBagInfo($fid, $uid, $today, $method);
		        if (empty($rowBag)) {
		            $dalGift->insertBag($fid, $info);
		        }
		        else {
		            $dalGift->updateBag($fid, $uid, $today, $method, $info);
		        }
		        $sentUids[] = $fid;
		        $mkey2 = 'i:u:gift:newrececnt:' . $fid;
                $cache2 = Hapyfish2_Cache_Factory::getMC($fid);
                $cache2->increment($mkey2, 1);
		    }
		    catch (Exception $e) {
		        $errMsg = join(' ', array('Err-send:', $uid, 'sendGift', $gid, 'to', $fid, 'method:', $method, $e->getMessage()));
                info_log($errMsg, self::$_errLogFile.$today);
		    }
		}//end for

		//update today sent uids cache
        $sentCache = array('dt' => $today, 'ids' => $sentUids);
        $cache->set($mkey, $sentCache, 3600*24);

        return $errMsg;
	}

	public static function getMywish($uid)
	{
        $today = date('Ymd');
		//read today wish cache
	    $mkey = 'i:u:gift:wish:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $wishCache = $cache->get($mkey);
        if ($wishCache) {
            $rowWish = $wishCache['wish'];
        }
        else {
            try {
                $dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
    		    $rowWish = $dalGift->getWish($uid);
    		}
    	    catch (Exception $e) {
                $errMsg = join(' ', array('getMywish:', $uid, 'getWish:', $e->getMessage()));
                info_log($errMsg, self::$_errLogFile.$today);
                return self::_retErrCode('-1');
    	    }
        }
        return $rowWish;
	}

	public static function mywish($uid, $gids)
	{

	    //empty gids
        if (!$gids || count($gids) == 0) {
            return self::_retErrCode('-902');
        }

        $gids = array_unique($gids);

        //gid count limit
        if (count($gids) > 3) {
            return self::_retErrCode('-909');
        }

        $userLev = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
        foreach ($gids as $gid) {
            if ($gid == 0) { continue; }
            //check gid validate
    		$giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($gid);
    		if (!$giftInfo) {
                return self::_retErrCode('-902');
    		}
    		//check user level
    		if (!$userLev || $userLev<$giftInfo['need_lev']) {
                return self::_retErrCode('-907');
    		}
        }

        $today = date('Ymd');
		//read today wish cache
	    $mkey = 'i:u:gift:wish:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $wishCache = $cache->get($mkey);
	    if ( $wishCache && isset($wishCache['dt']) && $wishCache['dt'] == $today ) {
            return self::_retErrCode('-910');
        }

        $tm = time();
	    //set my wish today
		$dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();

		//set today's wish
		$update = false;
		try {
		    $rowWish = $dalGift->getWish($uid);
		}
	    catch (Exception $e) {
            $errMsg = join(' ', array('Err-mywish:', $uid, 'getWish:', json_encode($gids), $e->getMessage()));
            info_log($errMsg, self::$_errLogFile.$today);
            return self::_retErrCode('-1');
	    }
		if (!empty($rowWish)) {
		    if (date('Ymd', $rowWish['create_time']) == $today) {
		        $wishCache = array('dt' => $today, 'wish' => $rowWish);
                $cache->set($mkey, $wishCache);
                return self::_retErrCode('-910');
		    }
		    $update = true;
		}
	    $wish = array(
	    	'fids' => '',
            'gid_1' => (isset($gids[0]) ? $gids[0] : 0),
            'gid_2' => (isset($gids[1]) ? $gids[1] : 0),
            'gid_3' => (isset($gids[2]) ? $gids[2] : 0),
            'gained_1' => '',
            'gained_2' => '',
            'gained_3' => '',
            'create_time' => $tm
        );
        try {
    		if ($update) {
                $dalGift->updateWish($uid, $wish);
    		}
    		else {
    		    $wish['uid'] = $uid;
    		    $dalGift->insertWish($uid, $wish);
    		}
        }
	    catch (Exception $e) {
	        $errMsg = join(' ', array('Err-mywish:', $uid, 'setWishs:', json_encode($gids), $e->getMessage()));
            info_log($errMsg, self::$_errLogFile.$today);
            return self::_retErrCode('-1');
	    }

		//update today wish cache
        $wishCache = array('dt' => $today, 'wish' => $wish);
        $cache->set($mkey, $wishCache);

        /*//send wish to friends
        foreach ($fids as $fid) {
		    //check is app user
		    if (!Hapyfish2_Ipanda_Cache_User::isAppUser($fid)) {
		        continue;
		    }
		    //check is friend
		    if (!Hapyfish2_Platform_Bll_Friend::isFriend($uid, $fid)) {
		        continue;
		    }

		    //sent wish to friend
		    $info = array(
		    		'uid' => $fid,
		    		'from_uid' => $uid,
		    		'gid_1' => (isset($gids[0]) ? $gids[0] : 0),
		    		'gid_2' => (isset($gids[1]) ? $gids[1] : 0),
		    		'gid_3' => (isset($gids[2]) ? $gids[2] : 0),
		    		'dealt' => 0,
		    		'create_time' => $tm
		    );

		    try {
		        $rowRequireWish = $dalGift->getFriendWish($fid, $uid);
		        if (empty($rowRequireWish)) {
		            $dalGift->insertFriendWish($fid, $info);
		        }
		        else {
		            //if (date('Ymd', $rowRequireWish['create_time']) < $today) {
		            $dalGift->updateFriendWish($fid, $uid, $info);
		            //}
		        }
		    }
		    catch (Exception $e) {
		        $errMsg = join(' ', array('Err-mywish:', $uid, 'sendRequiregiftWish:', json_encode($gids), 'to', $fid, $e->getMessage()));
                info_log($errMsg, self::$_errLogFile.$today);
		    }
		}//end for*/

		return self::_retResult();
	}


	private static function _retErrCode($code)
	{
	    return array('status' => -1, 'errCode'=>$code);
	}

    private static function _retResult($data = null)
	{
	    if ($data) {
	        return array('status' => 1, 'data' => $data);
	    }
	    else {
	        return array('status' => 1);
	    }
	}
}