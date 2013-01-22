<?php

class PayController extends Hapyfish2_Controller_Action_Page
{
    protected $uid;

    protected $info;

    protected $_aryPay = array(array('id' => 1, 'name' => '10金币',   'price' => 100,   'gold' => 10),
                         	   array('id' => 2, 'name' => '100金币',  'price' => 1000,  'gold' => 105),
                         	   array('id' => 3, 'name' => '200金币',  'price' => 2000,  'gold' => 210),
                         	   array('id' => 4, 'name' => '500金币',  'price' => 5000,  'gold' => 580),
                         	   array('id' => 5, 'name' => '1000金币', 'price' => 10000, 'gold' => 1200));

    protected static $_alipayId = '10149028';
    protected static $_proxyCode = 'HAPPYFISH_KLSL';
    
    const ALIPAY_ID = '10149028';
    const PROXY_CODE = 'HAPPYFISH_KLSL';
    
    protected function failed($msg)
    {
    	$uid = $this->uid;
    	info_log($uid . ':' . $msg, 'tbpay-route-failed');
    }

    public function indexAction()
    {
		$uid = $this->uid;
		$user = Hapyfish2_Platform_Bll_User::getUser($uid);
		$user['face'] = $user['figureurl'];
		$user['gold'] = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
		$this->view->user = $user;
		
		$section = array();
		$note = '';
		$settingInfo = Hapyfish2_Ipanda_Bll_Paysetting::getInfo($uid);
		if ($settingInfo) {
			$section = $settingInfo['section'];
			$note = $settingInfo['note'];
		}
		$this->view->section = $section;
		$this->view->note = $note;
		$this->render();
    }
    
    //handle and go to taobao payment page
    public function routeAction()
    {
    	$uid = $this->uid;
    	$puid = $this->info['puid'];
        $type_id = $this->_request->getParam('type_id');
        if (empty($type_id)) {
        	exit;
        }
        
        $settingInfo = Hapyfish2_Ipanda_Bll_Paysetting::getInfo($uid);
        if (empty($settingInfo)) {
        	$this->renderHTML('充值数据出错或暂时关闭，请联系管理员。');
        }
        
        $section = $settingInfo['section'];
		if (!isset($section[$type_id])) {
			exit;
		}
		
		$select = $section[$type_id];
		if ($select['open'] != 1) {
			$this->renderHTML('该选项已关闭，请选择其它选项。');
		}
		$amount = $select['amount'];
		//以分为单位
		$price = $amount*100;
        $gold = $select['gold']['num'];
        $itemName = $select['gold']['title'];

        if ($price <= 0 || empty($itemName)) {
            exit;
        }

        $orderId = Hapyfish2_Ipanda_Bll_Payment::createPayOrderId($puid);
        $buyer_time = time();
        //call api
        $params = array();
        $params['format'] = 'json';
        $params['item_id'] = $type_id;
        $params['item_version_id'] = 2;
        $params['total_price'] = $price;
        $params['item_name'] = $itemName;
        $params['item_version_name'] = '金币';
        $params['page_ret_url'] = HOST . '/callback/paydone?';
        $params['proxy_code'] = self::PROXY_CODE;
        $params['outer_order_id'] = $orderId;
        $params['buyer_time'] = $buyer_time;
        $params['description'] = '';
        $params['alipay_id'] = self::ALIPAY_ID;
        $rest = Taobao_Rest::getInstance();
        $rest->setUser($puid, $this->info['session_key']);
        $data = $rest->jianghu_getVasIsvUrl($params);
		//info_log('retdata:'.json_encode($data), 'aaa');
        if (empty($data) || !is_array($data) || !isset($data['vas_isv_url_get_response']['vas_isv_url'])) {
        	$this->failed('101');
            //request timeout,please try again later.
            $this->renderHTML('请求超时，请稍后重试。');
        }
        
  		$dataUrl = $data['vas_isv_url_get_response']['vas_isv_url'];
		if (isset($dataUrl['aplipay_isv_address'])) {
			//create pay order
	        $tradeNo = $dataUrl['order_id'];
	        $rst = Hapyfish2_Ipanda_Bll_Payment::createOrder($orderId, $uid, $amount, $gold, $tradeNo, $buyer_time);
			if ($rst) {
				return $this->_redirect($dataUrl['aplipay_isv_address']);
			}
			$this->failed('102');
			$msg = isset($dataUrl['message']) ? $dataUrl['message'] : '支付失败';
			$this->renderHTML($msg);
	    } else {
			if (1 == $dataUrl['status']) {
				//create pay order
				$tradeNo = $dataUrl['order_id'];
				$rst = Hapyfish2_Ipanda_Bll_Payment::createOrder($orderId, $uid, $amount, $gold, $tradeNo, $buyer_time);
				if ($rst) {
					$order = Hapyfish2_Ipanda_Bll_Payment::getOrder($uid, $orderId);
					if ($order['status'] == 1) {
						return $this->_redirect('/callback/payfinish');
					}
					$payRst = Hapyfish2_Ipanda_Bll_Payment::completeOrder($uid, $order);
					if ($payRst == 0) {
						$log = Hapyfish2_Util_Log::getInstance();
						$log->report('tbpaydone', array($orderId, $amount, $tradeNo, $uid, 1));
						return $this->_redirect('/callback/payfinish');
					} else {
						$this->failed('103');
					}
				} else {
					$this->failed('104');
				}
		        $msg = isset($dataUrl['message']) ? $dataUrl['message'] : '支付失败';
		        $this->renderHTML($msg);
        	} else {
        	    $this->failed('105');
        		$msg = isset($dataUrl['message']) ? $dataUrl['message'] : '支付失败';
				$this->renderHTML($msg);
        	}
        }
		
        exit;
    }
    
