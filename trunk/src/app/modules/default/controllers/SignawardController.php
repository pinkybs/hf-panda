<?php

class SignawardController extends Hapyfish2_Controller_Action_Api
{
    public function initdailyawardAction()
    {
        //header("Cache-Control: max-age=2592000");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        echo Hapyfish2_Ipanda_Bll_DailyAward::getDailyAwardsVoData();
        exit;
    }
    
    public function gaindailyawardAction()
    {
        $uid = $this->uid;
        $key = 'i:u:lock:gaindailyaward:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($key);
        if (!$ok) {
            $this->echoExtendError('-2');
        }
        $result = Hapyfish2_Ipanda_Bll_DailyAward::gainAwards($uid);
        //release lock
        $lock->unlock($key);
		if ($result['status'] == -1) {
		    $this->echoExtendError($result['errCode']);
		}
		
    	//派发任务
    	$event = array('uid' => $uid);
    	Hapyfish2_Ipanda_Bll_Event::gainDailyAward($event);
		
		$data = $this->returnResult($result);
        $this->echoResult($data);
    }
    
    public function befansAction()
    {
        $uid = $this->uid;
        $puid = $this->info['puid'];
        $session_key = $this->info['session_key'];
		$rest = Taobao_Rest::getInstance();
		$rest->setUser($puid, $session_key);
		$beFan = $rest->beFan();
		$status = $beFan ? '1' : '0';
        $result = array('status' => $status);
        $this->echoResult($result);
    }
   	
 }
