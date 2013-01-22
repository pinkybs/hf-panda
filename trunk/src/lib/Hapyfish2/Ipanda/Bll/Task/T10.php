<?php

/**
 * 解锁某动物
 * type = 10
 * eg: 解锁兔子
 */
class Hapyfish2_Ipanda_Bll_Task_T10
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['cid']) || $data['cid'] != $taskConditionInfo['cid']) {
			return;
		}
		
		$oldNum = Hapyfish2_Ipanda_Bll_Task_Base::getOneUnlockAnimalNum($uid, $taskConditionInfo['cid']);
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}