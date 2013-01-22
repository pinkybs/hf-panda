<?php

/**
 * application callback controller
 *
 * @copyright  Copyright (c) 2009 Community Factory Inc. (http://communityfactory.com)
 * @create    2009/08/07    HLJ
 */
class CallbackController extends Zend_Controller_Action
{

    /**
     * index Action
     *
     */
    public function indexAction()
    {
    	echo 'callback';
    	exit;
    }

    //pay method ****************
	public function paydoneAction()
    {
    	//{"sign":"223c95a5d1c7539806e0f866dcb37656","status":"1","message":null,"order_id":"2283436","sign_time":"Wed Jan 05 13:43:23 CST 2011","outer_order_id":"5432","proxy_code":"HAPPYFISH"}
    	$sig = $_GET['sign'];
    	$status = $_GET['status'];
    	$trade_no = $_GET['order_id'];
    	$out_trade_no = $_GET['outer_order_id'];
    	$callbackurl = HOST . '/callback/paydone?';
    	if ($sig != md5($callbackurl.$out_trade_no) || empty($status)) {
    		echo 'validate failed';
    		exit();
    	}

        info_log(Zend_Json::encode($_GET), 'payApi_syn_'.date('Ymd'));

        $aryInfo = explode('_', $out_trade_no);
        $puid = $aryInfo[2];
        $ok = true;
        $rowUser = Hapyfish2_Platform_Bll_UidMap::getUser($puid);
        $order = Hapyfish2_Ipanda_Bll_Payment::getOrder($rowUser['uid'], $out_trade_no);
        if ($order && $order['status'] == 0) {
            $ok = Hapyfish2_Ipanda_Bll_Payment::completeOrder($rowUser['uid'], $order);
            if ($ok == 0) {
            	$amount = $order['amount'];
                //file log
                $log = Hapyfish2_Util_Log::getInstance();
                $log->report('tbpaydone', array($out_trade_no, $amount, $trade_no, $rowUser['uid'], 2));
            }
        }

        $this->_redirect('/pay/payfinish');
        exit;
    }

    //pay method notify repeat ****************
	public function paydonenotifyAction()
    {
    	//{"sign":"223c95a5d1c7539806e0f866dcb37656","status":"1","message":null,"order_id":"2283436","sign_time":"Wed Jan 05 13:43:23 CST 2011","outer_order_id":"5432","proxy_code":"HAPPYFISH"}
    	$sig = $_POST['sign'];
    	$status = $_POST['status'];
    	$trade_no = $_POST['order_id'];
    	$out_trade_no = $_POST['outer_order_id'];
    	$callbackurl = HOST . '/callback/paydonenotify';
    	if ($sig != md5($callbackurl.$out_trade_no) || empty($status)) {
    		echo 'validate failed';
    		exit();
    	}

        info_log(Zend_Json::encode($_POST), 'payApi_asyn_'.date('Ymd'));

        $aryInfo = explode('_', $out_trade_no);
        $puid = $aryInfo[2];
        $ok = true;
        $rowUser = Hapyfish2_Platform_Bll_UidMap::getUser($puid);
        $order = Hapyfish2_Ipanda_Bll_Payment::getOrder($rowUser['uid'], $out_trade_no);
        if ($order && $order['status'] == 0) {
            $ok = Hapyfish2_Ipanda_Bll_Payment::completeOrder($rowUser['uid'], $order);
            if ($ok == 0) {
            	$amount = $order['amount'];
                //file log
                $log = Hapyfish2_Util_Log::getInstance();
                $log->report('tbpaydone', array($out_trade_no, $amount, $trade_no, $rowUser['uid'], 3));
            }
        }

        $this->_redirect('/callback/payfinish');
        exit;
    }

    public function payfinishAction()
    {
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
        $this->render();
    }



