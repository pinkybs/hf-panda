<?php

class Hapyfish2_Ipanda_Bll_Act
{
	public static function get($uid, $loginInfo)
	{
		$actState = array();
		$time = time();
		//连续登录
		if ($loginInfo && $loginInfo['active_login_count'] > 0) {
			$arySignAward = Hapyfish2_Ipanda_Bll_DailyAward::getAwards($uid, $loginInfo['active_login_count']);
			if ($arySignAward['signDay'] > 0) {
				$moduleData = array(
					'signAwardNumber' => $arySignAward['signAwardNumber'],
					'signDay' => $arySignAward['signDay'],
					'isfans' => $arySignAward['isfans']
				);
				$signAwardAct = array(
					'actName' => 'signAward',
					'initIndex' => 1,
					'state' => 2,
					'backModuleUrl' => STATIC_HOST . '/swf/SignAwardAct.swf?v=2011121501',
					'moduleData' => $moduleData
				);
				$actState[] = $signAwardAct;
			}
		}

		//礼物
		$mkey = 'i:u:gift:newrececnt:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $newReceCnt = (int)$cache->get($mkey);
		$giftAct = array(
			'actName' => 'gift',
			'initIndex' => 2,
			'state' => 2,
			'backModuleUrl' => STATIC_HOST . '/swf/GiftGetAct.swf?v=2012010301',
		    'moduleData' => array('giftNum' => $newReceCnt)
		);
		$actState[] = $giftAct;
		
		//圣诞节
		if($time <= 1325347199){
			$Userbell = Hapyfish2_Ipanda_Event_Bll_Christmas::getUserBell($uid);
			$christmas = array(
				'actName' => 'Christmas',
				'initIndex' => 3,
				'state' => 2,
				'backModuleUrl' => STATIC_HOST . '/swf/ChristmasAct.swf?v=2011123102',
				'moduleData' => array('num'=> $Userbell),
			);
			$actState[] = $christmas;
		}
//		$christmas_new = array(
//				'actName' => 'Christmas_new',
//				'initIndex' => 3,
//				'state' => 2,
//				'backModuleUrl' => STATIC_HOST . '/swf/ChristmasAct_new.swf?v=2011123101',
//				
//			);
//		$actState[] = $christmas_new;
		
		$hospitalData['hospitalList'] = Hapyfish2_Ipanda_Bll_Hospital::getInit($uid);
		$hospitalAct = array(
					'actName' => 'hospital',
					'initIndex' => 4,
					'state' => 2,
					'backModuleUrl' => STATIC_HOST . '/swf/hospital.swf?v=2012010301',
					'moduleData' => $hospitalData
				);
		$actState[] = $hospitalAct;
		$juanzeng = array(
				'actName' => 'juanzeng',
				'initIndex' => 1,
				'state' => 2,
				'menuIndex' => 1,
				'menuType' => 1,
				'menuJs' => 'goDonate',
				'menuUrl' => STATIC_HOST . '/swf/actBtn.swf?v=2011123101',
				'menuClass'=> 'act_juanzeng',
			);
			$actState[] = $juanzeng;
		$loveMyHome = array(
				'actName' => 'loveMyHome',
				'initIndex' => 1,
				'state' => 2,
				'menuIndex' => 2,
				'menuType' =>1,
				'menuUrl' => STATIC_HOST . '/swf/actBtn.swf?v=2011123101',
				'menuClass'=> 'act_loveMyHomeBtn',
				'menuLink'=> 'http://bangpai.taobao.com/group/thread/14592801-269267867.htm',
			);
		$actState[] = $loveMyHome;
		$lianJi = array(
			'actName' => 'lianJi',
			'initIndex' => 1,
			'state' => 2,
			'menuIndex' => 3,
			'menuType' =>1,
			'menuUrl' => STATIC_HOST . '/swf/actBtn.swf?v=2011123101',
			'menuClass'=> 'act_lianJIBtn',
			'menuLink'=> 'http://bangpai.taobao.com/group/thread/14592801-269640509.htm',
		);
		$actState[] = $lianJi;
		$yuanDan = array(
			'actName' => 'yuanDan',
			'initIndex' => 1,
			'state' => 2,
			'menuIndex' => 4,
			'menuType' =>1,
			'menuUrl' => STATIC_HOST . '/swf/actBtn.swf?v=2011123101',
			'menuClass'=> 'act_yuanDanJindanBtn',
			'menuLink'=> 'http://bangpai.taobao.com/group/thread/14592801-269647043.htm',
		);
		$actState[] = $yuanDan; 
		return $actState;
	}
}