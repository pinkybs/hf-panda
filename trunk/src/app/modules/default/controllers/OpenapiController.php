<?php

class OpenapiController extends Hapyfish2_Controller_Action_External
{    
	function check()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			$this->echoError(1001, 'uid can not empty');
		}
		
		$isAppUser = Hapyfish2_Ipanda_Cache_User::isAppUser($uid);
		if (!$isAppUser) {
			$this->echoError(1002, 'uid error, not app user');
			exit;
		}
		
		return $uid;
	}
	
	public function noopAction()
    {
    	$data = array('id' => SERVER_ID, 'time' => time());
    	$this->echoResult($data);
    }
	
	public function watchuserAction()
    {
		$uid = $this->check();
		$t = time();
		$sig = md5($uid . $t . APP_SECRET);
		
		$url = HOST . '/watch?uid=' . $uid . '&t=' . $t . '&sig=' . $sig;
		$data = array('url' => $url);
		$this->echoResult($data);
    }
	
	public function userinfoAction()
	{
		$uid = $this->check();
		$platformUser = Hapyfish2_Platform_Bll_User::getUser($uid);
		$ipandaUser = Hapyfish2_Ipanda_HFC_User::getUser($uid, array('exp' => 1, 'love' => 1, 'level' => 1, 'gold' => 1));
		$data = array(
			'face' => $platformUser['figureurl'],
			'puid' => $platformUser['puid'],
			'uid' => $uid,
			'nickname' => $platformUser['name'],
			'gender' => $platformUser['gender'],
			'level' => $ipandaUser['level'],
			'exp' => $ipandaUser['exp'],
			'love' => $ipandaUser['love'],
			'gold' => $ipandaUser['gold'],
			'homeurl' => 'http://jianghu.taobao.com/u/' . base64_encode($platformUser['puid']) . '/front.htm'
		);

		$data['status'] = Hapyfish2_Platform_Cache_User::getStatus($uid);
		
		$this->echoResult($data);
	}
	
	public function userinfobypuidAction()
	{
		$puid = $this->_request->getParam('puid');
		if (empty($puid)) {
			$this->echoError(1001, 'puid can not empty');
		}
		
		try {
			$platformUidInfo = Hapyfish2_Platform_Cache_UidMap::getUser($puid);
		} catch (Exception $e) {
			$platformUidInfo = null;
		}

		if (!$platformUidInfo) {
			$this->echoError(1002, 'puid error, not app user');
			exit;
		}
		$uid = $platformUidInfo['uid'];
		
		$platformUser = Hapyfish2_Platform_Bll_User::getUser($uid);
		$ipandaUser = Hapyfish2_Ipanda_HFC_User::getUser($uid, array('exp' => 1, 'love' => 1, 'level' => 1, 'gold' => 1));
		$data = array(
			'face' => $platformUser['figureurl'],
			'puid' => $platformUser['puid'],
			'uid' => $uid,
			'nickname' => $platformUser['name'],
			'gender' => $platformUser['gender'],
			'level' => $ipandaUser['level'],
			'exp' => $ipandaUser['exp'],
			'love' => $ipandaUser['love'],
			'gold' => $ipandaUser['gold'],
			'homeurl' => 'http://jianghu.taobao.com/u/' . base64_encode($platformUser['puid']) . '/front.htm'
		);

		$data['status'] = Hapyfish2_Platform_Cache_User::getStatus($uid);
		
		$this->echoResult($data);
	}
	
	public function leveluplogAction()
	{
		$uid = $this->check();
		$logs = Hapyfish2_Ipanda_Bll_LevelUpLog::getAll($uid);
		if (!$logs) {
			$logs = array();
		}
		$data = array('logs' => $logs);
		$this->echoResult($data);
	}
	
	/**
	 * 金币消耗记录
	 *
	 */
	public function goldlogAction()
	{
		$uid = $this->check();
		$time = time();
		$year = $this->_request->getParam('year');
		if (!$year) {
			$year = date('Y', $time);
		}
		$month = $this->_request->getParam('month');
		if (!$month) {
			$month = date('n', $time);
		}
		$limit = $this->_request->getParam('limit');
		if (!$limit) {
			$limit = 0;
		}
		
		$logs = Hapyfish2_Ipanda_Bll_ConsumeLog::getGold($uid, $year, $month, $limit);
		if (!$logs) {
			$logs = array();
		}
		$data = array('logs' => $logs);
		$this->echoResult($data);
	}
	
	/**
	 * 支付日志
	 *
	 */
	public function paylogAction()
	{
		$uid = $this->check();
		$limit = $this->_request->getParam('limit');
		if (!$limit) {
			$limit = 0;
		}
		
		$logs = Hapyfish2_Ipanda_Bll_PaymentLog::getPayment($uid, $limit);
		if (!$logs) {
			$logs = array();
		}
		$data = array('logs' => $logs);
		$this->echoResult($data);
	}
	
	/**
	 * 捐赠日志
	 *
	 */
	public function donatelogAction()
	{
		$uid = $this->check();
		$limit = $this->_request->getParam('limit');
		if (!$limit) {
			$limit = 0;
		}
		
		$logs = Hapyfish2_Ipanda_Bll_Donate::listDonate($uid, $limit);
		if (!$logs) {
			$logs = array();
		}
		$data = array('logs' => $logs);
		$this->echoResult($data);
	}
	
	public function tasklistAction()
	{
		$uid = $this->check();
		$data = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		if (!$data) {
			$data = array();
		}
		
		$this->echoResult($data);
	}
	
	public function logininfoAction()
	{
		$uid = $this->check();
		$data = Hapyfish2_Ipanda_HFC_User::getUserLoginInfo($uid);
		$this->echoResult($data);
	}
	
	public function appinfoAction()
	{
		$info = Hapyfish2_Ipanda_Cache_AppInfo::getInfo();
		$this->echoResult($info);
	}
	
	public function checkappstatusAction()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			$uid = 0;
		} else {
			$uid = $this->check();
		}
		$redirect = $this->_request->getParam('redirect');
		if ($redirect == '1') {
			$redirect = true;
		} else {
			$redirect = false;
		}
		$force = $this->_request->getParam('force');
		if ($force == '0') {
			$force = false;
		} else {
			$force = true;
		}
		
		$info = Hapyfish2_Ipanda_Bll_AppInfo::checkStatus($uid, $redirect, $force);
		$this->echoResult($info);
	}
	
	private function extract(&$data, $fields)
	{
		$out = array();
		foreach ($data as $k => $v) {
			$tmp = array();
			foreach ($fields as $f) {
				$tmp[$f] = $v[$f];
			}
			$out[$k] = $tmp;
		}
		
		return $out;
	}
	
	public function itemlistAction()
	{
		$type = $this->_request->getParam('type', '0');
		if ($type == 1) {
			$decoratelist = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
			$data = array(
				'decoratelist' => $this->extract($decoratelist, array('cid', 'name', 'can_buy'))
			);
		} else if ($type == 2) {
			$buildinglist = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
			$data = array(
				'buildinglist' => $this->extract($buildinglist, array('cid', 'name', 'can_buy'))
			);
		} else if ($type == 3) {
			$cardlist = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardList();
			$data = array(
				'cardlist' => $this->extract($cardlist, array('cid', 'name', 'can_buy'))
			);
		} else if ($type == 4) {
			$materiallist = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialList();
			$data = array(
				'materiallist' => $this->extract($materiallist, array('cid', 'name'))
			);
		} else {
			$decoratelist = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
			$buildinglist = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
			$cardlist = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardList();
			$materiallist = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialList();
			$data = array(
				'decoratelist' => $this->extract($decoratelist, array('cid', 'name', 'can_buy')),
				'buildinglist' => $this->extract($buildinglist, array('cid', 'name', 'can_buy')),
				'cardlist' => $this->extract($cardlist, array('cid', 'name', 'can_buy')),
				'materiallist' => $this->extract($materiallist, array('cid', 'name'))
			);
		}

		$this->echoResult($data);
	}
	
	public function userplatforminfoAction()
	{
		$uid = $this->check();
		$info = Hapyfish2_Platform_Bll_UserMore::getInfo($uid);
		$this->echoResult($info);
	}
	
}