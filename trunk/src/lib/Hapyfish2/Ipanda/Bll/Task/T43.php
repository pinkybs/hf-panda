<?php

/**
 * 正确回答某动物的几个问题（不重复）
 * type = 43
 * eg: 正确回答熊猫的20个问题（不重复）
 */
class Hapyfish2_Ipanda_Bll_Task_T43
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['id'])) {
			return;
		}
		
		$id = (int)$data['id'];
		$taskId = $taskInfo['id'];
		$conditionId = $taskConditionInfo['id'];
		
		if (!isset($openTask['data'][$taskId])) {
			$oldNum = 0;
			$openTask['data'][$taskId] = array($conditionId => array());
		} else if (!isset($openTask['data'][$taskId][$conditionId])) {
			$oldNum = 0;
			$openTask['data'][$taskId][$conditionId] = array();
		} else {
			if (!is_array($openTask['data'][$taskId][$conditionId])) {
				info_log($uid . ':' . json_encode($openTask['data'][$taskId]), 'T43');
				$oldNum = 1;
				$openTask['data'][$taskId][$conditionId] = array();
			} else {
				if (in_array($id, $openTask['data'][$taskId][$conditionId])) {
					return;
				}
				$oldNum = count($openTask['data'][$taskId][$conditionId]);
			}
		}
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		} else if ($oldNum + 1 == $taskConditionInfo['num']) {
			$openTask['data'][$taskId][$conditionId][] = $id;
			$ok = Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
			//还没升级，但也要更新一下数据
			if (!$ok) {
				Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
			}
		} else {
			$openTask['data'][$taskId][$conditionId][] = $id;
			Hapyfish2_Ipanda_HFC_TaskOpen::save($uid, $openTask);
		}
	}
}