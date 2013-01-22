<?php

class UnittestController extends Hapyfish2_Controller_Action_Api
{
	public function md5Action()
	{
		$num = $this->_request->getParam('num');
		$key = 'asd#sdfa@1&d36pw';
        $t = time();
        $sig = md5($num . $t . $key);
        
		echo $num . '.' . $t . '.' . $sig;
		exit;
	}
	
	private function getVersion($name)
	{
		$data = Hapyfish2_Ipanda_Cache_Basic_Extend::getCacheVersion();
		if ($data == null || !isset($data[$name])) {
			return $this->updateVersion($name);
		}
		
		return $data[$name];
	}

	public function createzipfileAction()
	{
		Hapyfish2_Ipanda_Bll_BasicInfo::dumpInitData('1.0', true);
		echo 'ok';
		exit;
	}
	
	public function getzipfileAction()
	{
		//header('Content-Description: File Transfer');     
		header('Content-Type: application/octet-stream');
		//header('Content-Transfer-Encoding: binary');
		echo Hapyfish2_Ipanda_Bll_BasicInfo::getInitData('1.0', true);
		exit;
	}
	
	public function getunzipfileAction()
	{
		$data = Hapyfish2_Ipanda_Bll_BasicInfo::getInitData('1.0', true);
		echo gzuncompress($data);
		exit;
	}
	
	public function awardtestAction()
	{
		$uid = $this->_request->getParam('uid');
		$award = new Hapyfish2_Ipanda_Bll_Award();
		$list = array(
			array('cid' => 1, 'num' => 1000),
			array('cid' => 2, 'num' => 10),
			array('cid' => 151, 'num' => 10),
			array('cid' => 244, 'num' => 10),
			array('cid' => 221, 'num' => 1),
			array('cid' => 1011, 'num' => 1)
		);
		foreach ($list as $item) {
			$award->setSomething($item['cid'], $item['num']);
		}
		$award->sendOne($uid);
		$content = $award->getContent($uid);
		print_r($content);
		exit;
	}
}