<?php

/**
 * 拥有某建筑数
 * type = 32
 * eg: 拥有15个建筑
 */
class Hapyfish2_Ipanda_Bll_Task_T32
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['cid']) || $data['cid'] != $taskConditionInfo['cid']) {
			return;
		}
		
		$oldNum = Hapyfish2_Ipanda_Bll_Task_Base::getBuildingNumByCid($uid, $taskConditionInfo['cid']);
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}