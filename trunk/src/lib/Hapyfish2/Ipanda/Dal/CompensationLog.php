<?php

class Hapyfish2_Ipanda_Dal_CompensationLog
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Island_Dal_CompensationLog
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
    	if (APP_STATUS_DEV == 4) {
    		return 'ipanda_user_compensationlog';
    	}
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_compensationlog_' . $id;
    }
    
    public function getAll($uid)
    {
    	$tbname = $this->getTableName($uid);
    	$sql = "SELECT id,uid,create_time FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }
    
    public function getOne($uid, $id , $date)
    {
    	$tbname = $this->getTableName($uid);
    	$sql = "SELECT uid FROM $tbname WHERE uid=:uid AND id=:id";
    	if($date > 0){
    		$sql .=" and create_time=:date";
    	}
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchOne($sql, array('uid' => $uid, 'id' => $id, 'date'=> $date));
    }
    
    public function insert($uid, $info)
    {
    	$tbname = $this->getTableName($uid);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	return $wdb->insert($tbname, $info); 	
    }
    
    public function clear($uid)
    {
        $tbname = $this->getTableName($uid);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
}