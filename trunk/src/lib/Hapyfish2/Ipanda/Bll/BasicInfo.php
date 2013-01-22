<?php

class Hapyfish2_Ipanda_Bll_BasicInfo
{	
	public static function getInitData($v = '1.0', $compress = false)
	{
		if (!$compress) {
			return self::restore($v);
		} else {
			return self::restoreCompress($v);
		}
	}
	
	public static function restore($v = '1.0')
	{
		$file = TEMP_DIR . '/initData.' . $v . '.cache';
		if (is_file($file)) {
			return file_get_contents($file);
		} else {
			return self::dumpInitData($v);
		}
	}
	
	public static function restoreCompress($v = '1.0')
	{
		$file = TEMP_DIR . '/initData.' . $v . '.cache.gz';
		if (is_file($file)) {
			return file_get_contents($file);
		} else {
			return self::dumpInitData($v, true);
		}
	}
	
	public static function dumpInitData($v = '1.0', $compress = true)
	{
		$resultInitVo = self::getInitVo();
		$file = TEMP_DIR . '/initData.' . $v . '.cache';
		$data = json_encode($resultInitVo);
		file_put_contents($file, $data);
		
		if ($compress) {
			$data = gzcompress($data, 9);
			$file .= '.gz';
			file_put_contents($file, $data);
		}

		return $data;
	}
	
	public static function removeDumpFile($v = '1.0', $compress = false)
	{
	    $file = TEMP_DIR . '/initData.' . $v . '.cache';
	    if ($compress) {
	        $file .= '.gz';
	    }
	    if (is_file($file)) {
            $rst = @unlink($file);
	    }
	    return $rst;
	}
	
	public static function getInitVo()
	{
        $resultInitVo = array();
		require (CONFIG_DIR . '/swfconfig.php');
		require (CONFIG_DIR . '/errorcode.php');
		require (CONFIG_DIR . '/logconfig.php');
      	$resultInitVo = $swfResult;
      	
        $resultInitVo['logconfig'] = $logconfig;
        $resultInitVo['errorcode'] = $error_code;
        
        $gameData = array(
        	'ipanda_building' 				=> self::getBuildingList(),
        	'ipanda_level_user' 			=> self::getUserLevelList(),
        	'ipanda_level_animal' 			=> self::getAnimalLevelList(),
        	'ipanda_decorate' 				=> self::getDecorateList(),
        	'ipanda_phytotron' 				=> self::getPhytotronList(),
        	'ipanda_phytotron_animal' 		=> self::getPhytotronAnimalList(),
        	'ipanda_phytotron_unlock_list' 	=> self::getPhytotronUnlockList(),
        	'ipanda_entend_forest' 			=> self::getEntendForestList(),
        	'ipanda_material' 				=> self::getMaterialList(),
        	'ipanda_card' 					=> self::getCardList(),
        	'ipanda_achievement' 			=> self::getAchievementList(),
        	//任务基础数据
        	'ipanda_task_type' 				=> self::getTaskTypeList(),
        	'ipanda_task_condition' 		=> self::getTaskConditionList(),
        	'ipanda_task_list' 				=> self::getTaskList(),
        	//熊猫问题
        	'ipanda_panda_qeustion' 		=> self::getPandaQuestionList(),
        	//对话
        	'ipanda_animal_dialogue'		=> self::getAnimalDialogue(),
        	//体力消耗
        	'energy_consume'				=> self::getEnergyConsume(),
        	//动物病症
        	'disease'                      => self::getDisease(),
        	//医院
        	'hospital'                     =>self::getHospital(),
        	//草药
        	'drug'                         =>self::getDrug(),
        );
        
        $resultInitVo['gameData'] = $gameData;
        
        return $resultInitVo;
	}
	
