<?php

/**
 * 升级到几级
 * type = 33
 * eg: 升级到10级
 */
class Hapyfish2_Ipanda_Bll_Task_T33
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['level'])) {
			return;
		}
		
		if ($data['level'] >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}