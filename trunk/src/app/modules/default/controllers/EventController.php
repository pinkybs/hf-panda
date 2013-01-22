<?php

class EventController extends Hapyfish2_Controller_Action_Api
{	
	public function initchristmasAction()
	{
		$uid = $this->uid;
		$time = time();
		if($time > 1325347199){
			$this->echoErrResult(-1001);
		}
		$num = Hapyfish2_Ipanda_Event_Bll_Christmas::getUserBell($uid);
		$result = Hapyfish2_Ipanda_Event_Bll_Christmas::getInit($uid);
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
	}
	
	public function christmasexchangeAction()
	{
		$uid = $this->uid;
		$id = $this->_request->getParam('id');
		$time = time();
		if($time > 1325347199){
			$this->echoErrResult(-1001);
		}
		$results = Hapyfish2_Ipanda_Event_Bll_Christmas::exchange($uid, $id);
		if(!$results){
			$this->echoErrResult(-1002);
		}
		$result['status'] = 1;
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
		
	}

 }
