<?php


class Hapyfish2_Ipanda_Dal_Decorate
{
    protected static $_instance;

    /**
     * Single Instance
     *
     * @return Hapyfish2_Ipanda_Dal_Phytotron
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
    		return 'ipanda_user_decorate';
    	}
    	
    	$id = floor($uid/DATABASE_NODE_NUM) % 50;
    	return 'ipanda_user_decorate_' . $id;
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
    
    public function update($uid, $id, $info)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$uid = $wdb->quote($uid);
        $id = $wdb->quote($id);
    	$where = "uid=$uid AND id=$id";
    	
        return $wdb->update($tbname, $info, $where); 
    }
    
    public function delete($uid, $id)
    {
        $tbname = $this->getTableName($uid);
        
        $sql = "DELETE FROM $tbname WHERE uid=:uid AND id=:id";
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
        return $wdb->query($sql, array('uid' => $uid, 'id' => $id));
    }
    
	public function updateAll($uid, $info)
    {
        $tbname = $this->getTableName($uid);
        
        $db = Hapyfish2_Db_Factory::getDB($uid);
        $wdb = $db['w'];
        
    	$uid = $wdb->quote($uid);
    	$where = "uid=$uid";
        return $wdb->update($tbname, $info, $where); 
    }
    
    public function init($uid)
    {
        $tbname = $this->getTableName($uid);

        $sql = "INSERT INTO $tbname(uid, id, cid, x, y, z, mirro, status, item_type)
            VALUES
            (:uid, 1, 421,  7, 0, 4, 0, 1, 21),
            (:uid, 2, 421,  7, 0, 1, 0, 1, 21),
            (:uid, 3, 621,  0, 0, 7, 0, 1, 21),
            (:uid, 4, 721,  4, 0, 7, 0, 1, 21),
            (:uid, 5, 721,  5, 0, 7, 0, 1, 21),
            (:uid, 6, 721,  6, 0, 0, 0, 1, 21),
            (:uid, 7, 721,  1, 0, 7, 0, 1, 21),
            (:uid, 8, 721,  5, 0, 0, 0, 1, 21),
            (:uid, 9, 721,  4, 0, 0, 0, 1, 21),
            (:uid, 10, 721,  3, 0, 0, 0, 1, 21),
            (:uid, 11, 721,  2, 0, 0, 0, 1, 21),
            (:uid, 12, 721,  1, 0, 0, 0, 1, 21),
            (:uid, 13, 821,  7, 0, 7, 0, 1, 21),
            (:uid, 14, 921,  7, 0, 5, 0, 1, 21),
            (:uid, 15, 921,  7, 0, 6, 0, 1, 21),
            (:uid, 16, 921,  0, 0, 6, 0, 1, 21),
            (:uid, 17, 921,  0, 0, 5, 0, 1, 21),
            (:uid, 18, 921,  0, 0, 4, 0, 1, 21),
            (:uid, 19, 921,  0, 0, 3, 0, 1, 21),
            (:uid, 20, 921,  0, 0, 2, 0, 1, 21),
            (:uid, 21, 921,  0, 0, 1, 0, 1, 21),
            (:uid, 22, 1021,  7, 0, 0, 0, 1, 21),
            (:uid, 23, 1121,  0, 0, 0, 0, 1, 21)";

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