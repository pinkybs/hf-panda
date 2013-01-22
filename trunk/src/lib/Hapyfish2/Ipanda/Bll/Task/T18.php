<?php

/**
 * 连续登陆
 * type = 18
 * eg: 连续3天登录
 */
class Hapyfish2_Ipanda_Bll_Task_T18
{
	public function trigger($uid, $openTask, &$taskInfo, &$taskConditionInfo, $data)
	{
		if (!$data || !isset($data['days'])) {
			return;
		}
		
		if ($data['days'] >= $taskConditionInfo['num']) {
			Hapyfish2_Ipanda_Bll_Task_Base::check($uid, $openTask, $taskInfo);
		}
	}
}