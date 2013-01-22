<?php

class Hapyfish2_Ipanda_Bll_Material
{
	public static function getList($uid)
	{
       $material = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
       $data = array();
       
       foreach ($material as $cid => $m) {
			if ($m['count'] > 0) {
	     		$data[] = array(
	     			'num' 		=> $m['count'],
	     			'cid'		=> $cid,
	   				'item_type'	=> substr($cid, -2),
	       		);
       		}
       }
       
       return $data;
	}
	
	public static function getInfo($uid, $cid)
	{
		$material = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
		if (empty($material) || !isset($material[$cid])) {
			return null;
		}
		
		return array('cid' => $cid, 'count' => $material[$cid]['count']);
	}
	
	public static function getOneMaterialCount($uid, $cid)
	{
		$material = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
		if (empty($material) || !isset($material[$cid])) {
			return 0;
		}
		
		return $material[$cid]['count'];
	}
}