	public function topAction()
	{
		$uid = $this->uid;
		$user = Hapyfish2_Platform_Bll_User::getUser($uid);
		$user['face'] = $user['figureurl'];
		$user['gold'] = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);

		$this->view->user = $user;
		$this->render();
	}

    public function logAction()
    {
		$uid = $this->uid;
		$user = Hapyfish2_Platform_Bll_User::getUser($uid);
		$user['face'] = $user['figureurl'];
		$user['gold'] = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);

    	$logs = Hapyfish2_Ipanda_Bll_PaymentLog::getPayment($uid, 50);
    	if (!$logs) {
    		$count = 0;
    		$logs = '[]';
    	} else {
    		$count = count($logs);
    		$logs = json_encode($logs);
    	}
    	$pageSize = 25;
    	$this->view->user = $user;
		$this->view->logs = $logs;
        $this->view->count = $count;
        $this->view->pageSize = 25;
        $this->view->pageNum = ceil($count/$pageSize);
        $this->render();
    }

    //go to taobao payment page
    public function gopayAction()
    {
    	$uid = $this->uid;
        $buy_type = $_GET;
        $buy_type = array_keys($buy_type);

        //$buy_type
        //["callback\/apipay","btnOrder1","uid"]
        $buy_type = str_replace('btnOrder', '', $buy_type[1]);

        //check buy_type
        $price = 0;
        ///以分为单位
        $payment = $this->_aryPay;
        $gold = 0;
        $itemName = '';
        foreach ($payment as $item) {
            if ($buy_type == $item['id']) {
                $price = $item['price'];
                $gold = $item['gold'];
                $itemName = $item['name'];
                break;
            }
        }

        if ($price <= 0 || empty($buy_type) || empty($itemName)) {
            exit;
        }

        $rowUser = Hapyfish2_Platform_Bll_User::getUser($uid);
        $orderId = Hapyfish2_Ipanda_Bll_Payment::createPayOrderId($rowUser['puid']);
        $buyer_time = time();
        //call api
        $params = array();
        $params['format'] = 'json';
        $params['item_id'] = $buy_type;
        $params['item_version_id'] = 2;
        $params['total_price'] = $price;
   	 	/*if ($uid == 10650884) {
        	$params['total_price'] = 1;
        }*/
        $params['item_name'] = $itemName;
        $params['item_version_name'] = '金币';
        $params['page_ret_url'] = HOST . '/callback/paydone?';
        $params['proxy_code'] = self::$_proxyCode;
        $params['outer_order_id'] = $orderId;
        $params['buyer_time'] = $buyer_time;
        $params['description'] = '';
        $params['alipay_id'] = self::$_alipayId;//'10149028';happyforest
        $rest = Taobao_Rest::getInstance();
        $rest->setUser($this->info['puid'], $this->info['session_key']);
        $data = $rest->jianghu_getVasIsvUrl($params);
		//info_log('retdata:'.json_encode($data), 'aaa');
        if (empty($data) || !is_array($data) || !isset($data['vas_isv_url_get_response']['vas_isv_url'])) {
            info_log('101' ,'tbpayfailed');
        	echo '<html><body>request timeout,please try again later.</body></html>';
            exit;
        }
  		$dataUrl = $data['vas_isv_url_get_response']['vas_isv_url'];
        try {
	        if (isset($dataUrl['aplipay_isv_address'])) {
		        //create pay order
		        $amount = (int)($price/100);
		        $tradeNo = $dataUrl['order_id'];
		        $rst = Hapyfish2_Ipanda_Bll_Payment::createOrder($orderId, $uid, $amount, $gold, $tradeNo, $buyer_time);
                if ($rst) {
                    return $this->_redirect($dataUrl['aplipay_isv_address']);
                }
                info_log('102' ,'tbpayfailed');
		        $msg = isset($dataUrl['message']) ? $dataUrl['message'] : '支付失败';
        		echo "<html><body>$msg</body></html>";
	            exit;
		    }
	        else {
	        	if (1 == $dataUrl['status']) {
    	        	//create pay order
    		        $amount = (int)($price/100);
    		        $tradeNo = $dataUrl['order_id'];
    		        $rst = Hapyfish2_Ipanda_Bll_Payment::createOrder($orderId, $uid, $amount, $gold, $tradeNo, $buyer_time);
                    if ($rst) {
                        $order = Hapyfish2_Ipanda_Bll_Payment::getOrder($uid, $orderId);
                        if ($order['status'] == 1) {
                            return $this->_redirect('/callback/payfinish');
                        }
                        $payRst = Hapyfish2_Ipanda_Bll_Payment::completeOrder($uid, $order);
                        if ($payRst == 0) {
                            $log = Hapyfish2_Util_Log::getInstance();
		                    $log->report('tbpaydone', array($orderId, $amount, $tradeNo, $uid, 1));
                            return $this->_redirect('/callback/payfinish');
                        }
                        else {
                            info_log('103' ,'tbpayfailed');
                        }
                    }
                    else {
                        info_log('105' ,'tbpayfailed');
                    }
			        //$msg = $dataUrl['message'];
			        $msg = isset($dataUrl['message']) ? $dataUrl['message'] : '支付失败';
	        		echo "<html><body>$msg</body></html>";
		            exit;
	        	}
	        	else {
	        	    info_log('104' ,'tbpayfailed');
	        		$msg = isset($dataUrl['message']) ? $dataUrl['message'] : '支付失败';
	        		echo "<html><body>$msg</body></html>";
		            exit;
	        	}
	        }
        } catch (Exception $e) {
            echo '-100';
            exit;
        }

        info_log('105' ,'tbpayfailed');
		echo "<html><body>Please retry.</body></html>";
		exit;
    }

    public function listorderAction()
    {
        $uid = $this->uid;
		$user = Hapyfish2_Platform_Bll_User::getUser($uid);
		$user['face'] = $user['figureurl'];
		$user['gold'] = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);

        $dalPay = Hapyfish2_Ipanda_Dal_PayOrder::getDefaultInstance();
        $lstPay = $dalPay->listOrder($uid, 1, 20);
		if ($lstPay) {
			$count = count($lstPay);
		} else {
			$count = 0;
		}
		$this->view->logs = $lstPay;
        $this->view->count = $count;
        $this->view->user = $user;
        $this->render();
    }

    public function getorderAction()
    {
    	$uid = $this->uid;
    	$orderid = $this->_request->getParam('id');
    	$rowPay = Hapyfish2_Ipanda_Bll_Payment::getOrder($uid, $orderid);
    	if (empty($rowPay) || $rowPay['uid'] != $uid) {
    		echo '<html><body>Failed!<br/><a href="/pay/listorder">返回</a></body></html>';
    		exit;
    	}
    	$rest = Taobao_Rest::getInstance();
        $rest->setUser($uid, $this->info['session_key']);
        $data = $rest->jianghu_getVasIsvInfo($rowPay['orderid'], self::$_proxyCode, $rowPay['order_time']);
    	if (empty($data) || !is_array($data) || !isset($data['vas_isv_info'])) {
        	echo '<html><body>Failed!<br/><a href="/pay/listorder">返回</a></body></html>';
    		exit;
        }

        $status = -1;
        if (1 == $data['vas_isv_info']['status']) {
        	if (0 == $rowPay['status']) {
        		//trade check success,insert into paylog
		        $status = Hapyfish2_Ipanda_Bll_Payment::completeOrder($uid, $rowPay);
        	} else {
        		$status = 0;
        	}
        }
        
        if ($status == 0) {
        	$msg = "订单号：$orderid 支付已完成";
        } else {
        	$msg = "订单号：$orderid 支付未完成";
        }

    	echo '<html><body>'.$msg.'<br/><a href="/pay/listorder">返回</a></body></html>';
    	exit;
    }

    public function payfinishAction()
    {
        $this->render();
    }
}