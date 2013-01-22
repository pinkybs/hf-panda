<?php

class Hapyfish2_Ipanda_Bll_Language
{
    public static function getText($key, $data)
    {
    	require CONFIG_DIR.'/language.'.COUNTRY.'.php';
    	$str = $language[$key];
    	foreach ($data as $k => $v) {
			$keys[] = '{*' . $k . '*}';
			$values[] = $v;
		}
		return str_replace($keys, $values, $str);
    }

}