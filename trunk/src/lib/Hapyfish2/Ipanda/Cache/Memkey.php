<?php

class Hapyfish2_Ipanda_Cache_Memkey
{
	
    public static function getuserkey($key = null)
    {
		$memKey = array(
			"i:u:isapp:" 						=> "i:u:isapp:",
			"i:u:ezinecount:" 					=> "i:u:ezinecount:",
			"i:u:buildinglist" 					=> "i:u:buildinglist",
			"i:u:bldids:all:" 					=> "i:u:bldids:all:",
			"i:u:bldids:onforest:" 				=> "i:u:bldids:onforest:",
			"i:u:forestlist:" 					=> "i:u:forestlist:",
			"i:u:phytotronlist:" 				=> "i:u:phytotronlist:",
			"i:u:phytotronanimallist:" 			=> "i:u:phytotronanimallist:",
			"i:u:phytotronadminlist:" 			=> "i:u:phytotronadminlist:",
			"i:u:phytotronadminmyloglist:" 		=> "i:u:phytotronadminmyloglist:",
			"i:u:decoratelist:" 				=> "i:u:decoratelist:",
			"i:u:deveplopland:" 				=> "i:u:deveplopland:",
			"i:u:phytotronlist:" 				=> "i:u:phytotronlist:",
			"i:u:buildinglevelunlocklist"		=> "i:u:buildinglevelunlocklist",
			"i:u:bldids:takelovelog:" 			=> "i:u:bldids:takelovelog:",
			"i:u:phytotronrentlog:" 			=> "i:u:phytotronrentlog:",
			"i:u:card:" 						=> "i:u:card:",
			"i:u:bldids:buildingattribute:" 	=> "i:u:bldids:buildingattribute:",
			"i:u:material:" 					=> "i:u:material:",
			"i:u:ach:" 							=> "i:u:ach:",
			"i:u:buildingunlocklist:" 			=> "i:u:buildingunlocklist:",
			"i:u:gridlist:" 					=> "i:u:gridlist:",
			"i:u:grid:" 						=> "i:u:grid:",
			"i:u:bldids:forestanimalnum" 		=> "i:u:bldids:forestanimalnum",
			"i:u:exp:" 							=> "i:u:exp:",
			"i:u:love:" 						=> "i:u:love:",
			"i:u:gold:" 						=> "i:u:gold:",
			"i:u:level:" 						=> "i:u:level:",
			"i:u:energyinfo:" 					=> "i:u:energyinfo:",
			"i:u:admin_num:" 					=> "i:u:admin_num:",
			"i:u:titleinfo:" 					=> "i:u:titleinfo:",
			"i:u:taskopen:" 					=> "i:u:taskopen:",
			"i:u:loginteractivebuild:" 			=> "i:u:loginteractivebuild:",
			"i:u:loginteractive:" 				=> "i:u:loginteractive2:",
			"i:u:cardstatus:" 					=> "i:u:cardstatus:",
			"i:u:mapgrid:" 						=> "i:u:mapgrid:",
		);
		
		if (!$key) {
			return $memKey;
		}
		
		if (!isset($memKey[$key])) {
			return $key;
		}
		
		return $memKey[$key];
    }
    
	public static function getbasickey($key = null)
    {
		$memKey = array(
			"ipanda:feedtemplate" 				=> "ipanda:feedtemplate",
			"ipanda:buildinglist" 				=> "ipanda:buildinglist",
			"ipanda:phytotronanimallist" 		=> "ipanda:phytotronanimallist",
			"ipanda:phytotronunlocklist" 		=> "ipanda:phytotronunlocklist",
			"ipanda:decoratelist" 				=> "ipanda:decoratelist",
			"ipanda:animallevellist" 			=> "ipanda:animallevellist",
			"ipanda:userlevellist" 				=> "ipanda:userlevellist",
			"ipanda:getmateriallist" 			=> "ipanda:getmateriallist",
			"ipanda:getcardlist" 				=> "ipanda:getcardlist",
			"ipanda:getgetmaterialgrouplist" 	=> "ipanda:getgetmaterialgrouplist",
			"ipanda:achievementlist" 			=> "ipanda:achievementlist",
			"ipanda:entendforestlist" 			=> "ipanda:entendforestlist",
			"ipanda:tasktypelist" 				=> "ipanda:tasktypelist",
			"ipanda:taskconditionlist" 			=> "ipanda:taskconditionlist",
			"ipanda:tasklist" 					=> "ipanda:tasklist",
			"ipanda:pandaquestionlist" 			=> "ipanda:pandaquestionlist",		
		
			'ipanda:asset:BuildingList'			=> 'ipanda:asset:BuildingList',
			'ipanda:asset:PhytotronAnimalList'	=> 'ipanda:asset:PhytotronAnimalList',
			'ipanda:asset:PhytotronUnlockList'	=> 'ipanda:asset:PhytotronUnlockList',
			'ipanda:asset:DecorateList'			=> 'ipanda:asset:DecorateList',
			'ipanda:asset:AnimalLevelList'		=> 'ipanda:asset:AnimalLevelList',
			'ipanda:asset:EntendForestList'		=> 'ipanda:asset:EntendForestList',
			'ipanda:asset:MaterialGroupList'	=> 'ipanda:asset:MaterialGroupList',
			'ipanda:asset:MaterialList'			=> 'ipanda:asset:MaterialList',
			'ipanda:asset:CardList'				=> 'ipanda:asset:CardList',
			'ipanda:asset:AnimalDialogue'		=> 'ipanda:asset:AnimalDialogue',
		
			'ipanda:info:FeedTemplate'			=> 'ipanda:info:FeedTemplate',
			'ipanda:info:UserLevelList'			=> 'ipanda:info:UserLevelList',
			'ipanda:info:AchievementList'		=> 'ipanda:info:AchievementList',
			'ipanda:info:TaskTypeList'			=> 'ipanda:info:TaskTypeList',
			'ipanda:info:TaskConditionList'		=> 'ipanda:info:TaskConditionList',
			'ipanda:info:TaskList'				=> 'ipanda:info:TaskList',
		
			'ipanda:extend:PandaQuestionList'	=> 'ipanda:extend:PandaQuestionList',
			'ipanda:extend:PandaQuestionSimpleList'	=> 'ipanda:extend:PandaQuestionSimpleList',
			'ipanda:extend:DailyAwardList'		=> 'ipanda:extend:DailyAwardList',
		);
		
		if(!$key) {
			return $memKey;
		}
		
		if (!isset($memKey[$key])) {
			return $key;
		}
		
		return $memKey[$key];
    }
	
}