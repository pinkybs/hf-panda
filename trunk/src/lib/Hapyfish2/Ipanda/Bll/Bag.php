<?php

class Hapyfish2_Ipanda_Bll_Bag
{
	public static function getList($uid)
	{
		$data = array();
		$cards = Hapyfish2_Ipanda_HFC_Card::getUserCard($uid);
       
		foreach ($cards as $cid => $card) {
			if ($card['count'] > 0) {
					$data[] = array(
			 			'num' 		=> $card['count'],
			 			'cid'		=> $cid,
						'item_type'	=> substr($cid, -2)
			  		);
			}
		}
 
		$material = Hapyfish2_Ipanda_HFC_Material::getUserMaterial($uid);
		foreach ($material as $cid => $m) {
			if ($m['count'] > 0) {
	     		$data[] = array(
	     			'num' 		=> $m['count'],
	     			'cid'		=> $cid,
	   				'item_type'	=> substr($cid, -2)
	       		);
			}
		}
 
		return $data;
	}

}