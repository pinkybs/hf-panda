<?php

class Hapyfish2_Ipanda_Bll_Task
{
	public static function getConditionInfo($taskId)
	{
		$info = array();
		
		$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($taskId);
		if (!$taskInfo) {
			return $info;
		}
		
		$conditionIds = json_decode($taskInfo['condition_ids'], true);
		if (empty($conditionIds)) {
			return $info;
		}
		
		foreach ($conditionIds as $id) {
			$info[] = 0;
		}
		
		return $info;
	}
	
	public static function getDoneInfo($uid, $taskId, &$status)
	{
		$info = array();
		
		$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($taskId);
		if (!$taskInfo) {
			return $info;
		}
		
		$conditionIds = json_decode($taskInfo['condition_ids'], true);
		if (empty($conditionIds)) {
			return $info;
		}
		
		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		
		if (!in_array($taskId, $openTask['list'])) {
			return $info;
		}
		
		$status = Hapyfish2_Ipanda_Cache_TaskStatus::update($uid, $openTask['list'], $taskId);
		
		if (isset($openTask['data'][$taskId])) {
			$taskData = $openTask['data'][$taskId];
		} else {
			$taskData = array();
		}
		
		foreach ($conditionIds as $id) {
			$taskConditionInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskConditionInfo($id);
			$type = $taskConditionInfo['condition_type'];
			$num = 0;
			if (isset($taskData[$id])) {
				if ($type == 29 || $type == 31 || $type == 43) {
					$num = count($taskData[$id]);
				} else {
					$num = $taskData[$id];
				}
			} else {
				if ($type == 9) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getUnlockAnimalNum($uid);
				} else if ($type == 10) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getOneUnlockAnimalNum($uid, $taskConditionInfo['cid']);
				} else if ($type == 11) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getPhytotronNum($uid);
				} else if ($type == 12) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getBuildingNum($uid);
				} else if ($type == 18) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getActiveLoginNum($uid);
				} else if ($type == 32) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getBuildingNumByCid($uid, $taskConditionInfo['cid']);
				} else if ($type == 33) {
					$num = Hapyfish2_Ipanda_Bll_Task_Base::getUserLevel($uid);
				}
			}
			
			$info[] = $num;
		}
		
		return $info;
	}
	
	public static function check($uid, $taskId)
	{
		$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($taskId);
		if (!$taskInfo) {
			return false;
		}

		$conditionIds = json_decode($taskInfo['condition_ids'], true);
		if (empty($conditionIds)) {
			return false;
		}

		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		if (!in_array($taskId, $openTask['list'])) {
			return false;
		}

		$ok = Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		
		return $ok;
	}
	
	public static function completeCondition($uid, $taskId, $conditionId)
	{
		$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($taskId);
		if (!$taskInfo) {
			return -104;
		}
		
		$conditionIds = json_decode($taskInfo['condition_ids'], true);
		if (empty($conditionIds) || !in_array($conditionId, $conditionIds)) {
			return -104;
		}
		
		$conditionInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskConditionInfo($conditionId);
		if (!$conditionInfo) {
			return -104;
		}
		
		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		if (!in_array($taskId, $openTask['list'])) {
			return -104;
		}
		
		$gold = $conditionInfo['complete_gold'];
		if ($gold <= 0) {
			return -104;
		}
		
		$userGold = Hapyfish2_Ipanda_HFC_User::getUserGold($uid);
		if ($userGold < $gold) {
			return -102;
		}
		
		//扣宝石
		$goldInfo = array(
			'uid' 			=> $uid,
			'cost' 			=> $gold,
			'summary' 		=> '使用金币立刻完成任务[' . $taskId . '],条件[' . $conditionId . ']',
			'create_time' 	=> time()
		);
		$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
		if (!$ok) {
			return -2;
		}
		
		$type = $conditionInfo['condition_type'];
		$num = $conditionInfo['num'];
		if ($type == 29 || $type == 31) {
			$data = array();
			$animalType = 61;
			//161,261,361,...
			for($i = 0; $i < $num; $i++) {
				$data[] = ($i + 1)*100 + $animalType;
			}
		} else if ($type == 43) {
			$data = array();
			for($i = 1; $i <= $num; $i++) {
				$data[] = $i;
			}
		} else {
			$data = $num;
		}
		
		if (!isset($openTask['data'][$taskId])) {
			$openTask['data'][$taskId] = array($conditionId => $data);
		} else {
			$openTask['data'][$taskId][$conditionId] = $data;
		}
		
		$ok = Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask, true);
		if (!$ok) {
			return -2;
		}
		
		Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		
		return 1;
	}
	
	public static function getUserTaskOpenTaskIds($uid)
	{
		$data = array();
		$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
		if (!$openTask) {
			return $data;
		}

		$status = Hapyfish2_Ipanda_Cache_TaskStatus::get($uid, $openTask['list']);
		foreach ($openTask['list'] as $tid) {
			$data[] = array('id' => $tid, 'status' => $status[$tid]);
		}
		
		return $data;
	}
	
    public static function listen($uid, $type, $data = null)
    {
    	$openTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
    	if ($openTask) {
    		if (!empty($openTask['list'])) {
    			$counter = 0;
    			foreach ($openTask['list'] as $tid) {
	    			$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($tid);
	    			if ($taskInfo) {
	    				$conditionIds = json_decode($taskInfo['condition_ids'], true);
	    				foreach ($conditionIds as $cid) {
	    					$taskConditionInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskConditionInfo($cid);
	    					//info_log($type . ':' . json_encode($taskConditionInfo), 'listen4');
	    					if ($taskConditionInfo && $taskConditionInfo['condition_type'] == $type) {
	    						if ($counter == 0) {
	    							$newOpenTask = $openTask;
	    						} else {
	    							$newOpenTask = Hapyfish2_Ipanda_HFC_TaskOpen::getInfo($uid);
	    						}
	    						self::handleTask($uid, $type, $newOpenTask, $taskInfo, $taskConditionInfo, $data);
	    						$counter++;
	    					}
	    				}
	    			}
    			}
    		}
    	}
    }
    
    private static function handleTask($uid, $type, &$openTask, &$taskInfo, &$taskConditionInfo, $data = null)
    {
        $name = 'T' . $type;
    	$implFile = 'Hapyfish2/Ipanda/Bll/Task/' . $name . '.php';
        if (is_file(LIB_DIR . '/' . $implFile)) {
            require_once $implFile;
            $implClassName = 'Hapyfish2_Ipanda_Bll_Task_' . $name;
            $impl = new $implClassName();
            $impl->trigger($uid, $openTask, $taskInfo, $taskConditionInfo, $data);
        }
    }
}