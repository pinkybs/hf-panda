<?php

/**
 * 道具数据操作类
 *
 */
class Hapyfish2_Ipanda_Dal_Card
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Card
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
    		return 'ipanda_user_card';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 10;
    	return 'ipanda_user_card_' . $id;
    }
    
    public function get($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT cid,count FROM $tbname WHERE uid=:uid";

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];

        return $rdb->fetchPairs($sql, array('uid' => $uid));
    }
    
    public function update($uid, $cid, $count)
    {
        $tbname = $this->getTableName($uid);
        $sql = "INSERT INTO $tbname (uid, cid, count) VALUES($uid, $cid, $count) ON DUPLICATE KEY UPDATE count=$count";

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];

        $wdb->query($sql);
    }
    
    public function getAll($uid)
    {
        $tbname = $this->getTableName($uid);
    	$sql = "SELECT * FROM $tbname WHERE uid=:uid";
    	
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $rdb = $db['r'];
    	
        return $rdb->fetchAll($sql, array('uid' => $uid));
    }

    public function insert($uid, $info)
    {
        $tbname = $this->getTableName($uid);

        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	return $wdb->insert($tbname, $info);
    }
    
    public function delete($uid, $id)
    {
        $tbname = $this->getTableName($uid);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid AND id=:id";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        return $wdb->query($sql, array('uid' => $uid, 'id' => $id));
    }
    
    public function init($uid)
    {
        $tbname = $this->getTableName($uid);

        $sql = "INSERT INTO $tbname(uid, cid, count)
        		VALUES
				(:uid, 443, 5),
				(:uid, 244, 5),
				(:uid, 344, 3)";
		
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