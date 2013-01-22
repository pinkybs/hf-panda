<?php

class Hapyfish2_Ipanda_Bll_DumpUser
{
	public static function restore($uid)
	{
		$file = TEMP_DIR . '/dump.' . $uid . '.cache';
		if (is_file($file)) {
			return file_get_contents($file);
		} else {
			return self::dump($uid);
		}
	}

	public static function dump($uid, $asGM = false)
	{
		$result = self::get($uid, $asGM);
		$file = TEMP_DIR . '/dump.' . $uid . '.cache';
		$data = json_encode($result);
		file_put_contents($file, $data);
		return $data;
	}
	
	public static function getDumpUserResult($uid)
	{
		$file = TEMP_DIR . '/dump.' . $uid . '.cache';
		if (is_file($file)) {
			$data = file_get_contents($file);
			return json_decode($data, true);
		} else {
			return array();
		}
	}
	
	public static function get($uid, $asGM = false)
    {		
		$phytotronVo = Hapyfish2_Ipanda_Bll_Phytotron::getPhytotronList($uid);
		foreach ($phytotronVo as &$p) {
			if ($asGM) {
				$p['uid'] = GM_UID_LELE;
			}
			$p['can_rent_animal'] = false;
		}
		$result['phytotronVo'] = $phytotronVo;
		$result['decorateVo'] 	= Hapyfish2_Ipanda_Bll_Decorate::getList($uid);
		$buildingListOnForest  	= Hapyfish2_Ipanda_Bll_Building::getBuildingListOnForest($uid);
		$building_list = $buildingListOnForest['building_list'];
    	foreach ($building_list as &$d) {
    		if ($asGM) {
				$d['uid'] = GM_UID_LELE;
			}
			$p['can_take_love'] = false;
		}
		$result['buildingVo'] 	= $building_list;
		
		$result['animal_list'] 	= $buildingListOnForest['animal_list'];
		$result['animalVo']  	= Hapyfish2_Ipanda_Bll_PhytotronAnimal::getAnimalList($uid);
		$result['forestVo'] 	= Hapyfish2_Ipanda_Bll_Forest::getForestByNo($uid, 0);
		
		$userVo = Hapyfish2_Ipanda_Bll_User::getUserInit($uid);

        if ($asGM) {
        	$userVo['uid'] = GM_UID_LELE;
        	$userVo['name'] = GM_NAME_LELE;
        	$userVo['face'] = STATIC_HOST . '/' . GM_FACE_LELE;
        }
		
		$result['userVo'] = $userVo;
		
		return $result;
    }

}