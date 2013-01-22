<?php

/**
 * Api Base Controller
 * user must login, identity not empty
 *
 * @package  Hapyfish2_Controller_Action
 */
class Hapyfish2_Controller_Action_Api extends Zend_Controller_Action
{
    protected $uid;

    protected $info;

    /**
     * initialize basic data
     * @return void
     */
    public function init()
    {
    	$info = $this->vailid();
        if (!$info) {
        	$result = array('status' => '-1', 'content' => 'vaild error');
			$this->echoResult($result);
        }
        
        Hapyfish2_Ipanda_Bll_AppInfo::checkStatus($info['uid'], true, true);

        $this->info = $info;
        $this->uid = $info['uid'];

        $data = array('uid' => $info['uid'], 'puid' => $info['puid'], 'session_key' => $info['session_key']);
        $context = Hapyfish2_Util_Context::getDefaultInstance();
        $context->setData($data);
        Hapyfish2_Ipanda_Bll_UserResult::setUser($info['uid']);

    	$controller = $this->getFrontController();
        $controller->unregisterPlugin('Zend_Controller_Plugin_ErrorHandler');
        $controller->setParam('noViewRenderer', true);
    }

    protected function vailid()
    {
    	$skey = $_COOKIE['hf_skey'];

    	if (!$skey) {
    		return false;
    	}

    	$tmp = explode('.', $skey);
    	if (empty($tmp)) {
    		return false;
    	}
    	$count = count($tmp);
    	if ($count != 5 && $count != 6) {
    		return false;
    	}

        $uid = $tmp[0];
        $puid = $tmp[1];
        $session_key = base64_decode($tmp[2]);
        $t = $tmp[3];

        $rnd = -1;
        if ($count == 5) {
        	$sig = $tmp[4];
	        $vsig = md5($uid . $puid . $session_key . $t . APP_SECRET);
	        if ($sig != $vsig) {
	        	return false;
	        }
        } else if ($count == 6) {
        	$rnd = $tmp[4];
        	$sig = $tmp[5];
        	$vsig = md5($uid . $puid . $session_key . $t . $rnd . APP_SECRET);
        	if ($sig != $vsig) {
	        	return false;
	        }
        }

        //max long time one day
        if (time() > $t + 86400) {
        	return false;
        }

        return array('uid' => $uid, 'puid' => $puid, 'session_key' => $session_key,  't' => $t, 'rnd' => $rnd);
    }

    protected function checkEcode($params = array())
    {
    	if ($this->info['rnd'] > 0) {
    		$rnd = $this->info['rnd'];
    		$uid = $this->uid;
    		$ts = $this->_request->getParam('tss');
    		$authid = $this->_request->getParam('authid');
    		$ok = true;
	    		if (empty($authid) || empty($ts)) {
	    			$ok = false;
	    		}
    		if ($ok) {
    			$ok = Hapyfish2_Ipanda_Bll_Ecode::check($rnd, $uid, $ts, $authid, $params);
    		}
    		if (!$ok) {
    			//Hapyfish2_Island_Bll_Block::add($uid, 1, 2);
    			info_log($uid, 'ecode-err');
	        	$result = array('status' => '-1', 'content' => 'serverWord_101');
	        	setcookie('hf_skey', '' , 0, '/', str_replace('http://', '.', HOST));
	        	//setcookie('hf_skey', '' , 0, '/', '.'.str_replace(HOST, 'http://', ''));
	        	$this->echoResult($result);
    		}
    	}
    }

	protected function returnResult($result = null, $update = null, $error = null)
	{
		$interId = $this->_request->getParam('interId');
		$uid = $this->uid;

		if ($result == null) {
			$result = array('status' => 1);
		}

		if ($update == null) {
			$update = array();
		}

		//是否用户信息改变
		$isChange = Hapyfish2_Ipanda_Bll_UserResult::isChange();
		if ($isChange) {
			$update['userVo'] = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);
		}

