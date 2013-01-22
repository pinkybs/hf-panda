<?php

class Hapyfish2_Ipanda_Bll_Card
{
	public static function getList($uid)
	{
       $cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
       $data = array();
       
       foreach ($cards as $cid => $card) {
	    	if ($card['count'] > 0) {
	     		$data[] = array(
       				'num' 		=> $card['count'],
       				'cid'		=> $cid,
     				'item_type'	=> substr($cid, -2)
	       		);
	    	}
       }
       
       return $data;
	}
	
	public static function getInfo($uid, $cid)
	{
		$cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
		if (empty($cards) || !isset($cards[$cid])) {
			return null;
		}
		
		return array('cid' => $cid, 'count' => $cards[$cid]['count']);
	}
	
	public static function getOneCardCount($uid, $cid)
	{
		$cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
		if (empty($cards) || !isset($cards[$cid])) {
			return 0;
		}
		
		return $cards[$cid]['count'];
	}
	
	public static function useCard($uid, $cid, $param, &$returnMsg)
	{
		$cardInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardInfo($cid);
		//没有这种道具
		if (!$cardInfo) {
			$returnMsg['eid'] = -1;
			return false;
		}
		
		//完成维修和培育 加速 ,需要目标物id
		if ($cardInfo['cid'] == 443) { 
			if (empty($param['obj_id']) || empty($param['type'])) {
				$returnMsg['eid'] = -104;
				return false;
			}
		} else if ($cardInfo['item_type'] == 42) {
			//亲密度加倍卡
			//检查该动物是否已经解锁
			$isUnlcok = Hapyfish2_Ipanda_Bll_PhytotronAnimal::isUnlock($uid, $cardInfo['effect_cid']);
			if (!$isUnlcok) {
				$returnMsg['eid'] = -605;
				return false;
			}
			
			$cardStatus = Hapyfish2_Ipanda_HFC_Card::getCardStatus($uid);
			$t = time();
			if (empty($cardStatus)) {
				$cardStatus = array(
					$cardInfo['cid'] => array($cardInfo['effect_cid'], $t + $cardInfo['effect_num']*60)
				);
			} else {
				$num = count($cardStatus);
				if ($num >= 5 && !isset($cardStatus[$cardInfo['cid']])) {
					$returnMsg['eid'] = -807;
					return false;
				}
				if (isset($cardStatus[$cardInfo['cid']])) {
					$cardStatus[$cardInfo['cid']][1] += $cardInfo['effect_num']*60;
				} else {
					$cardStatus[$cardInfo['cid']] = array($cardInfo['effect_cid'], $t + $cardInfo['effect_num']*60);
				}
			}
		}
		
		//先用道具
		$ok = Hapyfish2_Ipanda_HFC_Card::useUserCard($uid, $cid, 1);
		if (!$ok) {
			$returnMsg['eid'] = -803;
			return false;
		}
		
		//后获得道具属性
		if($cardInfo['effect_cid'] == 3) {
			//增加体力
			if ($cardInfo['effect_num'] == 0) {
				Hapyfish2_Ipanda_HFC_User::fullUserEnergy($uid);
			} else {
				Hapyfish2_Ipanda_HFC_User::incUserEnergy($uid, $cardInfo['effect_num']);
			}
			
	    	//派发任务
	    	//使用道具
	    	$event = array('uid' => $uid, 'cid' => $cid);
	    	Hapyfish2_Ipanda_Bll_Event::useCard($event);
		} else if ($cardInfo['cid'] == 443) { 
			//完成维修和培育 加速 
			// 1 : 培育屋加速
			// 2 : 建筑维修加速
			if ($param['type'] == 1) {
				$data = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid, $param['obj_id']);
				if($data['state'] == 1) {
					$updateInfo['end_time'] = time() - 1; 
					Hapyfish2_Ipanda_Bll_Phytotron::updatePhytotron($uid, $param['obj_id'], $updateInfo);
					
			    	//派发任务
			    	//加速培育屋
			    	$event = array('uid' => $uid, 'cid' => $cardInfo['cid']);
			    	Hapyfish2_Ipanda_Bll_Event::useSpeedCardForPhytotron($event);
				} else {
					$returnMsg['eid'] = -804;
					return false;
				}
			} else if ($param['type'] == 2) {
				$data = Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid, $param['obj_id']);
				if($data['state'] == 3) {
					$fixinfo['durable'] = $data['top_durable'];
					$fixinfo['fix_end_time'] = 0;
					Hapyfish2_Ipanda_HFC_Building::updateBuildingConsume($uid, $param['obj_id'], $fixinfo, true);
					
			    	//派发任务
			    	//加速维修
			    	$event = array('uid' => $uid, 'cid' => $cardInfo['cid']);
			    	Hapyfish2_Ipanda_Bll_Event::useSpeedCardForFixBuilding($event);
				} else {
					$returnMsg['eid'] = -805;
					return false;
				}
			}
		} else if ($cardInfo['item_type'] == 42) {			
			$ok2 = Hapyfish2_Ipanda_HFC_Card::updateCardStatus($uid, $cardStatus, true);
			if ($ok2) {
				Hapyfish2_Ipanda_Bll_UserResult::setCardStatusChange($uid, true);
			}
		} else {
			$returnMsg['eid'] = -805;
			return false;
		}
				
		return true;
	}

	/**
	 * 取得有影响效果的道具
	 *
	 * @param int $uid
	 * @param int $effect_cid
	 * @return Array
	 */
	public static function getRelateCard($uid, $effect_cid)
	{
		if($effect_cid == 3) {
			//体力
			$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardInfoByEffectCid($effect_cid);
			$cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
			$data = array();
			foreach($list as $v) {
				if (!isset($cards[$v['cid']])) {
					$num = 0;
				} else {
					$num = $cards[$v['cid']]['count'];
				}
				$data[] = array('cid' => $v['cid'], 'num' => $num);
			}
			
			return $data;
		}
		
		return null;
	}

	public static function getCardStatus($uid)
	{
		$list = array();
		$status = Hapyfish2_Ipanda_HFC_Card::getCardStatus($uid);
		if (empty($status)) {
			return $list;
		}
		
		$t = time();
		foreach ($status as $cid => $item) {
			if ($item[1] > $t) {
				$list[] = array('cid' => $cid, 'time' => ($item[1] - $t));
			}
		}
		
		return $list;
	}
	
	public static function hasIntimacyState($uid, $cid)
	{
		$status = Hapyfish2_Ipanda_HFC_Card::getCardStatus($uid);
		if (empty($status)) {
			return false;
		}
		
		foreach ($status as $item) {
			if ($item[0] == $cid) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function getIntimacyState($uid)
	{
		$state = array();
		$status = Hapyfish2_Ipanda_HFC_Card::getCardStatus($uid);
		if (empty($status)) {
			return $state;
		}
		
		foreach ($status as $item) {
			$state[$item[0]] = 1;
		}
		
		return $state;
	}
}