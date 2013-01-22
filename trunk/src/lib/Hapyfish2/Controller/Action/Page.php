<?php

/**
 * Page Base Controller
 * user must login, identity not empty
 *
 * @package  Hapyfish2_Controller_Action
 */
class Hapyfish2_Controller_Action_Page extends Zend_Controller_Action
{
    protected $uid;

    protected $info;
    
    protected $skey;

    /**
     * initialize basic data
     * @return void
     */
    public function init()
    {
    	$info = $this->vailid();
    	$appInfo = Hapyfish2_Ipanda_Bll_AppInfo::getAdvanceInfo();
        if (!$info) {
			if ($appInfo) {
				Hapyfish2_Ipanda_Bll_AppInfo::redirect($appInfo['app_link'], true);
			}
			exit;
        }
        
        Hapyfish2_Ipanda_Bll_AppInfo::checkStatus($info['uid'], true, true, $appInfo);

        $this->info = $info;
        $this->uid = $info['uid'];

        $data = array('uid' => $info['uid'], 'puid' => $info['puid'], 'session_key' => $info['session_key']);
        $context = Hapyfish2_Util_Context::getDefaultInstance();
        $context->setData($data);
        Hapyfish2_Ipanda_Bll_UserResult::setUser($info['uid']);

        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
        $this->view->skey = $this->skey;
        $this->view->appId = APP_ID;
        $this->view->appKey = APP_KEY;
    }

    protected function vailid()
    {
    	$skey = $this->_request->getParam('skey');
        if (empty($skey)) {
    	    $skey = $_COOKIE['hf_skey'];
        }

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

        $this->skey = $skey;
        return array('uid' => $uid, 'puid' => $puid, 'session_key' => $session_key,  't' => $t, 'rnd' => $rnd);
    }

    protected function echoResult($data)
    {
    	header("Cache-Control: no-store, no-cache, must-revalidate");
    	echo json_encode($data);
    	exit;
    }
    
    protected function renderHTML($html)
    {
    	echo '<html><body>' . $html . '</body></html>';
    	exit;
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