<?php

/**
 * Ipanda index controller
 *
 * @copyright  Copyright (c) 2010 HapyFish
 * @create      2010/10    lijun.hu
 */
class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
        $this->view->appId = APP_ID;
        $this->view->appKey = APP_KEY;
    }

    public function indexAction()
    {
		//uid = 0的时候，不要强制，因为可能是开发者/测试者开放模式，需要后面再检查uid
    	$appInfo = Hapyfish2_Ipanda_Bll_AppInfo::checkStatus(0, true, false);
        
    	$top_appkey = $this->_request->getParam('top_appkey');
        if (empty($top_appkey)) {
        	if ($appInfo) {
        		Hapyfish2_Ipanda_Bll_AppInfo::redirect($appInfo['app_link'], true);
        	}
            exit;
        }
        
    	try {
    		$application = Hapyfish2_Application_Taobao::newInstance($this);
        	$application->run();
    	} catch (Exception $e) {
    		err_log($e->getMessage());
    		//echo '加载数据出错，请重新进入。';
    		echo '<div style="text-align:center;margin-top:30px;"><img src="' . STATIC_HOST . '/maintance/images/problem1.gif" alt="加载数据出错，请重新进入" /></div>';
    		exit;
    	}

        $uid = $application->getUserId();
        $isnew = $application->isNewUser();
        $platformUid = $application->getPlatformUid();

        if ($isnew) {
			$ok = Hapyfish2_Ipanda_Bll_User::joinUser($uid);
        	if (!$ok) {
    			echo '创建初始化数据出错，请重新进入。';
    			exit;
        	}

	        $isInvite = $this->_request->getParam('hf_invite');
	        if ($isInvite) {
	            $inviteUid = 0;
	        	$isExists = 0;
	        	$ivniteType = 'INVITE';

    			$inviteUid = $this->_request->getParam('hf_inviter');
    			$sig = $this->_request->getParam('hf_sg');

	   			$dalInvite = Hapyfish2_Ipanda_Dal_InviteLog::getDefaultInstance();
    			$isExists = $dalInvite->getInvite($sig);

    			if ($inviteUid && $isExists && $inviteUid == $isExists['actor']) {
	    			$puidMS = Hapyfish2_Platform_Bll_UidMap::getUser($inviteUid);
	    			Hapyfish2_Ipanda_Bll_Invite::add($puidMS['uid'], $uid);

	    			if ($ivniteType == 'INVITE') {
	    				$dalInvite->deleteInvite($sig);
	 					info_log('inviter:'.$puidMS['uid'].',uid:'.$uid, 'invitejoin');
	    			}
	    		}
	        }
        } else {
        	$isAppUser = Hapyfish2_Ipanda_Cache_User::isAppUser($uid);
        	if (!$isAppUser) {
        		$ok = Hapyfish2_Ipanda_Bll_User::joinUser($uid);
        	    if (!$ok) {
    				echo '创建初始化数据出错，请重新进入。';
    				exit;
        		}
        		$isnew = true;
        	} else {
        		$status = Hapyfish2_Platform_Cache_User::getStatus($uid);
        		if ($status > 0) {
        			if ($status == 1) {
        				$msg = '该帐号(门牌号:' . $uid . ')因使用外挂或违规已被封禁，有问题请联系管理员QQ:800004811';
        			} else if ($status == 2) {
        				$msg = '该帐号(门牌号:' . $uid . ')因数据出现异常被暂停使用，有问题请联系管理员QQ:800004811';
        			} else if ($status == 3)  {
        				$msg = '该帐号(门牌号:' . $uid . ')因利用bug被暂停使用[待处理后恢复]，有问题请联系管理员QQ:800004811';
        			} else {
        				$msg = '该帐号(门牌号:' . $uid . ')暂时不能访问，有问题请联系管理员QQ:800004811';
        			}

        			echo $msg;
        			exit;
        		}
        	}
        }
        
        if ($appInfo) {
        	if ($appInfo['app_status'] == 2 || $appInfo['app_status'] == 3) {
		        //再次检查uid(开发者/测试者开放模式)
		        Hapyfish2_Ipanda_Bll_AppInfo::checkStatus($uid, true, true, $appInfo);
        	}
        }

        $next = $this->_request->getParam('hf_next');
		if ($next) {
		    $this->_redirect($next);
		}

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
        $this->view->platformUid = $platformUid;
        $this->view->showpay = true;
        $this->view->newuser = $isnew ? 1 : 0;
        $this->view->skey = $application->getSKey();
        $this->render();
    }
    
	public function maintanceAction()
	{
		$appInfo = Hapyfish2_Ipanda_Bll_AppInfo::getAdvanceInfo();
		$this->view->notice = $appInfo['maintance_notice'];
		$this->render();
	}

    public function assetlistAction()
    {
    	header("Cache-Control: no-store, no-cache, must-revalidate");
		require (CONFIG_DIR . '/asset.php');
    	echo json_encode($assetResult);
    	exit;
    }

 	protected function echoResult($data)
    {
    	header("Cache-Control: no-store, no-cache, must-revalidate");
    	echo json_encode($data);
    	exit;
    }
    
    public function testAction()
    {
        echo 'hello ipanda_taobao';
        exit;
    }
}