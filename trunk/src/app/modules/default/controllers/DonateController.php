<?php

/**
 * application donate controller
 *
 * @copyright  Copyright (c) 2011 Hapyfish
 * @create    2011/10/19    zx
 */
class DonateController extends Hapyfish2_Controller_Action_Page
{
    /**
     * index Action
     *
     */
    public function indexAction()
    {
        $info = $this->info;
        $uid = $this->uid;

        if (1 == APP_STATUS_DEV) {
    	    $this->view->actionUrl = 'https://mapi.alipay.com/gateway.do';
        }
        else {
            $this->view->actionUrl = 'http://mapi4.p63.alipay.net/gateway.do';
        }

    	//$part1='_input_charset=UTF-8&buyer_account_name=aa01@alitest.com&donate_item_name=2011063012223879231&notify_url=http://ipanda.happyfish001.com/callback/donatedone&out_trade_no=13197066623766_1001_10901092&partner=2088102000524200&return_url=http://ipanda.happyfish001.com/callback/donatefinish&service=alipay.donate.trade.create&total_fee=3';
    	//$part2='bt348s7g9y3xtbm7fs5d5s97gzbsdegy';
    	//echo md5($part1.$part2);
    	//exit;
        $this->render();
    }

    public function createtradeAction()
    {
        // 线上
        if (1 == APP_STATUS_DEV) {
            $md5Key = 'hcczusy25cbtvgbyxat5uq7pkzsuvei5';
            $partner = '2088302111803535';//2088302111803535
            $donate_item_name = '2011110114161830222';
        }
        // 测试
        else {
            $md5Key = 'bt348s7g9y3xtbm7fs5d5s97gzbsdegy';
            $partner = '2088102000524200';//测试
            $donate_item_name = '2011063012223879231';
        }

        $info = $this->info;
        $uid = $this->uid;
        $puid = $info['puid'];

        $fee = $this->_request->getParam('fee');
        if (!is_numeric($fee) || $fee<=0) {
            exit;
        }

        $donateid = Hapyfish2_Ipanda_Bll_Donate::createDonateId($puid);
        if (empty($donateid)) {
            exit;
        }

        $params = array();
        $params['service'] = 'alipay.donate.trade.create';
        $params['partner'] = $partner;
        $params['donate_item_name'] = $donate_item_name;
        $params['out_trade_no'] = $donateid;
        $params['total_fee'] = $fee;
        $params['notify_url'] = HOST . '/callback/donatedone';
        $params['return_url'] = HOST . '/callback/donatefinish';
        $params['_input_charset'] = 'UTF-8';
        //$params['buyer_account_name'] = 'aa01@alitest.com';
        ksort($params);
        $tmp = array();
        foreach ($params as $key => $val) {
            $tmp[] = $key.'='.$val;
        }
        $queryStr = implode('&', $tmp);

        $params['sign'] = md5($queryStr.$md5Key);
        $params['sign_type'] = 'MD5';

        //save donate order in db
        $gold = 0;
        $tradeNo = '';
        $createTime = time();
        $rst = Hapyfish2_Ipanda_Bll_Donate::createDonate($donateid, $donate_item_name, $uid, $fee, $gold, $tradeNo, $createTime);
        if ($rst) {
            echo json_encode($params);
        }
        exit;
    }

    public function listdonateAction()
    {
        $rst = Hapyfish2_Ipanda_Tool_DonateRank::listDonate();
	    foreach ($rst as $key => &$row) {
	        $user = Hapyfish2_Platform_Bll_User::getUser($row['uid']);
	        $row['name'] = ($user['name']);
            $cmpTm[$key]  = $row['complete_time'];
        }
        array_multisort($cmpTm, SORT_DESC, $rst);

        if (!$rst) {
    		$count = 0;
    		$rst = array();
    	} else {
    		$count = count($rst);
    		//$rst = json_encode($rst);
    	}
    	$pageSize = 25;

    	$result = array();
    	$result['list'] = $rst;
        $result['count'] = $count;
        $result['pageSize'] = $pageSize;
        $result['pageNum'] = ceil($count/$pageSize);
	    echo json_encode($result);
	    exit;
    }

}