<?php


class Hapyfish2_Ipanda_Event_Dal_Tongji
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Donate
     */
    public static function getDefaultInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
	public function getTableName($uid)
    {
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_info_' . $id;
    }
    
	public function getUid($id)
	{
		$tb = $this->getTableName($id);
		$sql = "select uid from $tb where last_login_time >=1325433600 and last_login_time<=1325477700";
		$db = Hapyfish2_Db_Factory::getDB($id);
        $rdb = $db['r'];
        return $rdb->fetchCol($sql);
	}

}