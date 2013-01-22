<?php

class PaysettingapiController extends Hapyfish2_Controller_Action_External
{
	public function infoAction()
	{
		$id = $this->_request->getParam('id');
		$active_id = 0;
		if ($id == null) {
			$info = Hapyfish2_Ipanda_Cache_Basic_Extend::getPaySettingList();
			foreach ($info as $v) {
				if ($v['active'] == 1) {
					$active_id = $v['id'];
					break;
				}
			}
		} else {
			$info = Hapyfish2_Ipanda_Cache_Basic_Extend::getPaySettingInfo($id);
		}
		$data = array('info' => $info, 'active_id' => $active_id, 'static_host' => STATIC_HOST);
		$this->echoResult($data);
	}
	
	public function updateAction()
	{
		$id = $this->_request->getParam('id');
		if ($id == null) {
			$this->echoError(2001, 'id can not empty');
		}
		$info = Hapyfish2_Ipanda_Cache_Basic_Extend::getPaySettingInfo($id);
		if (!$info) {
			$this->echoError(2002, 'id error');
		}
		
		$updateInfo = array('update_time' => time());
		$params = $this->_request->getParams();
		if (isset($params['section'])) {
			$updateInfo['section'] = $params['section'];
		}
		if (isset($params['end_time'])) {
			$updateInfo['end_time'] = $params['end_time'];
		}
		if (isset($params['note'])) {
			$updateInfo['note'] = $params['note'];
		}
		if (isset($params['next_id'])) {
			$updateInfo['next_id'] = $params['next_id'];
		}
		
		$ok = Hapyfish2_Ipanda_Cache_Basic_Extend::updatePaySettingInfo($id, $updateInfo);
		if (!$ok) {
			$this->echoError(2003, 'update failed');
		}
		
		$list = Hapyfish2_Ipanda_Bll_Server::getWebList();
		if (!empty($list)) {
			$host = str_replace('http://', '', HOST);
			foreach ($list as $server) {
				$url = 'http://' . $server['local_ip'] . '/paysettingapi/refresh';
				Hapyfish2_Ipanda_Bll_Server::requestWeb($host, $url);
			}
		}
		
		$data = array('result' => 1);
		$this->echoResult($data);
	}
	
	public function refreshAction()
	{
		Hapyfish2_Ipanda_Cache_Basic_Extend::resetPaySettingLocalCache();
		$data = array('result' => 'ok', 'server_id' => SERVER_ID);
		$this->echoResult($data);
	}
}