	public static function getAnimalDialogue()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getAnimalDialogue();
		$data = array();
		foreach ($list as &$v)
		{
			$v['content'] = json_decode($v['content'], true);
			$data[] = $v;
		}
		return $data;
	}
	
	public static function getEnergyConsume()
	{
		return array(
			'checkoutlove' 		=> EG_CHECKOUT_LOVE,
			'reciveanimal' 		=> EG_RECIVE_ANIMAL,
			'takefriendlove' 	=> EG_TAKE_FRIEND_LOVE,
			'fixfriendbuilding' => EG_FIX_FRIEND_BUILDING,
			'fixbuilding' 		=> EG_FIX_MY_BUILDING,
			'rantanimal' 		=> EG_RENT_ANIMAL,
			'putphytotron' 		=> EG_PER_PHYTOTRON,
			'putbuilding' 		=> EG_PER_BUILDING,
			'addintimacy' 		=> EG_ANIMAL_EXP,
		);
	}	
	public static function getBuildingList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingList();
		$data = array();
		
		foreach ($list as &$v) {
			$v['cid'] = (int)$v['cid'];
			$v['love_price'] = (int)$v['love_price'];
			$v['gold_price'] = (int)$v['gold_price'];
			$v['unlock_level_gold'] = (int)$v['unlock_level_gold'];
			$v['price_type'] = (int)$v['price_type'];
			$v['cheap_price'] = (int)$v['cheap_price'];
			$v['cheap_start_time'] = (int)$v['cheap_start_time'];
			$v['cheap_end_time'] = (int)$v['cheap_end_time'];
			$v['sale_price'] = (int)$v['sale_price'];
			$v['need_level'] = (int)$v['need_level'];
			$v['item_type'] = (int)$v['item_type'];
			$v['item_id'] = (int)$v['item_id'];
			$v['isnew'] = (int)$v['isnew'];
			$v['can_buy'] = (int)$v['can_buy'];
			$v['level'] = (int)$v['level'];
			$v['next_level_cid'] = (int)$v['next_level_cid'];
			$v['durable'] = (int)$v['durable'];
			$v['durable_time'] = (int)$v['durable_time'];
			$v['animal_cid'] = (int)$v['animal_cid'];
			$v['material_group_id'] = (int)$v['material_group_id'];
			$data[] = $v;
		}
		
		return $data;
	}

	public static function getBuildingInfo($cid)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getBuildingInfo($cid);
	}
	
	public static function getUserLevelList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Info::getUserLevelList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getUserLevelInfo($level)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Info::getUserLevelInfo($level);
	}
	
	public static function getAnimalLevelList()
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getAnimalLevelList();
	}
	
	public static function getDecorateList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateList();
		$data = array();
		
		foreach ($list as &$v) {
			$v['cid'] = (int)$v['cid'];
			$v['price_type'] = (int)$v['price_type'];
			$v['gold_price'] = (int)$v['gold_price'];
			$v['love_price'] = (int)$v['love_price'];
			$v['effect_time'] = (int)$v['effect_time'];
			$v['cheap_price'] = (int)$v['cheap_price'];
			$v['cheap_start_time'] = (int)$v['cheap_start_time'];
			$v['cheap_end_time'] = (int)$v['cheap_end_time'];
			$v['sale_price'] = (int)$v['sale_price'];
			$v['need_level'] = (int)$v['need_level'];
			$v['item_type'] = (int)$v['item_type'];
			$v['item_id'] = (int)$v['item_id'];
			$v['isnew'] = (int)$v['isnew'];
			$v['can_buy'] = (int)$v['can_buy'];
			$v['attr_love'] = (int)$v['attr_love'];
			$v['attr_exp'] = (int)$v['attr_exp'];
			$v['can_sale'] = (int)$v['can_sale'];
			$v['can_recyle'] = (int)$v['can_recyle'];
			$v['costMultiplier'] = (int)$v['walkable_weight'];
			$v['walkAble'] = (int)$v['walkable'];
			unset($v['walkable']);
			unset($v['walkable_weight']);
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getDecorateInfo($cid)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getDecorateInfo($cid);
	}
	
	public static function getPhytotronList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
		$data = array();
		if ($list) {
			foreach ($list as $v) {
				$data[] = array(
					'cid' 					=> (int)$v['phytotron_cid'],
					'name' 					=> $v['phytotron_name'],
					'class_name' 			=> $v['phytotron_class_name'],
					'item_id' 				=> (int)$v['phytotron_item_id'],
					'item_type' 			=> (int)$v['phytotron_item_type'],
					'nodes'					=> '2*2',
       				'animal_cid'			=> (int)$v['cid'],
       				'animal_class_name'		=> $v['class_name'],
       				'animal_name'			=> $v['name'],
					'consume_building'		=> $v['consume_building']
				);
			}
		}
		
		return $data;
	}
	
	public static function getPhytotronAnimalList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getPhytotronAnimalInfo($cid)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronAnimalInfo($cid);
	}
	
	public static function getPhytotronUnlockList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::loadPhytotronUnlockList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getPhytotronUnlockInfo($id)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getPhytotronUnlockInfo($id);
	}
	
	public static function getEntendForestList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getEntendForestList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getEntendForestInfo($num)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getEntendForestInfo($num);
	}
		
	public static function getMaterialGroupList()
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialGroupList();
	}
	
	public static function getMaterialList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getMaterialInfo($cid)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getMaterialInfo($cid);
	}
	
	public static function getCardList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getCardList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getCardInfoByCid($cid)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getCardInfo($cid);
	}
	
	public static function getAchievementList()
	{
		$list = Hapyfish2_Ipanda_Cache_Basic_Info::getAchievementList();
		$data = array();
		
		foreach ($list as $v) {
			$data[] = $v;
		}
		
		return $data;
	}
	
	public static function getAnimalLevelInfo($level)
	{
		return Hapyfish2_Ipanda_Cache_Basic_Asset::getAnimalLevelInfo($level);
	} 
	
	public static function getCurrentAnimalLevelInfo($num)
	{
		if (empty($num)) {
			$num = 0;
		}
		
		$list = Hapyfish2_Ipanda_Cache_Basic_Asset::getAnimalLevelList();
		
		$cur = array();
		$next = array();
		foreach ($list as $v) {
			if ($v['service_num'] > $num) {
				$next = $v;
				break;
			}
			
			$cur = $v;
		}
		
		$result['level']  = $cur['level'];
		$result['cur_num'] = $num - $cur['service_num'];
		$result['next_num'] = $next['service_num'] - $cur['service_num'];
		$result['love_price'] = $cur['love_price'];
		
		return $result;
	}
	
	public static function getCurrentUserLevelByExp($num)
	{
		if (empty($num)) {
			$num = 0;
		}
		
		$list = Hapyfish2_Ipanda_Cache_Basic_Info::getUserLevelList();
		
		$cur = array();
		$next = array();
		foreach ($list as $v) {
			if ($v['exp'] > $num) {
				$next = $v;
				break;
			}
			
			$cur = $v;
		}
		
		$result['level']  = $cur['level'];
		$result['cur_exp'] = $num - $cur['exp'];
		$result['next_exp'] = $next['exp']  - $cur['exp'];
		return $result;
	}
	
	////////////////////////////////////////////////////
	//任务相关
	public static function getTaskTypeList()
	{
		$taskTypeList = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskTypeList();
		$taskTypeData = array();
		
		foreach ($taskTypeList as $v) {
			$taskTypeData[] = array(
				'id' => (int)$v['id'],
				'desp' => $v['desp'],
				'icon_class_name' => $v['icon_class_name'],
				'is_client_action' => $v['is_client_action']
			);
		}
		
		return $taskTypeData;
	}
	
	public static function getTaskConditionList()
	{
		$taskConditionList = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskConditionList();
		$taskConditionData = array();
		
		foreach ($taskConditionList as $v) {
			$taskConditionData[] = array(
				'id' => (int)$v['id'],
				'desp' => $v['desp'],
				'condition_type' => (int)$v['condition_type'],
				'cid' => (int)$v['cid'],
				'num' => (int)$v['num'],
				'icon_class_name' => $v['icon_class_name'],
				'complete_gold' => (int)$v['complete_gold']
			);
		}
		
		return $taskConditionData;
	}
	
	public static function getTaskList()
	{
		$taskList = Hapyfish2_Ipanda_Cache_Basic_Info::getTaskList();
		$taskData = array();
				
		foreach ($taskList as $v) {
			$taskData[] = array(
				'id' => (int)$v['id'],
				'priority' => (int)$v['priority'],
				'condition_ids' => json_decode($v['condition_ids'], true),
				'title' => $v['title'],
				'foreword' => $v['foreword'],
				'help_desp' => $v['help_desp'],
				'done_desp' => $v['done_desp'],
				'guide' => (int)$v['guide'],
				'love'	=> (int)$v['love'],
				'exp'	=> (int)$v['exp'],
				'gold'	=> (int)$v['gold'],
				'materials'	=> json_decode($v['materials'], true),
				'items'	=> json_decode($v['items'], true),
				'decorates'	=> json_decode($v['decorates'], true)
			);
		}
		
		return $taskData;
	}
	
	public static function getPandaQuestionList()
	{
		$questionList = Hapyfish2_Ipanda_Cache_Basic_Extend::getPandaQuestionList();
		$questionData = array();
				
		foreach ($questionList as $v) {
			$award = array(
				array('cid' => 1, 'num' => $v['award_love']),
				array('cid' => 5, 'num' => $v['award_exp']),
				array('cid' => 6, 'num' => $v['award_intimacy'])
			);
			$questionData[] = array(
				'id' => (int)$v['id'],
				'question' => $v['question'],
				'answer_list' => array($v['answer_A'],$v['answer_B'],$v['answer_C'],$v['answer_D']),
				'answer' => $v['answer'],
				'content' => str_replace('"', '&quot;', $v['content']),
				'award' => $award
			);
		}
		
		return $questionData;
	}
	
	public static function getDisease()
	{
		$data =array();
		$disease = Hapyfish2_Ipanda_Cache_Hospital::getDisease();
		foreach($disease as $k => $v){
			$data[] = $v;
		}
		return $data;
	}
	
	public static function getHospital()
	{
		$data = array();
		$hospital = Hapyfish2_Ipanda_Cache_Hospital::getHospital();
		foreach($hospital as $k=>$v){
			$data[] = $v;
		}
		return $data;
	}
	
	public static function getDrug()
	{
		$data = array();
		$drug = Hapyfish2_Ipanda_Cache_Hospital::getDrug();
		foreach($drug as $k=>$v){
			$data[] = $v;
		}
		return $data;
	}
	
	
}