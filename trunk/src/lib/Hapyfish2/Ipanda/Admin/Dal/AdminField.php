<?php
/**
 * bujisky.li
 * bujisky.li@hapyfish.com
 * */
class Hapyfish2_Ipanda_Admin_Dal_AdminField  
{
	protected $tbname = 'admin_field';
	protected static $_instance;
	
	public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	

	public function get($column_no) 
	{
    	$sql = "SELECT * FROM ".$this->tbname." WHERE column_no='$column_no'";
    	
        $db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql);
	}
	
	
}