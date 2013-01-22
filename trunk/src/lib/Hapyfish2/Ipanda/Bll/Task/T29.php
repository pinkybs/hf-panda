<?php

/**
 * 租动物总类
 * type = 29
 * eg: 各种各样动物5种
 */
class Hapyfish2_Ipanda_Bll_Task_T29
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['cid'])) {
			return;
		}
		
		$cid = (int)$data['cid'];
		$taskId = $taskInfo['id'];
		$conditionId = $taskConditionInfo['id'];
		
		if (!isset($openTask['data'][$taskId])) {
			$oldNum = 0;
			$openTask['data'][$taskId] = array($conditionId => array());
		} else if (!isset($openTask['data'][$taskId][$conditionId])) {
			$oldNum = 0;
			$openTask['data'][$taskId][$conditionId] = array();
		} else {
			if (in_array($cid, $openTask['data'][$taskId][$conditionId])) {
				return;
			}
			
			$oldNum = count($openTask['data'][$taskId][$conditionId]);
		}
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		} else if ($oldNum + 1 == $taskConditionInfo['num']) {
			$openTask['data'][$taskId][$conditionId][] = $cid;
			$ok = Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
			//还没升级，但也要更新一下数据
			if (!$ok) {
				Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
			}
		} else {
			$openTask['data'][$taskId][$conditionId][] = $cid;
			Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
		}
	}
}