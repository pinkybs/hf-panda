<?php

class Hapyfish2_Ipanda_Bll_PandaQuestion
{
	public static function get($uid)
	{
		$info = array('id' => 0, 'tick' => '');
		
		$q = mt_rand(1, 100);
		//没有题
		if ($q > QUESTION_RATE) {
			return $info;
		}

		//随机取一道题
		$questionList = Hapyfish2_Ipanda_Cache_Basic_Extend::getPandaQuestionSimpleList();
		$userLevel = Hapyfish2_Ipanda_HFC_User::getUserLevel($uid);
		
		//允许的题目
		$allowQuestionList = array();
		foreach ($questionList as $id => $question) {
			if ($question['user_level'] <= $userLevel) {
				$allowQuestionList[] = $question;
			}
		}
		
		$len = count($allowQuestionList);
		if ($len == 0) {
			return $info;
		}
		
		$n = 0;
		$data = array();
		foreach ($allowQuestionList as $question) {
			$n += $question['weight'];
			$data[] = array($question['id'], $n);
		}
		$m = mt_rand(1, $n);
		$len = count($data);
		for($i = 0; $i < $len; $i++) {
			if ($m <= $data[$i][1]) {
				$info['id'] = $data[$i][0];
				break;
			}
		}

		$info['tick'] = $info['id'] . '_' . time();
		
		$ok = Hapyfish2_Ipanda_Cache_PandaQuestion::add($uid, $info);
		if ($ok) {
			return $info;
		} else {
			return array('id' => 0, 'tick' => '');
		}
	}
	
	public static function awswer($uid, $id, $tick)
	{
		$question = Hapyfish2_Ipanda_Cache_Basic_Extend::getPandaQuestionSimpleInfo($id);
		if (!$question) {
			return false;
		}
		
		$info = array('id' => $id, 'tick' => $tick);
		
		$ok = Hapyfish2_Ipanda_Cache_PandaQuestion::checkout($uid, $info);
		
		if ($ok) {
			//给奖励
			//爱心
			if ($question['award_love'] > 0) {
				Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $question['award_love']);
			}
			//经验
			if ($question['award_exp'] > 0) {
				Hapyfish2_Ipanda_HFC_User::incUserExp($uid, $question['award_exp']);
			}
			//亲密度
			if ($question['award_intimacy'] > 0) {
				Hapyfish2_Ipanda_Bll_PhytotronAnimal::addintimacy($uid, $question['animal_id'], $question['award_intimacy']);
			}
			
			//日志统计
            $logger = Hapyfish2_Util_Log::getInstance();
            $logger->report('pandaquestion', array($uid, $id));
		}
		
		return $ok;
	}

}