		//背包内东西变化
		//材料
		$materialChanged = Hapyfish2_Ipanda_Bll_UserResult::isMaterialChanged();
		if ($materialChanged) {
			if (!isset($result['materialVo'])) {
				$update['materialVo'] = Hapyfish2_Ipanda_Bll_Material::getList($uid);
			}
		}
		//道具
		$cardChanged = Hapyfish2_Ipanda_Bll_UserResult::isCardChanged();
		if ($cardChanged) {
			if (!isset($result['cardVo'])) {
				$update['cardVo'] = Hapyfish2_Ipanda_Bll_Card::getList($uid);
			}
		}
		
		//道具卡状态(亲密度加倍卡)
		$cardStatusChanged = Hapyfish2_Ipanda_Bll_UserResult::isCardStatusChanged();
		if ($cardStatusChanged) {
			if (!isset($result['cardStatus'])) {
				$update['cardStatus'] = Hapyfish2_Ipanda_Bll_Card::getCardStatus($uid);
			}
		}

		//是否有任务完成
		$taskCompletedId = Hapyfish2_Ipanda_Bll_UserResult::getTaskCompletedId();
		if (!empty($taskCompletedId)) {
			$update['taskVo'] = array('completedId' => $taskCompletedId);
		}
		//是否有新任务
		$taskNewId = Hapyfish2_Ipanda_Bll_UserResult::getTaskNewId();
		if (!empty($taskNewId)) {
			if (isset($update['taskVo'])) {
				$update['taskVo']['newId'] = $taskNewId;
			} else {
				$update['taskVo'] = array('newId' => $taskNewId);
			}
		}

		//是否成就完成
		$achieveCompletedId = Hapyfish2_Ipanda_Bll_UserResult::getAchieveCompletedId();
		if (!empty($achieveCompletedId)) {
			$update['achieveVo'] = array('completedId' => $achieveCompletedId);
		}

		//是否升级
		$isLevelUp = Hapyfish2_Ipanda_Bll_UserResult::isLevelUp();
		if ($isLevelUp) {
			$levelUpInfo = Hapyfish2_Ipanda_Bll_UserResult::getLevelUpInfo();
			$levelInfo = array('levelUp' => 1);
			if (isset($levelUpInfo['award'])) {
				$levelInfo['award'] = $levelUpInfo['award'];
			}
			if (isset($levelUpInfo['animal'])) {
				$levelInfo['animal'] = $levelUpInfo['animal'];
			}
			$update['levelInfo'] = $levelInfo;
		}
		
		//是否有要飘字的值
		$changedData = Hapyfish2_Ipanda_Bll_UserResult::getChanged();
		if (!empty($changedData)) {
			$changedVo = array();
			foreach ($changedData as $cid => $num) {
				$changedVo[] = array('cid' => $cid, 'num' => $num);
			}
			$update['changedVo'] = $changedVo;
		}

		return array(
			'interId'	=> $interId,
			'result' 	=> $result,
			'error'		=> $error,
			'update'	=> $update,
			'systime'	=> time()
		);
	}

    protected function echoResult($data)
    {
    	header("Cache-Control: no-store, no-cache, must-revalidate");
    	echo json_encode($data);
    	exit;
    }

    protected function echoErrResult($code)
    {
		$error = array('eid' => $code);
		$this->echoError($error);
		exit;
    }

    protected function echoError($error)
    {
    	header("Cache-Control: no-store, no-cache, must-revalidate");
		$interId = $this->_request->getParam('interId');		
		$data = array(
			'interId'	=> $interId,
			'result' 	=> null,
			'error'		=> $error,
			'update'	=> null,
			'systime'	=> time()
		);
		echo json_encode($data);
		exit;
    }
    
    protected function echoExtendError($code)
    {
    	header("Cache-Control: no-store, no-cache, must-revalidate");
    	include CONFIG_DIR . '/errorcode.php';
    	if (isset($error_code[$code])) {
    		$content = $error_code[$code]['msg'];
    	} else {
    		$content = $error_code['-2']['msg'];
    	}
    	
		$result = array(
			'result' => array(
				'status' => -1,
				'content' => $content
			)
		);
		$this->echoResult($result);
    }

    /**
     * proxy for undefined methods
     * override
     * @param string $methodName
     * @param array $args
     */
    public function __call($methodName, $args)
    {
        echo 'No This Method';
    }
}