<?php

class Hapyfish2_Ipanda_Bll_Task_Base
{
	const  MAX_SHOW_TASK_NUM = 6;
	
	//检查是否任务完成
	public static function check($uid, $openTask, &$taskInfo)
	{
		$taskId = (int)$taskInfo['id'];
		if (empty($openTask['data']) || !isset($openTask['data'][$taskId])) {
			$taskData = array();
		} else {
			$taskData = $openTask['data'][$taskId];
		}

		$conditionIds = json_decode($taskInfo['condition_ids'], true);
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
				
				if ($num < $taskConditionInfo['num']) {
					return false;
				}
			} else {
				if ($type == 9) {
					$num = self::getUnlockAnimalNum($uid);
				} else if ($type == 10) {
					$num = self::getOneUnlockAnimalNum($uid, $taskConditionInfo['cid']);
				} else if ($type == 11) {
					$num = self::getPhytotronNum($uid);
				} else if ($type == 12) {
					$num = self::getBuildingNum($uid);
				} else if ($type == 18) {
					$num = self::getActiveLoginNum($uid);
				} else if ($type == 32) {
					$num = self::getBuildingNumByCid($uid, $taskConditionInfo['cid']);
				} else if ($type == 33) {
					$num = self::getUserLevel($uid);
				} else {
					return false;
				}
				
				if ($num < $taskConditionInfo['num']) {
					return false;
				}
			}
		}
		
		$ok = Hapyfish2_Ipanda_Cache_Task::isCompletedTask($uid, $taskId);
		if ($ok) {
			//已经完成了，删除垃圾数据
			$list = array();
			foreach ($openTask['list'] as $tid) {
				if ($tid != $taskId) {
					$list[] = $tid;
				}
			}
			$openTask['list'] = $list;
			unset($openTask['data'][$taskId]);
			Hapyfish2_Ipanda_HFC_TaskOpen::update($uid, $openTask);
			return false;
		}
		
		$ok = Hapyfish2_Ipanda_Cache_Task::completeTask($uid, $taskId);
		if ($ok) {
			//记录完成id
			Hapyfish2_Ipanda_Bll_UserResult::addTaskCompletedId($uid, $taskId);
			
			$list = array();
			foreach ($openTask['list'] as $tid) {
				if ($tid != $taskId) {
					$list[] = $tid;
				}
			}
			$openTask['list'] = $list;
			unset($openTask['data'][$taskId]);
			
			//完成，触发下一个任务 
			$nextTaskList = json_decode($taskInfo['next_task_id'], true);
			if (!empty($nextTaskList)) {
				$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
				foreach ($nextTaskList as $tid) {
					$taskInfo1 = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($tid);
					if ($taskInfo1) {
	    				$frontTaskList = json_decode($taskInfo1['front_task_id'], true);
	    				$newTask = true;
	    				foreach ($frontTaskList as $id) {
							$ok0 = Hapyfish2_Ipanda_Cache_Task::isCompletedTask($uid, $id);
							//前置任务没有全部完成
							if (!$ok0) {
								$newTask = false;
								break;
							}
	    				}
	    				
	    				if ($newTask) {
	    					//等级是否到了
	    					if ($userLevel < $taskInfo1['need_user_level']) {
	    						//等级没到要求则放入buffer_list
	    						$openTask['buffer_list'][$tid] = $taskInfo1['need_user_level'];
	    					} else {
								//否则先放入list2待分配到list1
	    						$openTask['list2'][] = array((int)$taskInfo1['priority'], $tid);
	    					}
	    				}
	    			}
				}
			}
			
			//最多出现6个任务，其余缓存起来
			//有缓存的任务，需要使其生效
			$n1 = self::MAX_SHOW_TASK_NUM - count($openTask['list']);
			$n2 = count($openTask['list2']);
			
			if ($n1 > 0 && $n2 > 0) {
				if ($n1 >= $n2) {
					//全部生效
					foreach ($openTask['list2'] as $info) {
						$openTask['list'][] = $info[1];
						Hapyfish2_Ipanda_Bll_UserResult::addTaskNewId($uid, $info[1]);
					}
					$openTask['list2'] = array();
				} else {
					//从list2中取出$n1个优先级高的放入list
					$tmp = array();
					foreach ($openTask['list2'] as $info) {
						$tmp[$info[0]] = $info[1];
					}
					krsort($tmp);
					for($i = 0; $i < $n1; $i++) {
						$t = array_pop($tmp);
						$openTask['list'][] = $t;
						Hapyfish2_Ipanda_Bll_UserResult::addTaskNewId($uid, $t);
					}
					$list2 = array();
					foreach ($tmp as $p => $id) {
						$list2[] = array((int)$p, $id);
					}
					$openTask['list2'] = $list2;
				}
			}
			
			$saveOK = Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask, true);
			
			//给奖励
			$awardBot = new Hapyfish2_Ipanda_Bll_Award();
			if ($taskInfo['love'] > 0) {
				$awardBot->setLove($taskInfo['love']);
			}
			if ($taskInfo['exp'] > 0) {
				$awardBot->setExp($taskInfo['exp']);
			}
			if ($taskInfo['gold'] > 0) {
				$awardBot->setGold($taskInfo['gold'], 3);
			}
			$materials = json_decode($taskInfo['materials'], true);
			if (!empty($materials)) {
				$awardBot->setMaterial($materials[0], $materials[1]);
			}
			$items = json_decode($taskInfo['items'], true);
			if (!empty($items)) {
				$awardBot->setCard($items[0], $items[1]);
			}
			$decorates = json_decode($taskInfo['decorates'], true);
			if (!empty($decorates)) {
				$awardBot->setDecorate($decorates[0], $decorates[1]);
			}
			
			$awardBot->sendOne($uid);
		}
		
		return $ok;
	}
	
	public static function addTask($uid, &$openTask, $idsBuffer, $idsNew)
	{
		$n1 = self::MAX_SHOW_TASK_NUM - count($openTask['list']);
		
		//先放入list2
		if (!empty($idsBuffer)) {
			foreach ($idsBuffer as $tid) {
				$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($tid);
				$openTask['list2'][] = array((int)$taskInfo['priority'], $tid);
			}
		}
		
		if (!empty($idsNew)) {
			foreach ($idsNew as $tid) {
				$taskInfo = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskInfo($tid);
				$openTask['list2'][] = array((int)$taskInfo['priority'], $tid);
			}
		}
		
		if ($n1 > 0) {
			$n2 = count($openTask['list2']);
			//从list2中取出$n1个优先级高的放入list
			$tmp = array();
			foreach ($openTask['list2'] as $info) {
				$tmp[$info[0]] = $info[1];
			}
			krsort($tmp);
			$n = $n1 < $n2 ? $n1 : $n2;
			for($i = 0; $i < $n; $i++) {
				$t = array_pop($tmp);
				$openTask['list'][] = $t;
				Hapyfish2_Ipanda_Bll_UserResult::addTaskNewId($uid, $t);
			}
			$list2 = array();
			foreach ($tmp as $p => $id) {
				$list2[] = array((int)$p, $id);
			}
			$openTask['list2'] = $list2;
		}

		return Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask, true);
	}
	
	public static function getUnlockAnimalNum($uid)
	{
		$list = Hapyfish2_Ipanda_Cache_PhytotronAnimal::getPhytotronAnimalList($uid);
		if (empty($list)) {
			return 0;
		} else {
			return count($list);
		}
	}
	
	public static function getOneUnlockAnimalNum($uid, $cid)
	{
		$list = Hapyfish2_Ipanda_Cache_PhytotronAnimal::getPhytotronAnimalList($uid);
		if (empty($list)) {
			return 0;
		} else {
			foreach ($list as $k => $v) {
				if ($v['animal_cid'] == $cid){
					return 1;
				}
			}
			return 0;
		}
	}
	
	public static function getPhytotronNum($uid)
	{
		$list = Hapyfish2_Ipanda_Cache_Phytotron::getPhytotronList($uid);
		if (empty($list)) {
			return 0;
		} else {
			return count($list);
		}
	}
	
	public static function getBuildingNum($uid)
	{
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return 0;
		} else {
			return count($list);
		}
	}
	
	public static function getBuildingNumByCid($uid, $cid)
	{
		$list = Hapyfish2_Ipanda_HFC_Building::getAll($uid);
		if (empty($list)) {
			return 0;
		} else {
			$num = 0;
			foreach ($list as $item) {
				if ($item['cid'] == $cid) {
					$num++;
				}
			}
			return $num;
		}
	}
	
	public static function getActiveLoginNum($uid)
	{
		$loginInfo = Hapyfish2_Ipanda_HFC_User::getUserLoginInfo($uid);
		if (!$loginInfo || !isset($loginInfo['active_login_count'])) {
			return 0;
		}
		
		return $loginInfo['active_login_count'];
	}
	
	public static function getUserLevel($uid)
	{
		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		if (!$userLevel) {
			return 0;
		}
		
		return $userLevel;
	}
}