<?php

/**
 * 收动物数(培育动物数)
 * type = 19
 * eg: 孕育一对熊猫宝宝
 */
class Hapyfish2_Ipanda_Bll_Task_T19
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['cid']) || $data['cid'] != $taskConditionInfo['cid']) {
			return;
		}
		
		$num = $data['num'];
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
		} else if ($oldNum + $num >= $taskConditionInfo['num']) {
			$openTask['data'][$taskId][$conditionId] += $num;
			$ok = Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
			//还没升级，但也要更新一下数据
			if (!$ok) {
				Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
			}
		} else {
			$openTask['data'][$taskId][$conditionId] += $num;
			Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
		}
	}
}