<?php

class Hapyfish2_Ipanda_Bll_Award
{
	//特殊cid
	// 1 : 爱心
	// 2 : 宝石
	// 3 : 体力
	// 4 : 动物上限
	// 5 : 经验
	
	//爱心
	protected $_love;
	//经验
	protected $_exp;
	//宝石
	protected $_gold;
	//体力
	protected $_energy;
	
	//道具
	protected $_cards;
	//材料
	protected $_materials;
	//装饰
	protected $_decorates;
	//建筑
	protected $_buildings;
	
	protected $_content;
	
	public function __construct()
	{
		$this->_love = 0;
		$this->_exp = 0;
		$this->_gold = 0;
		$this->_energy = 0;
		
		$this->_cards = array();
		$this->_materials = array();
		$this->_decorates = array();
		$this->_buildings = array();
		$this->_phytotrons = array();
		
		$this->_content = array();
	}
	
	public function setLove($love)
	{
		$this->_love = $love;
	}
	
	public function setExp($exp)
	{
		$this->_exp = $exp;
	}

	public function setGold($gold, $type = 0)
	{
		$this->_gold = $gold;
		$this->_goldAddType = $type;
	}
	
	public function setEnergy($energy)
	{
		$this->_energy = $energy;
	}
	
	public function setProp($prop)
	{
		foreach ($prop as $k => $v) {
			if ($k == 'love') {
				$this->setLove($v);
			} else if ($k == 'exp') {
				$this->setExp($v);
			} else  if ($k == 'gold') {
				$this->setGold($v);
			} else  if ($k == 'energy') {
				$this->setEnergy($v);
			} 
		}
	}
	
	public function setCard($cid, $count)
	{
		if ($cid > 0) {
			if (isset($this->_cards[$cid])) {
				$this->_cards[$cid] += $count;
			} else {
				$this->_cards[$cid] = $count;
			}
		}
	}
	
	public function setCardList($cards)
	{
		foreach ($cards as $v) {
			$cid = $v[0];
			$count = $v[1];
			if ($cid > 0) {
				if (isset($this->_cards[$cid])) {
					$this->_cards[$cid] += $count;
				} else {
					$this->_cards[$cid] = $count;
				}
			}
		}
	}
	
	public function setMaterial($cid, $count)
	{
		if ($cid > 0) {
			if (isset($this->_materials[$cid])) {
				$this->_materials[$cid] += $count;
			} else {
				$this->_materials[$cid] = $count;
			}
		}
	}
	
	public function setMaterialList($materials)
	{
		foreach ($materials as $v) {
			$cid = $v[0];
			$count = $v[1];
			if ($cid > 0) {
				if (isset($this->_materials[$cid])) {
					$this->_materials[$cid] += $count;
				} else {
					$this->_materials[$cid] = $count;
				}
			}
		}
	}
	
	public function setDecorate($cid, $count)
	{
		if ($cid > 0) {
			if (isset($this->_decorates[$cid])) {
				$this->_decorates[$cid] += $count;
			} else {
				$this->_decorates[$cid] = $count;
			}
		}
	}
	
	public function setDecorateList($decorates)
	{
		foreach ($decorates as $v) {
			$cid = $v[0];
			$count = $v[1];
			if ($cid > 0) {
				if (isset($this->_decorates[$cid])) {
					$this->_decorates[$cid] += $count;
				} else {
					$this->_decorates[$cid] = $count;
				}
			}
		}
	}
	
	public function setBuilding($cid, $count)
	{
		if ($cid > 0) {
			if (isset($this->_buildings[$cid])) {
				$this->_buildings[$cid] += $count;
			} else {
				$this->_buildings[$cid] = $count;
			}
		}
	}
	
	public function setBuildingList($buildings)
	{
		foreach ($buildings as $v) {
			$cid = $v[0];
			$count = $v[1];
			if ($cid > 0) {
				if (isset($this->_buildings[$cid])) {
					$this->_buildings[$cid] += $count;
				} else {
					$this->_buildings[$cid] = $count;
				}
			}
		}
	}
	
	public function setSomething($cid, $num)
	{
		if ($cid > 0) {
			if ($cid == 1) {
				$this->setLove($num);
			} else if ($cid == 2) {
				$this->setGold($num);
			} else if ($cid == 3) {
				$this->setEnergy($num);
			} else if ($cid == 5) {
				$this->setExp($num);
			} else if ($cid > 100) {
				$type = substr($cid, -2);
				if ($type == 11) {
					$this->setBuilding($cid, $num);
				} else if ($type == 21) {
					$this->setDecorate($cid, $num);
				} else if ($type >= 41 && $type <=49) {
					$this->setCard($cid, $num);
				} else if ($type == 51) {
					$this->setMaterial($cid, $num);
				}
			}
		}
	}
	
	private function addContent($uid, $content)
	{
		if (!isset($this->_content[$uid])) {
			$this->_content[$uid] = array();
		}
		
		$this->_content[$uid][] = $content;
	}
	
