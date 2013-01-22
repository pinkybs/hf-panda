<?php

class WatchController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
    }

	function check()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			echo '1001';
			exit;
		}

		if (APP_STATUS_DEV == 1) {
			$t = $this->_request->getParam('t');
			if (empty($t)) {
				echo '1001';
				exit;
			}
	
			$sig = $this->_request->getParam('sig');
			if (empty($t)) {
				echo '1001';
				exit;
			}
	
			$validSig = md5($uid . $t . APP_SECRET);
			if ($sig != $validSig) {
				echo '1002';
				exit;
			}
	
			$now = time();
			if (abs($now - $t) > 1800) {
				echo '1003';
				exit;
			}
		}

		$isAppUser = Hapyfish2_Ipanda_Cache_User::isAppUser($uid);
		if (!$isAppUser) {
			echo 'uid error, not app user';
			exit;
		}

		return $uid;
	}

    /**
     * index Action
     *
     */
    public function indexAction()
    {
    	$uid = $this->check();
    	$user = Hapyfish2_Platform_Bll_User::getUser($uid);
        $puid = $user['puid'];
        $t = time();
        $rnd = mt_rand(1, ECODE_NUM);
        //simulate
        $session_key = md5($t);

        $sig = md5($uid . $puid . $session_key . $t . $rnd . APP_SECRET);

        $skey = $uid . '.' . $puid . '.' . base64_encode($session_key) . '.' . $t . '.' . $rnd . '.' . $sig;

        setcookie('hf_skey', $skey , 0, '/', str_replace('http://', '.', HOST));

        $notice = Hapyfish2_Ipanda_Cache_BasicInfo::getNoticeList();
        if (empty($notice)) {
        	$this->view->showNotice = false;
        } else {
        	$this->view->showNotice = true;
			$this->view->mainNotice = $notice['main'];
			$this->view->subNotice = $notice['sub'];
			$this->view->picNotice = $notice['pic'];
        }
        
        $this->view->ver_initData = Hapyfish2_Ipanda_Bll_CacheVersion::get('initData');
        $this->view->ver_asset = Hapyfish2_Ipanda_Bll_CacheVersion::get('asset');

        $this->view->uid = $uid;
        $this->view->platformUid = $puid;
        $this->view->skey = $skey;
        $this->view->newuser = 0;
        $this->render();
    }
 }

