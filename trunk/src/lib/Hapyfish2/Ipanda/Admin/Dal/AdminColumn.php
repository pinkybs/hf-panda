<?php
/**
 * bujisky.li
 * bujisky.li@hapyfish.com
 * */
class Hapyfish2_Ipanda_Admin_Dal_AdminColumn  
{
	protected $tbname = 'admin_column';
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
    	$sql = "SELECT db_type,column_name , table_name,column_no,p_no,primary_key FROM ".$this->tbname." WHERE column_no='$column_no'";
    	
        $db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
    	
        return $rdb->fetchRow($sql);
	}
	public function getAll() 
	{
    	$sql = "SELECT db_type,column_name , table_name,column_no,p_no,primary_key FROM ".$this->tbname." WHERE 1";
    	
        $db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql);
	}
	public function basicAdd($data,$table)
	{
		$fields = array();
		$values = array();
		foreach ($data as $key => $value)
		{
			$fields[] = "`".$key."`";
			$values[] = "'".$value."'";
		}
		$f = join(",",$fields);
		$v = join(",",$values);
		$sql = "insert into $table ($f) values ($v)";
		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
		$rdb->query($sql);
	}
	public function basicQuery($sql)
	{
		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
		return $rdb->query($sql);
	}
	public function userQuery($dbkey,$sql)
	{
		$db = Hapyfish2_Db_Factory::getDBByKey("db_".$dbkey);
        $rdb = $db['r'];
		return $rdb->query($sql);
	}
	public function getBasicList($table,$page,$num,$data,$where)
	{
		$start = ($page-1)*$num;
		
		
		$fields = array();
		$values = array();
		foreach ($data as $key => $value)
		{
			$fields[] = "`".$value['field_db_name']."`";
		
		}
		$f = join(",",$fields);
		
		$sql = "SELECT * from $table $where limit $start ,$num";
		echo $sql;
		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
		$rdb = $db['r'];
		return $rdb->fetchAll($sql);
	}
	public function getBasicCount($table,$where)
	{
		$sql = "SELECT count(*) as num from $table $where ";
		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
		$rdb = $db['r'];
		return $rdb->fetchRow($sql);
	}
	public function getBasicData($where,$table,$data)
	{
		$fields = array();
		$values = array();
		foreach ($data as $key => $value)
		{
			$fields[] = "`".$value['field_db_name']."`";
		
		}
		$f = join(",",$fields);
		
		$sql = "SELECT $f from $table where $where";
	
		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
		$rdb = $db['r'];
		return $rdb->fetchRow($sql);
	}
	public function basicUpdate($data,$table,$where)
	{

		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
		$rdb->update($table, $data, $where); 
		//
	}
	public function clear($data,$table,$where)
	{

		$db = Hapyfish2_Db_Factory::getBasicAdminDB("db_0");
        $rdb = $db['r'];
		$rdb->update($table, $data, $where); 
		//
	}
	
}