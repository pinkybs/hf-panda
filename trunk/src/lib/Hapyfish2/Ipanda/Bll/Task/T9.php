<?php

/**
 * 解锁动物数
 * type = 9
 * eg: 解锁4个动物
 */
class Hapyfish2_Ipanda_Bll_Task_T9
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data = null)
	{
		$oldNum = Hapyfish2_Ipanda_Bll_Task_Base::getUnlockAnimalNum($uid);
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}