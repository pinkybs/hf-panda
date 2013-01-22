<?php

class DevController extends Hapyfish2_Controller_Action_Api
{
    
	public function inituserinfoAction()
	{
		$uid = $this->uid;
		
		//加锁，防止刷
	    $key = 'i:u:lock:default:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($key);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
        
		Hapyfish2_Ipanda_Bll_User::updateUserTodayInfo($uid);
		
        //release lock
        $lock->unlock($key);
		
		$result['userVo'] = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
		$hospitalData['hospitalList'] = Hapyfish2_Ipanda_Bll_Hospital::getInit($uid);
		$result['acts'] = array(
			array(
				'actName' => 'gift',
				'initIndex' => 1,
				'state' => 2,
				'backModuleUrl' => 'swf/GiftGetAct.swf?v=2011102701',
				'moduleData' => array('giftNum' => 0)
			),
			array(
				'actName' => 'hospital',
				'initIndex' => 1,
				'state' => 2,
				'backModuleUrl' => 'swf/hospital.swf?v=2011102701',
				'moduleData' => $hospitalData
			),
		);
		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	public function userAction()
	{
		echo '{"giftDiarys":[{"id":"MTAwMXwxMDAyfDIwMTExMDI2fDE%3D","date":"1319608861","expTime":257303,"uid":"1002","hasGet":false,"giftCid":"144","giftType":"1"},{"id":"MTAwMXwxMDAyfDIwMTExMDI1fDA%3D","date":"1319534719","expTime":183161,"uid":"1002","hasGet":true,"giftCid":"251","giftType":"2"},{"id":"MTAwMXwxMDAyfDIwMTExMDIxfDA%3D","date":"1319183273","expTime":0,"uid":"1002","hasGet":true,"giftCid":"144","giftType":"1"}],"giftRequests":[],"giftUser":{"giftNum":3,"giftRequestNum":0,"isReleaseWish":true},"giftFriendUser":[{"uid":"1002","name":"\u672a\u77e5","face":"http:\/\/img.kaixin001.com.cn\/i\/50_0_0.gif","exp":105955,"level":"34","giftAble":true,"giftRequestAble":true},{"uid":"1003","name":"\u672a\u77e5","face":"http:\/\/img.kaixin001.com.cn\/i\/50_0_0.gif","exp":2349,"level":"8","giftAble":true,"giftRequestAble":true},{"uid":"1004","name":"\u672a\u77e5","face":"http:\/\/img.kaixin001.com.cn\/i\/50_0_0.gif","exp":1731,"level":"9","giftAble":true,"giftRequestAble":true}]}';
		exit;
	}
	public function listAction()
	{
		echo '{"gifts":[{"type":"1","lockLevel":"1","id":"144"},{"type":"2","lockLevel":"1","id":"151"},{"type":"3","lockLevel":"2","id":"321"},{"type":"3","lockLevel":"2","id":"721"},{"type":"3","lockLevel":"2","id":"621"},{"type":"3","lockLevel":"3","id":"1021"},{"type":"3","lockLevel":"3","id":"921"},{"type":"3","lockLevel":"3","id":"821"},{"type":"3","lockLevel":"3","id":"1121"},{"type":"2","lockLevel":"5","id":"251"},{"type":"2","lockLevel":"9","id":"351"},{"type":"2","lockLevel":"10","id":"451"},{"type":"2","lockLevel":"18","id":"551"},{"type":"2","lockLevel":"19","id":"651"},{"type":"2","lockLevel":"25","id":"751"},{"type":"2","lockLevel":"25","id":"851"}]}';
		exit;
	}
 }
