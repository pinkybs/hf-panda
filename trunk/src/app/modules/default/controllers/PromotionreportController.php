<?php

class PromotionreportController extends Zend_Controller_Action
{

    public function init()
    {
    }

	protected function vaild()
	{
	}

    protected function echoResult($data)
    {
    	$data['errno'] = 0;
    	echo json_encode($data);
    	exit;
    }

    protected function echoError($errno, $errmsg)
    {
    	$result = array('errno' => $errno, 'errmsg' => $errmsg);
    	echo json_encode($result);
    	exit;
    }

    public function noopAction()
    {
    	$data = array('id' => SERVER_ID, 'time' => time());
    	$this->echoResult($data);
    }


    //活动连接统计
    public function campaignAction()
    {
        $gameUrl = $this->getGameUrl();

        $promotecode = $this->_request->getParam('promotecode');
        if (empty($promotecode)) {
            $this->_redirect($gameUrl);
            exit('Thanks.');
        }

        $id = base64_decode($promotecode);

        if (!$id) {
            $this->_redirect($gameUrl);
            exit('Thank U.');
        }

        $prom = new Hapyfish2_Ipanda_Stat_Bll_Promotion($id);
        if ($prom->getErrCode()) {
            $this->_redirect($gameUrl);
            exit('Thank U For Visit.');
        }
        $rst = $prom->report('promotion');

        if ($rst) {
            //set cookie
            setcookie('hf_fromcamp', $promotecode, 0, '/', str_replace('http://', '.', HOST));
        }

        //redirect
        $gameUrl = $this->getGameUrl();
        if (strpos($gameUrl, '?') === false) {
            $gameUrl .= '?';
        }
        else {
            $gameUrl .= '&';
        }
        $gameUrl .= 'hf_fromcamp='.$promotecode;

        $this->_redirect($gameUrl);
        exit();
    }

    public function feedAction()
    {
        $gameUrl = $this->getGameUrl();

        $promotecode = $this->_request->getParam('promotecode');
        if (empty($promotecode)) {
            $this->_redirect($gameUrl);
            exit();
        }

        $id = base64_decode($promotecode);
        if (!$id) {
            $this->_redirect($gameUrl);
            exit();
        }

        $prom = new Hapyfish2_Ipanda_Stat_Bll_Promotion($id);
        if ($prom->getErrCode()) {
            $this->_redirect($gameUrl);
            exit();
        }
        $rst = $prom->report('feedlink');

        //redirect
        if (strpos($gameUrl, '?') === false) {
            $gameUrl .= '?';
        }
        else {
            $gameUrl .= '&';
        }
        $gameUrl .= 'hf_fromcamp='.$promotecode;

        $this->_redirect($gameUrl);
        exit();
    }

    protected function getGameUrl()
    {
        return 'http://yingyong.taobao.com/show.htm?app_id=109001';
    }

    protected function getClientIP()
    {
    	$ip = false;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) {
				array_unshift($ips, $ip);
				$ip = false;
			}
			for ($i = 0, $n = count($ips); $i < $n; $i++) {
				if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
					$ip = $ips[$i];
					break;
				}
			}
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
    }
}