<?php

class Hapyfish2_Ipanda_Event_Bll_Reward
{
	public static function get($uid, $id, $type)
	{
		
		$list = array();
		$Info = Hapyfish2_Ipanda_Event_Cache_Reward::getEventDetail($type);
		foreach($Info as $k => $v){
			if($id == $v['id']){
				$list = $v;
				break;
			}
		}
		//需要条件
		$need = json_decode($list['need_list']);
		//奖励
		$award = json_decode($list['reward']);
		$check = self::check($uid, $need);
		if($check){
			$ok = self::exchange($uid, $need, $award, $list['isDel']);
			if($ok){
				return true;
			}
		}else{
			return false;
		}
		return false;
	}
	
	public static function check($uid, $need)
	{
		$userVo = Hapyfish2_Ipanda_HFC_User::getUserVO($uid);
		foreach($need as $k=> $v){
			if($v->type == 'love'){
				if($userVo['love'] < $v->num){
					return false;
				}
			}else if ($v->type == 'gold'){
				if($userVo['gold'] < $v->num){
					return false;
				}
			}else if($v->type == 'card'){
				$cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
			 	if (!isset($cards[$v->cid]) || $cards[$v->cid]['count'] < $v->num) {
    				return false;
			 	}
			}else if($v->type == 'build'){
				$num = Hapyfish2_Ipanda_Bll_Task_Base::getBuildingNumByCid($uid, $v->cid);
				if($num <= 0){
					return false;
				}
			} else if($v->type == 'decorate'){
				$decorate = Hapyfish2_Ipanda_Cache_Decorate::getList($uid);
				if($decorate){
					return false;
				}else{
					$num = 0;
					foreach ($decorate as $item) {
						if ($item['cid'] == $v->cid) {
							$num++;
						}
					}
					if($num <= 0){
						return false;
					}
				}
			}
		}
		return true;
	}
	
	public static function exchange($uid, $need, $useraward, $isdel)
	{
		$award = new Hapyfish2_Ipanda_Bll_Award();
		foreach($useraward as $k => $v){
			if($v->type == 'love'){
				$award->setLove($v->num);
			}else if ($v->type == 'gold'){
				$award->setGold($v->num);
			}else if($v->type == 'card'){
				$award->setCard($v->cid, $v->num);
			}else if($v->type == 'build'){
				$award->setBuilding($v->cid, $v->num);
			} else if($v->type == 'decorate'){
				$award->setDecorate($v->cid, $v->num);
			}
		}
		$ok = $award->sendOne($uid);
		if($isdel == 1){
			foreach($need as $n => $del){
				if($del->type == 'love'){
					$okLove = Hapyfish2_Ipanda_HFC_User::decUserLove($uid, $del->num);
					if ($okLove) {
						Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '1', -$del->num);
					}
				}else if ($del->type == 'gold'){
					$goldInfo = array(
						'uid' 			=> $uid,
						'cost' 			=> $del->num,
						'summary' 		=> '参加活动',
						'create_time' 	=> time(),
						'user_level' 	=> '',
						'cid' 			=> '',
						'num' 			=> ''
					);
					$ok = Hapyfish2_Ipanda_Bll_Gold::consume($uid, $goldInfo);
					if ($ok) {
						Hapyfish2_Ipanda_Bll_UserResult::addChanged($uid, '2', -$del->num);
					}
				}else if($del->type == 'card'){
					Hapyfish2_Ipanda_HFC_Card::useUserCard($uid, $del->cid, $del->num);
				}else if($del->type == 'build'){
					//扣除建筑 待添加 //	TODO
				} else if($del->type == 'decorate'){
					//扣除装饰 待添加//	TODO
				}
			}
		}
		return $ok;
	}
}