	public function getContent($uid)
	{
		if (isset($this->_content[$uid])) {
			return $this->_content[$uid];
		}
		
		return '';
	}
	
	public function clearContent($uid)
	{
		if (isset($this->_content[$uid])) {
			unset($this->_content[$uid]);
		}
	}
	
	public function sendOne($uid)
	{
		$t = time();
		
		if ($this->_love > 0) {
			$ok = Hapyfish2_Ipanda_HFC_User::incUserLove($uid, $this->_love);
			if ($ok) {
				$this->addContent($uid, '爱心*' . $this->_love);
			}
		}
		
		if($this->_energy > 0){
			$ok = Hapyfish2_Ipanda_HFC_User::incUserEnergy($uid, $this->_energy);
		}
		
		if ($this->_exp > 0) {
			$ok = Hapyfish2_Ipanda_HFC_User::incUserExp($uid, $this->_exp);
			if ($ok) {
				$this->addContent($uid, '经验*' . $this->_exp);
			}
		}

		if ($this->_gold > 0) {
			$goldInfo = array('gold' => $this->_gold, 'type' => $this->_goldAddType);
			$ok = Hapyfish2_Ipanda_Bll_Gold::add($uid, $goldInfo);
			if ($ok) {
				$this->addContent($uid, '金币*' . $this->_gold);
			}
		}
		
		foreach ($this->_cards as $cid => $count) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardInfo($cid);
			if ($basicInfo) {
				$ok = Hapyfish2_Ipanda_HFC_Card::addUserCard($uid, $cid, $count);
		    	if ($ok) {
		    		$this->addContent($uid, $basicInfo['name'] . '*' . $count);
		    	}
	    	} else {
	    		info_log('[' . $uid . ']invalid card cid:' . $cid, 'award-error');
	    	}
		}
		
		foreach ($this->_materials as $cid => $count) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialInfo($cid);
			if ($basicInfo) {
				$ok = Hapyfish2_Ipanda_HFC_Material::addUserMaterial($uid, $cid, $count);
		    	if ($ok) {
		    		$this->addContent($uid, $basicInfo['name'] . '*' . $count);
		    	}
			} else {
				info_log('[' . $uid . ']invalid material cid:' . $cid, 'award-error');
			}
		}
		
		foreach ($this->_decorates as $cid => $count) {
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
			if ($basicInfo) {
				for($i = 0; $i < $count; $i++) {
					$decorate = array(
						'uid' => $uid,
						'cid' => $cid,
						'item_type' => $basicInfo['item_type'],
						'forest_no' => 0,
						'status' => 0,
						'x'	=> 0,
						'y'	=> 0,
						'z' => 0,
						'mirro' => 0,
						'buy_time' => $t,
						'buy_type' => 2
					);
					if ($basicInfo['effect_time'] > 0) {
						$decorate['end_time'] = $t + $basicInfo['effect_time']*86400;
					}
					Hapyfish2_Ipanda_Bll_Decorate::add($uid, $decorate);
				}
				$this->addContent($uid, $basicInfo['name'] . '*' . $count);
			} else {
				info_log('[' . $uid . ']invalid decoreate cid:' . $cid, 'award-error');
			}
		}
		
		foreach($this->_buildings as $cid => $count){
			$basicInfo = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingInfo($cid);
			if ($basicInfo) {
				$checkout = $basicInfo['checkout_time'];
				$i = 0;
				foreach ($checkout as $v)
				{
					if($v['default'] == 1)
					{
						$checkout_time = $v['time'];
						$checkout_love = $v['love'];
						break;
					}
					$i++;
				}
				$attr = $basicInfo['attribute'];
				$attr[6] = 0;
				$attr[7] = 0;
				for($i = 0; $i < $count; $i++) {
					$building = array(
							'uid' => $uid,
							'cid' => $cid,
							'level'=> $basicInfo['level'],
							'item_id'=> $basicInfo['item_id'],
							'item_type' => $basicInfo['item_type'],
							'forest_no' => 0,
							'checkout_time_type' => 0,
							'checkout_time' => $checkout_time,
							'checkout_love' => $checkout_love,
							'deposit' => 0,
							'x' => 0,
							'y' => 0,
							'z' => 0,
							'status' => 0,
							'mirro' => 0,
							'buy_time' => $t,
							'durable' => $basicInfo['durable'],
							'top_durable' => $basicInfo['durable'],
							'op_status' => 2,
							'attr' => json_encode($attr),
							'effect_source' => '[]'
					);
					Hapyfish2_Ipanda_Bll_Building::addBuilding($uid, $building, $basicInfo);
				}
				$this->addContent($uid, $basicInfo['name'] . '*' . $count);
			} else {
				info_log('[' . $uid . ']invalid building cid:' . $cid, 'award-error');
			}
		}
		
		return true;
	}
}