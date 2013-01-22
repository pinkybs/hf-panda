<?php

class RepairController extends Hapyfish2_Controller_Action_External
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
		}
		
		return $uid;
	}
    
	public function mapgridAction()
	{
		$uid = $this->check();
		Hapyfish2_Ipanda_Tool_Repair_MapGrid::repair($uid);
		$data = array('result' => 1);
		$this->echoResult($data);
	}
}