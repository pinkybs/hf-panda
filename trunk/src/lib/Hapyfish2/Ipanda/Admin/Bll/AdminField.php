<?php
/**
 * bujisky.li
 * bujisky.li@hapyfish.com
 * */
class Hapyfish2_Ipanda_Admin_Bll_AdminField
{
	
	
	
	
	public function getFieldInfo($column_no)
	{
		if(empty($column_no))
		{
			return 0;
		}
		$key = 'basic:adminfield:' . $column_no;
		
        $cache = Hapyfish2_Cache_Factory::getBasicMC(0);
        $data = $cache->get($key);
        $data = 0;
		if(empty($data))
		{
			$adminColumn = Hapyfish2_Ipanda_Admin_Dal_AdminField::getDefaultInstance();
			$data = $adminColumn->get($column_no);
			
		
	        $cache = Hapyfish2_Cache_Factory::getBasicMC(0);
	        $cache->set($key, $data);
		}
        
		return  $data;
	}
	
	
}