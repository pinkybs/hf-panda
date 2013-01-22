<?php

/**
 * 建设培育屋数
 * type = 11
 * eg: 建设4个培育屋
 */
class Hapyfish2_Ipanda_Bll_Task_T11
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data = null)
	{
		$oldNum = Hapyfish2_Ipanda_Bll_Task_Base::getPhytotronNum($uid);
		
		if ($oldNum >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}