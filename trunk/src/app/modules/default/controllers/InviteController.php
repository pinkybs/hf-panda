<?php

class InviteController extends Hapyfish2_Controller_Action_Api
{

    public function topAction()
    {
    	//$this->render();
    	return $this->_redirect($this->view->baseUrl . '/invite/friends');
    }

	public function friendsAction()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
        $this->view->appId = APP_ID;
        $this->view->appKey = APP_KEY;

        $puid = $this->info['puid'];
        //$friends = Bll_Cache_User::getTaobaoNotJoinFriends($this->uid, $_SESSION['session']);
        $taobao = Taobao_Rest::getInstance();
        $taobao->setUser($puid, $this->info['session_key']);
        $ptFriends = $taobao->jianghu_getFriends();

        $friends = null;
        if ( !empty($ptFriends) ) {
	        foreach ($ptFriends as $pfid => $data) {
	            $inGame = Hapyfish2_Platform_Bll_UidMap::getUser($pfid);
	            //if is in game friends
	            if (empty($inGame)) {
	                $friends[] = array('uid' => $pfid, 'name' => $data['name'], 'thumbnail' => $data['thumbnail']);
	            }
	        }
        }

        $count = count($friends);
        $this->view->count = $count;
        $this->view->friends = $friends;
		if(is_array($friends)){
			$friendsArray = array_chunk($friends, 16);
		}else{
			$friendsArray = array();
		}
        $pageCount = count($friendsArray);
        $this->view->friendsArray = $friendsArray;
        $this->view->pageCount = $pageCount;

        $pageArray = array();
        for ( $i = 0; $i < $pageCount; $i++ ) {
            $pageArray[$i] = 1;
        }
        $this->view->pageArray = $pageArray;
        $this->render();
    }

    public function sendAction()
    {
        $puid = $this->info['puid'];
		$strids = $this->_request->getParam('ids');
		$ids = explode(',', $strids);
        if(!empty($ids)) {
            foreach($ids as $id) {
                Hapyfish2_Ipanda_Bll_Message::send('INVITE', $puid, $id);
            }
        }
        echo '<div style="font-size:20pt;color:blue;"><br />&nbsp;&nbsp;&nbsp;邀请已成功发送。请点<a href="javascript:goInvite();">这里</a>返回</div>';
        exit;
    }

 }
