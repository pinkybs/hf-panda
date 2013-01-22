<?php

class GiftController extends Hapyfish2_Controller_Action_Api
{

	public function listAction()
	{
		header("Cache-Control: max-age=2592000");
    	echo Hapyfish2_Ipanda_Bll_Gift::getGiftVoData();
		exit;
	}

	public function userAction()
	{
		$uid = $this->uid;
		$newReceCnt = 0;
		$receiveList = Hapyfish2_Ipanda_Bll_Gift::getReceiveList($uid, $newReceCnt);
		$requestList = Hapyfish2_Ipanda_Bll_Gift::getRequestList($uid);

		//can send wish today
		$canWish = true;
	    $today = date('Ymd');
		//read today wish cache
        //get my wish
		$wishCache = Hapyfish2_Ipanda_Bll_Gift::getMywish($uid);
	    if ( $wishCache && isset($wishCache['create_time']) && date('Ymd', $wishCache['create_time']) == $today ) {
            $canWish = false;
        }
        $giftMyWish = array();
        for ($i=0; $i<3; $i++) {
            $giftMyWish[] = array('id' => 0, 'type' => 0);
        }
        if ($wishCache) {
            if ($wishCache['gid_1']) {
                $giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($wishCache['gid_1']);
                if ($giftInfo && $giftInfo['is_online'] == 1) {
                    $giftMyWish[0] = array('id' => $giftInfo['gid'], 'type' => $giftInfo['type']);
                }
            }
            if ($wishCache['gid_2']) {
                $giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($wishCache['gid_2']);
                if ($giftInfo && $giftInfo['is_online'] == 1) {
                    $giftMyWish[1] = array('id' => $giftInfo['gid'], 'type' => $giftInfo['type']);
                }
            }
            if ($wishCache['gid_3']) {
                $giftInfo = Hapyfish2_Ipanda_Cache_Gift::getBasicGiftInfo($wishCache['gid_3']);
                if ($giftInfo && $giftInfo['is_online'] == 1) {
                    $giftMyWish[2] = array('id' => $giftInfo['gid'], 'type' => $giftInfo['type']);
                }
            }
        }

        $hasNewGift = $newReceCnt ? true : false;

		$giftUser = array('giftNum' => $newReceCnt, 'giftRequestNum' => count($requestList),
						  'isReleaseWish' => $canWish, 'isNewGift' => $hasNewGift);

		$rankResult = Hapyfish2_Ipanda_Bll_Friend::getFriendList($uid, 1, 1000);
		$friendList = $rankResult['friends'];
		$mkey2 = 'i:u:gift:sent:g:uids:' . $uid;
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
        $sentCache = $cache->get($mkey2);
		foreach ($friendList as $key=>$data) {
		    $friendList[$key]['giftAble'] = true;
		    if ($sentCache && isset($sentCache['dt']) && $sentCache['dt'] == $today && isset($sentCache['ids'])) {
    		    if (in_array($data['uid'], $sentCache['ids'])) {
    		        $friendList[$key]['giftAble'] = false;
    		    }
		    }

		    $friendList[$key]['giftRequestAble'] = true;
		}

		$result = array('giftDiarys' => $receiveList, 'giftRequests' => $requestList,
						'giftUser' => $giftUser, 'giftFriendUser' => $friendList, 'giftMyWish' => $giftMyWish);

		$this->echoResult($result);
	}