	public function donatedoneAction()
    {
        info_log(json_encode($_REQUEST), 'donatetest');

        $donateid = $_REQUEST['outTradeNO'];
        $status = $_REQUEST['status'];
        $sign = $_REQUEST['sign'];

        if (empty($donateid) || empty($sign)) {
            echo 'invalid request';
            exit;
        }

        if ($status != 'SUCCESS') {
            echo 'status wrong';
            exit;
        }

        $aryInfo = explode('_', $donateid);
        if (!$aryInfo || count($aryInfo) != 3) {
            echo 'outTradeNO wrong';
            exit;
        }

        //validate
        $ct = 'outTradeNO='.$donateid.'&status='.$status;
        $key = file_get_contents(CONFIG_DIR."/donatepubkey.pem");//'MIIBuDCCASwGByqGSM44BAEwggEfAoGBAP1/U4EddRIpUt9KnC7s5Of2EbdSPO9EAMMeP4C2USZpRV1AIlH7WT2NWPq/xfW6MPbLm1Vs14E7gB00b/JmYLdrmVClpJ+f6AR7ECLCT7up1/63xhv4O1fnxqimFQ8E+4P208UewwI1VBNaFpEy9nXzrith1yrv8iIDGZ3RSAHHAhUAl2BQjxUjC8yykrmCouuEC/BYHPUCgYEA9+GghdabPd7LvKtcNrhXuXmUr7v6OuqC+VdMCz0HgmdRWVeOutRZT+ZxBxCBgLRJFnEj6EwoFhO3zwkyjMim4TwWeotUfI0o4KOuHiuzpnWRbqN/C/ohNWLx+2J6ASQ7zKTxvqhRkImog9/hWuWfBpKLZl6Ae1UlZAFMO/7PSSoDgYUAAoGBANYf4H6tJq3ym5FcmNfkc8wLE/NATQS3MYprYvXML+CcW23z4ujfprvZd/yVdBf4iW3Ylnb2shTJTZwJ+2uUWyP8s8mjT5sLTug9ReOk3hH1VBrgKkMz4M32iL6vcc0NiymmtyPnSyO5QVUtOkKWA3LJMP3BhAvE3KtwY27Gfb11';//
        $pukeyid = openssl_get_publickey($key);
        $signature = base64_decode($sign);
        $valid = openssl_verify($ct, $signature, $pukeyid, OPENSSL_ALGO_DSS1);
        if (!$valid) {
            echo 'sig valid error';
            exit;
        }

        $puid = $aryInfo[2];
        $rowUser = Hapyfish2_Platform_Bll_UidMap::getUser($puid);
        if (!$rowUser) {
            echo 'user not exist';
            exit;
        }

        $uid = $rowUser['uid'];
        $rst = Hapyfish2_Ipanda_Bll_Donate::completeDonate($uid, $donateid);
        if ($rst == 0) {
            $donate = Hapyfish2_Ipanda_Bll_Donate::getDonate($uid, $donateid);
            if ($donate) {
                //file log
                $log = Hapyfish2_Util_Log::getInstance();
                $log->report('donatedone', array($donateid, $donate['amount'], $uid));
            }
            echo 'SUCCESS';
        }
        else if ($rst == 1) {
            echo 'SUCCESS';
        }
        else {
            echo 'failed';
        }
        exit;
    }

    public function donatefinishAction()
    {
        $err = $this->_request->getParam('errmsg');
        if ($err) {
            $this->view->err = $err;
        }
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->staticUrl = STATIC_HOST;
        $this->view->hostUrl = HOST;
        $this->render();
    }

    public function dsacheckAction()
    {
        try {
            $ct = 'outTradeNO=7368134816501146&status=SUCCESS';
            $key = file_get_contents(CONFIG_DIR."/donatepubkey.pem");//'MIIBuDCCASwGByqGSM44BAEwggEfAoGBAP1/U4EddRIpUt9KnC7s5Of2EbdSPO9EAMMeP4C2USZpRV1AIlH7WT2NWPq/xfW6MPbLm1Vs14E7gB00b/JmYLdrmVClpJ+f6AR7ECLCT7up1/63xhv4O1fnxqimFQ8E+4P208UewwI1VBNaFpEy9nXzrith1yrv8iIDGZ3RSAHHAhUAl2BQjxUjC8yykrmCouuEC/BYHPUCgYEA9+GghdabPd7LvKtcNrhXuXmUr7v6OuqC+VdMCz0HgmdRWVeOutRZT+ZxBxCBgLRJFnEj6EwoFhO3zwkyjMim4TwWeotUfI0o4KOuHiuzpnWRbqN/C/ohNWLx+2J6ASQ7zKTxvqhRkImog9/hWuWfBpKLZl6Ae1UlZAFMO/7PSSoDgYUAAoGBANYf4H6tJq3ym5FcmNfkc8wLE/NATQS3MYprYvXML+CcW23z4ujfprvZd/yVdBf4iW3Ylnb2shTJTZwJ+2uUWyP8s8mjT5sLTug9ReOk3hH1VBrgKkMz4M32iL6vcc0NiymmtyPnSyO5QVUtOkKWA3LJMP3BhAvE3KtwY27Gfb11';//
            $pukeyid = openssl_get_publickey($key);
            //$keyData = openssl_pkey_get_details($pukeyid);
            //print_r($keyData);exit;
            $signature = base64_decode('MC0CFA7phPESMWgA3dGVEgaLn0WjQMeRAhUAkAzIs4EpzkxJH7hSVW7waL2Dg9c=');
            $valid = openssl_verify($ct, $signature, $pukeyid, OPENSSL_ALGO_DSS1);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
        echo "Signature validity: ". json_encode($valid);
        echo "Signature validity: ". $valid;
        exit;
    }

    /**
     * magic function
     *   if call the function is undefined,then echo undefined
     *
     * @param string $methodName
     * @param array $args
     * @return void
     */
    function __call($methodName, $args)
    {
        echo 'undefined method name: ' . $methodName;
        if (!empty($args) && is_array($args)) {
        	echo ', args:' . http_build_query($args);
        }
        exit;
    }

}