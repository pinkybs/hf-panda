<?php

class HospitalController extends Hapyfish2_Controller_Action_Api
{    
    /*
     * 动物 解锁列表
     * 
     */
	//医院初始化
    public function initdataAction()
    {
    	$uid = $this->uid;
    	$puid = $this->_request->getParam('uid');
    	if($puid == 0 ){
    		$puid = $uid;
    	}
    	$result['status'] = 1;
    	$result['hospitalList'] = Hapyfish2_Ipanda_Bll_Hospital::getInit($puid);
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
    }
    //建造医院
    public function buildAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$lockkey = 'i:u:lock:h:b' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$goodlsit = Hapyfish2_Ipanda_Bll_Hospital::buildHospital($uid, $id);
    	$goods = array();
    	$result['id'] = $id;
    	if(!isset($goodlsit['error'])){
    		$goods = $goodlsit;
    		$result['status'] = 1;
    	}else{
    		$result['status'] = -1;
    		$error = array('eid' => $goodlsit['error']);
    		if(isset($goodlsit['list'])){
    			$error['data'][] = $goodlsit['list'];
    		}
    		$lock->unlock($lockkey);
			$this->echoError($error);
			
    	}
        $update['goodsVo']['goods'] = $goods;
		$update['goodsVo']['bid'] = $id;
		$update['goodsVo']['type'] = 4;
		$lock->unlock($lockkey);
		$data = $this->returnResult($result, $update);
        $this->echoResult($data);
    }
    //收货药物
    public function getdrugAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$lockkey = 'i:u:lock:h:g:d' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$goodlsit = Hapyfish2_Ipanda_Bll_Hospital::getDurg($uid, $id);
    	$goods = array();
    	$result['id'] = $id;
    	if(is_array($goodlsit)){
    		$goods[] = $goodlsit;
    		$result['status'] = 1;
    	}else{
    		$result['status'] = -1;
    		$lock->unlock($lockkey);
    		 $this->echoErrResult($goodlsit);
    	}
        
        $update['goodsVo']['goods'] = $goods;
		$update['goodsVo']['bid'] = $id;
		$update['goodsVo']['type'] = 4;
		$result['status'] = 1;
		$lock->unlock($lockkey);
		$data = $this->returnResult($result, $update);
        $this->echoResult($data);
    }
    
//    public function gethospitalinfoAction()
//    {
//    	$id = $this->_request->getParam('id');
//    	$uid = $this->uid;
//    	if($id < 1 || $id > 4){
//    		 $this->echoErrResult(-1);
//    	}
//    	$result = Hapyfish2_Ipanda_Bll_Hospital::getHostitalDetail($uid, $id);
//    	$data = $this->returnResult($result);
//    	$this->echoResult($data);
//    }
	//生产药品
    public function productdrugAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$cid = $this->_request->getParam('cid');
    	$lockkey = 'i:u:lock:d:b' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$endTime = Hapyfish2_Ipanda_Bll_Hospital::productDrug($uid, $cid, $id);
    	if(isset($endTime['error'])){
    		$result['status'] = -1;
    		$error = array('eid' => $endTime['error']);
    		if(isset($endTime['list'])){
    			$error['data'][] = $endTime['list'];
    		}
    		$lock->unlock($lockkey);
			$this->echoError($error);
    		
    	}
    	$result['status'] = 1;
    	$result['leaveTime'] = $endTime['create_time'];
    	$result['totalTime'] = $endTime['create_time'];
    	$lock->unlock($lockkey);
		$data = $this->returnResult($result);
        $this->echoResult($data);
    }
    //加速做药
    public  function completeproductAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$lockkey = 'i:u:lock:d:b:c' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$endTime = Hapyfish2_Ipanda_Bll_Hospital::completeproduct($uid, $id);
    	if($endTime){
    		$error = array('eid' => $endTime);
    		if($endTime == -803){
    			$error['data'] = array('cid' => 443);
    		}
    		$lock->unlock($lockkey);
			$this->echoError($error);
    	}
    	$result['status'] = 1;
    	$result['hospitalVo'] = Hapyfish2_Ipanda_Bll_Hospital::gethospitalVo($uid, $id);
    	$lock->unlock($lockkey);
		$data = $this->returnResult($result);
        $this->echoResult($data);
    }
    //生病动物信息
    public function initdisdecorateAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$result = Hapyfish2_Ipanda_Bll_Hospital::initDisDecorate($uid, $id);
    	$data = $this->returnResult($result);
    	$this->echoResult($data);
    }
    //墓碑初始化
 	public function inittombstoneAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$result = Hapyfish2_Ipanda_Bll_Hospital::initTombstone($uid, $id);
    	if(!is_array($result)){
    		 $this->echoErrResult($result);
    	}
    	$data = $this->returnResult($result);
    	$this->echoResult($data);
    }
    //医治
    public function healAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$lockkey = 'i:u:lock:d:b:h' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$results = Hapyfish2_Ipanda_Bll_Hospital::heal($uid, $id);
    	if(isset($results['error'])){
    		$error = array('eid' => $results['error']);
    		if(isset($results['list'])){
    			$error['data'][] = $results['list'];
    		}
    		$lock->unlock($lockkey);
			$this->echoError($error);
    		
    	}
    	$result['status'] = 1;
    	$result['class_name'] = $results['class_name'];
    	$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid, true);
    	$lock->unlock($lockkey);
    	$data = $this->returnResult($result);
    	$this->echoResult($data);
    }
    //祈祷复活
    public function prayAction()
    {
    	$uid = $this->uid;
    	$id = $this->_request->getParam('id');
    	$lockkey = 'i:u:lock:d:b:p' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$result = Hapyfish2_Ipanda_Bll_Hospital::pray($uid, $id);
    	if($result){
    		 $lock->unlock($lockkey);
    		 $this->echoErrResult($result);
    	}
    	$result['status'] = 1;
    	$result['decorateVo'] = Hapyfish2_Ipanda_Bll_Decorate::getList($uid, true);
    	$result['phytotronVo'] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		$buildingListOnForest = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$result['buildingVo'] = $buildingListOnForest['building_list'];
    	$lock->unlock($lockkey);
    	$data = $this->returnResult($result);
    	$this->echoResult($data);
    }
    //check 生病动物是否死亡
    public function checkdeathAction()
    {
    	$uid = $this->uid;
    	$lockkey = 'i:u:lock:d:b:p' . $uid;
        $lock = Hapyfish2_Cache_Factory::getLock($uid);
        //get lock
        $ok = $lock->lock($lockkey);
        if (!$ok) {
            $this->echoErrResult(-1);
        }
    	$results = Hapyfish2_Ipanda_Bll_Hospital::checkDeath($uid);
    	if(!empty($results)){
    		$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid, true);
    		$result['phytotronVo'] = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
			$buildingListOnForest = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
			$result['buildingVo'] = $buildingListOnForest['building_list'];
    	}
    	$result['status'] = 1;
    	$lock->unlock($lockkey);
    	$data = $this->returnResult($result);
    	$this->echoResult($data);
    }
    //获取用户材料
    public function getusermaterialAction()
    {
    	$uid = $this->uid;
    	$list = Hapyfish2_Ipanda_Bll_Hospital::getUserMaterial($uid);
    	$result['status'] = 1;
    	$result['list'] = $list;
    	$data = $this->returnResult($result);
    	$this->echoResult($data);
    }
 }
