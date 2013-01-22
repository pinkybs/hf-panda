<?php

class AwardController extends Hapyfish2_Controller_Action_Api
{
	
    protected function checkHitData($hitdata)
    {
    	$tmp = explode('.', $hitdata);
    	if (empty($tmp)) {
    		return false;
    	}
    	
    	$count = count($tmp);
    	if ($count != 4) {
    		return false;
    	}

        $num = $tmp[0];
        $double = $tmp[1];
        $t = $tmp[2];
        $sig = $tmp[3];
        //验证字符串
        $key = 'asd#sdfa@1&d36pw';

		$vsig = md5($num . $double . $t . $key);
        if ($sig != $vsig) {
        	return false;
        }
        
        //有效时间(允许一定时间差)
		if (time() > $t + 300) {
        	return false;
        }

        return array('num' => $num, 'double' => $double, 't' => $t);
    }
	
	public function hitAction()
    {
        $uid = $this->uid;
        $hitdata = $this->_request->getParam('hitdata');
        if (empty($hitdata)) {
        	$this->echoErrResult(-104);
        }

        $info = $this->checkHitData($hitdata);
        if (!$info) {
        	$this->echoErrResult(-901);
        }
        
        $hitNum = (int)$info['num'];
        if ($hitNum <= 0 || $hitNum > 100) {
        	$this->echoErrResult(-901);
        }
        $double = (int)$info['double'];
        $maxDouble = 1 + floor($hitNum/5) + floor($hitNum/30);
        if ($double <= 0 || $double > $maxDouble) {
        	$this->echoErrResult(-901);
        }
 
        $ts = $info['t'];
        
        //其他有效性验证
        //最大连击数不能超过收钱掉落物品数(一定时间内)
        $checkCode = Hapyfish2_Ipanda_Cache_ComboHit::checkout($uid, $hitNum, $ts);
        if ($checkCode <= 0) {
        	info_log($uid . ':' . $checkCode, 'hit');
        	$this->echoErrResult(-2);
        }
        
        $key = 'i:u:lock:award:' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($key);
        if (!$ok) {
            $this->echoErrResult(-2);
        }
        
        $love = $hitNum * $double * 2;
        Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $love);
        //2011-12-05
        //奖励能量
        //3环以上才奖励
        $energy = 0;
        if ($double > 2) {
        	$energy = $double - 2;
        	//最多奖励3个能量值
        	if ($energy > 3) {
        		$energy = 3;
        	}
        	Hapyfish2_Ipanda_HFC_User::incUserEnergy($uid, $energy);
        }
        
        $result = array('status' => 1, 'love' => $love, 'energy' => $energy);
        
        //release lock
        $lock->unlock($key);
        
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
    }
   	
 }
