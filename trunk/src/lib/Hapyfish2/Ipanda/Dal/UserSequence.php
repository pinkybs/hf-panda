<?php


class Hapyfish2_Ipanda_Dal_UserSequence
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_UserSequence
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
    		return 'ipanda_user_seq';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_seq_' . $id;
    }
    
    public function getId($uid,$key)
    {
    	$data = array(
    		"phytotronAdminMylog" 			=> "f",
    		"phytotron" 					=> "g",	
    		"buildingLevelUnlock" 			=> "h",
    		"decorate" 						=> "i",
    		"PhytotronAnimal" 				=> "j",
    		
    	);
    	$name = $data[$key];
    	return $this->get($uid, $name);
    }
    
    public function get($uid, $name, $step = 1)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "UPDATE $tbname SET id=LAST_INSERT_ID(id+$step) WHERE uid=:uid AND `name`=:name";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid, 'name' => $name));
    	$pid = $wdb->lastInsertId();
    	if(empty($pid))
    	{
    		$sql = "INSERT INTO $tbname(uid, name, id) VALUES(:uid, :name, 100)";
    		$wdb->query($sql, array('uid' => $uid,"name" => $name));
    		$pid = 100;
    	}
        return $pid;
    }
    
    public function init($uid)
    {
        $tbname = $this->getTableName($uid);
        $sql = "INSERT INTO $tbname(uid, name, id) VALUES(:uid, 'a', 100),(:uid, 'b', 100),(:uid, 'c', 100)";
    
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        $wdb->query($sql, array('uid' => $uid));
    }
    
    public function clear($uid)
    {
        $tbname = $this->getTableName($uid);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        return $wdb->query($sql, array('uid' => $uid));
    }
}