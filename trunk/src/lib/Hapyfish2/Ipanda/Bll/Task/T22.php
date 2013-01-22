<?php

/**
 * 收好友爱心次数
 * type = 22
 * eg: 去好友获取10次爱心
 */
class Hapyfish2_Ipanda_Bll_Task_T22
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['cid']) || $data['cid'] != $taskConditionInfo['cid']) {
			return;
		}
		
		$taskId = $taskInfo['id'];
		$conditionId = $taskConditionInfo['id'];
		
		if (!isset($openTask['data'][$taskId])) {
			$oldNum = 0;
			$openTask['data'][$taskId] = array($conditionId => 0);
		} else if (!isset($openTask['data'][$taskId][$conditionId])) {
			$oldNum = 0;
			$openTask['data'][$taskId][$conditionId] = 0;
		} else {
			$oldNum = $openTask['data'][$taskId][$conditionId];
		}
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		} else if ($oldNum + 1 == $taskConditionInfo['num']) {
			$openTask['data'][$taskId][$conditionId] += 1;
			$ok = Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
			//还没升级，但也要更新一下数据
			if (!$ok) {
				Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
			}
		} else {
			$openTask['data'][$taskId][$conditionId] += 1;
			Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
		}
	}
}