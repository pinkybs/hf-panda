<?php

class Hapyfish2_Ipanda_Bll_CacheVersion
{	
	public static function get($name)
	{
		$data = Hapyfish2_Ipanda_Cache_Basic_Extend::getCacheVersion();
		if ($data == null || !isset($data[$name])) {
			return self::update($name);
		}
		
		return $data[$name];
	}
	
	public static function update($name, $version = null)
	{
		if ($version == null) {
			$version = date('YmdHi');
		}
		
		$data = Hapyfish2_Ipanda_Cache_Basic_Extend::getCacheVersion();
		if ($data == null) {
			$data = array();
		}
		$data[$name] = $version;
		$value = json_encode($data);
		Hapyfish2_Ipanda_Cache_Basic_Extend::updateCacheVersion($value);
		
		return $version;
	}
	
	public static function refresh()
	{
		return Hapyfish2_Ipanda_Cache_Basic_Extend::resetCacheVersion();
	}
}