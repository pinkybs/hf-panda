<?php

class TaskController extends Hapyfish2_Controller_Action_Api
{
	
   	public function gettaskAction()
   	{
   		$uid = $this->uid;
   		$id = $this->_request->getParam('id');
   		
   		if (empty($id)) {
			$this->echoErrResult(-104);
		}
   		
		$status = 0;
   		$data = Hapyfish2_Ipanda_Bll_Task::getDoneInfo($uid, $id, $status);
   		
   		$result['task'] = array('id' => $id, 'data' => $data, 'status' => $status);
   		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	public function checktaskAction()
   	{
   		$uid = $this->uid;
   		$id = $this->_request->getParam('id');
   		
   	   	if (empty($id)) {
			$this->echoErrResult(-104);
		}
   		
   		$ok = Hapyfish2_Ipanda_Bll_Task::check($uid, $id);
   		
   		$result = array('status' => 1, 'completed' => $ok);
   		
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	public function completeconditionAction()
   	{
   		$uid = $this->uid;
   		$taskId = $this->_request->getParam('tid');
   		$conditionId = $this->_request->getParam('id');
   		
		if (empty($taskId) || empty($conditionId)) {
			$this->echoErrResult(-104);
		}
		
		$code = Hapyfish2_Ipanda_Bll_Task::completeCondition($uid, $taskId, $conditionId);
		if ($code != 1) {
			$this->echoErrResult($code);
		}
		
		$result = array('status' => 1);
		$ret = $this->returnResult($result);
		$this->echoResult($ret);
   	}
   	
   	public function fullscreenAction()
   	{
   		$uid = $this->uid;
   		
   		//派发任务
		$event = array('uid' => $uid);
   		Hapyfish2_Ipanda_Bll_Event::fullScreen($event);
   		
   		$result['status'] = 1;
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}
   	
   	public function doneclientactionAction()
   	{
   		$uid = $this->uid;
   		
   		$ids = $this->_request->getParam('ids');
   		$type = $this->_request->getParam('type');
   		
   		if (empty($ids) || empty($type)) {
   			$this->echoErrResult(-104);
   		}
   		
   		//info_log($ids . ',' . $type, 'clientaction');
   		$taskTypeList = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskTypeList();
   		if (!isset($taskTypeList[$type])) {
   			$this->echoErrResult(-104);
   		}
   		
   		//派发任务
		$event = array('uid' => $uid, 'type' => $type, 'ids' => $ids);
   		Hapyfish2_Ipanda_Bll_Event::clientAction($event);
   		
   		$result['status'] = 1;
   		$ret = $this->returnResult($result);
   		$this->echoResult($ret);
   	}
 }
