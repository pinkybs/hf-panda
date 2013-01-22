<?php

class CheckController extends Hapyfish2_Controller_Action_Api
{
    public function onlineAction()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate");

        $rest = Taobao_Rest::getInstance();
        $info = $this->info;
        $rest->setUser($info['puid'], $info['session_key']);
        $user = $rest->top_getUser('user_id,email');
info_log(json_encode($user), 'chkonline');
        $rst = array('status' => 0, 'msg' => '');
        if (!$user) {
            $rst = array('status' => 1, 'msg' => 'platform session timeout');
        }
    	echo json_encode($rst);
    	exit;
    }


}