	public function friendrequestAction()
	{
	    include CONFIG_DIR . '/errorcode.php';
        $uid = $this->uid;
		$id = $this->_request->getParam('giftRequestId');
		$giftId = $this->_request->getParam('giftId');
	    if (empty($id) || empty($giftId)) {
            $result = array('result' => array('status'=>-1,'content'=>'invalid data'));
            $this->echoResult($result);
        }


	    $aryId = explode('|', base64_decode(urldecode($id)));
		if ( !(isset($aryId[0]) && isset($aryId[1])) ) {
		    //$result['status'] = -1;
            //$result['content'] = '-901';
            //return array('result' => $result);
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-901']['msg']));
            $this->echoResult($result);
		}

        $key = 'checkfriendrequest:' . $aryId[1];
        $lock = Hapyfish2_Cache_Factory::getLock($aryId[1]);
    	//get lock
		$ok = $lock->lock($key, 2);
	    if (!$ok) {
            //$this->echoErrResult(-1);
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-2']['msg']));
            $this->echoResult($result);
        }

		$result = Hapyfish2_Ipanda_Bll_Gift::sendWish($id, $giftId);
		if ($result['status'] == -1) {
		    //$this->echoErrResult($result['errCode']);
		    $code = $result['errCode'];
		    $result = array('result' => array('status'=>-1,'content'=>$error_code[$code]['msg']));
            $this->echoResult($result);
		}

        //release lock
        $lock->unlock($key);

		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

	public function ignoregiftAction()
	{
	    include CONFIG_DIR . '/errorcode.php';
        $uid = $this->uid;
        $id = $this->_request->getParam('giftDiaryId');
        $ids = array($id);

        $key = 'checkreceivegift:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
    	//get lock
		$ok = $lock->lock($key, 2);
	    if (!$ok) {
            //$this->echoErrResult(-1);
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-2']['msg']));
            $this->echoResult($result);
        }

		$result = Hapyfish2_Ipanda_Bll_Gift::ignore($uid, $ids);
	    if ($result['status'] == -1) {
		    //$this->echoErrResult($result['errCode']);
		    $code = $result['errCode'];
		    $result = array('result' => array('status'=>-1,'content'=>$error_code[$code]['msg']));
		    $this->echoResult($result);
		}

        //release lock
        $lock->unlock($key);

		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

	public function receivegiftAction()
	{
	    include CONFIG_DIR . '/errorcode.php';
        $uid = $this->uid;
        $ids = $this->_request->getParam('giftDiaryId');

	    $key = 'checkreceivegift:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
    	//get lock
		$ok = $lock->lock($key, 2);
	    if (!$ok) {
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-2']['msg']));
            $this->echoResult($result);
        }

	    $ids = explode('-', $ids);
		$result = Hapyfish2_Ipanda_Bll_Gift::accept($uid, $ids);

		//release lock
        $lock->unlock($key);

	    if ($result['status'] == -1) {
		    //$this->echoErrResult($result['errCode']);
		    $code = $result['errCode'];
		    $result = array('result' => array('status'=>-1,'content'=>$error_code[$code]['msg']));
		    $this->echoResult($result);
		}

    	//派发任务
    	$event = array('uid' => $uid, 'num' => count($ids));
    	Hapyfish2_Ipanda_Bll_Event::receiveGift($event);

		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

	public function sendAction()
	{
	    include CONFIG_DIR . '/errorcode.php';
		$uid = $this->uid;
		$giftId = $this->_request->getParam('giftId');
		$fids = $this->_request->getParam('friendId');
	    if (empty($giftId) || empty($fids)) {
            //$this->echoErrResult('-901');
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-901']['msg']));
            $this->echoResult($result);
        }

        $key = 'giftsend:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
    	//get lock
		$ok = $lock->lock($key, 2);
	    if (!$ok) {
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-2']['msg']));
            $this->echoResult($result);
        }

        $fids = explode('-', $fids);
	    $result = Hapyfish2_Ipanda_Bll_Gift::send($uid, $giftId, $fids);

		//release lock
        $lock->unlock($key);

	    if ($result['status'] == -1) {
		    //$this->echoErrResult($result['errCode']);
		    $code = $result['errCode'];
		    $result = array('result' => array('status'=>-1,'content'=>$error_code[$code]['msg']));
		    $this->echoResult($result);
		}

    	//派发任务
    	$event = array('uid' => $uid, 'num' => count($fids));
    	Hapyfish2_Ipanda_Bll_Event::sendGift($event);

		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

	public function mywishAction()
	{
	    include CONFIG_DIR . '/errorcode.php';
		$uid = $this->uid;
		$gids = $this->_request->getParam('giftId');

        if (empty($gids)) {
            $result = array('result' => array('status'=>-1,'content'=>'invalid data'));
            $this->echoResult($result);
        }

	    $key = 'mywish:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
    	//get lock
		$ok = $lock->lock($key, 2);
	    if (!$ok) {
            $result = array('result' => array('status'=>-1,'content'=>$error_code['-2']['msg']));
            $this->echoResult($result);
        }

        $gids = explode('-', $gids);
		$result = Hapyfish2_Ipanda_Bll_Gift::mywish($uid, $gids);

		//release lock
        $lock->unlock($key);

	    if ($result['status'] == -1) {
		    //$this->echoErrResult($result['errCode']);
		    $code = $result['errCode'];
		    $result = array('result' => array('status'=>-1,'content'=>$error_code[$code]['msg']));
		    $this->echoResult($result);
		}

    	//派发任务
    	$event = array('uid' => $uid);
    	Hapyfish2_Ipanda_Bll_Event::sendWish($event);

		$data = $this->returnResult($result);
        $this->echoResult($data);
	}

	public function hadreadAction()
	{
	    $uid = $this->uid;
	    $rst = Hapyfish2_Ipanda_Bll_Gift::readReceive($uid);
        $result = array('status'=>$rst);
        $this->echoResult($result);
	}

 }
