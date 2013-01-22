<?php

/**
 * 建设建筑数
 * type = 12
 * eg: 拥有15个建筑
 */
class Hapyfish2_Ipanda_Bll_Task_T12
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data = null)
	{
		$oldNum = Hapyfish2_Ipanda_Bll_Task_Base::getBuildingNum($uid);
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}