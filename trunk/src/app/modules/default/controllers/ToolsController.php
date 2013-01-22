<?php

class ToolsController extends Zend_Controller_Action
{
	function vaild()
	{

	}

	function check()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			echo 'uid can not empty';
			exit;
		}

		$isAppUser = Hapyfish2_Ipanda_Cache_User::isAppUser($uid);
		if (!$isAppUser) {
			echo 'uid error, not app user';
			exit;
		}

		return $uid;
	}
	
	public function initswfAction()
    {
    	$uid = $this->_request->getParam('uid');

        $this->uid = $uid;
        $user = Hapyfish2_Platform_Bll_User::getUser($uid);
        $puid = $user['puid'];
        $t = time();
        $rnd = mt_rand(1, ECODE_NUM);
        //simulate
        $session_key = md5($t);

        $sig = md5($uid . $puid . $session_key . $t . $rnd . APP_SECRET);

        $skey = $uid . '.' . $puid . '.' . base64_encode($session_key) . '.' . $t . '.' . $rnd . '.' . $sig;

        setcookie('hf_skey', $skey , 0, '/', str_replace('http://', '.', HOST));
        /*
    	$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		require (CONFIG_DIR . '/swfconfig.php');

    	if ($userLevel < 5) {
			if ($this->info['rnd'] > 0) {
				$swfResult_0['edata'] = HOST . '/api/edata?t=' . time();
			}
			$this->echoResult($swfResult_0);
    	} else {
			if ($this->info['rnd'] > 0) {
				$swfResult_1['edata'] = HOST . '/api/edata?t=' . time();
			}
			$this->echoResult($swfResult_1);
		}
		*/
        echo "OK";exit;
    }
	function registerAction()
	{

	    $puid = $this->_request->getParam('puid');
		$uidInfo = Hapyfish2_Platform_Cache_UidMap::getUser($puid);
		if (!$uidInfo) {
    		$uidInfo = Hapyfish2_Platform_Cache_UidMap::newUser($puid);
    		if (!$uidInfo) {
    			echo 'inituser error: 1';
    			exit;
    		}
		}
		$name = $this->_request->getParam('name');
		if (empty($name)) {
			$name = '测试' . $puid;
		}
		$figureurl = $this->_request->getParam('figureurl');
		if (empty($figureurl)) {
			$figureurl = 'http://hdn.xnimg.cn/photos/hdn521/20091210/1355/tiny_E7Io_11729b019116.jpg';
		}

		$uid = $uidInfo['uid'];
        $user = array();
        $user['uid'] = $uid;
        $user['puid'] = $puid;
        $user['name'] = $name;
        $user['figureurl'] = $figureurl;
        $user['gender'] = rand(0,1);
		Hapyfish2_Platform_Bll_User::addUser($user);

		$ret = Hapyfish2_Ipanda_Bll_User::joinUser($uid);
		var_dump($ret);
		var_dump($uid);exit;
	}

	function registertestAction()
	{
		$j = 1;
		for($i = 1022 ; $i < 1122 ; $i++)
		{
			$j++;
			$user = array(
				        	'uid' => $i,
				        	'puid' => time(),
				        	'name' => '测试'.$i,
				        	'figureurl' => 'http://img.kaixin001.com.cn/i/50_0_0.gif',
				        	'gender' => -1,
				        	'create_time' => 0
				        );
			Hapyfish2_Platform_Bll_User::addUser($user);
			Hapyfish2_Ipanda_Bll_User::joinUser($i);
		}

		echo "OK";exit;
	}
	function  updatefriendAction()
	{
		$uid = $this->_request->getParam('uid');
		$fids = $this->_request->getParam('fids');
		$fids = explode(",", $fids);

		Hapyfish2_Platform_Bll_Friend::updateFriend($uid, $fids);
		echo "好友列表更新";exit;
	}
	function cleartakelogAction()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			echo 'uid can not empty';
			exit;
		}
		
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		
		foreach ($list as $v)
		{
			Hapyfish2_Ipanda_Cache_Building::cleartakelog($uid, $v['id']);
		}
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		var_dump($list);
		foreach ($list as $v)
		{
			Hapyfish2_Ipanda_Cache_Phytotron::cleartakelog($uid, $v['id']);
		}
		echo "清理偷取记录";exit;
	}
	function getinitvoAction()
	{
		$ret = Hapyfish2_Ipanda_Bll_BasicInfo::getInitVo();
		var_dump($ret);
		exit;
	}
	function activeuserAction()
	{
		$uid = $this->_request->getParam('uid');
		//增加一个新森林，一个新动物
		if(empty($uid))
		{
			echo "error";exit;
		}
		$dalForest = Hapyfish2_Ipanda_Dal_Forest::getDefaultInstance();
		$dalForest->init($uid);

		$dalPhytotronAnimal = Hapyfish2_Ipanda_Dal_PhytotronAnimal::getDefaultInstance();
		$dalPhytotronAnimal->init($uid);
		echo "active ok";exit;
	}
	function adduserdataAction()
	{
		$uid = $this->_request->getParam('uid');
		$gold = $this->_request->getParam('gold');
		$love = $this->_request->getParam('love');
		$exp = $this->_request->getParam('exp');
		$energy = $this->_request->getParam('energy');
		if(empty($uid))
		{
			echo "error-uid";exit;
		}
		$uservo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
		if(empty($uservo))
		{
			echo "uid not exist";exit;
		}
		$awardRot = new Hapyfish2_Ipanda_Bll_Award();
		if($gold > 0)
		{
			$awardRot->setGold($gold, '1');
		}
		if($love > 0)
		{
			$awardRot->setlove($love);
		}
		if($exp > 0)
		{
			$awardRot->setExp($exp);
		}
		if($energy > 0)
		{
			$awardRot->setEnergy($energy);
		}
		$awardRot->sendOne($uid);
		echo "OK - $gold -$love - $exp - $energy";exit;
	}
	function updateenergyAction()
	{
		$energy = $this->_request->getParam('energy');
		$uid = $this->_request->getParam('uid');
		
		$energy_top = $this->_request->getParam('energy_top');
		if(empty($uid))
		{
			echo "error-uid";exit;
		}
		if(empty($energy_top))
		{
			$energy_top =30; 
		}
		Hapyfish2_Ipanda_HFC_User::updateUserEnergy($uid, array("energy" =>$energy ,"energy_top" => $energy_top ,"energy_recover_time" => time() ));
		echo "ok";exit;
	}
	function updatebuildingAction()
	{
		$uid = $this->_request->getParam('uid');
		$building_id = $this->_request->getParam('id');
		$table = $this->_request->getParam('table');
		if(empty($uid))
		{
			echo "empty-uid or empty id ";exit;
		}
		$info = $this->_request->getParam('info');
		if(empty($table))
		{
			$ret = Hapyfish2_Ipanda_Bll_Building::updateBuilding($uid, $building_id, $info);
		}
		else if(strtolower($table) == "decorate")
		{
			$ret = Hapyfish2_Ipanda_Bll_Decorate::update($uid, $building_id, $info);
		}
		else  if(strtolower($table) == "phytotron")
		{
			$ret = Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $building_id, $info);
		}

		if($ret)
		{
			echo "OK";
		}
		else
		{
			echo "error";
		}
		exit;
	}
	function updateuserexpAction()
	{
		$uid = $this->_request->getParam('uid');
		$exp = $this->_request->getParam('exp');
		if(empty($uid) || empty($exp))
		{
			echo "error-uid";exit;
		}
		$uservo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
		if(empty($uservo))
		{
			echo "uid not exist";exit;
		}
		$data = Hapyfish2_Ipanda_Bll_BasicInfo::getCurrentUserLevelByExp($exp);
		Hapyfish2_Ipanda_HFC_User::updateUserExp($uid, $exp,true);
		Hapyfish2_Ipanda_HFC_User::updateUserLevel($uid, $data['level']);
		echo "OK";exit;
	}
	function updateusermaterialAction()
	{
		$uid = $this->_request->getParam('uid');
		$cid = $this->_request->getParam('cid');
		$num = $this->_request->getParam('num');
		if(empty($uid))
		{
			echo "error-uid";exit;
		}
		if(!empty($cid))
		{
			$material = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
			$material[$cid]['count'] = $num;
			$material[$cid]['update'] = 1;
			Hapyfish2_Ipanda_HFC_Material::updateUserMaterial($uid, $material, true);
		}
		echo "OK";exit;
	}
	function clearbasiccacheAction()
	{
		$key = $this->_request->getParam('key');
		$key ="";
		if(empty($key))
		{
			$cache = Hapyfish2_Ipanda_Cache_BasicInfo::getBasicMC();
			$localcache = Hapyfish2_Cache_LocalCache::getInstance();

			$key = 'ipanda:userlevellist';
			$localcache->delete($key);
			$cache->delete($key);

			$key = 'ipanda:buildinglist';
			$localcache->delete($key);
			$cache->delete($key);

			$key = 'ipanda:phytotronunlocklist';
			$localcache->delete($key);
			$cache->delete($key);

			$key = 'ipanda:entendforestlist';
			$localcache->delete($key);
			$cache->delete($key);
			
			Hapyfish2_Ipanda_Cache_BasicInfo::loadNoticeList();
			echo "OK";
			exit;
		}
	}

	function loadinitdataAction()
	{
		$list = Hapyfish2_Ipanda_Cache_Memkey::getbasickey();
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$cache = Hapyfish2_Ipanda_Cache_BasicInfo::getBasicMC();
		foreach ($list as $v)
		{
			$key = $v;
			$localcache->delete($key);
			$cache->delete($key);
		}
		
		//重新刷新任务
		Hapyfish2_Ipanda_Cache_Basic_Info::loadAllTaskConditionInfo();
		Hapyfish2_Ipanda_Cache_Basic_Info::loadAllTaskInfo();
		
		Hapyfish2_Ipanda_Bll_BasicInfo::dumpInitData();
		echo 'done';
		exit;
	}

	function loadinitdataofallAction()
	{
		$list = Hapyfish2_Ipanda_Bll_Server::getWebList();
		if (!empty($list)) {
			$host = str_replace('http://', '', HOST);
			foreach ($list as $server) {
				$url = 'http://' . $server['local_ip'] . '/tools/loadinitdata';
				$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
				echo $url . ':' . $result . '<br/>';
			}
			
			//更新缓存版本
			//先更新mc
			$url = HOST . '/tools/updateversion?name=initData';
			$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
			echo 'update version result:' . $result . '<br/>';
			echo 'refresh:<br/>';
			foreach ($list as $server) {
				$url = 'http://' . $server['local_ip'] . '/tools/refreshversion';
				$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
				echo $url . ':' . $result . '<br/>';
			}
		}
		echo 'done';
		exit;
	}
	
	function refreshversionAction()
	{
		$data = Hapyfish2_Ipanda_Bll_CacheVersion::refresh();
		echo json_encode($data);
		exit;
	}
	
	function updateversionAction()
	{
		$name = $this->_request->getParam('name');
		if (empty($name)) {
			echo 'name empty!';
			exit;
		}
		$version = Hapyfish2_Ipanda_Bll_CacheVersion::update($name);
		echo $name . ':' . $version;
		exit;
	}
	
	function updateversionallAction()
	{
		$name = $this->_request->getParam('name');
		if (empty($name)) {
			echo 'name empty!';
			exit;
		}
		$list = Hapyfish2_Ipanda_Bll_Server::getWebList();
		if (!empty($list)) {
			//先更新mc
			$url = HOST . '/tools/updateversion?name=' . $name;
			$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
			echo 'update result:' . $result . '<br/>';
			$host = str_replace('http://', '', HOST);
			echo 'refresh:<br/>';
			foreach ($list as $server) {
				$url = 'http://' . $server['local_ip'] . '/tools/refreshversion';
				$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
				echo $url . ':' . $result . '<br/>';
			}
		}
		echo 'done';
		exit;
	}

	function clearusercacheAction()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			echo 'uid can not empty';
			exit;
		}
		$list = Hapyfish2_Ipanda_Cache_Memkey::getuserkey();
		$hfccache =  Hapyfish2_Cache_Factory::getHFC($uid);
		$cache = Hapyfish2_Cache_Factory::getMC($uid);
		foreach ($list as $k => $v)
		{
			$key = $v . $uid ;
			echo $key;echo"<br>";
			$hfccache->delete($key);
			$cache->delete($key);
		}

		echo "OK";
		exit;
	}
	function addintimacyAction()
	{
		$uid = $this->_request->getParam('uid');
		if (empty($uid)) {
			echo 'uid can not empty';
			exit;
		}
		$cid = $this->_request->getParam('cid');
		$num = $this->_request->getParam('num');
		if(empty($cid) || empty($num))
		{
			echo 'error ';
			exit;
		}
		Hapyfish2_Ipanda_Bll_PhytotronAnimal::addintimacy($uid, $cid,$num);
		echo "OK";exit;
	}

	public function checktaskAction()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskList();
		$i = 1;
		echo '==========================================================================================<br/>';
		foreach ($list as $task) {
			$next = json_decode($task['next_task_id'], true);
			if (!empty($next)) {
				foreach ($next as $id) {
					if (!isset($list[$id])) {
						echo '[' . $i++ . '] Task Id: ' . $task['id'] . ':next_task_id:' . $id . '(' . $task['next_task_id'] . ')<br/>';
						continue;
					}

					$preTask = $list[$id];
					$pre = json_decode($preTask['front_task_id'], true);
					if (empty($pre)) {
						echo '[' . $i++ . '] Task Id: ' . $task['id'] . ':next_task_id:' . $id . '(' . $task['next_task_id'] . '): front_task_id empty([])<br/>';
						continue;
					}

					if (!in_array($task['id'], $pre)) {
						echo '[' . $i++ . '] Task Id: ' . $task['id'] . ':next_task_id:' . $id . '(' . $task['next_task_id'] . '): not in Task(' . $preTask['id'] . ') front_task_id(' . $preTask['front_task_id'] . ')<br/>';
						continue;
					}
				}
			}
		}

		echo '==========================================================================================<br/>';

		echo '检查是否有孤立任务，只有前置，没有触发<br/>';
		echo '==========================================================================================<br/>';
		$i = 1;
		foreach ($list as $task) {
			$front = json_decode($task['front_task_id'], true);
			if (!empty($front)) {
				$ok = $this->isInNextTask($list, $task['id']);
				if (!$ok) {
					echo '[' . $i++ . '] Task Id: ' . $task['id'] . ':front_task_id:(' . $task['front_task_id'] . ')<br/>';
				}
			}
		}

		echo '==========================================================================================<br/>';
		exit;
	}

	private function isInNextTask(&$list, $id)
	{
		foreach ($list as $task) {
			$next = json_decode($task['next_task_id'], true);
			if (!empty($next)) {
				if (in_array($id, $next)) {
					return true;
				}
			}
		}

		return false;
	}

	public function updatetaskAction()
	{
		$uid = $this->_request->getParam('uid');
		$taskId = (int)$this->_request->getParam('id');

		if (empty($uid) || empty($taskId)) {
			echo '参数不对';
			exit;
		}

		$dalTask = Hapyfish2_Ipanda_Dal_Task::getDefaultInstance();
		$dalTask->clear($uid);
		
    	$key = 'i:u:alltask:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->delete($key);

		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		$openTask['list'] = array($taskId);
		$openTask['list2'] = array();
		$openTask['data'] = array();
		$openTask['buffer_list'] = array();

		Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
		
    	$key = 'i:u:taskstatus:' . $uid;
    	$cache->delete($key);

		echo 'ok';
		exit;
	}
	
	public function pushtaskAction()
	{
		$uid = $this->_request->getParam('uid');
		$taskId = (int)$this->_request->getParam('id');

		if (empty($uid) || empty($taskId)) {
			echo '参数不对';
			exit;
		}

		$dalTask = Hapyfish2_Ipanda_Dal_Task::getDefaultInstance();
		$dalTask->clear($uid);
		
    	$key = 'i:u:alltask:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->delete($key);

		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		$openTask['list'][] = $taskId;

		Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
		
    	$key = 'i:u:taskstatus:' . $uid;
    	$cache->delete($key);

		echo 'ok';
		exit;
	}

	public function dumpuserAction()
	{
		$uid = $this->_request->getParam('uid');
		Hapyfish2_Ipanda_Bll_DumpUser::dump($uid, true);
		echo 'ok';
		exit;
	}

    public function reloadgiftAction()
	{
		$key = 'ipanda:giftlist';
		$cache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
		$cache->delete($key);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$localcache->delete($key);

        Hapyfish2_Ipanda_Cache_Gift::loadBasicGiftList();

		$v = '1.0';
		$file = TEMP_DIR . '/giftvo.' . $v . '.cache';
		@unlink($file);

		echo SERVER_ID . 'OK';
		exit;
	}
	
    public function reloadgiftallAction()
	{
		$list = Hapyfish2_Ipanda_Bll_Server::getWebList();
		if (!empty($list)) {
			$host = str_replace('http://', '', HOST);
			foreach ($list as $server) {
				$url = 'http://' . $server['local_ip'] . '/tools/reloadgift';
				$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
				echo $url . ':' . $result . '<br/>';
			}
		}
		echo 'OK';
		exit;
	}

    public function cleargifttodaywishAction()
	{
	    $uid = $this->check();
        $dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
        $dalGift->deleteWish($uid);
        $mkey = 'i:u:gift:wish:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->delete($mkey);
        echo 'ok';
        exit;
	}

    public function cleargifttodaysentAction()
	{
        $uid = $this->check();
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $mkey = 'i:u:gift:sent:g:uids:' . $uid;
        $mkey2 = 'i:u:gift:sent:w:uids:' . $uid;
        echo $uid.'<br/>gift sent:'.json_encode($cache->get($mkey));
        echo '<br/>wish sent:'.json_encode($cache->get($mkey2));
        $cache->delete($mkey);
        $cache->delete($mkey2);
        echo 'clear ok';
        exit;
	}

    public function clearreceivegiftAction()
	{
	    $uid = $this->check();
        $dalGift = Hapyfish2_Ipanda_Dal_Gift::getDefaultInstance();
        $dalGift->deleteBag($uid);
        echo 'ok';
        exit;
	}
	
    public function updateactiveloginAction()
	{
	    $uid = $this->check();
        $day = $this->_request->getParam('day');
        if (empty($day)) {
        	$day = 0;
        }
        if ($day > 5) {
        	$day = 5;
        }
        
    	$key = 'i:u:dlyaward:' . $uid;
        $cache = Hapyfish2_Cache_Factory::getMC($uid);
        $cache->delete($key);
        
        if ($day > 0) {
	        $loginInfo = Hapyfish2_Ipanda_HFC_User::getUserLoginInfo($uid);
	        $loginInfo['active_login_count'] = $day;
	        Hapyfish2_Ipanda_HFC_User::updateUserLoginInfo($uid, $loginInfo, true);
        }
        echo 'ok';
        exit;
	}
	
	public function clearhristmasAction()
	{
		 $uid = $this->_request->getParam('uid');
		 $key = 'ipanda:e:c:is:get'.$uid;
		 $cache = Hapyfish2_Cache_Factory::getMC($uid);
		 $cache->delete($key);
		 echo "ok";
		 exit;
		
	}
	
	public function clearchristmasconfigAction()
	{
		$key = 'ipanda:u:e:r';
		$cache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
		$cache->delete($key);
		echo "ok";
		exit;
	}
	
	public function updatelimitAction()
	{
		 $uid = $this->_request->getParam('uid');
		 $key = 'ipanda:e:c:is:get'.$uid;
		 $cache = Hapyfish2_Cache_Factory::getMC($uid);
		 $data = $cache->get($key);
		 $data['date'] = 20111211;
		 $cache->set($key, $data);
		 echo "ok";
		 exit;
	}
	
	public function getchristmasstatAction()
	{
		$file = LOG_DIR.'/get20.log';
		$data = Hapyfish2_Ipanda_Stat_Bll_Christmas::get($file);
		foreach ($data['list'] as $k=>$v){
			echo $k.'---'.$v[1].'<br/>';
		}
		echo 'num----'.$data['num'];
		exit;
	}
	
	public function getchristmasexchangeAction()
	{
		$file = LOG_DIR.'/exchange20.log';
		$data = Hapyfish2_Ipanda_Stat_Bll_Christmas::getExchange($file);
		foreach ($data as $k=>$v){
			echo $k.'---'.$v.'<br/>';
		}
		exit;
	}
	
	public function reloadhospitalAction()
	{
		$key = 'i:u:h:h:config';
		$key1 = 'i:u:h:d:config';
		$key2 = 'i:u:h:drug:config';
		$cache = Hapyfish2_Cache_Factory::getBasicMC('mc_0');
		$cache->delete($key);
		$cache->delete($key1);
		$cache->delete($key2);
		$localcache = Hapyfish2_Cache_LocalCache::getInstance();
		$localcache->delete($key);
		$localcache->delete($key1);
		$localcache->delete($key2);
		echo SERVER_ID . 'OK';
		exit;
	}
	
    public function reloadhospitalallAction()
	{
		$list = Hapyfish2_Ipanda_Bll_Server::getWebList();
		if (!empty($list)) {
			$host = str_replace('http://', '', HOST);
			foreach ($list as $server) {
				$url = 'http://' . $server['local_ip'] . '/tools/reloadhospital';
				$result = Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
				echo $url . ':' . $result . '<br/>';
			}
		}
		echo 'OK';
		exit;
	}
	
	public function getmapAction()
	{
		$uid = $this->_request->getParam('uid');
		$map = Hapyfish2_Ipanda_Bll_MapGrid::getMap($uid);
		print"<pre>";
		print_r($map);
		print"</pre>";
		exit;
	}
	public function clearuserhospitalAction()
	{
		$uid = $this->_request->getParam('uid');
		$key = 'ipanda:u:h:h:all'.$uid;
		$cache= Hapyfish2_Cache_Factory::getHFC($uid);
		$cache->delete($key);
		$dal = Hapyfish2_Ipanda_Dal_Hospital::getDefaultInstance();
		$dal->clearUserHospital($uid);
		echo "ok";
		exit;
	}
	
	
   public function showgridAction()
	{
	    $uid = $this->_request->getParam('uid');
		$data = Hapyfish2_Ipanda_Bll_MapGrid::getMap($uid);
		//print_r($data);
		$x = 0;
		$z = 0;
		foreach($data as $k => $v) {
			$tmp = explode(',', $k);
			if ($tmp[0] > $x) {
				$x = $tmp[0];
			}
			if ($tmp[1] > $z) {
				$z = $tmp[1];
			}
		}
		
		echo '<div style="overflow:auto;">';
		
		//echo 'x width:' . ($x + 1) . '<br/>';
		//echo 'z width:' . ($z + 1) . '<br/>';
		
		$table = '<table>';
		$b = array();
		$p = array();
		for($i = 0; $i <= $z; $i++) {
			$table .= '<tr>';
			for($j = 0; $j <= $x; $j++) {
				$table .= '<td  style="width:30px;height:30px;';
				$v = $j . ',' . $i;
				if (isset($data[$v])) {
					if ($data[$v] == 0) {
						$table .= 'background-color:black;">';
					} else {
						$temp = explode(':', $data[$v]);
						$type = substr($temp[1], -2);
						if ($type == '11') {
							$table .= 'background-color:red;"';
							if (!isset($b[$temp[0]])) {
								$building = Hapyfish2_Ipanda_HFC_Building::getOne($uid, $temp[0]);
								$table .= ' title="' . $building['attr'] . '-' . $building['effect_source'] . '">';
								//$b[$temp[0]] = 1;
							} else {
								$table .= '>';
							}
						} else if ($type == '21') {
							$table .= 'background-color:green;">';
						} else {
							$table .= 'background-color:blue;"';
							if (!isset($p[$temp[0]])) {
								$phytotron = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronInfo($uid, $temp[0]);
								$table .= ' title="' . $phytotron['effect_exp'] . '-' . $phytotron['effect_source'] . '">';
								//$b[$temp[0]] = 1;
							} else {
								$table .= '>';
							}
						}
					}
				} else {
					$table .= '">';
				}
				$table .= '</td>';
			}
			$table .= '</tr>';
		}
		$table .= '</table>';
		echo $table;
		echo '</div>';
        exit;
	}
	
	public function todethAction()
	{
		$uid = $this->_request->getParam('uid');
		$id = $this->_request->getParam('id');
		$time = time();
		$data = Hapyfish2_Ipanda_Cache_Hospital::getUserDisAni($uid, $id);
		$data['die_time'] = $time+100;
		Hapyfish2_Ipanda_Cache_Hospital::update($uid, $data);
		echo "ok";
	}
}